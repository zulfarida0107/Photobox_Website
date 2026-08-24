<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateLayout6GridClassicCoordinates extends Migration
{
    public function up()
    {
        $newCoords = json_encode([
            ['slot' => 1, 'x' => 104, 'y' => 186, 'w' => 440, 'h' => 420],
            ['slot' => 2, 'x' => 656, 'y' => 186, 'w' => 440, 'h' => 420],
            ['slot' => 3, 'x' => 104, 'y' => 690, 'w' => 440, 'h' => 420],
            ['slot' => 4, 'x' => 656, 'y' => 690, 'w' => 440, 'h' => 420],
            ['slot' => 5, 'x' => 104, 'y' => 1194, 'w' => 440, 'h' => 420],
            ['slot' => 6, 'x' => 656, 'y' => 1194, 'w' => 440, 'h' => 420],
        ]);

        $this->db->table('frame_layouts')
            ->where('id', 'layout_6_grid')
            ->update(['slot_coordinates' => $newCoords]);
    }

    public function down()
    {
        $oldCoords = json_encode([
            ['slot' => 1, 'x' => 50,  'y' => 50,   'w' => 525, 'h' => 525],
            ['slot' => 2, 'x' => 625, 'y' => 50,   'w' => 525, 'h' => 525],
            ['slot' => 3, 'x' => 50,  'y' => 625,  'w' => 525, 'h' => 525],
            ['slot' => 4, 'x' => 625, 'y' => 625,  'w' => 525, 'h' => 525],
            ['slot' => 5, 'x' => 50,  'y' => 1200, 'w' => 525, 'h' => 525],
            ['slot' => 6, 'x' => 625, 'y' => 1200, 'w' => 525, 'h' => 525],
        ]);

        $this->db->table('frame_layouts')
            ->where('id', 'layout_6_grid')
            ->update(['slot_coordinates' => $oldCoords]);
    }
}
