<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategoryIdToFrames extends Migration
{
    public function up()
    {
        $fields = [
            'category_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'name'
            ]
        ];
        $this->forge->addColumn('frames', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('frames', 'category_id');
    }
}
