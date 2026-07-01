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

    public function createWalkin(): ResponseInterface
    {
        $customerModel = new \App\Models\CustomerModel();
        $slotModel = new \App\Models\SlotModel();
        $boothModel = new \App\Models\BoothModel();
        $packageModel = new \App\Models\PackageModel();

        try {
            // 1. Find or create default walk-in customer
            $customer = $customerModel->where('email', 'walkin@snapbox.com')->first();
            if ($customer === null) {
                $customerId = 'cust_' . bin2hex(random_bytes(5));
                $customerModel->insert([
                    'id' => $customerId,
                    'name' => 'Walk-in Customer',
                    'email' => 'walkin@snapbox.com',
                    'phone' => '0000000000',
                ]);
                $customer = $customerModel->find($customerId);
            }

            // 2. Find first package
            $package = $packageModel->first();
            if ($package === null) {
                return $this->response->setStatusCode(500)->setJSON([
                    'status' => 500,
                    'error' => 'Package tidak ditemukan di database.',
                ]);
            }

            // 3. Find first booth
            $booth = $boothModel->first();
            if ($booth === null) {
                return $this->response->setStatusCode(500)->setJSON([
                    'status' => 500,
                    'error' => 'Booth tidak ditemukan di database.',
                ]);
            }

            // 4. Find or create slot for today
            $today = date('Y-m-d');
            $slot = $slotModel->where('slot_date', $today)->first();
            if ($slot === null) {
                $slotId = 'slot_' . bin2hex(random_bytes(5));
                $slotModel->insert([
                    'id' => $slotId,
                    'booth_id' => $booth['id'],
                    'slot_date' => $today,
                    'start_time' => '08:00:00',
                    'max_bookings' => 100,
                    'booked_count' => 0,
                ]);
                $slot = $slotModel->find($slotId);
            }

            // 5. Generate booking ID & code
            $bookingId = 'BK-' . strtoupper(bin2hex(random_bytes(5)));
            $bookingCode = $bookingId;

            $bookingData = [
                'id' => $bookingId,
                'booking_code' => $bookingCode,
                'customer_id' => $customer['id'],
                'slot_id' => $slot['id'],
                'booth_id' => $booth['id'],
                'package_id' => $package['id'],
                'queue_number' => 1,
                'status' => 'pending',
            ];

            $this->bookingModel->insert($bookingData);
            $booking = $this->bookingModel->find($bookingId);

            return $this->respondCreated([
                'message' => 'Walk-in booking berhasil dibuat.',
                'booking' => $booking,
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 500,
                'error' => 'Gagal membuat walk-in booking: ' . $e->getMessage(),
            ]);
        }
    }
}
