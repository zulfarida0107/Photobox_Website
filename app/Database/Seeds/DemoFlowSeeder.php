<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoFlowSeeder extends Seeder
{
    public function run()
    {
        $db = $this->db;
        $now = date('Y-m-d H:i:s');

        $db->query('SET FOREIGN_KEY_CHECKS=0');

        // Clear dependent tables first to satisfy foreign key constraints.
        $db->table('photos')->truncate();
        $db->table('sessions')->truncate();
        $db->table('payments')->truncate();
        $db->table('bookings')->truncate();
        $db->table('slots')->truncate();

        // Clear master tables used by demo data.
        $db->table('print_options')->truncate();
        $db->table('packages')->truncate();
        $db->table('filters')->truncate();
        $db->table('frames')->truncate();
        $db->table('booths')->truncate();
        $db->table('customers')->truncate();

        $db->query('SET FOREIGN_KEY_CHECKS=1');

        $db->table('customers')->insert([
            'id' => 'cus_demo_001',
            'name' => 'Demo Customer',
            'email' => 'demo.customer@example.com',
            'phone' => '081234567890',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $db->table('booths')->insert([
            'id' => 'boo_demo_001',
            'name' => 'Booth A',
            'status' => 'active',
            'current_queue' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $db->table('slots')->insert([
            'id' => 'slo_demo_001',
            'booth_id' => 'boo_demo_001',
            'slot_date' => date('Y-m-d', strtotime('+1 day')),
            'start_time' => '10:00:00',
            'max_bookings' => 20,
            'booked_count' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $db->table('packages')->insert([
            'id' => 'pkg_demo_001',
            'name' => 'Regular Package',
            'photo_count' => 10,
            'duration' => 15,
            'base_price' => 50000.00,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $db->table('print_options')->insertBatch([
            [
                'id' => 'prn_demo_001',
                'copies' => 1,
                'extra_price' => 0.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 'prn_demo_002',
                'copies' => 2,
                'extra_price' => 10000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $db->table('frames')->insertBatch([
            [
                'id' => 'frm_demo_001',
                'name' => 'Classic Frame',
                'file_path' => 'frames/classic.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 'frm_demo_002',
                'name' => 'Modern Frame',
                'file_path' => 'frames/modern.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $db->table('filters')->insertBatch([
            [
                'id' => 'flt_demo_001',
                'name' => 'Normal',
                'css_value' => 'none',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 'flt_demo_002',
                'name' => 'B&W',
                'css_value' => 'grayscale(1)',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $db->table('bookings')->insert([
            'id' => 'bok_demo_001',
            'booking_code' => '842000',
            'customer_id' => 'cus_demo_001',
            'slot_id' => 'slo_demo_001',
            'booth_id' => 'boo_demo_001',
            'package_id' => 'pkg_demo_001',
            'queue_number' => 1,
            'status' => 'pending',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
