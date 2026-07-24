<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CuteKawaiiFrameSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // 1. Ensure category cat_cute_kawaii
        $catExists = $this->db->table('frame_categories')->where('id', 'cat_cute_kawaii')->countAllResults();
        if ($catExists == 0) {
            $this->db->table('frame_categories')->insert([
                'id' => 'cat_cute_kawaii',
                'name' => 'Cute & Kawaii',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 2. Insert or update frame frm_kawaii_love_001
        $frameExists = $this->db->table('frames')->where('id', 'frm_kawaii_love_001')->countAllResults();
        if ($frameExists > 0) {
            $this->db->table('frames')->where('id', 'frm_kawaii_love_001')->update([
                'name'        => 'Cute Kawaii Love Heart (6 Grid)',
                'file_path'   => 'uploads/frames/cuitbox_kawaii_love_heart.png',
                'layout_id'   => 'layout_6_grid_2',
                'category_id' => 'cat_cute_kawaii',
                'is_active'   => 1,
                'updated_at'  => $now,
            ]);
        } else {
            $this->db->table('frames')->insert([
                'id'          => 'frm_kawaii_love_001',
                'name'        => 'Cute Kawaii Love Heart (6 Grid)',
                'file_path'   => 'uploads/frames/cuitbox_kawaii_love_heart.png',
                'layout_id'   => 'layout_6_grid_2',
                'category_id' => 'cat_cute_kawaii',
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }
    }
}
