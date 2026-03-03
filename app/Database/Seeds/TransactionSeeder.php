<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'invoice_number' => 'INV-20260301-ABCDE12345',
                'user_id' => 1,
                'game_id' => 1,
                'payment_method_id' => 1,
                'amount' => 100000.00,
                'status' => 'success',
                'payment_proof' => 'proof_20260301_1.jpg',
                'note' => 'Pembayaran berhasil diproses',
                'created_at' => '2026-03-01 10:00:00',
                'updated_at' => '2026-03-01 10:30:00'
            ],
            [
                'invoice_number' => 'INV-20260301-FGHIJ67890',
                'user_id' => 2,
                'game_id' => 2,
                'payment_method_id' => 2,
                'amount' => 200000.00,
                'status' => 'success',
                'payment_proof' => 'proof_20260301_2.jpg',
                'note' => null,
                'created_at' => '2026-03-01 14:00:00',
                'updated_at' => '2026-03-01 14:45:00'
            ],
            [
                'invoice_number' => 'INV-20260302-KLMNO11223',
                'user_id' => 1,
                'game_id' => 3,
                'payment_method_id' => 3,
                'amount' => 50000.00,
                'status' => 'pending',
                'payment_proof' => null,
                'note' => 'Menunggu pembayaran dari user',
                'created_at' => '2026-03-02 09:15:00',
                'updated_at' => '2026-03-02 09:15:00'
            ],
            [
                'invoice_number' => 'INV-20260302-PQRST44556',
                'user_id' => 3,
                'game_id' => 4,
                'payment_method_id' => 4,
                'amount' => 150000.00,
                'status' => 'pending',
                'payment_proof' => null,
                'note' => null,
                'created_at' => '2026-03-02 11:30:00',
                'updated_at' => '2026-03-02 11:30:00'
            ],
            [
                'invoice_number' => 'INV-20260302-UVWXY77889',
                'user_id' => 2,
                'game_id' => 5,
                'payment_method_id' => 5,
                'amount' => 300000.00,
                'status' => 'pending',
                'payment_proof' => null,
                'note' => 'Menunggu pembayaran',
                'created_at' => '2026-03-02 13:20:00',
                'updated_at' => '2026-03-02 13:20:00'
            ]
        ];

        $this->db->table('transactions')->insertBatch($data);
        echo "✅ TransactionSeeder: 5 data berhasil ditambahkan\n";
    }
}