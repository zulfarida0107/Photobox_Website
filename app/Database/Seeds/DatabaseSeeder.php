<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ============================================================
        // HAPUS DATA LAMA (TRUNCATE)
        // ============================================================
        $this->db->table('payments')->truncate();
        $this->db->table('bookings')->truncate();
        $this->db->table('schedules')->truncate();
        $this->db->table('booths')->truncate();
        $this->db->table('users')->truncate();

        // ============================================================
        // 1. USERS (5 user)
        // ============================================================
        $this->db->table('users')->insertBatch([
            [
                'name' => 'Admin Photobox',
                'email' => 'admin@photobox.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'phone' => '081234567890',
                'is_active' => 1,
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'phone' => '08123456789',
                'is_active' => 1,
            ],
            [
                'name' => 'Siti Rahma',
                'email' => 'siti@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'phone' => '08129876543',
                'is_active' => 1,
            ],
            [
                'name' => 'Andi Wijaya',
                'email' => 'andi@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'phone' => '08125551234',
                'is_active' => 1,
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'phone' => '08127778888',
                'is_active' => 0,
            ],
        ]);

        // ============================================================
        // 2. BOOTHS (5 booth)
        // ============================================================
        $this->db->table('booths')->insertBatch([
            [
                'name' => 'Retro Vibes',
                'description' => 'Warm tungsten glow with orange velvet backdrop',
                'features' => 'Warm Tungsten Glow, Orange Velvet Backdrop, Wedge Ceramic & Impact',
                'price' => 45000,
                'is_active' => 1,
            ],
            [
                'name' => 'Neon Party',
                'description' => 'RGB LED neon lighting with reflective black panels',
                'features' => 'RGB LED Neon Lighting, Reflective Black Panels, LED Projection Screen',
                'price' => 55000,
                'is_active' => 1,
            ],
            [
                'name' => 'Classic Black',
                'description' => 'Dynamic spotlights with metal chrome backdrop',
                'features' => 'Dynamic Spotlights, Metal Chrome Backdrop, Premium Crystal Projection',
                'price' => 65000,
                'is_active' => 1,
            ],
            [
                'name' => 'Pastel Dream',
                'description' => 'Soft pastel colors with dreamy atmosphere',
                'features' => 'Pastel Background, Soft Lighting, Dreamy Decor',
                'price' => 35000,
                'is_active' => 1,
            ],
            [
                'name' => 'Vintage Wood',
                'description' => 'Rustic wooden backdrop with vintage props',
                'features' => 'Wooden Background, Vintage Props, Warm Lighting',
                'price' => 40000,
                'is_active' => 0,
            ],
        ]);

        // ============================================================
        // 3. SCHEDULES (14 jadwal)
        // ============================================================
        $this->db->table('schedules')->insertBatch([
            ['date' => '2025-10-09', 'start_time' => '09:00:00', 'end_time' => '11:00:00', 'session_name' => 'PAGI', 'max_bookings' => 2, 'booked_count' => 1, 'is_available' => 1],
            ['date' => '2025-10-09', 'start_time' => '13:00:00', 'end_time' => '15:00:00', 'session_name' => 'SIANG', 'max_bookings' => 2, 'booked_count' => 1, 'is_available' => 1],
            ['date' => '2025-10-09', 'start_time' => '17:00:00', 'end_time' => '19:00:00', 'session_name' => 'SORE', 'max_bookings' => 2, 'booked_count' => 0, 'is_available' => 1],
            ['date' => '2025-10-09', 'start_time' => '21:00:00', 'end_time' => '23:00:00', 'session_name' => 'MALAM', 'max_bookings' => 1, 'booked_count' => 0, 'is_available' => 1],
            ['date' => '2025-10-10', 'start_time' => '09:00:00', 'end_time' => '11:00:00', 'session_name' => 'PAGI', 'max_bookings' => 2, 'booked_count' => 0, 'is_available' => 1],
            ['date' => '2025-10-10', 'start_time' => '13:00:00', 'end_time' => '15:00:00', 'session_name' => 'SIANG', 'max_bookings' => 2, 'booked_count' => 1, 'is_available' => 1],
            ['date' => '2025-10-10', 'start_time' => '17:00:00', 'end_time' => '19:00:00', 'session_name' => 'SORE', 'max_bookings' => 2, 'booked_count' => 0, 'is_available' => 1],
            ['date' => '2025-10-10', 'start_time' => '21:00:00', 'end_time' => '23:00:00', 'session_name' => 'MALAM', 'max_bookings' => 1, 'booked_count' => 0, 'is_available' => 1],
            ['date' => '2025-10-11', 'start_time' => '09:00:00', 'end_time' => '11:00:00', 'session_name' => 'PAGI', 'max_bookings' => 2, 'booked_count' => 0, 'is_available' => 1],
            ['date' => '2025-10-11', 'start_time' => '13:00:00', 'end_time' => '15:00:00', 'session_name' => 'SIANG', 'max_bookings' => 2, 'booked_count' => 0, 'is_available' => 1],
            ['date' => '2025-10-11', 'start_time' => '17:00:00', 'end_time' => '19:00:00', 'session_name' => 'SORE', 'max_bookings' => 2, 'booked_count' => 0, 'is_available' => 1],
            ['date' => '2025-10-12', 'start_time' => '09:00:00', 'end_time' => '11:00:00', 'session_name' => 'PAGI', 'max_bookings' => 2, 'booked_count' => 0, 'is_available' => 1],
            ['date' => '2025-10-12', 'start_time' => '13:00:00', 'end_time' => '15:00:00', 'session_name' => 'SIANG', 'max_bookings' => 2, 'booked_count' => 0, 'is_available' => 1],
            ['date' => '2025-10-12', 'start_time' => '17:00:00', 'end_time' => '19:00:00', 'session_name' => 'SORE', 'max_bookings' => 2, 'booked_count' => 0, 'is_available' => 1],
        ]);

        // ============================================================
        // 4. BOOKINGS (7 booking)
        // ============================================================
        $this->db->table('bookings')->insertBatch([
            [
                'booking_code' => 'FF-2025-A1B2C3',
                'user_id' => 2,
                'booth_id' => 1,
                'schedule_id' => 1,
                'booking_date' => '2025-10-09',
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
                'total_price' => 45000,
                'status' => 'paid',
                'payment_status' => 'paid',
                'notes' => 'Request for extra props',
            ],
            [
                'booking_code' => 'FF-2025-D4E5F6',
                'user_id' => 3,
                'booth_id' => 2,
                'schedule_id' => 3,
                'booking_date' => '2025-10-09',
                'start_time' => '17:00:00',
                'end_time' => '19:00:00',
                'total_price' => 55000,
                'status' => 'confirmed',
                'payment_status' => 'unpaid',
                'notes' => NULL,
            ],
            [
                'booking_code' => 'FF-2025-G7H8I9',
                'user_id' => 4,
                'booth_id' => 3,
                'schedule_id' => 4,
                'booking_date' => '2025-10-09',
                'start_time' => '21:00:00',
                'end_time' => '23:00:00',
                'total_price' => 65000,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'notes' => NULL,
            ],
            [
                'booking_code' => 'FF-2025-J0K1L2',
                'user_id' => 2,
                'booth_id' => 2,
                'schedule_id' => 6,
                'booking_date' => '2025-10-10',
                'start_time' => '13:00:00',
                'end_time' => '15:00:00',
                'total_price' => 55000,
                'status' => 'completed',
                'payment_status' => 'paid',
                'notes' => 'Birthday celebration',
            ],
            [
                'booking_code' => 'FF-2025-M3N4O5',
                'user_id' => 3,
                'booth_id' => 1,
                'schedule_id' => 5,
                'booking_date' => '2025-10-10',
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
                'total_price' => 45000,
                'status' => 'cancelled',
                'payment_status' => 'unpaid',
                'notes' => NULL,
            ],
            [
                'booking_code' => 'FF-2025-P6Q7R8',
                'user_id' => 5,
                'booth_id' => 4,
                'schedule_id' => 2,
                'booking_date' => '2025-10-09',
                'start_time' => '13:00:00',
                'end_time' => '15:00:00',
                'total_price' => 35000,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'notes' => NULL,
            ],
            [
                'booking_code' => 'FF-2025-S9T0U1',
                'user_id' => 2,
                'booth_id' => 3,
                'schedule_id' => 7,
                'booking_date' => '2025-10-10',
                'start_time' => '17:00:00',
                'end_time' => '19:00:00',
                'total_price' => 65000,
                'status' => 'confirmed',
                'payment_status' => 'unpaid',
                'notes' => 'Group of 4 people',
            ],
        ]);

        // ============================================================
        // 5. PAYMENTS (3 payment)
        // ============================================================
        $this->db->table('payments')->insertBatch([
            [
                'booking_id' => 1,
                'user_id' => 2,
                'amount' => 45000,
                'payment_method' => 'QRIS',
                'payment_status' => 'paid',
                'transaction_id' => 'TX-2025-001',
                'payment_date' => date('Y-m-d H:i:s'),
                'qr_code' => 'https://api.qrserver.com/v1/create-qr-code/?data=PAYMENT-001',
                'expiry_time' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
            ],
            [
                'booking_id' => 4,
                'user_id' => 2,
                'amount' => 55000,
                'payment_method' => 'Bank Transfer',
                'payment_status' => 'paid',
                'transaction_id' => 'TX-2025-004',
                'payment_date' => date('Y-m-d H:i:s'),
                'qr_code' => NULL,
                'expiry_time' => date('Y-m-d H:i:s', strtotime('+24 hours')),
            ],
            [
                'booking_id' => 3,
                'user_id' => 4,
                'amount' => 65000,
                'payment_method' => 'QRIS',
                'payment_status' => 'pending',
                'transaction_id' => NULL,
                'payment_date' => NULL,
                'qr_code' => 'https://api.qrserver.com/v1/create-qr-code/?data=PAYMENT-003',
                'expiry_time' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
            ],
        ]);

        // ============================================================
        // OUTPUT SUMMARY
        // ============================================================
        echo "\n✅ ============================================================\n";
        echo "✅ DATABASE SEEDED SUCCESSFULLY!\n";
        echo "✅ ============================================================\n\n";
        
        echo "📊 SUMMARY (5 TABLES):\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "  users        : " . $this->db->table('users')->countAll() . " rows\n";
        echo "  booths       : " . $this->db->table('booths')->countAll() . " rows\n";
        echo "  schedules    : " . $this->db->table('schedules')->countAll() . " rows\n";
        echo "  bookings     : " . $this->db->table('bookings')->countAll() . " rows\n";
        echo "  payments     : " . $this->db->table('payments')->countAll() . " rows\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
        echo "\n👤 TEST ACCOUNTS:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "  Admin    : admin@photobox.com / admin123\n";
        echo "  User 1   : budi@example.com / password123\n";
        echo "  User 2   : siti@example.com / password123\n";
        echo "  User 3   : andi@example.com / password123\n";
        echo "  User 4   : dewi@example.com / password123\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "\n🚀 Ready for testing!\n";
    }
}