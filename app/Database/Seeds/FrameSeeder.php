<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FrameSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id'         => 'FRM_001',
                'name'       => 'Pastel Dream',
                'category'   => 'Pastel',
                'file_path'  => 'uploads/frames/pastel_dream.png',
                'price'      => 10000.00,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'         => 'FRM_002',
                'name'       => 'Cute Kitty',
                'category'   => 'Cute',
                'file_path'  => 'uploads/frames/cute_kitty.png',
                'price'      => 12000.00,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'         => 'FRM_003',
                'name'       => 'Classic Minimalist',
                'category'   => 'Minimalist',
                'file_path'  => 'uploads/frames/classic_layout.png',
                'price'      => 15000.00,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'         => 'FRM_004',
                'name'       => 'Simple Minimalist',
                'category'   => 'Minimalist',
                'file_path'  => 'uploads/frames/simple_minimalist.png',
                'price'      => 10000.00,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'         => 'FRM_005',
                'name'       => 'Daily News',
                'category'   => 'Newspaper',
                'file_path'  => 'uploads/frames/daily_news.png',
                'price'      => 18000.00,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'         => 'FRM_006',
                'name'       => 'Vogue Cover',
                'category'   => 'Magazine',
                'file_path'  => 'uploads/frames/vogue_cover.png',
                'price'      => 20000.00,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'         => 'FRM_007',
                'name'       => '90s Vibe',
                'category'   => 'Retro 90s',
                'file_path'  => 'uploads/frames/90s_vibe.png',
                'price'      => 15000.00,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'         => 'FRM_008',
                'name'       => 'Y2K Cyber',
                'category'   => 'Y2k',
                'file_path'  => 'uploads/frames/y2k_cyber.png',
                'price'      => 15000.00,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Ensure table is empty before seeding
        $this->db->table('frames')->emptyTable();
        $this->db->table('frames')->insertBatch($data);
    }
}
