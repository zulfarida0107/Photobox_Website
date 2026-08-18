<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateCuitboxFramesCategoryAndLayout extends Migration
{
    public function up()
    {
        // Update frm_cuit_001
        $this->db->table('frames')
            ->where('id', 'frm_cuit_001')
            ->update([
                'category' => 'cute',
                'layout_id' => 'layout_4_grid',
                'file_path' => 'uploads/frames/frame_cuitbox_black_white_4.png'
            ]);

        // Update frm_cuit_002
        $this->db->table('frames')
            ->where('id', 'frm_cuit_002')
            ->update([
                'category' => 'cute',
                'layout_id' => 'layout_4_grid',
                'file_path' => 'uploads/frames/frame_cuitbox_black_pink_4.png'
            ]);

        // Update frm_cuit_003
        $this->db->table('frames')
            ->where('id', 'frm_cuit_003')
            ->update([
                'category' => 'cute',
                'layout_id' => 'layout_4_grid',
                'file_path' => 'uploads/frames/frame_cuitbox_pink_plaid_4.png'
            ]);

        // Update frm_cuit_004
        $this->db->table('frames')
            ->where('id', 'frm_cuit_004')
            ->update([
                'category' => 'cute',
                'layout_id' => 'layout_4_grid',
                'file_path' => 'uploads/frames/frame_cuitbox_blue_plaid_4.png'
            ]);

        // Update frm_kawaii_love_001
        $this->db->table('frames')
            ->where('id', 'frm_kawaii_love_001')
            ->update([
                'category' => 'cute',
                'layout_id' => 'layout_6_grid_2',
                'file_path' => 'uploads/frames/frame_cuitbox_kawaii_love_heart_6.png'
            ]);
    }

    public function down()
    {
        // Revert to old values
        $this->db->table('frames')
            ->where('id', 'frm_cuit_001')
            ->update([
                'category' => 'minimalist',
                'layout_id' => 'layout_1_single',
                'file_path' => 'uploads/frames/cuitbox_black_white.png'
            ]);

        $this->db->table('frames')
            ->where('id', 'frm_cuit_002')
            ->update([
                'category' => 'minimalist',
                'layout_id' => 'layout_1_single',
                'file_path' => 'uploads/frames/cuitbox_black_pink.png'
            ]);

        $this->db->table('frames')
            ->where('id', 'frm_cuit_003')
            ->update([
                'category' => 'minimalist',
                'layout_id' => 'layout_1_single',
                'file_path' => 'uploads/frames/cuitbox_pink_plaid.png'
            ]);

        $this->db->table('frames')
            ->where('id', 'frm_cuit_004')
            ->update([
                'category' => 'minimalist',
                'layout_id' => 'layout_1_single',
                'file_path' => 'uploads/frames/cuitbox_blue_plaid.png'
            ]);

        $this->db->table('frames')
            ->where('id', 'frm_kawaii_love_001')
            ->update([
                'category' => 'minimalist',
                'layout_id' => 'layout_6_grid_2',
                'file_path' => 'uploads/frames/cuitbox_kawaii_love_heart.png'
            ]);
    }
}
