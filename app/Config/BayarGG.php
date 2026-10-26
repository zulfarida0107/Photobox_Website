<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Konfigurasi integrasi Payment Gateway Bayar.gg
 * 
 * Dokumentasi API: https://bayar.gg/api
 * Dashboard: https://www.bayar.gg/dashboard
 */
class BayarGG extends BaseConfig
{
    /**
     * API Key dari dashboard Bayar.gg (menu Developer → API Key)
     * Format: pk_live_xxx atau pk_test_xxx
     */
    public string $apiKey = '';

    /**
     * Webhook Secret Key untuk validasi callback signature
     * Format: whsec_xxx
     */
    public string $webhookSecret = '';

    /**
     * Metode pembayaran QRIS yang digunakan
     * Opsi: 'qris' (QRIS Admin), 'qris_bayar_gg', 'qris_user'
     */
    public string $paymentMethod = 'qris';

    /**
     * Base URL API Bayar.gg
     */
    public string $apiBaseUrl = 'https://www.bayar.gg/api';

    /**
     * Payment/Checkout URL default
     */
    public string $paymentUrl = 'https://www.bayar.gg/pay';

    /**
     * Durasi kedaluwarsa QRIS dalam menit
     */
    public int $expiryMinutes = 15;

    /**
     * Aktifkan konverter QRIS statis → dinamis (untuk mode qris_user)
     */
    public bool $useQrisConverter = false;

    /**
     * URL callback webhook (diisi otomatis dari base_url jika kosong)
     */
    public string $callbackUrl = '';

    public function __construct()
    {
        parent::__construct();

        $this->apiKey         = (string) env('bayargg.apiKey', '');
        $this->webhookSecret  = (string) env('bayargg.webhookSecret', '');
        $this->paymentMethod  = (string) env('bayargg.paymentMethod', 'qris');
        $this->expiryMinutes  = (int) env('bayargg.expiryMinutes', 15);
        $this->useQrisConverter = (bool) env('bayargg.useQrisConverter', false);

        $customCallback = (string) env('bayargg.callbackUrl', '');
        if (!empty($customCallback)) {
            $this->callbackUrl = $customCallback;
        }
    }

    /**
     * Mendapatkan callback URL final (otomatis dari base_url jika belum diset)
     */
    public function getCallbackUrl(): string
    {
        if (!empty($this->callbackUrl)) {
            return $this->callbackUrl;
        }

        return rtrim(site_url('api/payment/bayargg/callback'), '/');
    }
}
