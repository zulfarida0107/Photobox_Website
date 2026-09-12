<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SessionModel;
use App\Models\PhotoModel;
use App\Models\FrameModel;
use App\Models\FilterModel;

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
            ->select('s.*, b.booking_code, fr.name AS frame_name, fi.name AS filter_name')
            ->join('bookings b',  'b.id = s.booking_id', 'left')
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
            ->select('s.*, b.booking_code, fr.name AS frame_name, fi.name AS filter_name')
            ->join('bookings b',  'b.id = s.booking_id', 'left')
            ->join('frames fr',   'fr.id = s.frame_id',  'left')
            ->join('filters fi',  'fi.id = s.filter_id', 'left')
            ->where('s.id', $id)
            ->get()->getRowArray();

        if (!$session) {
            return redirect()->to(base_url('admin/sessions'))
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

        if (empty($session['email_recipient'])) {
            return redirect()->to(base_url('admin/sessions'))
                ->with('error', 'Sesi ini tidak memiliki penerima email.');
        }

        // Ambil semua foto sesi ini
        $photos      = $this->photoModel->where('session_id', $id)->orderBy('shot_number', 'ASC')->findAll();
        $driveUrl    = $session['drive_folder_url'] ?? null;
        $bookingCode = $session['booking_id'] ?? '-';
        $sessionDate = date('d M Y', strtotime($session['created_at']));

        $photoListHtml = '';
        foreach ($photos as $i => $photo) {
            $photoUrl       = base_url($photo['file_path']);
            $photoListHtml .= "<li style='margin-bottom:6px;'><a href='{$photoUrl}'>Foto " . ($i + 1) . "</a></li>";
        }
        if ($driveUrl) {
            $photoListHtml .= "<li style='margin-bottom:6px;'><a href='{$driveUrl}'>📁 Buka Folder Google Drive</a></li>";
        }

        $htmlBody = "
<div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:20px;'>
  <h2 style='color:#4f46e5;'>🎞️ Galeri Foto Anda Sudah Siap!</h2>
  <p>Halo,</p>
  <p>Terima kasih telah menggunakan <strong>CuitBox Studio</strong>. Berikut adalah tautan galeri foto dari sesi Anda pada <strong>{$sessionDate}</strong>.</p>
  <p><strong>Kode Sesi/Booking:</strong> {$bookingCode}</p>
  <ul style='line-height:1.8;'>{$photoListHtml}</ul>
  <p style='color:#6b7280;font-size:0.875rem;'>Email ini dikirim ulang oleh Admin CuitBox Studio.</p>
  <hr/>
  <p style='color:#9ca3af;font-size:0.75rem;'>CuitBox Studio — Smart Photobox Management</p>
</div>";

        $emailService = \Config\Services::email();
        $emailService->clear();
        $emailService->setFrom('cuitboxstudio@gmail.com', 'CuitBox Studio');
        $emailService->setTo($session['email_recipient']);
        $emailService->setSubject('🎞️ Galeri Foto CuitBox Studio — Sesi ' . $sessionDate);
        $emailService->setMessage($htmlBody);

        if ($emailService->send()) {
            return redirect()->to(base_url('admin/sessions'))
                ->with('success', "Email galeri sesi #{$id} berhasil dikirim ulang ke {$session['email_recipient']}.");
        }

        log_message('error', 'Email resend gagal sesi #' . $id . ': ' . $emailService->printDebugger(['headers']));
        return redirect()->to(base_url('admin/sessions'))
            ->with('error', "Gagal mengirim email ke {$session['email_recipient']}. Periksa konfigurasi SMTP di .env.");
    }

    // =========================================================================
    // RESEND ALL — Kirim ulang email untuk semua sesi yang punya email
    // =========================================================================
    public function resendAll()
    {
        $sessions = $this->sessionModel
            ->where('email_recipient IS NOT NULL')
            ->where('email_recipient !=', '')
            ->findAll();

        $successCount = 0;
        $failCount    = 0;

        foreach ($sessions as $sess) {
            $photos      = $this->photoModel->where('session_id', $sess['id'])->orderBy('shot_number', 'ASC')->findAll();
            $driveUrl    = $sess['drive_folder_url'] ?? null;
            $bookingCode = $sess['booking_id'] ?? '-';
            $sessDate    = date('d M Y', strtotime($sess['created_at']));

            $photoListHtml = '';
            foreach ($photos as $i => $photo) {
                $photoUrl       = base_url($photo['file_path']);
                $photoListHtml .= "<li><a href='{$photoUrl}'>Foto " . ($i + 1) . "</a></li>";
            }
            if ($driveUrl) {
                $photoListHtml .= "<li><a href='{$driveUrl}'>📁 Google Drive</a></li>";
            }

            $htmlBody = "
<div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:20px;'>
  <h2 style='color:#4f46e5;'>🎞️ Galeri Foto Anda Sudah Siap!</h2>
  <p>Terima kasih telah menggunakan <strong>CuitBox Studio</strong> pada <strong>{$sessDate}</strong>.</p>
  <p><strong>Kode Sesi/Booking:</strong> {$bookingCode}</p>
  <ul style='line-height:1.8;'>{$photoListHtml}</ul>
  <hr/>
  <p style='color:#9ca3af;font-size:0.75rem;'>CuitBox Studio — Smart Photobox Management</p>
</div>";

            $emailService = \Config\Services::email();
            $emailService->clear();
            $emailService->setFrom('cuitboxstudio@gmail.com', 'CuitBox Studio');
            $emailService->setTo($sess['email_recipient']);
            $emailService->setSubject('🎞️ Galeri Foto CuitBox Studio — Sesi ' . $sessDate);
            $emailService->setMessage($htmlBody);

            if ($emailService->send()) {
                $successCount++;
            } else {
                $failCount++;
                log_message('error', 'Email resendAll gagal sesi #' . $sess['id']);
            }
        }

        $count = count($sessions);
        $msg   = "Kirim ulang selesai: {$successCount}/{$count} berhasil.";
        if ($failCount > 0) {
            $msg .= " {$failCount} gagal — periksa konfigurasi SMTP.";
        }

        return redirect()->to(base_url('admin/sessions'))
            ->with('success', $msg);
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
            if (!empty($photo['file_path']) && file_exists(FCPATH . $photo['file_path'])) {
                unlink(FCPATH . $photo['file_path']);
            }
        }

        // Hapus data foto & sesi dari DB
        $this->photoModel->where('session_id', $id)->delete();
        $this->sessionModel->delete($id);

        return redirect()->to(base_url('admin/sessions'))
            ->with('success', "Sesi #{$id} dan seluruh fotonya berhasil dihapus.");
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
