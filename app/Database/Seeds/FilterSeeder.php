<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FilterSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id'         => 'FLT-00001',
                'name'       => 'Normal / Original',
                'css_value'  => 'none',
                'is_active'  => true,
            ],
            [
                'id'         => 'FLT-00002',
                'name'       => 'Hitam Putih (B/W)',
                'css_value'  => 'grayscale(100%)',
                'is_active'  => true,
            ],
            [
                'id'         => 'FLT-00003',
                'name'       => 'Klasik Sepia',
                'css_value'  => 'sepia(80%) contrast(110%)',
                'is_active'  => true,
            ],
            [
                'id'         => 'FLT-00004',
                'name'       => 'Retro / Vintage',
                'css_value'  => 'sepia(40%) saturate(150%) hue-rotate(-20deg)',
                'is_active'  => true,
            ],
            [
                'id'         => 'FLT-00005',
                'name'       => 'Soft Pastel',
                'css_value'  => 'saturate(80%) brightness(120%) contrast(90%)',
                'is_active'  => false, // Nonaktif by default as per mockup
            ],
            [
                'id'         => 'FLT-00006',
                'name'       => 'High Contrast',
                'css_value'  => 'contrast(150%) saturate(120%)',
                'is_active'  => true,
            ],
        ];

        foreach ($data as $filter) {
            $existing = $this->db->table('filters')->where('id', $filter['id'])->countAllResults();
            if ($existing == 0) {
                // Add timestamps
                $filter['created_at'] = date('Y-m-d H:i:s');
                $filter['updated_at'] = date('Y-m-d H:i:s');
                $this->db->table('filters')->insert($filter);
            } else {
                $filter['updated_at'] = date('Y-m-d H:i:s');
                $this->db->table('filters')->where('id', $filter['id'])->update($filter);
            }
        }
    }
}
