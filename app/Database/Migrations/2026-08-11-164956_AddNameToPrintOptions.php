<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNameToPrintOptions extends Migration
{
    public function up()
    {
        $this->forge->addColumn('print_options', [
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'default'    => 'Opsi Cetak',
                'after'      => 'id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('print_options', 'name');
    }
}
