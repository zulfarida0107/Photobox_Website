<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateLayout6Grid2Coordinates extends Migration
{
    public function up()
    {
        $newCoords = '[{"slot":1,"x":25,"y":21,"w":551,"h":557},{"slot":2,"x":626,"y":19,"w":549,"h":554},{"slot":3,"x":25,"y":621,"w":551,"h":555},{"slot":4,"x":626,"y":621,"w":549,"h":555},{"slot":5,"x":25,"y":1222,"w":551,"h":557},{"slot":6,"x":626,"y":1223,"w":549,"h":555}]';

        $this->db->table('frame_layouts')
            ->where('id', 'layout_6_grid_2')
            ->update(['slot_coordinates' => $newCoords]);
    }

    public function down()
    {
        $oldCoords = '[{"slot":1,"x":62,"y":88,"w":458,"h":457},{"slot":2,"x":655,"y":88,"w":458,"h":457},{"slot":3,"x":62,"y":668,"w":458,"h":457},{"slot":4,"x":655,"y":668,"w":458,"h":457},{"slot":5,"x":62,"y":1245,"w":458,"h":457},{"slot":6,"x":655,"y":1245,"w":458,"h":457}]';

        $this->db->table('frame_layouts')
            ->where('id', 'layout_6_grid_2')
            ->update(['slot_coordinates' => $oldCoords]);
    }
}
