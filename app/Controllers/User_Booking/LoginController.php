<?php

/**
 * Berkas: app/Controllers/User_Booking/LoginController.php
 * Proyek: CuitBox Studio - Photobox Website & Management System
 * Author: Zulfarida (Lead Backend & Web Developer)
 * 
 * Standar Kompetensi Kerja Nasional Indonesia (SKKNI):
 * - Unit SKKNI: J.620100.015.01 (Menyusun Fungsi, File atau Sumber Daya Pemrograman dalam Organisasi yang Rapi)
 * - Unit SKKNI: J.620100.016.01 (Menulis Kode dengan Prinsip Sesuai Guidelines dan Best Practices)
 * - Unit SKKNI: J.620100.017.02 (Mengimplementasikan Pemrograman Terstruktur)
 * - Unit SKKNI: J.620100.019.02 (Menggunakan Library atau Komponen Pre-Existing - Email SMTP)
 * 
 * Deskripsi:
 * Mengelola alur autentikasi pelanggan (Customer Portal):
 * 1. Halaman login pelanggan dan validasi masukan
 * 2. Autentikasi hash kata sandi (password_verify) dan pengecekan keaktifan akun
 * 3. Alur lupa password (Forgot Password) dengan token kriptografi acak (32 byte)
 * 4. Pengiriman instruksi pemulihan via layanan SMTP Email HTML
 * 5. Validasi masa kedaluwarsa token reset dan pembaruan password terenkripsi
 */

namespace App\Controllers\User_Booking;

use App\Models\CustomerModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RedirectResponse;

/**
 * Class LoginController
 *
 * Mengendalikan gerbang autentikasi dan pemulihan akun pelanggan pada portal booking.
 *
 * @package App\Controllers\User_Booking
 */
class LoginController extends Controller
{
    /**
     * Menampilkan antarmuka formulir login pelanggan
     *
     * @return string Render tampilan view login pelanggan
     */
    public function index()
    {
        $data = [
            'title' => 'Login - CuitBox Studio'
        ];
        return view('User_Booking/login', $data);
    }

    /**
     * Memproses verifikasi kredensial autentikasi login pelanggan
     *
     * Alur pemrosesan:
     * 1. Validasi masukan email (wajib & format email) dan password (min 8 karakter)
     * 2. Pencarian akun pelanggan di database via CustomerModel
     * 3. Verifikasi keabsahan password menggunakan password_verify()
     * 4. Pengecekan status keaktifan akun ('active' vs 'suspended/inactive')
     * 5. Penyimpanan state sesi pelanggan (customer_id, customer_name, customer_email, isLoggedIn)
     * 6. Pengalihan pengguna ke halaman pemilihan booth (/booth)
     *
     * @return RedirectResponse Redirect ke alur booking jika sukses atau kembali jika gagal
     */
    public function auth()
    {
        // 1. Aturan validasi input kredensial
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[8]'
        ];

