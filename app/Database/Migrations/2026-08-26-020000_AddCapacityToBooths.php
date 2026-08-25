<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCapacityToBooths extends Migration
{
    public function up()
    {
         = [
            'capacity' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'default'    => 4,
                'null'       => true,
                'after'      => 'name',
            ],
        ];

        ->forge->addColumn('booths', );
    }

    public function down()
    {
        ->forge->dropColumn('booths', 'capacity');
    }
}
