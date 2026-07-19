<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUploadPathsToSessions extends Migration
{
    public function up()
    {
        $this->forge->addColumn('sessions', [
            'final_image_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'drive_folder_url',
            ],
            'capture_folder' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'final_image_path',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('sessions', [
            'final_image_path',
            'capture_folder',
        ]);
    }
}