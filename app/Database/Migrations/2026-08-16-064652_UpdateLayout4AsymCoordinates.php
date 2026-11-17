<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateLayout4AsymCoordinates extends Migration
{
    public function up()
    {
        $coordinates = json_encode([
            ['slot' => 1, 'x' => 80,  'y' => 172, 'w' => 356, 'h' => 467],
            ['slot' => 2, 'x' => 608, 'y' => 172, 'w' => 355, 'h' => 619],
            ['slot' => 3, 'x' => 80,  'y' => 732, 'w' => 356, 'h' => 619],
            ['slot' => 4, 'x' => 608, 'y' => 884, 'w' => 355, 'h' => 467],
        ]);

        $this->db->table('frame_layouts')
            ->where('id', 'layout_4_asym')
            ->update(['slot_coordinates' => $coordinates]);
    }

    public function down()
    {
        $coordinates = json_encode([
            ['slot' => 1, 'x' => 50,  'y' => 50,   'w' => 525, 'h' => 700],
            ['slot' => 2, 'x' => 625, 'y' => 50,   'w' => 525, 'h' => 900],
            ['slot' => 3, 'x' => 50,  'y' => 800,  'w' => 525, 'h' => 900],
            ['slot' => 4, 'x' => 625, 'y' => 1000, 'w' => 525, 'h' => 700],
        ]);

        $this->db->table('frame_layouts')
            ->where('id', 'layout_4_asym')
            ->update(['slot_coordinates' => $coordinates]);
    }
}
