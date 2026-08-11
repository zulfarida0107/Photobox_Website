<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SessionModel;
use App\Models\PhotoModel;
use App\Models\FrameModel;
use App\Models\FilterModel;
use App\Services\EmailService;
use App\Services\GoogleDriveService;

class SessionAndPhotoController extends BaseController
{
    protected SessionModel $sessionModel;
    protected PhotoModel   $photoModel;
    protected FrameModel   $frameModel;
    protected FilterModel  $filterModel;

    public function __construct()
    {
        $this->sessionModel = new SessionModel();
        $this->photoModel   = new PhotoModel();
        $this->frameModel   = new FrameModel();
        $this->filterModel  = new FilterModel();
    }

    // =========================================================================
    // INDEX — Daftar sesi + galeri foto sesi terbaru + stats
    // =========================================================================
    public function index()
    {
        $perPage     = 10;
        $currentPage = max(1, (int) ($this->request->getGet('page') ?? 1));
        $keyword     = $this->request->getGet('q') ?? '';

        // --- Query sesi dengan JOIN ke frames & filters ---
        $db = $this->sessionModel->db;
        $builder = $db->table('sessions s')
            ->select('s.*, b.booking_code, fr.name AS frame_name, fi.name AS filter_name, c.email AS customer_email')
            ->join('bookings b',  'b.id = s.booking_id', 'left')
            ->join('customers c', 'c.id = b.customer_id', 'left')
            ->join('frames fr',   'fr.id = s.frame_id',  'left')
            ->join('filters fi',  'fi.id = s.filter_id', 'left')
            ->orderBy('s.created_at', 'DESC');

        if ($keyword !== '') {
            $builder->groupStart()
                ->like('s.id', $keyword)
                ->orLike('s.booking_id', $keyword)
                ->orLike('s.email_recipient', $keyword)
                ->groupEnd();
        }

        $totalSessions = $builder->countAllResults(false);
        $totalPages    = max(1, (int) ceil($totalSessions / $perPage));
        $sessions      = $builder->limit($perPage, ($currentPage - 1) * $perPage)->get()->getResultArray();

        // --- Galeri: foto dari sesi paling baru ---
        $gallerySession = !empty($sessions) ? $sessions[0] : null;
        $photos = [];
        if ($gallerySession) {
            $photos = $this->photoModel
                ->where('session_id', $gallerySession['id'])
                ->orderBy('shot_number', 'ASC')
                ->findAll();
        }

        // --- Stats ---
        $statTotalPhotos = $this->photoModel->countAll();
        $statToday       = $this->sessionModel
            ->where('created_at >=', date('Y-m-d 00:00:00'))
            ->countAllResults();
        $statThisMonth   = $this->sessionModel
            ->where('created_at >=', date('Y-m-01 00:00:00'))
            ->countAllResults();
        $latestSession   = $this->sessionModel->orderBy('created_at', 'DESC')->first();

        $data = [
            'title'          => 'Manajemen Sesi & Galeri | Admin Panel',
            'sessions'       => $sessions,
            'totalSessions'  => $totalSessions,
            'currentPage'    => $currentPage,
            'totalPages'     => $totalPages,
            'keyword'        => $keyword,
            'photos'         => $photos,
            'gallerySession' => $gallerySession,
            'statTotalPhotos' => $statTotalPhotos,
            'statToday'      => $statToday,
            'statThisMonth'  => $statThisMonth,
            'latestSession'  => $latestSession,
        ];

        return view('Admin/SessionAndPhoto/SessionAndPhotoManajemen', $data);
    }

    // =========================================================================
    // SHOW — Detail satu sesi + semua fotonya
    // =========================================================================
    public function show(string $id)
    {
        $db = $this->sessionModel->db;
        $session = $db->table('sessions s')
            ->select('s.*, b.booking_code, fr.name AS frame_name, fi.name AS filter_name, c.email AS customer_email')
            ->join('bookings b',  'b.id = s.booking_id', 'left')
            ->join('customers c', 'c.id = b.customer_id', 'left')
            ->join('frames fr',   'fr.id = s.frame_id',  'left')
            ->join('filters fi',  'fi.id = s.filter_id', 'left')
            ->where('s.id', $id)
            ->get()->getRowArray();

        if (!$session) {
            return redirect()->to(base_url('admin/sessions#table-session'))
                ->with('error', 'Sesi tidak ditemukan.');
        }

        $photos = $this->photoModel
            ->where('session_id', $id)
            ->orderBy('shot_number', 'ASC')
            ->findAll();

        return view('Admin/SessionAndPhoto/SessionDetail', [
            'title'   => 'Detail Sesi #' . $id . ' | Admin Panel',
            'session' => $session,
            'photos'  => $photos,
        ]);
    }

