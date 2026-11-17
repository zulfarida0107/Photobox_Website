<?php

namespace App\Libraries;

use Config\BayarGG;

/**
 * Service library untuk integrasi REST API Bayar.gg
 * 
 * Menangani:
 * - Pembuatan invoice / transaksi QRIS dinamis
 * - Pengecekan status pembayaran
 * - Validasi webhook callback signature
 */
class BayarGGService
{
    private BayarGG $config;

    public function __construct(?BayarGG $config = null)
    {
        $this->config = $config ?? config('BayarGG');
    }

    /**
     * Membuat invoice pembayaran QRIS dinamis baru
     *
     * @param int    $amount      Nominal pembayaran (min 5000, max 500000 untuk QRIS Admin)
     * @param string $description Deskripsi transaksi
     * @param string $orderId     Order ID internal (untuk referensi di callback)
     * @param array  $customer    Data pelanggan opsional ['name','email','phone']
     * @return array Response dari Bayar.gg API
     * @throws \RuntimeException Jika API Key belum dikonfigurasi atau request gagal
     */
    public function createPayment(
        int    $amount,
        string $description = '',
        string $orderId = '',
        array  $customer = []
    ): array {
        if (empty($this->config->apiKey)) {
            throw new \RuntimeException('Bayar.gg API Key belum dikonfigurasi di .env');
        }

        $payload = [
            'amount'         => $amount,
            'payment_url'    => $this->config->paymentUrl,
            'payment_method' => $this->config->paymentMethod,
            'callback_url'   => $this->config->getCallbackUrl(),
        ];

        // Deskripsi transaksi
        if (!empty($description)) {
            $payload['description'] = $description;
        } elseif (!empty($orderId)) {
            $payload['description'] = 'Pembayaran CuitBox #' . $orderId;
        }

        // Data pelanggan (opsional)
        if (!empty($customer['name'])) {
            $payload['customer_name'] = $customer['name'];
        }
        if (!empty($customer['email'])) {
            $payload['customer_email'] = $customer['email'];
        }
        if (!empty($customer['phone'])) {
            $payload['customer_phone'] = $customer['phone'];
        }

        // QRIS Converter
        if ($this->config->useQrisConverter) {
            $payload['use_qris_converter'] = true;
        }

        $response = $this->httpPost('/create-payment.php', $payload);

        $isSuccess = ($response['success'] ?? false) === true || ($response['status'] ?? false) === true;
        if (!$isSuccess) {
            $errorMsg = $response['error'] ?? $response['message'] ?? 'Unknown error dari Bayar.gg API';
            log_message('error', '[BayarGG] Create payment gagal: ' . $errorMsg . ' | Payload: ' . json_encode($payload));
            throw new \RuntimeException('Bayar.gg: ' . $errorMsg);
        }

        log_message('info', '[BayarGG] Invoice berhasil dibuat: ' . json_encode($response['data'] ?? []));

        return $response;
    }

    /**
     * Mengecek status pembayaran invoice
     *
     * @param string $invoiceId Invoice ID dari Bayar.gg (contoh: BAYAR-XXXXX123)
     * @return array Response status dari Bayar.gg API
     * @throws \RuntimeException Jika request gagal
     */
    public function checkPayment(string $invoiceId): array
    {
        if (empty($this->config->apiKey)) {
            throw new \RuntimeException('Bayar.gg API Key belum dikonfigurasi di .env');
        }

        $url = $this->config->apiBaseUrl . '/check-payment.php?invoice=' . urlencode($invoiceId);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_HTTPHEADER     => [
                'X-API-Key: ' . $this->config->apiKey,
                'Accept: application/json',
            ],
        ]);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($result === false) {
            log_message('error', '[BayarGG] Check payment cURL error: ' . $curlError);
            throw new \RuntimeException('Gagal menghubungi Bayar.gg: ' . $curlError);
        }

        $response = json_decode($result, true);
        if (!is_array($response)) {
            log_message('error', '[BayarGG] Check payment response invalid (HTTP ' . $httpCode . '): ' . $result);
            throw new \RuntimeException('Bayar.gg mengembalikan respons tidak valid');
        }

        return $response;
    }

    /**
     * Validasi signature webhook callback dari Bayar.gg
     *
     * @param array  $payload           Data JSON dari callback
     * @param string $incomingSignature  Header signature yang diterima
     * @return bool True jika signature valid
     */
    public function validateCallback(array $payload, string $incomingSignature): bool
    {
        if (empty($this->config->webhookSecret)) {
            // Jika webhook secret belum diset, terima semua (development mode)
            log_message('warning', '[BayarGG] Webhook secret belum dikonfigurasi, melewati validasi signature.');
            return true;
        }

        // Susun string-to-sign sesuai dokumentasi Bayar.gg
        $stringToSign = ($payload['invoice_id'] ?? '')
            . ($payload['status'] ?? '')
            . ($payload['final_amount'] ?? $payload['amount'] ?? '')
            . ($payload['timestamp'] ?? '');

        $calculatedSignature = hash_hmac('sha256', $stringToSign, $this->config->webhookSecret);

        return hash_equals($calculatedSignature, $incomingSignature);
    }

    /**
     * Mapping status Bayar.gg ke status internal aplikasi
     *
     * @param string $bayarggStatus Status dari Bayar.gg ('paid','pending','expired','cancelled')
     * @return string Status internal ('paid','pending','failed')
     */
    public function mapStatus(string $bayarggStatus): string
    {
        return match (strtolower($bayarggStatus)) {
            'paid', 'settlement', 'completed' => 'paid',
            'expired', 'cancelled', 'failed', 'denied' => 'failed',
            default => 'pending',
        };
    }

    /**
     * Kirim HTTP POST ke Bayar.gg API
     */
    private function httpPost(string $endpoint, array $data): array
    {
        $url = $this->config->apiBaseUrl . $endpoint;
        $jsonPayload = json_encode($data);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $jsonPayload,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'X-API-Key: ' . $this->config->apiKey,
                'Accept: application/json',
                'Content-Length: ' . strlen($jsonPayload),
            ],
        ]);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($result === false) {
            log_message('error', '[BayarGG] HTTP POST cURL error: ' . $curlError . ' | URL: ' . $url);
            throw new \RuntimeException('Gagal menghubungi server Bayar.gg: ' . $curlError);
        }

        $decoded = json_decode($result, true);
        if (!is_array($decoded)) {
            log_message('error', '[BayarGG] HTTP POST response invalid (HTTP ' . $httpCode . '): ' . $result);
            return ['status' => false, 'message' => 'Respons API tidak valid (HTTP ' . $httpCode . ')'];
        }

        return $decoded;
    }
}
