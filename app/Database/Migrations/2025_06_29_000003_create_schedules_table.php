<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSchedulesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'date' => ['type' => 'DATE'],
            'start_time' => ['type' => 'TIME'],
            'end_time' => ['type' => 'TIME'],
            'session_name' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'max_bookings' => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
            'booked_count' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'is_available' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('schedules');
    }

    public function down()
    {
        $this->forge->dropTable('schedules');
    }
}