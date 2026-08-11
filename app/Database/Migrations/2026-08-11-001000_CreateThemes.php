<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateThemes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'key' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'label' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('key', true);
        $this->forge->createTable('themes');
    }

    public function down()
    {
        $this->forge->dropTable('themes');
    }
}
