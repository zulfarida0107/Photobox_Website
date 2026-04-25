<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePhotos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'VARCHAR', 'constraint' => 20],
            'session_id' => ['type' => 'VARCHAR', 'constraint' => 20],
            'file_path' => ['type' => 'TEXT'],
            'drive_file_id' => ['type' => 'VARCHAR', 'constraint' => 100],
            'shot_number' => ['type' => 'INT'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('session_id', 'sessions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('photos');
    }

    public function down()
    {
        $this->forge->dropTable('photos');
    }
}