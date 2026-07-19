<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFrameLayouts extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'VARCHAR', 'constraint' => 20],
            'name'             => ['type' => 'VARCHAR', 'constraint' => 100],
            'photo_count'      => ['type' => 'INT', 'default' => 4],
            'aspect_ratio'     => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'slot_coordinates' => ['type' => 'TEXT', 'null' => true],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('frame_layouts');
    }

    public function down()
    {
        $this->forge->dropTable('frame_layouts');
    }
}
