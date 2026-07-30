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
            'category_id' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'layout_id' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'file_path' => ['type' => 'TEXT'],
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