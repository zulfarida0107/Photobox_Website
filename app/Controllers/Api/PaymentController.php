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
        $payload       = $this->getRequestPayload();
        $bookingId     = trim((string) ($payload['booking_id'] ?? ''));
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

        $serverKey    = config('Midtrans')->serverKey ?? (string) env('midtrans.serverKey', '');
        $isProduction = (bool) (config('Midtrans')->isProduction ?? false);

        $qrString = 'MIDTRANS-' . strtoupper(bin2hex(random_bytes(5)));
        $qrUrl    = null;

        if ($payment === null) {
            $paymentId = $this->makeId('pay_');
            $payment = [
                'id'              => $paymentId,
                'booking_id'      => $bookingId,
                'print_option_id' => $printOptionId,
                'amount'          => $amount,
                'gateway_ref'     => $qrString,
                'status'          => 'pending',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ];
            $this->paymentModel->insert($payment);
        } else {
            $paymentId = $payment['id'];
            $this->paymentModel->update($paymentId, [
                'print_option_id' => $printOptionId,
                'amount'          => $amount,
                'status'          => 'pending',
                'updated_at'      => date('Y-m-d H:i:s'),
            ]);
            $payment = $this->paymentModel->find($paymentId);
        }

        // Generate Midtrans Dynamic QRIS via CoreApi
        if (!empty($serverKey)) {
            try {
                \Midtrans\Config::$serverKey    = $serverKey;
                \Midtrans\Config::$isProduction = $isProduction;
                \Midtrans\Config::$isSanitized  = true;

                $chargeParams = [
                    'payment_type'        => 'qris',
                    'transaction_details' => [
                        'order_id'     => $payment['id'],
                        'gross_amount' => (int) $amount,
                    ],
                    'qris' => [
                        'acquirer' => 'gopay',
                    ],
                    'item_details' => [
                        [
                            'id'       => (string) $printOptionId,
                            'price'    => (int) $amount,
                            'quantity' => 1,
                            'name'     => 'Cetak ' . ($printOption['copies'] ?? 2) . ' Lembar Foto',
                        ]
                    ]
                ];

                $chargeResponse = \Midtrans\CoreApi::charge($chargeParams);

                if (!empty($chargeResponse->qr_string)) {
                    $qrString = $chargeResponse->qr_string;
                }

                if (!empty($chargeResponse->actions) && is_array($chargeResponse->actions)) {
                    foreach ($chargeResponse->actions as $action) {
                        if (($action->name ?? '') === 'generate-qr-code') {
                            $qrUrl = $action->url ?? null;
                            break;
                        }
                    }
                }

                if (empty($qrUrl) && !empty($qrString)) {
                    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrString);
                }

                $this->paymentModel->update($payment['id'], [
                    'gateway_ref' => $qrString,
                    'updated_at'  => date('Y-m-d H:i:s'),
                ]);
                $payment['gateway_ref'] = $qrString;
            } catch (\Throwable $e) {
                log_message('error', '[Midtrans Dynamic QRIS Charge Error] ' . $e->getMessage());
                if (empty($qrUrl)) {
                    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrString);
                }
            }
        } else {
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrString);
        }

        return $this->respondCreated([
            'message'   => 'QRIS dinamis Midtrans berhasil dibuat.',
            'payment'   => $payment,
            'qr_string' => $qrString,
            'qr_url'    => $qrUrl,
            'qr_code'   => $qrUrl,
            'midtrans'  => [
                'order_id'     => $payment['id'],
                'gross_amount' => $payment['amount'],
                'qr_string'    => $qrString,
                'qr_url'       => $qrUrl,
            ],
        ]);
    }

    public function statusByQuery(): ResponseInterface
    {
        $paymentId = trim((string) $this->request->getGet('payment_id'));
        if ($paymentId === '') {
            return $this->failValidation('Gunakan query payment_id. Contoh: /api/payment?payment_id=pay_xxx');
        }

        return $this->status($paymentId);
    }

    public function status(string $paymentId): ResponseInterface
    {
        $payment = $this->paymentModel->find($paymentId);
        if ($payment === null) {
            return $this->failNotFound('Payment tidak ditemukan.');
        }

        // Live Fallback Midtrans Status Inquiry jika status masih pending
        if ($payment['status'] === 'pending') {
            $serverKey = config('Midtrans')->serverKey ?? (string) env('midtrans.serverKey', '');
            if (!empty($serverKey)) {
                try {
                    \Midtrans\Config::$serverKey    = $serverKey;
                    \Midtrans\Config::$isProduction = (bool) (config('Midtrans')->isProduction ?? false);

                    $statusObj = \Midtrans\Transaction::status($paymentId);
                    $txStatus  = $statusObj->transaction_status ?? '';
                    $fraud     = $statusObj->fraud_status ?? 'accept';

                    if ($txStatus === 'settlement' || ($txStatus === 'capture' && $fraud === 'accept')) {
                        $this->paymentModel->update($payment['id'], [
                            'status'         => 'paid',
                            'payment_method' => $statusObj->payment_type ?? 'QRIS',
                            'updated_at'     => date('Y-m-d H:i:s'),
                        ]);
                        $this->markBookingAsPaid($payment['booking_id']);
                        $payment = $this->paymentModel->find($paymentId);
                    } elseif (in_array($txStatus, ['deny', 'cancel', 'expire'], true)) {
                        $this->paymentModel->update($payment['id'], [
                            'status'     => 'failed',
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                        $payment = $this->paymentModel->find($paymentId);
                    }
                } catch (\Throwable $e) {
                    // Ignore inquiry errors if not found
                }
            }
        }

        if ($payment['status'] === 'paid') {
            $this->markBookingAsPaid($payment['booking_id']);
        }

        return $this->respondOk([
            'payment'   => $payment,
            'next_step' => $payment['status'] === 'paid' ? 'choose_frame' : 'wait_payment',
        ]);
    }

    public function midtransCallback(): ResponseInterface
    {
        $payload = $this->getRequestPayload();

        // Health-check jika endpoint diakses via GET oleh browser / tools
        if (strtoupper($this->request->getMethod()) === 'GET' && empty($payload)) {
            return $this->respondOk([
                'status'  => 'online',
                'service' => 'Midtrans Webhook Callback',
                'message' => 'Endpoint aktif dan siap menerima notifikasi HTTP POST dari Midtrans.'
            ]);
        }

        $orderId           = trim((string) ($payload['order_id'] ?? ''));
        $statusCode        = trim((string) ($payload['status_code'] ?? ''));
        $grossAmount       = trim((string) ($payload['gross_amount'] ?? ''));
        $inputSignature    = trim((string) ($payload['signature_key'] ?? ''));
        $transactionStatus = strtolower(trim((string) ($payload['transaction_status'] ?? 'pending')));
        $fraudStatus       = strtolower(trim((string) ($payload['fraud_status'] ?? '')));
        $paymentType       = strtolower(trim((string) ($payload['payment_type'] ?? '')));

        if ($orderId === '') {
            return $this->failValidation('order_id wajib diisi.');
        }

        // 1. Validasi Keamanan SHA-512 Signature Key resmi Midtrans
        $midtransConfig = config('Midtrans');
        $serverKey      = $midtransConfig->serverKey ?? (string) env('midtrans.serverKey', '');
        $isProduction   = (bool) ($midtransConfig->isProduction ?? false) || (ENVIRONMENT === 'production');

        if (!empty($serverKey)) {
            // Jika ada signature yang dikirim (webhook asli Midtrans):
            if ($inputSignature !== '') {
                if ($statusCode === '' || $grossAmount === '') {
                    log_message('warning', "[Midtrans Security] Incomplete callback signature payload for order: {$orderId}");
                    return $this->failUnauthorized('Payload signature Midtrans tidak lengkap.');
                }

                $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
                if (!hash_equals($expectedSignature, $inputSignature)) {
                    log_message('critical', "[Midtrans Security] Signature mismatch! Order: {$orderId}, Input: {$inputSignature}, Expected: {$expectedSignature}");
                    return $this->failUnauthorized('Verifikasi signature key Midtrans gagal.');
                }
            } else {
                // Jika tidak ada signature: hanya diperbolehkan pada mode development/testing (Dev Mode Bypass di Kiosk Desktop)
                if ($isProduction) {
                    log_message('warning', "[Midtrans Security] Signature key required in production for order: {$orderId}");
                    return $this->failUnauthorized('Signature key Midtrans wajib disertakan.');
                }
                log_message('info', "[Dev Mode Bypass] Signature bypass diaktifkan untuk testing lokal kiosk. Order: {$orderId}");
            }
        } else {
            log_message('warning', "[Midtrans Security] Midtrans serverKey belum dikonfigurasi di .env. Lewati verifikasi signature.");
        }

        // 2. Cari data pembayaran di database
        $payment = $this->paymentModel->find($orderId);
        if ($payment === null) {
            log_message('error', "[Midtrans Callback] Payment tidak ditemukan untuk order: {$orderId}");
            return $this->failNotFound('Payment tidak ditemukan.');
        }

        // 3. Pemetaan status transaksi Midtrans
        $mappedStatus = match ($transactionStatus) {
            'capture' => ($paymentType === 'credit_card' && $fraudStatus === 'challenge') ? 'pending' : 'paid',
            'settlement' => 'paid',
            'deny', 'cancel', 'expire', 'failure' => 'failed',
            default => 'pending',
        };

        // 4. Update status pembayaran dan booking secara atomik (Database Transaction)
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $this->paymentModel->update($payment['id'], [
                'status' => $mappedStatus,
            ]);

            if ($mappedStatus === 'paid') {
                $this->markBookingAsPaid($payment['booking_id']);
            } elseif ($mappedStatus === 'failed') {
                $booking = $this->bookingModel->find($payment['booking_id']);
                if ($booking !== null && $booking['status'] === 'pending') {
                    $this->bookingModel->update($payment['booking_id'], ['status' => 'cancelled']);
                }
            }

            $db->transCommit();
            log_message('info', "[Midtrans Callback] Order {$orderId} berhasil diproses dengan status {$mappedStatus}.");
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', "[Midtrans Callback] Gagal memproses update database: " . $e->getMessage());
            return $this->failServerError('Gagal memperbarui status transaksi.');
        }

        return $this->respondOk([
            'status'         => 'success',
            'message'        => 'Callback Midtrans berhasil diverifikasi dan diproses.',
            'payment_id'     => $payment['id'],
            'order_id'       => $orderId,
            'payment_status' => $mappedStatus,
        ]);
    }

    private function markBookingAsPaid(string $bookingId): void
    {
        $booking = $this->bookingModel->find($bookingId);
        if ($booking !== null && in_array($booking['status'], ['pending', 'confirmed'], true)) {
            $this->bookingModel->update($bookingId, ['status' => 'paid']);
        }
    }
}
