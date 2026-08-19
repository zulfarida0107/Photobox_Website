<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FrameCategorySeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $data = [
            [
                'id'         => 'cat_pastel',
                'name'       => 'pastel',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 'cat_cute',
                'name'       => 'cute',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 'cat_layout',
                'name'       => 'layout',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 'cat_minimalist',
                'name'       => 'minimalist',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 'cat_newspaper',
                'name'       => 'newspaper',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 'cat_magazine',
                'name'       => 'magazine',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 'cat_retro_90s',
                'name'       => 'retro 90s',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 'cat_y2k',
                'name'       => 'y2k',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Truncate table first to avoid duplicate keys when re-running
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        $this->db->table('frame_categories')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');

        $this->db->table('frame_categories')->insertBatch($data);
    }
}
