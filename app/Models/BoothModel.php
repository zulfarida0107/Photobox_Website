<?php

/**
 * Berkas: app/Models/BoothModel.php
 * Proyek: CuitBox Studio - Photobox Website & Management System
 * Author: Zulfarida (Lead Backend & Web Developer)
 * 
 * Standar Kompetensi Kerja Nasional Indonesia (SKKNI):
 * - Unit SKKNI: J.620100.015.01 (Menyusun Fungsi, File atau Sumber Daya Pemrograman dalam Organisasi yang Rapi)
 * - Unit SKKNI: J.620100.016.01 (Menulis Kode dengan Prinsip Sesuai Guidelines dan Best Practices)
 * - Unit SKKNI: J.620100.017.02 (Mengimplementasikan Pemrograman Terstruktur - Akses Basis Data)
 * 
 * Deskripsi:
 * Model entitas mesin bilik photobox fisik (tabel 'booths').
 * Mengatur pemetaan kolom entitas booth studio, lokasi geografis (latitude & longitude),
 * alamat IP jaringan mesin bilik, status operasional (ready/active, maintenance, inactive),
 * antrean saat ini, serta metode kustom untuk mengambil daftar unit aktif siap pakai.
 */

namespace App\Models;

use CodeIgniter\Model;

/**
 * Class BoothModel
 *
 * Mengelola operasi basis data untuk unit mesin bilik photobox CuitBox Studio.
 *
 * @package App\Models
 */
class BoothModel extends Model
{
    /**
     * Nama tabel di database
     *
     * @var string
     */
    protected $table = 'booths';

    /**
     * Nama primary key tabel
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Format primary key bukan auto-increment integer murni (format: BTH-001)
     *
     * @var bool
     */
    protected $useAutoIncrement = false;

    /**
     * Format pengembalian hasil query sebagai array asosiatif
     *
     * @var string
     */
    protected $returnType = 'array';

    /**
     * Mengaktifkan perlindungan field mass-assignment
     *
     * @var bool
     */
    protected $protectFields = true;

    /**
     * Kolom-kolom yang diizinkan untuk manipulasi data (insert / update)
     *
     * @var array<string>
     */
    protected $allowedFields = [
        'id',
        'location_id',
        'name',
        'capacity',
        'image',
        'ip_address',
        'latitude',
        'longitude',
        'active_booking_id',
        'status',
        'current_queue',
    ];

    /**
     * Menonaktifkan soft delete
     *
     * @var bool
     */
    protected $useSoftDeletes = false;

    /**
     * Mengaktifkan pencatatan waktu otomatis saat rekaman dibuat dan diperbarui
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
     * Nama kolom pencatat tanggal pembuatan
     *
     * @var string
     */
    protected $createdField = 'created_at';

    /**
     * Nama kolom pencatat tanggal modifikasi terakhir
     *
     * @var string
     */
    protected $updatedField = 'updated_at';

    /**
     * Mengambil daftar seluruh unit booth yang siap digunakan (tidak sedang dalam perbaikan)
     *
     * @return array Daftar entitas booth berstatus selain 'maintenance'
     */
    public function getActiveBooths()
    {
        return $this->where('status !=', 'maintenance')->findAll();
    }
}
