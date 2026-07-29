<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropPhotoCountFromFrames extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('frames', 'photo_count');
    }

    public function down()
    {
        $fields = [
            'photo_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 6,
                'after' => 'file_path'
            ]
        ];
        $this->forge->addColumn('frames', $fields);
    }
}
