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

        $db->table('customers')->insertBatch([
            [
                'id' => 'cus_001',
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '08123456789',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 'cus_002',
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '08987654321',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 'cus_003',
                'name' => 'Aesthetic User',
                'email' => 'user@snapbox.com',
                'phone' => '08122334455',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 'cus_004',
                'name' => 'Test Customer',
                'email' => 'test@snapbox.com',
                'phone' => '08112233445',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 'cus_005',
                'name' => 'Regular Customer',
                'email' => 'regular@snapbox.com',
                'phone' => '08998877665',
                'created_at' => $now,
                'updated_at' => $now,
            ],
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
            'slot_date' => date('Y-m-d'),
            'start_time' => '10:00:00',
            'max_bookings' => 20,
            'booked_count' => 5,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $db->table('packages')->insert([
            'id' => 'pkg_demo_001',
            'name' => 'Regular Package',
            'photo_count' => 10,
            'duration' => 15,
            'base_price' => 0.00,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $db->table('print_options')->insertBatch([
            [
                'id' => '1',
                'copies' => 2,
                'extra_price' => 25000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => '2',
                'copies' => 3,
                'extra_price' => 55000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => '3',
                'copies' => 4,
                'extra_price' => 75000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => '4',
                'copies' => 5,
                'extra_price' => 85000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $db->table('frames')->insertBatch([
            [
                'id' => '1',
                'name' => 'Soft Cloud',
                'file_path' => 'frames/soft_cloud.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => '2',
                'name' => 'Midnight Neon',
                'file_path' => 'frames/midnight_neon.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => '3',
                'name' => 'Cherry Blossom',
                'file_path' => 'frames/cherry_blossom.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => '4',
                'name' => 'Classic White',
                'file_path' => 'frames/classic_white.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => '5',
                'name' => 'Kawaii Kitty',
                'file_path' => 'frames/kawaii_kitty.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => '6',
                'name' => 'Retro Wave',
                'file_path' => 'frames/retro_wave.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $db->table('filters')->insertBatch([
            [
                'id' => '1',
                'name' => 'Normal',
                'css_value' => 'none',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => '2',
                'name' => 'B&W',
                'css_value' => 'grayscale(1)',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => '3',
                'name' => 'Sepia',
                'css_value' => 'sepia(1)',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => '4',
                'name' => 'Vintage',
                'css_value' => 'contrast(1.2) sepia(0.3)',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $db->table('bookings')->insertBatch([
            [
                'id' => 'BOOKING001',
                'booking_code' => 'BOOKING001',
                'customer_id' => 'cus_001',
                'slot_id' => 'slo_demo_001',
                'booth_id' => 'boo_demo_001',
                'package_id' => 'pkg_demo_001',
                'queue_number' => 1,
                'status' => 'pending',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 'BOOKING002',
                'booking_code' => 'BOOKING002',
                'customer_id' => 'cus_002',
                'slot_id' => 'slo_demo_001',
                'booth_id' => 'boo_demo_001',
                'package_id' => 'pkg_demo_001',
                'queue_number' => 2,
                'status' => 'pending',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => '842000',
                'booking_code' => '842000',
                'customer_id' => 'cus_003',
                'slot_id' => 'slo_demo_001',
                'booth_id' => 'boo_demo_001',
                'package_id' => 'pkg_demo_001',
                'queue_number' => 3,
                'status' => 'pending',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => '123456',
                'booking_code' => '123456',
                'customer_id' => 'cus_004',
                'slot_id' => 'slo_demo_001',
                'booth_id' => 'boo_demo_001',
                'package_id' => 'pkg_demo_001',
                'queue_number' => 4,
                'status' => 'pending',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => '654321',
                'booking_code' => '654321',
                'customer_id' => 'cus_005',
                'slot_id' => 'slo_demo_001',
                'booth_id' => 'boo_demo_001',
                'package_id' => 'pkg_demo_001',
                'queue_number' => 5,
                'status' => 'pending',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