        // 2. Pesan kesalahan kustom yang informatif
        $messages = [
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.'
            ],
            'password' => [
                'required'   => 'Password wajib diisi.',
                'min_length' => 'Password minimal 8 karakter.'
            ]
        ];

        // 3. Evaluasi validasi form
        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 4. Ekstraksi nilai input
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // 5. Query data pelanggan berdasarkan email
        $customerModel = new CustomerModel();
        $user = $customerModel->where('email', $email)->first();

        // 6. Validasi eksistensi pengguna dan kesesuaian hash password
        if ($user && password_verify((string) $password, $user['password'])) {
            // Verifikasi status operasional akun pelanggan
            if ($user['status'] !== 'active') {
                return redirect()->back()->withInput()->with('error', 'Akun Anda tidak aktif. Silakan hubungi admin.');
            }

            // Daftarkan variabel autentikasi ke dalam session server
            session()->set([
                'customer_id'    => $user['id'],
                'customer_name'  => $user['name'],
                'customer_email' => $user['email'],
                'customer_phone' => $user['phone'],
                'isLoggedIn'     => true
            ]);

            // Alihkan ke halaman pemilihan booth photobox
            return redirect()->to('/booth')->with('success', 'Selamat datang kembali, ' . $user['name'] . '!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }
    }

    /**
     * Mengakhiri sesi aktif pelanggan (Logout)
     *
     * Menghancurkan seluruh variabel sesi pengguna dan mengalihkan ke formulir login.
     *
     * @return RedirectResponse Redirect ke halaman login
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Menampilkan antarmuka formulir pengajuan lupa kata sandi
     *
     * @return string Render tampilan view forgot_password
     */
    public function forgotPassword()
    {
        $data = [
            'title' => 'Lupa Password - CuitBox Studio'
        ];
        return view('User_Booking/forgot_password', $data);
    }

    /**
     * Memproses permintaan reset kata sandi dan mengirim tautan verifikasi ke email
     *
     * Langkah keamanan yang diterapkan:
     * 1. Validasi sintaksis alamat email
     * 2. Pencegahan 'User Enumeration' dengan selalu mengembalikan pesan netral
     * 3. Pembuatan token acak kriptografis 32 byte via random_bytes()
     * 4. Hashing token dengan algoritma SHA-256 sebelum disimpan ke basis data
     * 5. Pembatasan masa kedaluwarsa token selama 1 jam (3600 detik)
     * 6. Pengiriman email HTML melalui layanan SMTP
     *
     * @return RedirectResponse Redirect dengan pesan informasi pengiriman email
     */
    public function processForgotPassword()
    {
        // 1. Validasi input email
        $rules = [
            'email' => 'required|valid_email'
        ];

        $messages = [
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $customerModel = new CustomerModel();
        $user = $customerModel->where('email', $email)->first();

        // 2. Berikan pesan netral ambigu untuk mencegah teknik serangan user enumeration
        if (!$user) {
            return redirect()->to('/login')->with('success', 'Jika email terdaftar, instruksi reset password telah dikirim ke email Anda.');
        }

        // 3. Generate token kriptografi acak (32 byte)
        $rawToken    = bin2hex(random_bytes(32));
        $tokenHash   = hash('sha256', $rawToken);
        $expiresAt   = date('Y-m-d H:i:s', time() + 3600); // 1 jam batas kedaluwarsa

        // 4. Perbarui data token reset pada rekaman pengguna
        $customerModel->update($user['id'], [
            'reset_token'            => $tokenHash,
            'reset_token_expires_at' => $expiresAt,
        ]);

        // 5. Susun tautan pemulihan dengan token mentah
        $resetLink = base_url('reset-password?token=' . $rawToken . '&email=' . urlencode((string) $email));

        // 6. Konfigurasi dan pengiriman pesan email HTML
        $emailService = \Config\Services::email();
        $emailService->setFrom('cuitboxstudio@gmail.com', 'CuitBox Studio');
        $emailService->setCRLF("\r\n");
        $emailService->setNewline("\r\n");
        $emailService->setMailType('html');
        $emailService->setTo((string) $email);
        $emailService->setSubject('Instruksi Reset Password - CuitBox Studio');

        $htmlMessage = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                body { font-family: "Plus Jakarta Sans", Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 20px; }
                .container { max-width: 500px; margin: 0 auto; background: #ffffff; border-radius: 16px; padding: 32px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); text-align: center; }
                .title { font-size: 20px; font-weight: 800; color: #1e293b; margin-bottom: 12px; }
                .text { color: #64748b; font-size: 14px; line-height: 1.6; margin-bottom: 24px; }
                .btn { display: inline-block; background-color: #d9f99d; color: #1e293b; font-weight: 700; text-decoration: none; padding: 14px 28px; border-radius: 9999px; box-shadow: 0 4px 12px rgba(190, 242, 100, 0.4); margin-bottom: 24px; }
                .link-box { word-break: break-all; font-size: 11px; color: #94a3b8; background: #f1f5f9; padding: 10px; border-radius: 8px; }
                .warning { font-size: 12px; color: #ef4444; margin-top: 16px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="title">Permintaan Reset Password</div>
                <div class="text">Halo ' . esc($user['name']) . ',<br><br>Kami menerima permintaan untuk mereset password akun CuitBox Studio Anda. Klik tombol di bawah ini untuk membuat password baru:</div>
                <a href="' . $resetLink . '" class="btn">Atur Ulang Password</a>
                <div class="text" style="font-size:12px; margin-bottom:8px;">Atau salin tautan berikut ke browser Anda:</div>
                <div class="link-box">' . $resetLink . '</div>
                <div class="warning">*Tautan ini hanya berlaku selama 1 jam demi keamanan akun Anda.</div>
            </div>
        </body>
        </html>
        ';

        $emailService->setMessage($htmlMessage);

        if ($emailService->send(false)) {
            return redirect()->to('/login')->with('success', 'Tautan reset password telah dikirim ke email Anda. Silakan periksa Inbox atau folder Spam.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal mengirim email reset password. Silakan coba beberapa saat lagi.');
        }
    }

    /**
     * Menampilkan formulir pembuatan password baru setelah validasi token
     *
     * @return string|RedirectResponse Render view formulir reset password atau redirect jika tidak valid
     */
    public function resetPassword()
    {
        $token = (string) $this->request->getGet('token');
        $email = (string) $this->request->getGet('email');

        if (empty($token) || empty($email)) {
            return redirect()->to('/login')->with('error', 'Tautan reset password tidak valid atau tidak lengkap.');
        }

        $customerModel = new CustomerModel();
        $tokenHash     = hash('sha256', $token);
        $user          = $customerModel->where('email', $email)
            ->where('reset_token', $tokenHash)
            ->where('reset_token_expires_at >=', date('Y-m-d H:i:s'))
            ->first();

        if (!$user) {
            return redirect()->to('/forgot-password')->with('error', 'Tautan reset password sudah kedaluwarsa atau tidak valid. Silakan ajukan ulang.');
        }

        $data = [
            'title' => 'Atur Ulang Password - CuitBox Studio',
            'token' => $token,
            'email' => $email,
        ];

        return view('User_Booking/reset_password', $data);
    }

    /**
     * Memproses penyimpanan kata sandi baru ke dalam basis data
     *
     * @return RedirectResponse Redirect ke halaman login dengan status sukses
     */
    public function processResetPassword()
    {
        // 1. Aturan validasi password baru dan kecocokan konfirmasi
        $rules = [
            'token'            => 'required',
            'email'            => 'required|valid_email',
            'password'         => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]'
        ];

        $messages = [
            'password' => [
                'required'   => 'Password baru wajib diisi.',
                'min_length' => 'Password minimal 8 karakter.'
            ],
            'confirm_password' => [
                'required' => 'Konfirmasi password wajib diisi.',
                'matches'  => 'Konfirmasi password tidak cocok.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $token    = (string) $this->request->getPost('token');
        $email    = (string) $this->request->getPost('email');
        $password = (string) $this->request->getPost('password');

        $customerModel = new CustomerModel();
        $tokenHash     = hash('sha256', $token);
        $user          = $customerModel->where('email', $email)
            ->where('reset_token', $tokenHash)
            ->where('reset_token_expires_at >=', date('Y-m-d H:i:s'))
            ->first();

        if (!$user) {
            return redirect()->to('/forgot-password')->with('error', 'Tautan reset password sudah tidak berlaku. Silakan ajukan ulang.');
        }

        // Simpan hash password baru dan bersihkan token pemulihan
        $customerModel->update($user['id'], [
            'password'               => password_hash($password, PASSWORD_DEFAULT),
            'reset_token'            => null,
            'reset_token_expires_at' => null,
            'updated_at'             => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/login')->with('success', 'Password Anda berhasil diperbarui! Silakan masuk dengan password baru.');
    }
}
