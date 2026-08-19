<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFrames extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'VARCHAR', 'constraint' => 20],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'category' => ['type' => 'VARCHAR', 'constraint' => 20],
            'file_path' => ['type' => 'TEXT'],
            'price' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('frames');
    }

    public function down()
    {
        $this->forge->dropTable('frames');
    }
}