<?php

namespace App\Controllers\Api;

use App\Models\BookingModel;
use App\Models\SessionModel;
use CodeIgniter\HTTP\ResponseInterface;

class SessionController extends BaseApiController
{
    private BookingModel $bookingModel;
    private SessionModel $sessionModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->sessionModel = new SessionModel();
    }

    public function start(): ResponseInterface
    {
        $payload = $this->request->getJSON(true) ?? $this->request->getPost();
        $bookingId = trim((string) ($payload['booking_id'] ?? ''));
        $frameId = trim((string) ($payload['frame_id'] ?? ''));
        $filterId = trim((string) ($payload['filter_id'] ?? ''));

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

        $session = $this->sessionModel->where('booking_id', $bookingId)->first();
        if ($session === null) {
            $session = [
                'id' => $this->makeId('ses_'),
                'booking_id' => $bookingId,
                'frame_id' => $frameId,
                'filter_id' => $filterId,
                'email_recipient' => '',
                'drive_folder_url' => '',
            ];
            $this->sessionModel->insert($session);
            $session = $this->sessionModel->find($session['id']);
        } else {
            $this->sessionModel->update($session['id'], [
                'frame_id' => $frameId,
                'filter_id' => $filterId,
            ]);
            $session = $this->sessionModel->find($session['id']);
        }

        return $this->respondOk([
            'message' => 'Session siap. Lanjut countdown dan capture.',
            'session' => $session,
            'capture_rules' => [
                'target_shots' => 10,
                'timer_seconds_per_shot' => 15,
                'preview_timeout_seconds' => 420,
            ],
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

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->failValidation('Format email tidak valid.');
        }

        $this->sessionModel->update($sessionId, ['email_recipient' => $email]);

        return $this->respondOk([
            'message' => 'Email penerima disimpan. Lanjut kirim hasil.',
            'session_id' => $sessionId,
            'email' => $email,
        ]);
    }

    public function complete(string $sessionId): ResponseInterface
    {
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
            return $this->failValidation('Booking belum paid, belum bisa complete.');
        }

        $this->bookingModel->update($booking['id'], ['status' => 'completed']);

        return $this->respondOk([
            'message' => 'Flow selesai. Booth siap reset untuk customer berikutnya.',
            'booking_id' => $booking['id'],
            'session_id' => $sessionId,
            'next_step' => 'reset',
        ]);
    }
}
