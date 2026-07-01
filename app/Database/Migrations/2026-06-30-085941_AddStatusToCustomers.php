<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToCustomers extends Migration
{
    public function up()
    {
        $fields = [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default'    => 'active',
                'after'      => 'phone'
            ]
        ];
        $this->forge->addColumn('customers', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('customers', 'status');
    }
}

