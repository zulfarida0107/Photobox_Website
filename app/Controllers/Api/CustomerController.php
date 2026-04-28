<?php

namespace App\Controllers\Api;

use App\Models\BookingModel;
use App\Models\PaymentModel;
use App\Models\PhotoModel;
use App\Models\SessionModel;
use CodeIgniter\HTTP\ResponseInterface;

class CustomerController extends BaseApiController
{
    private BookingModel $bookingModel;
    private PaymentModel $paymentModel;
    private SessionModel $sessionModel;
    private PhotoModel $photoModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->paymentModel = new PaymentModel();
        $this->sessionModel = new SessionModel();
        $this->photoModel = new PhotoModel();
    }

    public function verifyBooking(): ResponseInterface
    {
        $payload = $this->request->getJSON(true) ?? $this->request->getPost();
        $bookingCode = trim((string) ($payload['booking_code'] ?? $payload['qr_code'] ?? ''));

        if ($bookingCode === '') {
            return $this->failValidation('booking_code atau qr_code wajib diisi.');
        }

        $booking = $this->bookingModel->where('booking_code', $bookingCode)->first();
        if ($booking === null) {
            return $this->failNotFound('Booking tidak ditemukan.');
        }

        return $this->respondOk([
            'message' => 'Booking valid.',
            'booking' => $booking,
            'flow_state' => $this->buildFlowState($booking),
        ]);
    }

    private function buildFlowState(array $booking): array
    {
        if ($booking['status'] === 'cancelled') {
            return ['current_step' => 'cancelled', 'next_step' => null];
        }

        if ($booking['status'] === 'completed') {
            return ['current_step' => 'done', 'next_step' => 'reset'];
        }

        $paidPayment = $this->paymentModel
            ->where('booking_id', $booking['id'])
            ->where('status', 'paid')
            ->first();

        if ($paidPayment === null) {
            return ['current_step' => 'verify_booking', 'next_step' => 'choose_print_option'];
        }

        $session = $this->sessionModel->where('booking_id', $booking['id'])->first();
        if ($session === null) {
            return ['current_step' => 'payment_success', 'next_step' => 'choose_frame'];
        }

        $photoCount = $this->photoModel->where('session_id', $session['id'])->countAllResults();
        if ($photoCount < 10) {
            return ['current_step' => 'capture', 'next_step' => 'upload_photos'];
        }

        if (trim((string) $session['email_recipient']) === '') {
            return ['current_step' => 'preview', 'next_step' => 'input_email'];
        }

        return ['current_step' => 'ready_to_send', 'next_step' => 'complete_session'];
    }
}
