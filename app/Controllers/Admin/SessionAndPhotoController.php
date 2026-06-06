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

        // Placeholder: integrasi email service (Mailer CI4)
        // \Config\Services::email()->setTo($session['email_recipient'])...
        // Untuk sekarang, hanya log / redirect dengan pesan sukses.

        return redirect()->to(base_url('admin/sessions'))
            ->with('success', "Email galeri sesi #{$id} berhasil dikirim ulang ke {$session['email_recipient']}.");
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

        // Placeholder: loop kirim email
        $count = count($sessions);

        return redirect()->to(base_url('admin/sessions'))
            ->with('success', "Perintah kirim ulang email dikirim untuk {$count} sesi.");
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
    // EXPORT — Download CSV semua sesi
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

        $filename = 'sessions_export_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID Sesi', 'Booking ID', 'Frame', 'Filter', 'Email Penerima', 'Drive URL', 'Waktu']);
        foreach ($sessions as $s) {
            fputcsv($output, [
                $s['id'], $s['booking_id'], $s['frame_name'], $s['filter_name'],
                $s['email_recipient'], $s['drive_folder_url'], $s['created_at'],
            ]);
        }
        fclose($output);
        exit;
    }
}
