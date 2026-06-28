<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsActiveToFrames extends Migration
{
    public function up()
    {
        $fields = [
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'after'      => 'file_path'
            ]
        ];
        $this->forge->addColumn('frames', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('frames', 'is_active');
    }
}
