<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddActiveBookingToBooths extends Migration
{
    public function up()
    {
        $fields = [
            'active_booking_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'ip_address'
            ]
        ];
        $this->forge->addColumn('booths', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('booths', 'active_booking_id');
    }
}
