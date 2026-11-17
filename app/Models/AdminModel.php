<?php

/**
 * Berkas: app/Models/AdminModel.php
 * Proyek: CuitBox Studio - Photobox Website & Management System
 * Author: Zulfarida (Lead Backend & Web Developer)
 * 
 * Standar Kompetensi Kerja Nasional Indonesia (SKKNI):
 * - Unit SKKNI: J.620100.015.01 (Menyusun Fungsi, File atau Sumber Daya Pemrograman dalam Organisasi yang Rapi)
 * - Unit SKKNI: J.620100.016.01 (Menulis Kode dengan Prinsip Sesuai Guidelines dan Best Practices)
 * - Unit SKKNI: J.620100.017.02 (Mengimplementasikan Pemrograman Terstruktur - Akses Basis Data)
 * 
 * Deskripsi:
 * Model entitas data administrator dan pengelola sistem (tabel 'admins').
 * Mengatur pemetaan kolom, perlindungan field (mass-assignment protection),
 * penanganan primary key kustom non-auto-increment (format: ADM-XXX),
 * serta pengelolaan timestamp otomatis (created_at dan updated_at).
 */

namespace App\Models;

use CodeIgniter\Model;

/**
 * Class AdminModel
 *
 * Mengelola interaksi basis data untuk tabel admins.
 *
 * @package App\Models
 */
class AdminModel extends Model
{
    /**
     * Nama tabel pada basis data
     *
     * @var string
     */
    protected $table = 'admins';

    /**
     * Nama primary key tabel
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Menandakan bahwa primary key bukan auto increment integer (karena menggunakan format ADM-XXX)
     *
     * @var bool
     */
    protected $useAutoIncrement = false;

    /**
     * Tipe kembalian data saat query dieksekusi (array asosiatif)
     *
     * @var string
     */
    protected $returnType = 'array';

    /**
     * Proteksi pengisian field mass-assignment
     *
     * @var bool
     */
    protected $protectFields = true;

    /**
     * Daftar field yang diizinkan untuk diisi atau diperbarui
     *
     * @var array<string>
     */
    protected $allowedFields = [
        'id',
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Menonaktifkan fitur soft deletes (penghapusan bersifat permanen)
     *
     * @var bool
     */
    protected $useSoftDeletes = false;

    /**
     * Mengaktifkan pencatatan waktu otomatis untuk pembuatan dan pembaruan data
     *
     * @var bool
     */
    protected $useTimestamps = true;

    /**
     * Format penyimpanan tanggal dan waktu
     *
     * @var string
     */
    protected $dateFormat = 'datetime';

    /**
     * Kolom pencatat waktu pembuatan rekaman
     *
     * @var string
     */
    protected $createdField = 'created_at';

    /**
     * Kolom pencatat waktu modifikasi rekaman terakhir
     *
     * @var string
     */
    protected $updatedField = 'updated_at';
}
