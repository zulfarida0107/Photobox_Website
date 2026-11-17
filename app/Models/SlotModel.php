<?php

/**
 * Berkas: app/Models/SlotModel.php
 * Proyek: CuitBox Studio - Photobox Website & Management System
 * Author: Zulfarida (Lead Backend & Web Developer)
 * 
 * Standar Kompetensi Kerja Nasional Indonesia (SKKNI):
 * - Unit SKKNI: J.620100.015.01 (Menyusun Fungsi, File atau Sumber Daya Pemrograman dalam Organisasi yang Rapi)
 * - Unit SKKNI: J.620100.016.01 (Menulis Kode dengan Prinsip Sesuai Guidelines dan Best Practices)
 * - Unit SKKNI: J.620100.017.02 (Mengimplementasikan Pemrograman Terstruktur - Akses Basis Data)
 * 
 * Deskripsi:
 * Model entitas slot jadwal operasional pemotretan (tabel 'slots').
 * Mengatur penjadwalan tanggal sesi (slot_date), jam mulai sesi foto (start_time),
 * kapasitas maksimum pemesanan per sesi (max_bookings), serta penghitung pemesanan aktif (booked_count)
 * guna mencegah terjadinya bentrok reservasi (double-booking).
 */

namespace App\Models;

use CodeIgniter\Model;

/**
 * Class SlotModel
 *
 * Mengelola interaksi basis data untuk slot waktu operasional studio photobox.
 *
 * @package App\Models
 */
class SlotModel extends Model
{
    /**
     * Nama tabel pada basis data
     *
     * @var string
     */
    protected $table = 'slots';

    /**
     * Nama primary key tabel
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Format primary key non-auto increment
     *
     * @var bool
     */
    protected $useAutoIncrement = false;

    /**
     * Tipe kembalian hasil query berupa array asosiatif
     *
     * @var string
     */
    protected $returnType = 'array';

    /**
     * Proteksi pengisian mass-assignment
     *
     * @var bool
     */
    protected $protectFields = true;

    /**
     * Kolom-kolom yang diizinkan untuk manipulasi data
     *
     * @var array<string>
     */
    protected $allowedFields = [
        'id',
        'booth_id',
        'slot_date',
        'start_time',
        'max_bookings',
        'booked_count',
    ];

    /**
     * Menonaktifkan soft delete
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

    /**
     * Mengambil daftar slot operasional yang tersedia pada tanggal tertentu
     *
     * @param string|null $date Tanggal yang dicari (format: YYYY-MM-DD) atau null untuk seluruh data
     * @return array Daftar slot operasional yang cocok dengan kriteria
     */
    public function getAvailableSlots($date = null)
    {
        if ($date) {
            return $this->where('slot_date', $date)->findAll();
        }
        return $this->findAll();
    }
}
