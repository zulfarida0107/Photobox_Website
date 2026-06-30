<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'booking_code' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'booth_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'schedule_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'booking_date' => ['type' => 'DATE'],
            'start_time' => ['type' => 'TIME'],
            'end_time' => ['type' => 'TIME'],
            'total_price' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'status' => ['type' => 'ENUM', 'constraint' => ['pending', 'confirmed', 'paid', 'completed', 'cancelled'], 'default' => 'pending'],
            'payment_status' => ['type' => 'ENUM', 'constraint' => ['unpaid', 'paid', 'expired'], 'default' => 'unpaid'],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('booking_code');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('booth_id', 'booths', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('schedule_id', 'schedules', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('bookings');
    }

    public function down()
    {
        $this->forge->dropTable('bookings');
    }
}