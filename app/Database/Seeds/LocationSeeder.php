<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run()
    {
        $db = $this->db;
        $now = date('Y-m-d H:i:s');

        $locCount = $db->table('locations')->countAll();
        if ($locCount == 0) {
            $db->table('locations')->insertBatch([
                [
                    'id' => 'LOC-00001',
                    'name' => 'Margo City Mall',
                    'city' => 'Depok',
                    'address' => 'Jl. Margonda Raya No. 358, Kemiri Muka, Kec. Beji',
                    'latitude' => -6.37311100,
                    'longitude' => 106.83472200,
                    'status' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'id' => 'LOC-00002',
                    'name' => 'Grand Indonesia',
                    'city' => 'Jakarta Pusat',
                    'address' => 'Jl. M.H. Thamrin No. 1, Menteng',
                    'latitude' => -6.19527800,
                    'longitude' => 106.82083300,
                    'status' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'id' => 'LOC-00003',
                    'name' => 'Paris Van Java Mall',
                    'city' => 'Bandung',
                    'address' => 'Jl. Sukajadi No. 131-139, Cipedes',
                    'latitude' => -6.88944400,
                    'longitude' => 107.59611100,
                    'status' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);

            // Assign existing booths to LOC-00001
            $firstBooth = $db->table('booths')->get()->getFirstRow();
            if ($firstBooth) {
                $db->table('booths')->where('id', $firstBooth->id)->update(['location_id' => 'LOC-00001']);
            }
        }
    }
}
