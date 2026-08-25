<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLocations extends Migration
{
    public function up()
    {
        // 1. Buat Tabel locations
        $this->forge->addField([
            'id' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'city' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'address' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'latitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,8',
                'null'       => true,
            ],
            'longitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '11,8',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default'    => 'active',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('locations', true);

        // 2. Tambahkan kolom location_id ke tabel booths jika belum ada
        if ($this->db->tableExists('booths') && !$this->db->fieldExists('location_id', 'booths')) {
            $this->forge->addColumn('booths', [
                'location_id' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'null'       => true,
                    'after'      => 'id',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('booths') && $this->db->fieldExists('location_id', 'booths')) {
            $this->forge->dropColumn('booths', 'location_id');
        }
        $this->forge->dropTable('locations', true);
    }
}
