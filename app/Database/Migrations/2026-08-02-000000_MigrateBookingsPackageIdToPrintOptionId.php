<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MigrateBookingsPackageIdToPrintOptionId extends Migration
{
    public function up()
    {
        $fields = $this->db->getFieldNames('bookings');

        if (in_array('package_id', $fields)) {
            // Step 1: Hapus foreign key lama (package_id)
            try {
                $this->forge->dropForeignKey('bookings', 'bookings_package_id_foreign');
            } catch (\Throwable $e) {
                // Abaikan jika sudah tidak ada
            }

            // Step 2: Ubah nama kolom package_id menjadi print_option_id
            $this->db->query('ALTER TABLE bookings CHANGE `package_id` `print_option_id` VARCHAR(20) NULL DEFAULT NULL');
        }

        // Set existing records' print_option_id to NULL to prevent foreign key violation
        $this->db->query('UPDATE bookings SET print_option_id = NULL');

        // Step 3: Tambahkan foreign key baru ke print_options
        try {
            $this->forge->addForeignKey('print_option_id', 'print_options', 'id', 'SET NULL', 'CASCADE');
            $this->forge->processIndexes('bookings');
        } catch (\Throwable $e) {
            // Abaikan jika sudah ditambahkan
        }
    }

    public function down()
    {
        // Rollback: hapus FK baru, rename kolom kembali, tambah FK lama
        $this->forge->dropForeignKey('bookings', 'bookings_print_option_id_foreign');
        $this->db->query('ALTER TABLE bookings CHANGE `print_option_id` `package_id` VARCHAR(20) NULL DEFAULT NULL');
        $this->forge->addForeignKey('package_id', 'packages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->processIndexes('bookings');
    }
}
