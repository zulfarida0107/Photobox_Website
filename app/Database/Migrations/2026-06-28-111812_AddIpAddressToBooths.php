<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIpAddressToBooths extends Migration
{
    public function up()
    {
        $fields = [
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
                'after'      => 'name'
            ]
        ];
        $this->forge->addColumn('booths', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('booths', 'ip_address');
    }
}
