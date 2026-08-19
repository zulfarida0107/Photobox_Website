<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterFramesTable extends Migration
{
    public function up()
    {
        $fields = $this->db->getFieldNames('frames');
        
        // Remove old columns if they exist
        if (in_array('category_id', $fields)) {
            $this->forge->dropColumn('frames', 'category_id');
        }
        if (in_array('layout_id', $fields)) {
            // Drop foreign key constraint first
            $this->db->query('ALTER TABLE frames DROP FOREIGN KEY fk_frames_layout_id');
            $this->forge->dropColumn('frames', 'layout_id');
        }

        // Add new columns
        $newFields = [];
        if (!in_array('category', $fields)) {
            $newFields['category'] = [
                'type'       => 'ENUM',
                'constraint' => ['pastel', 'cute', 'layout', 'minimalist', 'newspaper', 'magazine', 'retro 90s', 'y2k'],
                'default'    => 'minimalist',
                'after'      => 'name',
            ];
        }
        
        if (!in_array('price', $fields)) {
            $newFields['price'] = [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
                'after'      => 'file_path',
            ];
        }

        if (!empty($newFields)) {
            $this->forge->addColumn('frames', $newFields);
        }
    }

    public function down()
    {
        $fields = $this->db->getFieldNames('frames');
        if (in_array('category', $fields)) {
            $this->forge->dropColumn('frames', 'category');
        }
        if (in_array('price', $fields)) {
            $this->forge->dropColumn('frames', 'price');
        }
    }
}
