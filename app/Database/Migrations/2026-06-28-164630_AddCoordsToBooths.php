<?php
 
namespace App\Database\Migrations;
 
use CodeIgniter\Database\Migration;
 
class AddCoordsToBooths extends Migration
{
    public function up()
    {
        $fields = [
            'latitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,8',
                'null'       => true,
                'after'      => 'ip_address'
            ],
            'longitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '11,8',
                'null'       => true,
                'after'      => 'latitude'
            ]
        ];
        $this->forge->addColumn('booths', $fields);
    }
 
    public function down()
    {
        $this->forge->dropColumn('booths', ['latitude', 'longitude']);
    }
}
