<?php

/**
 * Berkas: app/Models/PaymentModel.php
 * Proyek: CuitBox Studio - Photobox Website & Management System
 * Author: Zulfarida (Lead Backend & Web Developer)
 * 
 * Standar Kompetensi Kerja Nasional Indonesia (SKKNI):
 * - Unit SKKNI: J.620100.015.01 (Menyusun Fungsi, File atau Sumber Daya Pemrograman dalam Organisasi yang Rapi)
 * - Unit SKKNI: J.620100.016.01 (Menulis Kode dengan Prinsip Sesuai Guidelines dan Best Practices)
 * - Unit SKKNI: J.620100.017.02 (Mengimplementasikan Pemrograman Terstruktur - Akses Basis Data)
 * 
 * Deskripsi:
 * Model entitas data transaksi keuangan dan pembayaran (tabel 'payments').
 * Mengatur relasi terhadap data reservasi (booking_id), opsi cetak tambahan (print_option_id),
 * nominal pembayaran (amount), nomor referensi gateway (gateway_ref),
 * serta status verifikasi pembayaran (pending, paid, failed, expired).
 */

namespace App\Models;

use CodeIgniter\Model;

/**
 * Class PaymentModel
 *
 * Mengelola interaksi basis data untuk pencatatan transaksi pembayaran CuitBox Studio.
 *
 * @package App\Models
 */
class PaymentModel extends Model
{
    /**
     * Nama tabel pada basis data
     *
     * @var string
     */
    protected $table = 'payments';

    /**
     * Nama primary key tabel
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Format primary key non-auto increment (format: PAY-XXXXXX)
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
     * Kolom-kolom yang diizinkan untuk manipulasi data transaksi
     *
     * @var array<string>
     */
    protected $allowedFields = [
        'id',
        'booking_id',
        'print_option_id',
        'amount',
        'gateway_ref',
        'status',
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
     * Kolom tanggal pembuatan rekaman transaksi
     *
     * @var string
     */
    protected $createdField = 'created_at';

    /**
     * Kolom tanggal pembaruan status transaksi terakhir
     *
     * @var string
     */
    protected $updatedField = 'updated_at';
}
