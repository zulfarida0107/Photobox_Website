<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DeactivateDemoFrames extends Migration
{
    public function up()
    {
        // Deactivate demo frames that have broken preview images (IDs 1, 2, 3, 4, 5, 6)
        $this->db->table('frames')
            ->whereIn('id', ['1', '2', '3', '4', '5', '6'])
            ->update(['is_active' => 0]);
    }

    public function down()
    {
        // Re-activate demo frames
        $this->db->table('frames')
            ->whereIn('id', ['1', '2', '3', '4', '5', '6'])
            ->update(['is_active' => 1]);
    }
}
