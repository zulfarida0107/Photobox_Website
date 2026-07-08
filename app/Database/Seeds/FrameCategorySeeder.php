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
                'id'         => 'cat_retro_y2k',
                'name'       => 'Retro 90s / Y2K',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 'cat_newspaper_mag',
                'name'       => 'Newspaper / Magazine',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 'cat_minimalist_clean',
                'name'       => 'Minimalist Clean',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 'cat_cute_kawaii',
                'name'       => 'Cute & Kawaii',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 'cat_aesthetic_pastel',
                'name'       => 'Aesthetic Pastel',
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
