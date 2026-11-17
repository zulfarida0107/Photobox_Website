<?php

/**
 * Berkas: app/Controllers/Admin/AuthController.php
 * Proyek: CuitBox Studio - Photobox Website & Management System
 * Author: Zulfarida (Lead Backend & Web Developer)
 * 
 * Standar Kompetensi Kerja Nasional Indonesia (SKKNI):
 * - Unit SKKNI: J.620100.015.01 (Menyusun Fungsi, File atau Sumber Daya Pemrograman dalam Organisasi yang Rapi)
 * - Unit SKKNI: J.620100.016.01 (Menulis Kode dengan Prinsip Sesuai Guidelines dan Best Practices)
 * - Unit SKKNI: J.620100.017.02 (Mengimplementasikan Pemrograman Terstruktur)
 * 
 * Deskripsi:
 * Mengelola alur autentikasi administrator, validasi kredensial (email & password terenkripsi Bcrypt),
 * manajemen state sesi (session guard), pembuatan cookie Remember-Me bertanda tangan HMAC SHA-256,
 * serta pembersihan sesi saat proses logout.
 */

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Class AuthController
 *
 * Mengendalikan gerbang masuk autentikasi pengguna tingkat administrator dan staf operasional.
 *
 * @package App\Controllers\Admin
 */
class AuthController extends BaseController
{
    /**
     * Objek model administrator untuk interaksi basis data
     *
     * @var AdminModel
     */
    protected $adminModel;

    /**
     * Inisialisasi dependensi model administrator
     */
    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    /**
     * Menampilkan antarmuka formulir login administrator
     *
     * Memeriksa apakah pengguna sudah memiliki sesi login aktif.
     * Jika sudah login, otomatis dialihkan ke halaman dashboard admin.
     *
     * @return RedirectResponse|string Tampilan view login atau redirect ke dashboard
     */
    public function login()
    {
        // 1. Cek status sesi login pengguna saat ini
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/admin/dashboard');
        }

        // 2. Render halaman formulir login administrator
        return view('Admin/auth/login', [
            'title' => 'Login - Admin CuitBox Studio'
        ]);
    }

    /**
     * Memproses verifikasi kredensial autentikasi login administrator
     *
     * Langkah pemrosesan:
     * 1. Validasi format email dan panjang minimum kata sandi
     * 2. Pencarian data administrator berdasarkan email di tabel basis data
     * 3. Verifikasi kecocokan hash kata sandi menggunakan fungsi password_verify() (Bcrypt)
     * 4. Registrasi variabel sesi pengguna (ID, Nama, Email, Peran/Role, Status Login)
     * 5. Penyimpanan token cookie Remember-Me dengan enkripsi HMAC SHA-256 (opsional)
     *
     * @return RedirectResponse Redirect ke dashboard jika sukses, atau kembali ke login jika gagal
     */
    public function attemptLogin()
    {
        // 1. Aturan validasi masukan formulir
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[5]',
        ];

        // 2. Evaluasi validasi input form
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        // 3. Ekstraksi data dari permintaan POST
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // 4. Query record admin berdasarkan alamat email
        $admin = $this->adminModel->where('email', $email)->first();

        // 5. Validasi eksistensi admin dan keabsahan hash password Bcrypt
        if ($admin && password_verify($password, $admin['password'])) {
            // Daftarkan variabel autentikasi ke dalam session server
            session()->set([
                'adminId'    => $admin['id'],
                'adminName'  => $admin['name'],
                'adminEmail' => $admin['email'],
                'adminRole'  => $admin['role'],
                'isLoggedIn' => true,
            ]);

            // Set Remember Me Cookie (berlaku 30 hari dengan tanda tangan HMAC SHA-256)
            if ($this->request->getPost('remember')) {
                helper('cookie');
                $signature   = hash_hmac('sha256', $admin['id'] . $admin['password'], config('App')->baseURL);
                $cookieToken = base64_encode($admin['id'] . ':' . $signature);
                set_cookie('remember_admin', $cookieToken, 30 * 24 * 60 * 60);
            }

            // Alihkan ke halaman dashboard utama dengan pesan sukses
            return redirect()->to('/admin/dashboard')
                ->with('success', 'Selamat datang kembali, ' . esc($admin['name']) . '!');
        }

        // 6. Tangani kegagalan autentikasi (kredensial tidak valid)
        return redirect()->back()
            ->with('error', 'Email atau password salah.')
            ->withInput();
    }

    /**
     * Mengakhiri sesi aktif administrator (Logout)
     *
     * Menghancurkan seluruh data sesi yang tersimpan di server
     * dan menghapus cookie autentikasi yang tersimpan pada peramban klien.
     *
     * @return RedirectResponse Redirect kembali ke halaman login admin
     */
    public function logout()
    {
        // 1. Hancurkan seluruh payload sesi
        session()->destroy();

        // 2. Hapus cookie persistensi sesi jika ada
        helper('cookie');
        delete_cookie('remember_admin');

        // 3. Alihkan pengguna kembali ke formulir login
        return redirect()->to('/admin/login')
            ->with('success', 'Anda telah berhasil logout.');
    }
}
