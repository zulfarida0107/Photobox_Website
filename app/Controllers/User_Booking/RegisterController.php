<?php

/**
 * Berkas: app/Controllers/User_Booking/RegisterController.php
 * Proyek: CuitBox Studio - Photobox Website & Management System
 * Author: Zulfarida (Lead Backend & Web Developer)
 * 
 * Standar Kompetensi Kerja Nasional Indonesia (SKKNI):
 * - Unit SKKNI: J.620100.005.02 (Mengimplementasikan User Interface)
 * - Unit SKKNI: J.620100.015.01 (Menyusun Fungsi, File atau Sumber Daya Pemrograman dalam Organisasi yang Rapi)
 * - Unit SKKNI: J.620100.016.01 (Menulis Kode dengan Prinsip Sesuai Guidelines dan Best Practices)
 * - Unit SKKNI: J.620100.017.02 (Mengimplementasikan Pemrograman Terstruktur - Validasi & I/O)
 * 
 * Deskripsi:
 * Mengendalikan alur registrasi akun pelanggan baru pada portal pemesanan CuitBox Studio.
 * Mencakup validasi AJAX ketersediaan email secara real-time, validasi kompleksitas kata sandi
 * dengan pola Regular Expression (huruf besar, kecil, angka, simbol), penomoran ID otomatis ('C0001', 'C0002'),
 * hashing kata sandi Bcrypt, penanganan pengecualian (try-catch error handling),
 * serta auto-login sesi pelanggan setelah registrasi berhasil.
 */

namespace App\Controllers\User_Booking;

use App\Models\CustomerModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Class RegisterController
 *
 * Mengendalikan formulir dan pemrosesan pendaftaran akun pelanggan baru.
 *
 * @package App\Controllers\User_Booking
 */
class RegisterController extends Controller
{
    /**
     * Menampilkan antarmuka formulir registrasi akun pelanggan
     *
     * @return string Render tampilan view register
     */
    public function index()
    {
        $data = [
            'title' => 'Akun Register - CuitBox Studio'
        ];
        return view('User_Booking/register', $data);
    }

    /**
     * Memeriksa ketersediaan email secara asinkron via permintaan AJAX
     *
     * Digunakan oleh antarmuka frontend untuk memberikan umpan balik langsung (live feedback)
     * kepada pengguna apakah email sudah pernah terdaftar atau belum.
     *
     * @return ResponseInterface Respon dalam format JSON berisi status 'exists' dan pesan
     */
    public function checkEmail()
    {
        $email = $this->request->getPost('email');
        
        // Validasi masukan email tidak boleh kosong
        if (empty($email)) {
            return $this->response->setJSON([
                'exists'  => false, 
                'message' => 'Email tidak boleh kosong'
            ]);
        }
        
        $customerModel = new CustomerModel();
        $user = $customerModel->where('email', $email)->first();
        
        // Kembalikan respon ketersediaan email
        if ($user) {
            return $this->response->setJSON([
                'exists'  => true, 
                'message' => 'Email sudah terdaftar! Silakan <a href="' . base_url('login') . '" class="text-blue-600 font-semibold hover:underline" style="color: #2563eb !important; font-weight: 600 !important; text-decoration: underline !important;">Masuk di sini</a> atau gunakan email lain.'
            ]);
        } else {
            return $this->response->setJSON([
                'exists'  => false, 
                'message' => 'Email tersedia'
            ]);
        }
    }

