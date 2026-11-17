<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Master Database Seeder
 * 
 * Menjalankan seluruh seeding data master dan demo flow secara terpadu & berurutan.
 * Dilengkapi pengaman FOREIGN_KEY_CHECKS=0 agar dapat di-run berulang kali (idempotent)
 * tanpa menimbulkan kendala relasi foreign key.
 * 
 * Jalankan via CLI:
 * php spark db:seed DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $db  = $this->db;
        $now = date('Y-m-d H:i:s');

        echo "\n=== MEMULAI MASTER SEEDING DATABASE ===\n";

        // 1. Nonaktifkan pemeriksaan Foreign Key untuk pembersihan aman
        $db->query('SET FOREIGN_KEY_CHECKS = 0');

        echo "[1/8] Membersihkan data lama...\n";
        $tablesToClean = [
            'photos',
            'sessions',
            'payments',
            'bookings',
            'slots',
            'blocked_dates',
            'print_options',
            'packages',
            'frames',
            'frame_categories',
            'frame_layouts',
            'filters',
            'booths',
            'locations',
            'customers',
            'admins',
        ];

        foreach ($tablesToClean as $table) {
            if ($db->tableExists($table)) {
                $db->table($table)->truncate();
            }
        }

        // 2. Seed Data Administrator
        echo "[2/8] Menanam data Admin & Staf...\n";
        $db->table('admins')->insertBatch([
            [
                'id'         => 'adm_001',
                'name'       => 'Super Admin CuitBox',
                'email'      => 'admin@cuitboxstudio.com',
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'role'       => 'superadmin',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 'adm_002',
                'name'       => 'Staff CuitBox',
                'email'      => 'staff@cuitboxstudio.com',
                'password'   => password_hash('staff123', PASSWORD_DEFAULT),
                'role'       => 'staff',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 3. Seed Lokasi Cabang & Mesin Booth
        echo "[3/8] Menanam data Lokasi Cabang & Booth...\n";
        $db->table('locations')->insertBatch([
            [
                'id'         => 'LOC-00001',
                'name'       => 'Margo City Mall',
                'city'       => 'Depok',
                'address'    => 'Jl. Margonda Raya No. 358, Kemiri Muka, Kec. Beji',
                'latitude'   => -6.37311100,
                'longitude'  => 106.83472200,
                'status'     => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 'LOC-00002',
                'name'       => 'Grand Indonesia',
                'city'       => 'Jakarta Pusat',
                'address'    => 'Jl. M.H. Thamrin No. 1, Menteng',
                'latitude'   => -6.19527800,
                'longitude'  => 106.82083300,
                'status'     => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 'LOC-00003',
                'name'       => 'Paris Van Java Mall',
                'city'       => 'Bandung',
                'address'    => 'Jl. Sukajadi No. 131-139, Cipedes',
                'latitude'   => -6.88944400,
                'longitude'  => 107.59611100,
                'status'     => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $db->table('booths')->insertBatch([
            [
                'id'            => 'boo_demo_001',
                'name'          => 'Booth Studio A (Margo City)',
                'location_id'   => 'LOC-00001',
                'capacity'      => 4,
                'status'        => 'active',
                'current_queue' => 1,
                'ip_address'    => '127.0.0.1',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id'            => 'boo_demo_002',
                'name'          => 'Booth Studio B (Grand Indonesia)',
                'location_id'   => 'LOC-00002',
                'capacity'      => 4,
                'status'        => 'active',
                'current_queue' => 1,
                'ip_address'    => '127.0.0.1',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id'            => 'booth_001',
                'name'          => 'Booth Premium CuitBox',
                'location_id'   => 'LOC-00001',
                'capacity'      => 6,
                'status'        => 'active',
                'current_queue' => 1,
                'ip_address'    => '127.0.0.1',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ]);

        // 4. Seed Kategori, Layout & Frame Foto
        echo "[4/8] Menanam Kategori, Layout & Desain Frame...\n";
        $db->table('frame_categories')->insertBatch([
            ['id' => 'cat_pastel',     'name' => 'Pastel',     'created_at' => $now, 'updated_at' => $now],
            ['id' => 'cat_cute',       'name' => 'Cute',       'created_at' => $now, 'updated_at' => $now],
            ['id' => 'cat_minimalist', 'name' => 'Minimalist', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 'cat_newspaper',  'name' => 'Newspaper',  'created_at' => $now, 'updated_at' => $now],
            ['id' => 'cat_magazine',   'name' => 'Magazine',   'created_at' => $now, 'updated_at' => $now],
            ['id' => 'cat_retro',      'name' => 'Retro 90s',  'created_at' => $now, 'updated_at' => $now],
            ['id' => 'cat_y2k',        'name' => 'Y2k',        'created_at' => $now, 'updated_at' => $now],
        ]);

        $db->table('frame_layouts')->insertBatch([
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
                    ['slot' => 1, 'x' => 45,  'y' => 110, 'w' => 530, 'h' => 700],
                    ['slot' => 2, 'x' => 625, 'y' => 110, 'w' => 530, 'h' => 700],
                    ['slot' => 3, 'x' => 45,  'y' => 910, 'w' => 530, 'h' => 700],
                    ['slot' => 4, 'x' => 625, 'y' => 910, 'w' => 530, 'h' => 700],
                ]),
                'image_path'       => 'uploads/layouts/layout_4_grid.png',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'id'               => 'layout_6_grid_2',
                'name'             => 'Cute Kawaii Love Heart (6 Grid)',
                'photo_count'      => 6,
                'aspect_ratio'     => '1:1',
                'slot_coordinates' => json_encode([
                    ['slot' => 1, 'x' => 25,  'y' => 21,   'w' => 551, 'h' => 557],
                    ['slot' => 2, 'x' => 626, 'y' => 19,   'w' => 549, 'h' => 554],
                    ['slot' => 3, 'x' => 25,  'y' => 621,  'w' => 551, 'h' => 555],
                    ['slot' => 4, 'x' => 626, 'y' => 621,  'w' => 549, 'h' => 555],
                    ['slot' => 5, 'x' => 25,  'y' => 1222, 'w' => 551, 'h' => 557],
                    ['slot' => 6, 'x' => 626, 'y' => 1223, 'w' => 549, 'h' => 555],
                ]),
                'image_path'       => 'uploads/layouts/layout_6_grid_2.png',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
        ]);

        $db->table('frames')->insertBatch([
            [
                'id'         => 'frm_cuit_001',
                'name'       => 'Cuitbox Black White',
                'category'   => 'Cute',
                'layout_id'  => 'layout_4_grid',
                'file_path'  => 'uploads/frames/frame_cuitbox_black_white_4.png',
                'price'      => 0.00,
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 'frm_cuit_002',
                'name'       => 'Cuitbox Black Pink',
                'category'   => 'Cute',
                'layout_id'  => 'layout_4_grid',
                'file_path'  => 'uploads/frames/frame_cuitbox_black_pink_4.png',
                'price'      => 0.00,
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 'frm_cuit_003',
                'name'       => 'Cuitbox Pink Plaid',
                'category'   => 'Cute',
                'layout_id'  => 'layout_4_grid',
                'file_path'  => 'uploads/frames/frame_cuitbox_pink_plaid_4.png',
                'price'      => 0.00,
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 'frm_cuit_004',
                'name'       => 'Cuitbox Blue Plaid',
                'category'   => 'Cute',
                'layout_id'  => 'layout_4_grid',
                'file_path'  => 'uploads/frames/frame_cuitbox_blue_plaid_4.png',
                'price'      => 0.00,
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 'frm_kawaii_love_001',
                'name'       => 'Cute Kawaii Love Heart (6 Grid)',
                'category'   => 'Cute',
                'layout_id'  => 'layout_6_grid_2',
                'file_path'  => 'uploads/frames/frame_cuitbox_kawaii_love_heart_6.png',
                'price'      => 0.00,
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 'FRM_001',
                'name'       => 'Pastel Dream',
                'category'   => 'Pastel',
                'layout_id'  => 'layout_4_grid',
                'file_path'  => 'uploads/frames/pastel_dream.png',
                'price'      => 0.00,
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 'FRM_003',
                'name'       => 'Classic Minimalist',
                'category'   => 'Minimalist',
                'layout_id'  => 'layout_4_grid',
                'file_path'  => 'uploads/frames/classic_layout.png',
                'price'      => 0.00,
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 5. Seed Filter Warna Foto
        echo "[5/8] Menanam Filter Efek Foto...\n";
        $db->table('filters')->insertBatch([
            [
                'id'        => 'FLT-00001',
                'name'      => 'Normal / Original',
                'css_value' => 'none',
                'is_active' => true,
            ],
            [
                'id'        => 'FLT-00002',
                'name'      => 'Hitam Putih (B/W)',
                'css_value' => 'grayscale(100%)',
                'is_active' => true,
            ],
            [
                'id'        => 'FLT-00003',
                'name'      => 'Klasik Sepia',
                'css_value' => 'sepia(80%) contrast(110%)',
                'is_active' => true,
            ],
            [
                'id'        => 'FLT-00004',
                'name'      => 'Warm Glow',
                'css_value' => 'brightness(105%) saturate(120%) hue-rotate(-10deg)',
                'is_active' => true,
            ],
            [
                'id'        => 'FLT-00005',
                'name'      => 'Cool Aesthetic',
                'css_value' => 'contrast(105%) saturate(110%) hue-rotate(15deg)',
                'is_active' => true,
            ],
        ]);

        // 6. Seed Opsi Cetak & Paket
        echo "[6/8] Menanam Opsi Cetak & Paket Layanan...\n";
        $db->table('print_options')->insertBatch([
            [
                'id'          => '1',
                'copies'      => 2,
                'extra_price' => 25000.00,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => '2',
                'copies'      => 3,
                'extra_price' => 55000.00,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => '3',
                'copies'      => 4,
                'extra_price' => 75000.00,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => '4',
                'copies'      => 5,
                'extra_price' => 85000.00,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ]);

        $db->table('packages')->insert([
            'id'          => 'pkg_demo_001',
            'name'        => 'Regular Package',
            'photo_count' => 10,
            'duration'    => 15,
            'base_price'  => 0.00,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        // 7. Seed Akun Pelanggan (dengan password hash agar langsung bisa login)
        echo "[7/8] Menanam Akun Pelanggan...\n";
        $defaultPassword = password_hash('password123', PASSWORD_DEFAULT);
        $db->table('customers')->insertBatch([
            [
                'id'         => 'C0001',
                'name'       => 'Aidan Joe',
                'email'      => 'aidanjoe88@gmail.com',
                'phone'      => '+6281220426261',
                'status'     => 'active',
                'password'   => $defaultPassword,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 'cus_001',
                'name'       => 'John Doe',
                'email'      => 'john@example.com',
                'phone'      => '08123456789',
                'status'     => 'active',
                'password'   => $defaultPassword,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 'cus_002',
                'name'       => 'Jane Smith',
                'email'      => 'jane@example.com',
                'phone'      => '08987654321',
                'status'     => 'active',
                'password'   => $defaultPassword,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 8. Seed Slot Jadwal & Demo Tiket Booking
        echo "[8/8] Menanam Slot Jadwal & Tiket Demo...\n";

        // Generate slot untuk hari ini & 3 hari ke depan (ID max 20 chars: SL_md_Hi misal SL_1026_1000 = 12 chars)
        $slotsToInsert = [];
        $timeSchedules = [
            '10:00:00', '11:00:00', '12:00:00', '13:00:00', '14:00:00',
            '15:00:00', '16:00:00', '17:00:00', '18:00:00', '19:00:00',
            '20:00:00', '21:00:00'
        ];

        for ($dayOffset = 0; $dayOffset <= 3; $dayOffset++) {
            $slotDate = date('Y-m-d', strtotime("+$dayOffset day"));
            $datePart = date('md', strtotime($slotDate));

            foreach ($timeSchedules as $time) {
                $timePart = substr(str_replace(':', '', $time), 0, 4); // '1000'
                // Format ID: SL_1026_A_1000 = 14 karakter (sangat aman di VARCHAR(20))
                $slotId = 'SL_' . $datePart . '_A_' . $timePart;

                $slotsToInsert[] = [
                    'id'           => $slotId,
                    'booth_id'     => 'boo_demo_001',
                    'slot_date'    => $slotDate,
                    'start_time'   => $time,
                    'max_bookings' => 1,
                    'booked_count' => ($time === '10:00:00' && $dayOffset === 0) ? 1 : 0,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];
            }
        }
        $db->table('slots')->insertBatch($slotsToInsert);

        // Demo Booking aktif yang siap diuji di Kiosk Desktop
        $firstSlotId = $slotsToInsert[0]['id'];
        $db->table('bookings')->insert([
            'id'                => 'BKG_DEMO_001',
            'booking_code'      => 'BOOK-DEMO-001',
            'customer_id'       => 'C0001',
            'slot_id'           => $firstSlotId,
            'booth_id'          => 'boo_demo_001',
            'print_option_id'   => '1',
            'queue_number'      => 1,
            'status'            => 'pending',
            'created_at'        => $now,
            'updated_at'        => $now,
        ]);

        // 9. Aktifkan kembali Foreign Key Checks
        $db->query('SET FOREIGN_KEY_CHECKS = 1');

        echo "\n=== MASTER SEEDING BERHASIL DISELESAIKAN (100%) ===\n";
        echo "Data Siap Digunakan:\n";
        echo "  - Superadmin Login : admin@cuitboxstudio.com / admin123\n";
        echo "  - Customer Login   : aidanjoe88@gmail.com / password123\n";
        echo "  - Demo Booking Code: BOOK-DEMO-001 (untuk Kiosk Desktop)\n";
        echo "  - Booths & Lokasi  : Margo City, Grand Indonesia, Paris Van Java\n";
        echo "  - Frames & QRIS    : Siap digunakan di Web & Desktop Kiosk\n\n";
    }
}
