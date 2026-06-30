<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'booking_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'amount' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'payment_method' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'payment_status' => ['type' => 'ENUM', 'constraint' => ['pending', 'paid', 'failed', 'expired'], 'default' => 'pending'],
            'transaction_id' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'payment_date' => ['type' => 'DATETIME', 'null' => true],
            'qr_code' => ['type' => 'TEXT', 'null' => true],
            'expiry_time' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('booking_id', 'bookings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('payments');
    }

    public function down()
    {
        $this->forge->dropTable('payments');
    }
}