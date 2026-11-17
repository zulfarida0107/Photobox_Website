<?php

/**
 * Berkas: app/Models/BookingModel.php
 * Proyek: CuitBox Studio - Photobox Website & Management System
 * Author: Zulfarida (Lead Backend & Web Developer)
 * 
 * Standar Kompetensi Kerja Nasional Indonesia (SKKNI):
 * - Unit SKKNI: J.620100.015.01 (Menyusun Fungsi, File atau Sumber Daya Pemrograman dalam Organisasi yang Rapi)
 * - Unit SKKNI: J.620100.016.01 (Menulis Kode dengan Prinsip Sesuai Guidelines dan Best Practices)
 * - Unit SKKNI: J.620100.017.02 (Mengimplementasikan Pemrograman Terstruktur - Akses Basis Data)
 * 
 * Deskripsi:
 * Model entitas data reservasi dan pemesanan sesi foto (tabel 'bookings').
 * Mengelola kode reservasi unik (booking_code), relasi terhadap pelanggan (customer_id),
 * slot waktu (slot_id), bilik photobox (booth_id), opsi cetak (print_option_id),
 * nomor antrean (queue_number), serta status pemesanan (pending, confirmed, completed, cancelled).
 */

namespace App\Models;

use CodeIgniter\Model;

/**
 * Class BookingModel
 *
 * Mengelola operasi basis data untuk pemesanan sesi foto photobox CuitBox Studio.
 *
 * @package App\Models
 */
class BookingModel extends Model
{
    /**
     * Nama tabel pada basis data
     *
     * @var string
     */
    protected $table = 'bookings';

    /**
     * Nama primary key tabel
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Format primary key non-auto increment (format: BKG-XXXXXX)
     *
     * @var bool
     */
    protected $useAutoIncrement = false;

    /**
     * Format hasil query berupa array asosiatif
     *
     * @var string
     */
    protected $returnType = 'array';

    /**
     * Proteksi pengisian atribut (mass assignment)
     *
     * @var bool
     */
    protected $protectFields = true;

    /**
     * Daftar field yang diizinkan untuk manipulasi data
     *
     * @var array<string>
     */
    protected $allowedFields = [
        'id',
        'booking_code',
        'customer_id',
        'slot_id',
        'booth_id',
        'print_option_id',
        'queue_number',
        'status',
        'edit_count',
    ];

    /**
     * Nonaktifkan soft delete (data dihapus secara permanen jika dieksekusi)
     *
     * @var bool
     */
    protected $useSoftDeletes = false;

    /**
     * Mengaktifkan pencatatan waktu otomatis
     *
     * @var bool
     */
    protected $useTimestamps = true;

    /**
     * Format tipe data tanggal
     *
     * @var string
     */
    protected $dateFormat = 'datetime';

    /**
     * Kolom tanggal pembuatan rekaman
     *
     * @var string
     */
    protected $createdField = 'created_at';

    /**
     * Kolom tanggal pembaruan rekaman terakhir
     *
     * @var string
     */
    protected $updatedField = 'updated_at';
}
