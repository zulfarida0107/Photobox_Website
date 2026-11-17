<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddImageToBooths extends Migration
{
    public function up()
    {
        $fields = [
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'capacity',
            ],
        ];

        $this->forge->addColumn('booths', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('booths', 'image');
    }
}