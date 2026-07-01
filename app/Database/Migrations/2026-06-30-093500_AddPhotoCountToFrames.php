<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhotoCountToFrames extends Migration
{
    public function up()
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

        // Automatically set photo_count = 1 for newspaper/news frames
        $this->db->query("UPDATE frames SET photo_count = 1 WHERE name LIKE '%News%'");
    }

    public function down()
    {
        $this->forge->dropColumn('frames', 'photo_count');
    }
}
