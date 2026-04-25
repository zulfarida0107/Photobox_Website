<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePayments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'VARCHAR', 'constraint' => 20],
            'booking_id' => ['type' => 'VARCHAR', 'constraint' => 20],
            'print_option_id' => ['type' => 'VARCHAR', 'constraint' => 20],
            'amount' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'gateway_ref' => ['type' => 'VARCHAR', 'constraint' => 100],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending','paid','failed'],
                'default' => 'pending'
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('booking_id', 'bookings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('print_option_id', 'print_options', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('payments');
    }

    public function down()
    {
        $this->forge->dropTable('payments');
    }
}
