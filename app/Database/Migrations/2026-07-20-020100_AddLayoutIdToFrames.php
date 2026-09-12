<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLayoutIdToFrames extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('frames') && !$this->db->fieldExists('layout_id', 'frames')) {
            $afterCol = $this->db->fieldExists('category', 'frames') ? 'category' : 'name';
            $fields = [
                'layout_id' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'null'       => true,
                    'after'      => $afterCol,
                ]
            ];
            $this->forge->addColumn('frames', $fields);

            // Add foreign key constraint if table frame_layouts exists
            if ($this->db->tableExists('frame_layouts')) {
                $this->db->query('ALTER TABLE frames ADD CONSTRAINT fk_frames_layout_id FOREIGN KEY (layout_id) REFERENCES frame_layouts(id) ON DELETE SET NULL ON UPDATE CASCADE');
            }
        }
    }

    public function down()
    {
        if ($this->db->tableExists('frames') && $this->db->fieldExists('layout_id', 'frames')) {
            try {
                $this->db->query('ALTER TABLE frames DROP FOREIGN KEY fk_frames_layout_id');
            } catch (\Throwable $e) {
                // Ignore if constraint does not exist
            }
            $this->forge->dropColumn('frames', 'layout_id');
        }
    }
}
