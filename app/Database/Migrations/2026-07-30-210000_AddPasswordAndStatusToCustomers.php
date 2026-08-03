<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPasswordAndStatusToCustomers extends Migration
{
    public function up()
    {
        // Tambah kolom password jika belum ada
        $fields = $this->db->getFieldNames('customers');

        if (!in_array('password', $fields)) {
            $this->forge->addColumn('customers', [
                'password' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                    'after'      => 'phone',
                ],
            ]);
        }

        if (!in_array('status', $fields)) {
            $this->forge->addColumn('customers', [
                'status' => [
                    'type'       => 'ENUM',
                    'constraint' => ['active', 'inactive'],
                    'default'    => 'active',
                    'null'       => false,
                    'after'      => 'password',
                ],
            ]);
        }
    }

    public function down()
    {
        $fields = $this->db->getFieldNames('customers');

        if (in_array('status', $fields)) {
            $this->forge->dropColumn('customers', 'status');
        }

        if (in_array('password', $fields)) {
            $this->forge->dropColumn('customers', 'password');
        }
    }
}
