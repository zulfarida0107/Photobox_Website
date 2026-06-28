<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $db = $this->db;
        $now = date('Y-m-d H:i:s');

        $db->table('admins')->truncate();

        $db->table('admins')->insertBatch([
            [
                'id' => 'adm_001',
                'name' => 'Super Admin',
                'email' => 'admin@cuitboxstudio.com',
                'password' => password_hash('admin123', PASSWORD_BCRYPT),
                'role' => 'superadmin',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 'adm_002',
                'name' => 'Staff CuitBox',
                'email' => 'staff@cuitboxstudio.com',
                'password' => password_hash('staff123', PASSWORD_BCRYPT),
                'role' => 'staff',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