    /**
     * Memproses data formulir registrasi pelanggan baru
     *
     * Alur pemrosesan:
     * 1. Validasi masukan: nama (min 4 karakter), email unik, nomor telepon, dan password kompleks
     * 2. Pengecekan aturan keamanan tambahan: melarang karakter titik (.) pada kata sandi
     * 3. Pembuatan primary key otomatis berformat 'CXXXX' berurutan
     * 4. Enkripsi password menggunakan password_hash() dengan algoritma PASSWORD_DEFAULT
     * 5. Eksekusi penyimpanan data ke tabel 'customers' dengan blok penanganan galat try-catch
     * 6. Registrasi otomatis variabel sesi pengguna dan pengalihan ke katalog booth (/booth)
     *
     * @return RedirectResponse Redirect ke halaman pemilihan booth jika sukses atau kembali jika gagal
     */
    public function store()
    {
        // 1. Aturan validasi masukan formulir
        $rules = [ 
            'name'             => 'required|min_length[4]|max_length[100]',
            'email'            => 'required|valid_email|is_unique[customers.email]',
            'phone'            => 'permit_empty|min_length[11]|max_length[12]|regex_match[/^[0-9]+$/]',
            'password'         => [
                'required',
                'min_length[8]',
                'max_length[50]',
                'regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>])[a-zA-Z\d!@#$%^&*(),.?":{}|<>]+$/]'
            ],
            'confirm_password' => 'required|matches[password]'
        ];

        // 2. Definisi pesan kesalahan dalam bahasa Indonesia
        $messages = [
            'phone' => [
                'regex_match' => 'Nomor telepon harus berupa angka.',
                'min_length'  => 'Nomor telepon minimal 11 digit.',
                'max_length'  => 'Nomor telepon maksimal 12 digit.',
            ],
            'password' => [
                'required'    => 'Password wajib diisi.',
                'min_length'  => 'Password minimal 8 karakter.',
                'max_length'  => 'Password maksimal 50 karakter.',
                'regex_match' => 'Password harus mengandung: huruf kapital, huruf kecil, angka, dan karakter khusus (!@#$%^&*(),.?":{}|<>)'
            ],
            'email' => [
                'is_unique'   => 'Email sudah terdaftar. Silakan gunakan email lain.'
            ],
            'confirm_password' => [
                'matches'     => 'Konfirmasi password tidak sesuai dengan password.'
            ]
        ];

        // 3. Evaluasi validasi input
        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 4. Validasi sanitasi khusus: password tidak boleh mengandung titik (.)
        $password = (string) $this->request->getPost('password');
        if (strpos($password, '.') !== false) {
            return redirect()->back()->withInput()->with('error', 'Password tidak boleh mengandung titik (.)');
        }

        $customerModel = new CustomerModel();
        
        // 5. Generate Primary Key Pelanggan secara unik dan berurutan (C0001, C0002, dst.)
        $existingCustomers = $customerModel->like('id', 'C', 'after')->findAll();
        $maxNum = 0;
        foreach ($existingCustomers as $c) {
            if (preg_match('/^C(\d+)$/i', $c['id'], $matches)) {
                $num = (int) $matches[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }
        $newIdNum = $maxNum + 1;
        do {
            $newId = 'C' . str_pad((string) $newIdNum, 4, '0', STR_PAD_LEFT);
            $newIdNum++;
        } while ($customerModel->find($newId) !== null);

        // 6. Normalisasi nomor telepon dengan kode negara
        $countryCode = $this->request->getPost('country_code') ?? '+62';
        $phoneNumber = $this->request->getPost('phone') ?? '';
        $fullPhone   = $countryCode . $phoneNumber;
        $email       = (string) $this->request->getPost('email');
        $name        = (string) $this->request->getPost('name');

        // 7. Penyusunan payload array data pelanggan
        $data = [
            'id'       => $newId,
            'name'     => $name,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'phone'    => $fullPhone,
            'status'   => 'active'
        ];

        // 8. Eksekusi penyimpanan dengan penanganan eksepsi (Error Handling - SKKNI Unit 04)
        try {
            $customerModel->insert($data);
            
            // Inisialisasi sesi login otomatis (Auto-Login)
            session()->set([
                'customer_id'    => $newId,
                'customer_name'  => $name,
                'customer_email' => $email,
                'customer_phone' => $fullPhone,
                'isLoggedIn'     => true
            ]);
            
            return redirect()->to('/booth')->with('success', 'Akun berhasil dibuat! Selamat datang, ' . $name . '!');
            
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Menangani pengalihan registrasi langsung dari tautan landing page atau beranda
     *
     * @return RedirectResponse Redirect ke halaman formulir registrasi
     */
    public function directRegister()
    {
        if ($this->request->getPost('direct_booking') || $this->request->getPost('from_home') || $this->request->getPost('from_landing')) {
            session()->setFlashdata('info', 'Silakan lengkapi data registrasi Anda untuk memulai pemesanan.');
        }
        
        return redirect()->to(base_url('register'));
    }

    /**
     * Alias backward compatibility untuk directRegister dari tautan landing page
     *
     * @return RedirectResponse
     */
    public function fromLanding()
    {
        return $this->directRegister();
    }
}
