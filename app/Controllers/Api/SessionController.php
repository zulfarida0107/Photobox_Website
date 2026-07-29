<?php

namespace App\Controllers\Api;

use App\Models\BookingModel;
use App\Models\SessionModel;
use App\Models\PhotoModel;
use App\Services\GoogleDriveService;
use App\Services\EmailService;
use CodeIgniter\HTTP\ResponseInterface;

class SessionController extends BaseApiController
{
    private BookingModel $bookingModel;
    private SessionModel $sessionModel;
    private PhotoModel $photoModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->sessionModel = new SessionModel();
        $this->photoModel = new PhotoModel();
    }

    public function start(): ResponseInterface
    {
        $payload = $this->request->getJSON(true) ?? $this->request->getPost();

        $bookingId = trim((string) ($payload['booking_id'] ?? ''));
        $frameId   = trim((string) ($payload['frame_id'] ?? ''));
        $filterId  = trim((string) ($payload['filter_id'] ?? ''));

        if ($bookingId === '' || $frameId === '' || $filterId === '') {
            return $this->failValidation('booking_id, frame_id, dan filter_id wajib diisi.');
        }

        $booking = $this->bookingModel->find($bookingId);

        if ($booking === null) {
            return $this->failNotFound('Booking tidak ditemukan.');
        }

        if ($booking['status'] !== 'paid') {
            return $this->failValidation('Session hanya bisa dimulai setelah pembayaran sukses.');
        }

        $session = $this->sessionModel
            ->where('booking_id', $bookingId)
            ->first();

        if ($session === null) {

            $session = [
                'id'               => $this->makeId('ses_'),
                'booking_id'       => $bookingId,
                'frame_id'         => $frameId,
                'filter_id'        => $filterId,
                'email_recipient'  => '',
                'drive_folder_url' => '',
            ];

            $this->sessionModel->insert($session);

            $session = $this->sessionModel->find($session['id']);
        } else {

            $this->sessionModel->update($session['id'], [
                'frame_id'  => $frameId,
                'filter_id' => $filterId,
            ]);

            $session = $this->sessionModel->find($session['id']);
        }

        return $this->respondOk([
            'message' => 'Session siap. Lanjut countdown dan capture.',
            'session' => $session,
            'capture_rules' => [
                'target_shots'             => 10,
                'timer_seconds_per_shot'   => 15,
                'preview_timeout_seconds'  => 420,
            ],
        ]);
    }

    public function upload(string $sessionId): ResponseInterface
    {
        $session = $this->sessionModel->find($sessionId);

        if ($session === null) {
            return $this->failNotFound('Session tidak ditemukan.');
        }
        // Hapus metadata foto lama jika user upload ulang
        $this->photoModel
            ->where('session_id', $sessionId)
            ->delete();

        // photostrip final
        $finalImage = $this->request->getFile('final_image');

        if (!$finalImage || !$finalImage->isValid()) {
            return $this->failValidation('Final image wajib dikirim.');
        }

        // seluruh hasil capture
        $capturedPhotos = $this->request->getFileMultiple('captured_photos');

        // fallback apabila FE mengirim captured_photos[]
        if (empty($capturedPhotos)) {
            $capturedPhotos = $this->request->getFileMultiple('captured_photos[]');
        }

        if (!is_array($capturedPhotos)) {
            $capturedPhotos = [];
        }

        $folder = WRITEPATH . 'uploads/sessions/' . $sessionId;

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        // simpan photostrip
        $finalImage->move(
            $folder,
            'final.png',
            true
        );

        $this->photoModel->insert([
            'id'            => $this->makeId('pho_'),
            'session_id'    => $sessionId,
            'file_path'     => 'uploads/sessions/' . $sessionId . '/final.png',
            'drive_file_id' => '',
            'shot_number'   => 0,
        ]);

        // simpan video transisi jika ada
        $finalVideoTransition = $this->request->getFile('final_video_transition');
        if ($finalVideoTransition && $finalVideoTransition->isValid()) {
            $ext = $finalVideoTransition->getExtension();
            $transitionFilename = 'final_video_transition.' . $ext;
            $finalVideoTransition->move(
                $folder,
                $transitionFilename,
                true
            );
            $this->photoModel->insert([
                'id'            => $this->makeId('pho_'),
                'session_id'    => $sessionId,
                'file_path'     => 'uploads/sessions/' . $sessionId . '/' . $transitionFilename,
                'drive_file_id' => '',
                'shot_number'   => 999, // 999 for transition video
            ]);
        }

        // simpan video loop jika ada
        $finalVideoLoop = $this->request->getFile('final_video_loop');
        if ($finalVideoLoop && $finalVideoLoop->isValid()) {
            $ext = $finalVideoLoop->getExtension();
            $loopFilename = 'final_video_loop.' . $ext;
            $finalVideoLoop->move(
                $folder,
                $loopFilename,
                true
            );
            $this->photoModel->insert([
                'id'            => $this->makeId('pho_'),
                'session_id'    => $sessionId,
                'file_path'     => 'uploads/sessions/' . $sessionId . '/' . $loopFilename,
                'drive_file_id' => '',
                'shot_number'   => 998, // 998 for loop video
            ]);
        }

        $savedCaptures = [];

        foreach ($capturedPhotos as $index => $photo) {

            if (!$photo || !$photo->isValid()) {
                continue;
            }

            $filename = 'capture_' .
                ($index + 1) .
                '.' .
                $photo->getExtension();

            $photo->move(
                $folder,
                $filename,
                true
            );

            $this->photoModel->insert([
                'id'            => $this->makeId('pho_'),
                'session_id'    => $sessionId,
                'file_path'     => 'uploads/sessions/' . $sessionId . '/' . $filename,
                'drive_file_id' => '',
                'shot_number'   => $index + 1,
            ]);

            $savedCaptures[] = $filename;
        }

        // logging untuk debugging
        log_message('debug', '=== SESSION UPLOAD ===');
        log_message('debug', 'Session : ' . $sessionId);
        log_message('debug', 'Final   : final.png');
        log_message('debug', 'Capture : ' . count($savedCaptures));

        return $this->respondOk([
            'message'         => 'Upload berhasil.',
            'session_id'      => $sessionId,
            'final_image'     => 'final.png',
            'captured_count'  => count($savedCaptures),
            'captured_files'  => $savedCaptures,
            'photos_saved'    => $this->photoModel
                ->where('session_id', $sessionId)
                ->countAllResults(),
        ]);
    }

    public function uploadLive(string $sessionId): ResponseInterface
    {
        $session = $this->sessionModel->find($sessionId);

        if ($session === null) {
            return $this->failNotFound('Session tidak ditemukan.');
        }

        // Hapus metadata live photo lama jika user upload ulang
        $this->photoModel
            ->where('session_id', $sessionId)
            ->where('shot_number >=', 100)
            ->delete();

        // live photos (secret videos)
        $livePhotos = $this->request->getFileMultiple('live_photos');
        if (empty($livePhotos)) {
            $livePhotos = $this->request->getFileMultiple('live_photos[]');
        }
        if (!is_array($livePhotos)) {
            $livePhotos = [];
        }

        $folder = WRITEPATH . 'uploads/sessions/' . $sessionId;
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $savedLivePhotos = [];
        foreach ($livePhotos as $index => $livePhoto) {
            if (!$livePhoto || !$livePhoto->isValid()) {
                continue;
            }

            $liveFilename = 'live_' .
                ($index + 1) .
                '.' .
                $livePhoto->getExtension();

            $livePhoto->move(
                $folder,
                $liveFilename,
                true
            );

            $this->photoModel->insert([
                'id'            => $this->makeId('pho_'),
                'session_id'    => $sessionId,
                'file_path'     => 'uploads/sessions/' . $sessionId . '/' . $liveFilename,
                'drive_file_id' => '',
                'shot_number'   => 100 + ($index + 1),
            ]);

            $savedLivePhotos[] = $liveFilename;
        }

        // logging untuk debugging
        log_message('debug', '=== SESSION LIVE UPLOAD ===');
        log_message('debug', 'Session : ' . $sessionId);
        log_message('debug', 'Live    : ' . count($savedLivePhotos));

        return $this->respondOk([
            'message'         => 'Upload live photo berhasil.',
            'session_id'      => $sessionId,
            'live_count'      => count($savedLivePhotos),
            'live_files'      => $savedLivePhotos,
            'photos_saved'    => $this->photoModel
                ->where('session_id', $sessionId)
                ->countAllResults(),
        ]);
    }

    public function submitEmail(string $sessionId): ResponseInterface
    {
        $session = $this->sessionModel->find($sessionId);

        if ($session === null) {
            return $this->failNotFound('Session tidak ditemukan.');
        }

        $payload = $this->request->getJSON(true) ?? $this->request->getPost();

        $email = trim((string) ($payload['email'] ?? ''));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->failValidation('Format email tidak valid.');
        }

        $this->sessionModel->update($sessionId, [
            'email_recipient' => $email,
        ]);

        return $this->respondOk([
            'message' => 'Email penerima disimpan.',
            'session_id' => $sessionId,
            'email' => $email,
        ]);
    }

    public function complete(string $sessionId): ResponseInterface
    {
        set_time_limit(300);

        $session = $this->sessionModel->find($sessionId);

        if ($session === null) {
            return $this->failNotFound('Session tidak ditemukan.');
        }

        if (trim((string) $session['email_recipient']) === '') {
            return $this->failValidation('Email penerima belum diisi.');
        }

        $booking = $this->bookingModel->find($session['booking_id']);

        if ($booking === null) {
            return $this->failNotFound('Booking tidak ditemukan.');
        }

        if ($booking['status'] !== 'paid') {
            return $this->failValidation('Booking belum paid.');
        }

        try {

            $driveService = new GoogleDriveService();

            $driveLink = $driveService->uploadSessionPhotos($sessionId);

            $emailService = new EmailService();

            $sent = $emailService->sendDriveLink(
                $session['email_recipient'],
                $driveLink
            );

            if (! $sent) {
                throw new \RuntimeException(
                    'Gagal mengirim email.'
                );
            }

            $this->bookingModel->update($booking['id'], [
                'status' => 'completed',
            ]);

            return $this->respondOk([
                'message' => 'Flow selesai.',
                'booking_id' => $booking['id'],
                'session_id' => $sessionId,
                'drive_link' => $driveLink,
                'next_step' => 'reset',
            ]);
        } catch (\Throwable $e) {

            log_message('error', $e->getMessage());

            return $this->failServerError(
                $e->getMessage()
            );
        }
    }
}
