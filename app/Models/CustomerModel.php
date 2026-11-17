<?php

/**
 * Berkas: app/Models/CustomerModel.php
 * Proyek: CuitBox Studio - Photobox Website & Management System
 * Author: Zulfarida (Lead Backend & Web Developer)
 * 
 * Standar Kompetensi Kerja Nasional Indonesia (SKKNI):
 * - Unit SKKNI: J.620100.015.01 (Menyusun Fungsi, File atau Sumber Daya Pemrograman dalam Organisasi yang Rapi)
 * - Unit SKKNI: J.620100.016.01 (Menulis Kode dengan Prinsip Sesuai Guidelines dan Best Practices)
 * - Unit SKKNI: J.620100.017.02 (Mengimplementasikan Pemrograman Terstruktur - Akses Basis Data)
 * 
 * Deskripsi:
 * Model entitas data akun pengguna/pelanggan umum (tabel 'customers').
 * Mengelola data profil pelanggan (nama, email, nomor ponsel), kredensial terenkripsi,
 * status akun (active/suspended), serta atribut token pemulihan kata sandi (reset_token)
 * beserta masa kedaluwarsanya (reset_token_expires_at).
 */

namespace App\Models;

use CodeIgniter\Model;

/**
 * Class CustomerModel
 *
 * Mengelola operasi basis data untuk akun pelanggan CuitBox Studio.
 *
 * @package App\Models
 */
class CustomerModel extends Model
{
    /**
     * Nama tabel pada basis data
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * Nama primary key tabel
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Format primary key non-auto increment (format: CST-XXXXXX)
     *
     * @var bool
     */
    protected $useAutoIncrement = false;

    /**
     * Format kembalian baris query dalam array asosiatif
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
        'name',
        'email',
        'password',
        'phone',
        'status',
        'reset_token',
        'reset_token_expires_at',
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
     * Kolom tanggal pembuatan akun
     *
     * @var string
     */
    protected $createdField = 'created_at';

    /**
     * Kolom tanggal modifikasi data akun terakhir
     *
     * @var string
     */
    protected $updatedField = 'updated_at';
}