    // =========================================================================
    // RESEND — Kirim ulang email galeri untuk satu sesi
    // =========================================================================
    public function resend(string $id)
    {
        $session = $this->sessionModel->find($id);
        if (!$session) {
            return redirect()->to(base_url('admin/sessions'))
                ->with('error', 'Sesi tidak ditemukan.');
        }

        $db = $this->sessionModel->db;
        $customerEmail = $db->table('bookings b')
            ->select('c.email')
            ->join('customers c', 'c.id = b.customer_id', 'left')
            ->where('b.id', $session['booking_id'])
            ->get()->getRowArray();
        
        $emailRecipient = $session['email_recipient'] ?? ($customerEmail['email'] ?? '');

        if (empty($emailRecipient)) {
            return redirect()->to(base_url('admin/sessions#table-session'))
                ->with('error', 'Sesi ini tidak memiliki penerima email.');
        }

        // Coba upload ke Drive dulu (atau gunakan drive_folder_url yang sudah ada)
        $driveLink = $session['drive_folder_url'] ?? null;

        if (empty($driveLink)) {
            try {
                $driveService = new GoogleDriveService();
                $driveLink    = $driveService->uploadSessionPhotos($id);
                $this->sessionModel->update($id, ['drive_folder_url' => $driveLink]);
            } catch (\Throwable $driveErr) {
                log_message('error', 'Resend Drive upload error: ' . $driveErr->getMessage());
                $driveLink = null;
            }
        }

        // Kirim email
        if (!empty($emailRecipient)) {
            try {
                $emailService = new EmailService();
                // Send specific params to email template
                // Assuming it expects photos array
                $photos = (new \App\Models\PhotoModel())->where('session_id', $id)->findAll();
                
                $emailParams = [
                    'recipient'   => $emailRecipient,
                    'subject'     => 'Your Photobox Session #' . $id,
                    'messageBody' => 'Thank you for using Photobox! Here are your session results.',
                    'driveLink'   => $driveLink,
                ];
                $emailService->sendEmail($emailParams);

                return redirect()->to(base_url('admin/sessions#table-session'))
                    ->with('success', 'Email galeri berhasil dikirim ulang ke ' . esc($emailRecipient) . '.');
            } catch (\Throwable $e) {
                log_message('error', 'Resend Email error: ' . $e->getMessage());
                return redirect()->to(base_url('admin/sessions#table-session'))
                    ->with('error', 'Gagal mengirim ulang email: ' . $e->getMessage());
            }
        }

        return redirect()->to(base_url('admin/sessions#table-session'))
            ->with('error', 'Gagal mengirim ulang email.');
    }

    // =========================================================================
    // RESEND ALL — Kirim ulang email untuk semua sesi yang punya email
    // =========================================================================
    public function resendAll()
    {
        $db = $this->sessionModel->db;
        $sessions = $db->table('sessions s')
            ->select('s.*, c.email AS customer_email')
            ->join('bookings b', 'b.id = s.booking_id', 'left')
            ->join('customers c', 'c.id = b.customer_id', 'left')
            ->where("(s.email_recipient IS NOT NULL AND s.email_recipient != '') OR (c.email IS NOT NULL AND c.email != '')")
            ->get()->getResultArray();

        $emailService = new EmailService();
        $successCount = 0;
        $failCount    = 0;

        foreach ($sessions as $sess) {
            $driveLink = $sess['drive_folder_url'] ?? null;

            if (empty($driveLink)) {
                try {
                    $driveService = new GoogleDriveService();
                    $driveLink    = $driveService->uploadSessionPhotos($sess['id']);
                    $this->sessionModel->update($sess['id'], ['drive_folder_url' => $driveLink]);
                } catch (\Throwable $e) {
                    log_message('error', 'ResendAll Drive error for ' . $sess['id'] . ': ' . $e->getMessage());
                    $driveLink = null;
                }
            }

            try {
                $recipient = !empty($sess['email_recipient']) ? $sess['email_recipient'] : $sess['customer_email'];
                $sent = $emailService->sendDriveLink($recipient, $driveLink, $sess['id']);
                if ($sent) {
                    $successCount++;
                } else {
                    $failCount++;
                }
            } catch (\Throwable $e) {
                log_message('error', 'ResendAll email error for ' . $sess['id'] . ': ' . $e->getMessage());
                $failCount++;
            }
        }

        $msg = "Berhasil kirim ulang: {$successCount} email.";
        if ($failCount > 0) {
            $msg .= " Gagal: {$failCount} (cek log SMTP).";
        }

        return redirect()->to(base_url('admin/sessions#table-session'))->with('success', $msg);
    }

