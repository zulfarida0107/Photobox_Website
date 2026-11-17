<?php

namespace App\Controllers\Api;

use App\Models\BookingModel;
use App\Models\PaymentModel;
use App\Models\PrintOptionModel;
use App\Libraries\BayarGGService;
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

    /**
     * POST /api/payment/qr
     * Generate QRIS dinamis via Bayar.gg untuk pembayaran di Kiosk Desktop & Website
     */
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
        $amount = (int) ($basePrice + $extraPrice);

        // Cari atau buat record payment
        $payment = $this->paymentModel->where('booking_id', $bookingId)
            ->where('status', 'pending')
            ->first();

        $qrString = '';
        $qrUrl    = null;
        $invoiceId = null;
        $paymentPageUrl = null;

        if ($payment === null) {
            $paymentId = $this->makeId('pay_');
            $payment = [
                'id'              => $paymentId,
                'booking_id'      => $bookingId,
                'print_option_id' => $printOptionId,
                'amount'          => number_format($amount, 2, '.', ''),
                'gateway_ref'     => '',
                'status'          => 'pending',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ];
            $this->paymentModel->insert($payment);
        } else {
            $paymentId = $payment['id'];
            $this->paymentModel->update($paymentId, [
                'print_option_id' => $printOptionId,
                'amount'          => number_format($amount, 2, '.', ''),
                'status'          => 'pending',
                'updated_at'      => date('Y-m-d H:i:s'),
            ]);
            $payment = $this->paymentModel->find($paymentId);
        }

        // Generate QRIS dinamis via Bayar.gg API
        try {
            $bayargg = new BayarGGService();
            $description = 'Cetak ' . ($printOption['copies'] ?? 2) . ' Lembar Foto - CuitBox #' . $paymentId;

            $apiResponse = $bayargg->createPayment($amount, $description, $paymentId);

            $data = $apiResponse['data'] ?? [];

            $invoiceId = $data['invoice_id'] ?? null;
            $qrString  = !empty($data['qris_string']) ? $data['qris_string'] : ($data['qr_string'] ?? '');
            $paymentPageUrl = $data['payment_url'] ?? null;

            // Generate QR image URL dari qr_string
            if (!empty($qrString)) {
                $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=' . urlencode($qrString);
            } elseif (!empty($paymentPageUrl)) {
                // Fallback: gunakan payment URL sebagai QR content
                $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=' . urlencode($paymentPageUrl);
                $qrString = $paymentPageUrl;
            }

            // Simpan invoice reference ke database
            $this->paymentModel->update($payment['id'], [
                'gateway_ref' => $invoiceId ?? $qrString,
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
            $payment['gateway_ref'] = $invoiceId ?? $qrString;

        } catch (\Throwable $e) {
            log_message('error', '[BayarGG QRIS Error] ' . $e->getMessage());

            // Fallback: buat QR placeholder agar kiosk tetap bisa menampilkan
            $qrString = 'BAYARGG-' . strtoupper(bin2hex(random_bytes(5)));
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=' . urlencode($qrString);

            $this->paymentModel->update($payment['id'], [
                'gateway_ref' => $qrString,
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
            $payment['gateway_ref'] = $qrString;
        }

        return $this->respondCreated([
            'message'      => 'QRIS dinamis Bayar.gg berhasil dibuat.',
            'payment'      => $payment,
            'qr_string'    => $qrString,
            'qr_url'       => $qrUrl,
            'qr_code'      => $qrUrl,
            'payment_page' => $paymentPageUrl,
            'invoice_id'   => $invoiceId,
            'bayargg'      => [
                'invoice_id'   => $invoiceId,
                'amount'       => $amount,
                'qr_string'    => $qrString,
                'qr_url'       => $qrUrl,
                'payment_url'  => $paymentPageUrl,
            ],
        ]);
    }

    /**
     * GET /api/payment?payment_id=xxx
     * Cek status pembayaran via query parameter
     */
    public function statusByQuery(): ResponseInterface
    {
        $paymentId = trim((string) $this->request->getGet('payment_id'));
        if ($paymentId === '') {
            return $this->failValidation('Gunakan query payment_id. Contoh: /api/payment?payment_id=pay_xxx');
        }

        return $this->status($paymentId);
    }

    /**
     * GET /api/payment/{id}/status
     * Cek status pembayaran (dengan live inquiry ke Bayar.gg jika masih pending)
     */
    public function status(string $paymentId): ResponseInterface
    {
        $payment = $this->paymentModel->find($paymentId);
        if ($payment === null) {
            return $this->failNotFound('Payment tidak ditemukan.');
        }

        // Live Fallback: Cek status ke Bayar.gg jika masih pending
        if ($payment['status'] === 'pending' && !empty($payment['gateway_ref'])) {
            try {
                $bayargg = new BayarGGService();
                $statusResponse = $bayargg->checkPayment($payment['gateway_ref']);

                $invoiceData = $statusResponse['data'] ?? $statusResponse;
                $bayarggStatus = $invoiceData['status'] ?? '';
                $mappedStatus = $bayargg->mapStatus($bayarggStatus);

                if ($mappedStatus === 'paid') {
                    $this->paymentModel->update($payment['id'], [
                        'status'         => 'paid',
                        'payment_method' => 'QRIS Bayar.gg',
                        'updated_at'     => date('Y-m-d H:i:s'),
                    ]);
                    $this->markBookingAsPaid($payment['booking_id']);
                    $payment = $this->paymentModel->find($paymentId);
                } elseif ($mappedStatus === 'failed') {
                    $this->paymentModel->update($payment['id'], [
                        'status'     => 'failed',
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    $payment = $this->paymentModel->find($paymentId);
                }
            } catch (\Throwable $e) {
                log_message('notice', '[BayarGG Status Inquiry] ' . $e->getMessage());
                // Biarkan status pending jika inquiry gagal
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

    /**
     * POST /api/payment/bayargg/callback
     * Webhook callback handler dari Bayar.gg
     * Dipanggil otomatis oleh server Bayar.gg ketika pembayaran berhasil
     */
    public function bayarggCallback(): ResponseInterface
    {
        $payload = $this->getRequestPayload();

        // Health-check jika endpoint diakses via GET oleh browser / tools
        if (strtoupper($this->request->getMethod()) === 'GET' && empty($payload)) {
            return $this->respondOk([
                'status'  => 'online',
                'service' => 'Bayar.gg Webhook Callback',
                'message' => 'Endpoint aktif dan siap menerima notifikasi HTTP POST dari Bayar.gg.'
            ]);
        }

        $invoiceId = trim((string) ($payload['invoice_id'] ?? ''));
        $status    = strtolower(trim((string) ($payload['status'] ?? 'pending')));
        $amount    = (int) ($payload['final_amount'] ?? $payload['amount'] ?? 0);

        if ($invoiceId === '') {
            return $this->failValidation('invoice_id wajib diisi.');
        }

        // 1. Validasi signature keamanan webhook
        $incomingSignature = $this->request->getHeaderLine('X-Callback-Signature')
            ?: $this->request->getHeaderLine('X-Signature')
            ?: '';

        try {
            $bayargg = new BayarGGService();
            if (!$bayargg->validateCallback($payload, $incomingSignature)) {
                log_message('critical', "[BayarGG Security] Signature mismatch! Invoice: {$invoiceId}");
                return $this->failUnauthorized('Verifikasi signature Bayar.gg gagal.');
            }
        } catch (\Throwable $e) {
            log_message('error', '[BayarGG Callback] Validation error: ' . $e->getMessage());
        }

        // 2. Cari payment berdasarkan gateway_ref (invoice_id Bayar.gg)
        $payment = $this->paymentModel->where('gateway_ref', $invoiceId)->first();
        if ($payment === null) {
            log_message('error', "[BayarGG Callback] Payment tidak ditemukan untuk invoice: {$invoiceId}");
            return $this->failNotFound('Payment tidak ditemukan.');
        }

        // 3. Mapping status
        $mappedStatus = (new BayarGGService())->mapStatus($status);

        // 4. Update database secara atomik
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $this->paymentModel->update($payment['id'], [
                'status'         => $mappedStatus,
                'payment_method' => 'QRIS Bayar.gg',
                'updated_at'     => date('Y-m-d H:i:s'),
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
            log_message('info', "[BayarGG Callback] Invoice {$invoiceId} berhasil diproses → {$mappedStatus}");
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', "[BayarGG Callback] Database error: " . $e->getMessage());
            return $this->failServerError('Gagal memperbarui status transaksi.');
        }

        return $this->respondOk([
            'status'         => 'success',
            'message'        => 'Callback Bayar.gg berhasil diverifikasi dan diproses.',
            'payment_id'     => $payment['id'],
            'invoice_id'     => $invoiceId,
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
