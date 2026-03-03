<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Bank Transfer
            [
                'name' => 'BCA',
                'type' => 'bank',
                'description' => 'Bank Central Asia - Transfer antar bank',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Mandiri',
                'type' => 'bank',
                'description' => 'Bank Mandiri - Transfer antar bank',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'BNI',
                'type' => 'bank',
                'description' => 'Bank Negara Indonesia - Transfer antar bank',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'BRI',
                'type' => 'bank',
                'description' => 'Bank Rakyat Indonesia - Transfer antar bank',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'CIMB Niaga',
                'type' => 'bank',
                'description' => 'Bank CIMB Niaga - Transfer antar bank',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            // E-Wallet
            [
                'name' => 'OVO',
                'type' => 'e-wallet',
                'description' => 'OVO Cash - Pembayaran via aplikasi OVO',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'DANA',
                'type' => 'e-wallet',
                'description' => 'Dompet Digital DANA',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'GoPay',
                'type' => 'e-wallet',
                'description' => 'GoPay dari Gojek',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'ShopeePay',
                'type' => 'e-wallet',
                'description' => 'ShopeePay - Pembayaran via Shopee',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'LinkAja',
                'type' => 'e-wallet',
                'description' => 'LinkAja - Dompet digital',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('payment_methods')->insertBatch($data);
        echo "✅ PaymentMethodSeeder: 10 data berhasil ditambahkan\n";
    }
}