    // =========================================================================
    // DELETE SESSION — Hapus sesi beserta semua fotonya
    // =========================================================================
    public function deleteSession(string $id)
    {
        $session = $this->sessionModel->find($id);
        if (!$session) {
            return redirect()->to(base_url('admin/sessions'))
                ->with('error', 'Sesi tidak ditemukan.');
        }

        // Hapus file fisik semua foto dalam sesi ini
        $photos = $this->photoModel->where('session_id', $id)->findAll();
        foreach ($photos as $photo) {
            if (!empty($photo['file_path']) && file_exists(WRITEPATH . $photo['file_path'])) {
                unlink(WRITEPATH . $photo['file_path']);
            }
        }

        // Hapus data foto & sesi dari DB
        $this->photoModel->where('session_id', $id)->delete();
        $this->sessionModel->delete($id);

        return redirect()->to(base_url('admin/sessions#table-session'))
            ->with('success', 'Sesi beserta foto berhasil dihapus.');
    }

    // =========================================================================
    // DELETE PHOTO — Hapus satu foto
    // =========================================================================
    public function deletePhoto(string $id)
    {
        $photo = $this->photoModel->find($id);
        if (!$photo) {
            return redirect()->to(base_url('admin/sessions'))
                ->with('error', 'Foto tidak ditemukan.');
        }

        // Hapus file fisik
        if (!empty($photo['file_path']) && file_exists(FCPATH . $photo['file_path'])) {
            unlink(FCPATH . $photo['file_path']);
        }

        $sessionId = $photo['session_id'];
        $this->photoModel->delete($id);

        return redirect()->to(base_url('admin/sessions/' . $sessionId))
            ->with('success', 'Foto berhasil dihapus.');
    }

    // =========================================================================
    // EXPORT — Download Excel semua sesi
    // =========================================================================
    public function export()
    {
        $sessions = $this->sessionModel->db
            ->table('sessions s')
            ->select('s.id, s.booking_id, s.email_recipient, s.drive_folder_url, s.created_at, fr.name AS frame_name, fi.name AS filter_name')
            ->join('frames fr',  'fr.id = s.frame_id',  'left')
            ->join('filters fi', 'fi.id = s.filter_id', 'left')
            ->orderBy('s.created_at', 'DESC')
            ->get()->getResultArray();

        $filename = 'Laporan_Galeri_Sesi_' . date('Ymd_His') . '.csv';
        
        // Header untuk file CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        // Buka stream ke output
        $output = fopen('php://output', 'w');
        
        // Tambahkan BOM agar karakter terbaca benar di Excel
        fputs($output, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));
        
        // Force Excel to use semicolon separator (optional but helps some regions)
        fputs($output, "sep=;\n");

        $adminName = session('adminName') ?? session('admin_name') ?? 'Admin';
        $adminRole = session('adminRole') ?? session('admin_role') ?? 'Unknown Role';
        $adminEmail = session('adminEmail');
        
        if (!$adminEmail && session('adminId')) {
            $adminModel = new \App\Models\AdminModel();
            $adminData = $adminModel->find(session('adminId'));
            if ($adminData) {
                $adminEmail = $adminData['email'];
                $adminName = $adminData['name'];
            }
        }
        $adminEmail = $adminEmail ?? 'No Email';
        
        $penanggungJawab = $adminName . ' (' . $adminRole . ') - ' . $adminEmail;

        $currentTime = date('d M Y, H:i:s');
        $currentDate = date('d F Y');

        // Header Metadata
        fputcsv($output, ['LAPORAN DAFTAR RIWAYAT SESI TERKINI'], ';');
        fputcsv($output, [], ';');
        fputcsv($output, ['Penanggung Jawab: ' . $penanggungJawab], ';');
        fputcsv($output, ['Waktu Realtime: ' . $currentTime . ' WIB'], ';');
        fputcsv($output, ['Keterangan Tanggal: Diekspor pada ' . $currentDate], ';');
        fputcsv($output, [], ';');

        // Header Tabel Kolom
        fputcsv($output, [
            'ID Sesi',
            'Kode Booking',
            'Frame',
            'Filter',
            'Email Penerima',
            'Drive URL',
            'Waktu Dibuat'
        ], ';');

        // Isi Data Sesi
        foreach ($sessions as $s) {
            fputcsv($output, [
                '#' . $s['id'],
                $s['booking_id'] ?? '-',
                $s['frame_name'] ?? 'N/A',
                $s['filter_name'] ?? 'N/A',
                $s['email_recipient'] ?? '-',
                $s['drive_folder_url'] ?? '-',
                $s['created_at']
            ], ';');
        }

        fclose($output);
        exit;
    }
}
