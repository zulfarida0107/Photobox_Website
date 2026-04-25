<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSlots extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'VARCHAR', 'constraint' => 20],
            'booth_id' => ['type' => 'VARCHAR', 'constraint' => 20],
            'slot_date' => ['type' => 'DATE'],
            'start_time' => ['type' => 'TIME'],
            'max_bookings' => ['type' => 'INT'],
            'booked_count' => ['type' => 'INT', 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('booth_id', 'booths', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('slots');
    }

    public function down()
    {
        $this->forge->dropTable('slots');
    }
}