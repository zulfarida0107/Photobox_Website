<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateBoothStatusEnum extends Migration
{
    public function up()
    {
        $fields = [
            'status' => [
                'name'       => 'status',
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive', 'maintenance'],
                'default'    => 'active'
            ]
        ];
        $this->forge->modifyColumn('booths', $fields);
    }

    public function down()
    {
        $fields = [
            'status' => [
                'name'       => 'status',
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default'    => 'active'
            ]
        ];
        $this->forge->modifyColumn('booths', $fields);
    }
}
