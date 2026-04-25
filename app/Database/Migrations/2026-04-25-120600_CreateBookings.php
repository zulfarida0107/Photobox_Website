<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookings extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'VARCHAR', 'constraint' => 20],
            'booking_code' => ['type' => 'VARCHAR', 'constraint' => 50],
            'customer_id' => ['type' => 'VARCHAR', 'constraint' => 20],
            'slot_id' => ['type' => 'VARCHAR', 'constraint' => 20],
            'booth_id' => ['type' => 'VARCHAR', 'constraint' => 20],
            'package_id' => ['type' => 'VARCHAR', 'constraint' => 20],
            'queue_number' => ['type' => 'INT'],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending','paid','completed','cancelled'],
                'default' => 'pending'
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('booking_code');
        $this->forge->addForeignKey('customer_id', 'customers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('slot_id', 'slots', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('booth_id', 'booths', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('package_id', 'packages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('bookings');
    }

    public function down()
    {
        $this->forge->dropTable('bookings');
    }
}