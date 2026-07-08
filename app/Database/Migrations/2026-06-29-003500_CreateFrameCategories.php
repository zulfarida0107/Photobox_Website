<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFrameCategories extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'VARCHAR', 'constraint' => 20],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('frame_categories');
    }

    public function down()
    {
        $this->forge->dropTable('frame_categories');
    }
}
