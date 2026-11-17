<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCapacityToBooths extends Migration
{
    public function up()
    {
        $fields = [
            'capacity' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'default'    => 4,
                'null'       => true,
                'after'      => 'name',
            ],
        ];

        $this->forge->addColumn('booths', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('booths', 'capacity');
    }
}
