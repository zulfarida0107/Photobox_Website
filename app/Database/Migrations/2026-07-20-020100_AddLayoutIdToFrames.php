<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLayoutIdToFrames extends Migration
{
    public function up()
    {
        $fields = [
            'layout_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'category_id'
            ]
        ];
        $this->forge->addColumn('frames', $fields);

        // Add foreign key constraint
        $this->db->query('ALTER TABLE frames ADD CONSTRAINT fk_frames_layout_id FOREIGN KEY (layout_id) REFERENCES frame_layouts(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        // Drop foreign key first
        $this->db->query('ALTER TABLE frames DROP FOREIGN KEY fk_frames_layout_id');
        $this->forge->dropColumn('frames', 'layout_id');
    }
}
