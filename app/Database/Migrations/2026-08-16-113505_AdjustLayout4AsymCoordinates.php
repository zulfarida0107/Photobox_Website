<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AdjustLayout4AsymCoordinates extends Migration
{
    public function up()
    {
        $coordinates = json_encode([
            ['slot' => 1, 'x' => 91,  'y' => 206,  'w' => 405, 'h' => 559],
            ['slot' => 2, 'x' => 691, 'y' => 206,  'w' => 403, 'h' => 741],
            ['slot' => 3, 'x' => 91,  'y' => 876,  'w' => 405, 'h' => 741],
            ['slot' => 4, 'x' => 691, 'y' => 1058, 'w' => 403, 'h' => 559],
        ]);

        $this->db->table('frame_layouts')
            ->where('id', 'layout_4_asym')
            ->update(['slot_coordinates' => $coordinates]);
    }

    public function down()
    {
        // Revert to coordinates set by the previous migration
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
}
