<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePrintOptions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'VARCHAR', 'constraint' => 20],
            'copies' => ['type' => 'INT'],
            'extra_price' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('print_options');
    }

    public function down()
    {
        $this->forge->dropTable('print_options');
    }
}