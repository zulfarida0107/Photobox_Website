<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FrameLayoutSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        
        $data = [
            [
                'id'               => 'layout_1_single',
                'name'             => '1 Photo Single Window',
                'photo_count'      => 1,
                'aspect_ratio'     => '3:4',
                'slot_coordinates' => json_encode([
                    ['slot' => 1, 'x' => 91, 'y' => 207, 'w' => 1019, 'h' => 1390],
                ]),
                'image_path'       => 'uploads/layouts/layout_1_single.png',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'id'               => 'layout_4_grid',
                'name'             => '4 Grid Classic (2x2)',
                'photo_count'      => 4,
                'aspect_ratio'     => '3:4',
                'slot_coordinates' => json_encode([
                    ['slot' => 1, 'x' => 89,  'y' => 205, 'w' => 440, 'h' => 671],
                    ['slot' => 2, 'x' => 672, 'y' => 206, 'w' => 438, 'h' => 670],
                    ['slot' => 3, 'x' => 90,  'y' => 925, 'w' => 438, 'h' => 677],
                    ['slot' => 4, 'x' => 672, 'y' => 924, 'w' => 438, 'h' => 676],
                ]),
                'image_path'       => 'uploads/layouts/layout_4_grid.png',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'id'               => 'layout_4_asym',
                'name'             => '4 Grid Staggered / Asymmetric',
                'photo_count'      => 4,
                'aspect_ratio'     => '3:4',
                'slot_coordinates' => json_encode([
                    ['slot' => 1, 'x' => 50,  'y' => 50,   'w' => 525, 'h' => 700],
                    ['slot' => 2, 'x' => 625, 'y' => 50,   'w' => 525, 'h' => 900],
                    ['slot' => 3, 'x' => 50,  'y' => 800,  'w' => 525, 'h' => 900],
                    ['slot' => 4, 'x' => 625, 'y' => 1000, 'w' => 525, 'h' => 700],
                ]),
                'image_path'       => 'uploads/layouts/layout_4_asym.png',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'id'               => 'layout_6_grid',
                'name'             => '6 Grid Classic (2x3)',
                'photo_count'      => 6,
                'aspect_ratio'     => '1:1',
                'slot_coordinates' => json_encode([
                    ['slot' => 1, 'x' => 104, 'y' => 186,  'w' => 440, 'h' => 420],
                    ['slot' => 2, 'x' => 656, 'y' => 186,  'w' => 440, 'h' => 420],
                    ['slot' => 3, 'x' => 104, 'y' => 690,  'w' => 440, 'h' => 420],
                    ['slot' => 4, 'x' => 656, 'y' => 690,  'w' => 440, 'h' => 420],
                    ['slot' => 5, 'x' => 104, 'y' => 1194, 'w' => 440, 'h' => 420],
                    ['slot' => 6, 'x' => 656, 'y' => 1194, 'w' => 440, 'h' => 420],
                ]),
                'image_path'       => 'uploads/layouts/layout_6_grid.png',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'id'               => 'layout_6_grid_2',
                'name'             => '6 Grid (2x3) (2)',
                'photo_count'      => 6,
                'aspect_ratio'     => '1:1',
                'slot_coordinates' => json_encode([
                    ['slot' => 1, 'x' => 62,  'y' => 88,   'w' => 458, 'h' => 457],
                    ['slot' => 2, 'x' => 655, 'y' => 88,   'w' => 458, 'h' => 457],
                    ['slot' => 3, 'x' => 62,  'y' => 668,  'w' => 458, 'h' => 457],
                    ['slot' => 4, 'x' => 655, 'y' => 668,  'w' => 458, 'h' => 457],
                    ['slot' => 5, 'x' => 62,  'y' => 1245, 'w' => 458, 'h' => 457],
                    ['slot' => 6, 'x' => 655, 'y' => 1245, 'w' => 458, 'h' => 457],
                ]),
                'image_path'       => 'uploads/layouts/layout_6_grid_2.png',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'id'               => 'layout_8_grid',
                'name'             => '8 Grid Classic (2x4)',
                'photo_count'      => 8,
                'aspect_ratio'     => '4:3',
                'slot_coordinates' => json_encode([
                    ['slot' => 1, 'x' => 50,  'y' => 50,   'w' => 525, 'h' => 393],
                    ['slot' => 2, 'x' => 625, 'y' => 50,   'w' => 525, 'h' => 393],
                    ['slot' => 3, 'x' => 50,  'y' => 493,  'w' => 525, 'h' => 393],
                    ['slot' => 4, 'x' => 625, 'y' => 493,  'w' => 525, 'h' => 393],
                    ['slot' => 5, 'x' => 50,  'y' => 936,  'w' => 525, 'h' => 393],
                    ['slot' => 6, 'x' => 625, 'y' => 936,  'w' => 525, 'h' => 393],
                    ['slot' => 7, 'x' => 50,  'y' => 1379, 'w' => 525, 'h' => 393],
                    ['slot' => 8, 'x' => 625, 'y' => 1379, 'w' => 525, 'h' => 393],
                ]),
                'image_path'       => 'uploads/layouts/layout_8_grid.png',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
        ];

        // Truncate table first to avoid duplicates
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        $this->db->table('frame_layouts')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');

        $this->db->table('frame_layouts')->insertBatch($data);
    }
}
