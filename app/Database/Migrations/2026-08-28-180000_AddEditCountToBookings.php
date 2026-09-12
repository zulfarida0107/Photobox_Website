<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEditCountToBookings extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('bookings') && !$this->db->fieldExists('edit_count', 'bookings')) {
            $this->forge->addColumn('bookings', [
                'edit_count' => [
                    'type'       => 'INT',
                    'constraint' => 5,
                    'unsigned'   => true,
                    'default'    => 0,
                    'null'       => false,
                    'after'      => 'status',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('bookings') && $this->db->fieldExists('edit_count', 'bookings')) {
            $this->forge->dropColumn('bookings', 'edit_count');
        }
    }
}
