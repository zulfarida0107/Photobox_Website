<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterFramesTable extends Migration
{
    public function up()
    {
        $fields = $this->db->getFieldNames('frames');
        
        // Remove old category_id column if it exists
        if (in_array('category_id', $fields)) {
            $this->forge->dropColumn('frames', 'category_id');
        }

        // Add new columns if missing
        $newFields = [];
        if (!in_array('category', $fields)) {
            $newFields['category'] = [
                'type'       => 'ENUM',
                'constraint' => ['Pastel', 'Cute', 'Minimalist', 'Newspaper', 'Magazine', 'Retro 90s', 'Y2k'],
                'default'    => 'Minimalist',
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
