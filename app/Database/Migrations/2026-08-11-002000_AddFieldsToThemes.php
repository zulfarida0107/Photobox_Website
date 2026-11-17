<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldsToThemes extends Migration
{
    public function up()
    {
        $fields = [
            'capacity' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
                'after'      => 'label',
            ],
            'preview_image' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'capacity',
            ],
        ];

        $this->forge->addColumn('themes', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('themes', 'capacity');
        $this->forge->dropColumn('themes', 'preview_image');
    }
}
