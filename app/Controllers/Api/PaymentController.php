<?php

namespace App\Controllers\Api;

use App\Models\BookingModel;
use App\Models\PaymentModel;
use App\Models\PrintOptionModel;
use CodeIgniter\HTTP\ResponseInterface;

class PaymentController extends BaseApiController
{
    private BookingModel $bookingModel;
    private PrintOptionModel $printOptionModel;
    private PaymentModel $paymentModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->printOptionModel = new PrintOptionModel();
        $this->paymentModel = new PaymentModel();
    }

    public function createQr(): ResponseInterface
    {
        $payload = $this->request->getJSON(true) ?? $this->request->getPost();
        $bookingId = trim((string) ($payload['booking_id'] ?? ''));
        $printOptionId = trim((string) ($payload['print_option_id'] ?? ''));

        if ($bookingId === '' || $printOptionId === '') {
            return $this->failValidation('booking_id dan print_option_id wajib diisi.');
        }

        $booking = $this->bookingModel->find($bookingId);
        if ($booking === null) {
            return $this->failNotFound('Booking tidak ditemukan.');
        }

        if (in_array($booking['status'], ['cancelled', 'completed'], true)) {
            return $this->failValidation('Booking tidak bisa diproses pembayaran lagi.');
        }

        $printOption = $this->printOptionModel->find($printOptionId);
        if ($printOption === null) {
            if (substr($printOptionId, 0, 7) === 'custom_') {
                $copies = (int) substr($printOptionId, 7);
                if ($copies > 0) {
                    $extraPrice = 25000.00;
                    if ($copies > 2) {
                        $extraPrice += ($copies - 2) * 15000.00;
                    }
                    $printOption = [
                        'id' => $printOptionId,
                        'copies' => $copies,
                        'extra_price' => $extraPrice,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    $this->printOptionModel->insert($printOption);
                } else {
                    return $this->failNotFound('Print option tidak ditemukan.');
                }
            } else {
                return $this->failNotFound('Print option tidak ditemukan.');
            }
        }

        $basePrice = 0.00;
        $extraPrice = (float) ($printOption['extra_price'] ?? 0);
        $amount = number_format($basePrice + $extraPrice, 2, '.', '');

        $payment = $this->paymentModel->where('booking_id', $bookingId)
            ->where('status', 'pending')
            ->first();

        $gatewayRef = 'MIDTRANS-' . strtoupper(bin2hex(random_bytes(5)));

        if ($payment === null) {
            $payment = [
                'id' => $this->makeId('pay_'),
                'booking_id' => $bookingId,
                'print_option_id' => $printOptionId,
                'amount' => $amount,
                'gateway_ref' => $gatewayRef,
                'status' => 'pending',
            ];
            $this->paymentModel->insert($payment);
        } else {
            $this->paymentModel->update($payment['id'], [
                'print_option_id' => $printOptionId,
                'amount' => $amount,
                'gateway_ref' => $gatewayRef,
                'status' => 'pending',
            ]);
            $payment = $this->paymentModel->find($payment['id']);
        }

        return $this->respondCreated([
            'message' => 'QR pembayaran berhasil dibuat (placeholder Midtrans).',
            'payment' => $payment,
            'midtrans' => [
                'order_id' => $payment['id'],
                'gross_amount' => $payment['amount'],
                'qr_string' => $payment['gateway_ref'],
            ],
        ]);
    }

    public function statusByQuery(): ResponseInterface
    {
        $paymentId = trim((string) $this->request->getGet('payment_id'));
        if ($paymentId === '') {
            return $this->failValidation('Gunakan query payment_id. Contoh: /api/desktop/payment?payment_id=pay_xxx');
        }

        return $this->status($paymentId);
    }

    public function status(string $paymentId): ResponseInterface
    {
        $payment = $this->paymentModel->find($paymentId);
        if ($payment === null) {
            return $this->failNotFound('Payment tidak ditemukan.');
        }

        if ($payment['status'] === 'paid') {
            $this->markBookingAsPaid($payment['booking_id']);
        }

        return $this->respondOk([
            'payment' => $payment,
            'next_step' => $payment['status'] === 'paid' ? 'choose_frame' : 'wait_payment',
        ]);
    }

    public function midtransCallback(): ResponseInterface
    {
        $payload = $this->request->getJSON(true) ?? [];
        $orderId = trim((string) ($payload['order_id'] ?? ''));
        $transactionStatus = strtolower((string) ($payload['transaction_status'] ?? 'pending'));

        if ($orderId === '') {
            return $this->failValidation('order_id wajib diisi.');
        }

        $payment = $this->paymentModel->find($orderId);
        if ($payment === null) {
            return $this->failNotFound('Payment tidak ditemukan.');
        }

        $mappedStatus = match ($transactionStatus) {
            'capture', 'settlement' => 'paid',
            'deny', 'cancel', 'expire', 'failure' => 'failed',
            default => 'pending',
        };

        $this->paymentModel->update($payment['id'], ['status' => $mappedStatus]);
        if ($mappedStatus === 'paid') {
            $this->markBookingAsPaid($payment['booking_id']);
        }

        return $this->respondOk([
            'message' => 'Callback diproses.',
            'payment_id' => $payment['id'],
            'status' => $mappedStatus,
        ]);
    }

    private function markBookingAsPaid(string $bookingId): void
    {
        $booking = $this->bookingModel->find($bookingId);
        if ($booking !== null && $booking['status'] === 'pending') {
            $this->bookingModel->update($bookingId, ['status' => 'paid']);
        }
    }
}
