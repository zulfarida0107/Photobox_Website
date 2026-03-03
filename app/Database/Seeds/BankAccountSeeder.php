<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'bank_name' => 'BCA',
                'account_number' => '1234567890',
                'account_name' => 'PT Topup Indonesia',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'bank_name' => 'Mandiri',
                'account_number' => '1234567891',
                'account_name' => 'PT Topup Indonesia',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'bank_name' => 'BNI',
                'account_number' => '1234567892',
                'account_name' => 'PT Topup Indonesia',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'bank_name' => 'BRI',
                'account_number' => '1234567893',
                'account_name' => 'PT Topup Indonesia',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'bank_name' => 'CIMB Niaga',
                'account_number' => '1234567894',
                'account_name' => 'PT Topup Indonesia',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('bank_accounts')->insertBatch($data);
        echo "✅ BankAccountSeeder: 5 data berhasil ditambahkan\n";
    }
}