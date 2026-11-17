<?php

/**
 * Berkas: app/Controllers/User_Booking/BoothController.php
 * Proyek: CuitBox Studio - Photobox Website & Management System
 * Author: Zulfarida (Lead Backend & Web Developer)
 * 
 * Standar Kompetensi Kerja Nasional Indonesia (SKKNI):
 * - Unit SKKNI: J.620100.005.02 (Mengimplementasikan User Interface)
 * - Unit SKKNI: J.620100.015.01 (Menyusun Fungsi, File atau Sumber Daya Pemrograman dalam Organisasi yang Rapi)
 * - Unit SKKNI: J.620100.016.01 (Menulis Kode dengan Prinsip Sesuai Guidelines dan Best Practices)
 * - Unit SKKNI: J.620100.017.02 (Mengimplementasikan Pemrograman Terstruktur)
 * 
 * Deskripsi:
 * Mengendalikan alur pemilihan booth/studio foto fisik oleh pelanggan (User Booking Portal).
 * Mengambil daftar mesin booth aktif yang terintegrasi dengan tabel lokasi geografis (relasi JOIN),
 * serta menyimpan pilihan booth ke dalam variabel session sebelum melanjutkan ke tahap pemilihan jadwal/slot.
 */

namespace App\Controllers\User_Booking;

use App\Controllers\BaseController;
use App\Models\BoothModel;
use CodeIgniter\HTTP\RedirectResponse;

/**
 * Class BoothController
 *
 * Mengatur interaksi pemilihan unit booth photobox untuk pelanggan pada portal booking.
 *
 * @package App\Controllers\User_Booking
 */
class BoothController extends BaseController
{
    /**
     * Model untuk manipulasi dan pembacaan tabel booths
     *
     * @var BoothModel
     */
    protected $boothModel;

    /**
     * Inisialisasi controller dan instansiasi BoothModel
     */
    public function __construct()
    {
        $this->boothModel = new BoothModel();
    }

    /**
     * Menampilkan antarmuka katalog pemilihan booth studio untuk pelanggan
     *
     * Alur pemrosesan:
     * 1. Melakukan query JOIN antara tabel 'booths' dan tabel 'locations'
     * 2. Menyaring unit booth yang berstatus siap pakai (mengabaikan unit 'maintenance' dan 'inactive')
     * 3. Mengurutkan hasil secara ascending berdasarkan ID booth
     * 4. Membaca session 'selected_booth_id' jika pelanggan sebelumnya sudah pernah memilih booth
     * 5. Mengirimkan dataset booth ke tampilan view 'User_Booking/booth'
     *
     * @return string Render tampilan view katalog booth
     */
    public function index()
    {
        // 1. Eksekusi query relasional untuk memperoleh data booth beserta alamat & koordinat lokasi
        $booths = $this->boothModel
            ->select('booths.*, locations.name AS location_name, locations.city AS location_city, locations.address AS location_address, locations.latitude, locations.longitude')
            ->join('locations', 'locations.id = booths.location_id', 'left')
            ->where('booths.status !=', 'maintenance')
            ->where('booths.status !=', 'inactive')
            ->orderBy('booths.id', 'ASC')
            ->findAll();

        // 2. Susun payload data untuk render antarmuka pengguna
        $data = [
            'title'           => 'Pilih Booth - CuitBox Studio',
            'booths'          => $booths,
            'selectedBoothId' => session()->get('selected_booth_id')
        ];

        return view('User_Booking/booth', $data);
    }

    /**
     * Menyimpan booth yang dipilih oleh pelanggan ke dalam session
     *
     * Alur pemrosesan:
     * 1. Menangkap ID booth yang dikirim melalui metode POST dari form pemilihan
     * 2. Validasi input: memastikan nilai ID booth tidak kosong dan valid di basis data
     * 3. Menyimpan ID, Nama, dan Harga sewa booth ke dalam session pengguna
     * 4. Mengalihkan alur reservasi ke halaman pemilihan jadwal sesi foto (/schedule)
     *
     * @return RedirectResponse Redirect ke halaman pemilihan jadwal atau kembali jika gagal
     */
    public function store()
    {
        // 1. Ambil input ID booth dari request form
        $boothId = $this->request->getPost('booth_id');

        // 2. Validasi kelengkapan data masukan
        if (empty($boothId)) {
            return redirect()->back()->with('error', 'Silakan pilih salah satu booth terlebih dahulu.');
        }

        // 3. Verifikasi ketersediaan booth pada basis data
        $booth = $this->boothModel->find($boothId);
        if (!$booth) {
            return redirect()->back()->with('error', 'Booth yang dipilih tidak valid.');
        }

        // 4. Simpan preferensi booth terpilih ke dalam sesi pengguna
        session()->set([
            'selected_booth_id'    => $booth['id'],
            'selected_booth_name'  => $booth['name'],
            'selected_booth_price' => $booth['price'] ?? 0,
        ]);

        // 5. Alihkan ke langkah berikutnya (pemilihan jadwal/slot)
        return redirect()->to('/schedule');
    }
}
