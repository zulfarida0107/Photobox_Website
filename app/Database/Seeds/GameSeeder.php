<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GameSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Mobile Legends',
                'description' => 'Game MOBA populer dari Moonton dengan jutaan pemain aktif',
                'category' => 'MOBA',
                'image' => 'ml.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'PUBG Mobile',
                'description' => 'Game Battle Royale dari Tencent dengan grafis memukau',
                'category' => 'Battle Royale',
                'image' => 'pubg.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Free Fire',
                'description' => 'Game Battle Royale ringan dari Garena',
                'category' => 'Battle Royale',
                'image' => 'ff.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Valorant',
                'description' => 'Game FPS taktis dari Riot Games',
                'category' => 'FPS',
                'image' => 'valorant.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Genshin Impact',
                'description' => 'Game RPG Open World dengan grafis anime dari miHoYo',
                'category' => 'RPG',
                'image' => 'genshin.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('games')->insertBatch($data);
        echo "✅ GameSeeder: 5 data berhasil ditambahkan\n";
    }
}