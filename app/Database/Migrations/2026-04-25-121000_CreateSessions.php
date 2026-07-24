<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSessions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'VARCHAR', 'constraint' => 20],
            'booking_id' => ['type' => 'VARCHAR', 'constraint' => 20],
            'frame_id' => ['type' => 'VARCHAR', 'constraint' => 20],
            'filter_id' => ['type' => 'VARCHAR', 'constraint' => 20],
            'email_recipient' => ['type' => 'VARCHAR', 'constraint' => 100],
            'drive_folder_url' => ['type' => 'TEXT'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'email_recipient' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],

            'drive_folder_id' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],

            'drive_folder_url' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('booking_id', 'bookings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('frame_id', 'frames', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('filter_id', 'filters', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('sessions');
    }

    public function down()
    {
        $this->forge->dropTable('sessions');
    }
}
