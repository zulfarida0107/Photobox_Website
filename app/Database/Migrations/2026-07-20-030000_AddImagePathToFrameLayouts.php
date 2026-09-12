<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddImagePathToFrameLayouts extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('frame_layouts') && !$this->db->fieldExists('image_path', 'frame_layouts')) {
            $fields = [
                'image_path' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                    'after'      => 'slot_coordinates',
                ],
            ];

            $this->forge->addColumn('frame_layouts', $fields);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('frame_layouts') && $this->db->fieldExists('image_path', 'frame_layouts')) {
            $this->forge->dropColumn('frame_layouts', 'image_path');
        }
    }
}
