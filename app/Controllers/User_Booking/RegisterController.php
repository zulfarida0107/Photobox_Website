<?php

namespace App\Controllers\User_Booking;

use App\Models\CustomerModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

class RegisterController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Akun Register - CuitBox Studio'
        ];
        return view('User_Booking/register', $data);
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[4]|max_length[100]',
            'email' => 'required|valid_email|is_unique[customers.email]',
            // ✅ PERBAIKI: Hapus <max_length 12=""></max_length>
            'phone' => 'permit_empty|min_length[11]|max_length[12]|regex_match[/^[0-9]+$/]',
            'password' => [
                'required',
                'min_length[8]',
                'max_length[50]',
                'regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>])[a-zA-Z\d!@#$%^&*(),.?":{}|<>]+$/]'
            ],
            'confirm_password' => 'required|matches[password]'
        ];

        $messages = [
            'phone' => [
                'regex_match' => 'Nomor telepon harus berupa angka.',
                'min_length' => 'Nomor telepon minimal 11 digit.',
                'max_length' => 'Nomor telepon maksimal 12 digit.',
            ],
            'password' => [
                'required' => 'Password wajib diisi.',
                'min_length' => 'Password minimal 8 karakter.',
                'max_length' => 'Password maksimal 50 karakter.',
                'regex_match' => 'Password harus mengandung: huruf kapital, huruf kecil, angka, dan karakter khusus (!@#$%^&*(),.?":{}|<>)'
            ],
            'email' => [
                'is_unique' => 'Email sudah terdaftar. Silakan gunakan email lain.'
            ],
            'confirm_password' => [
                'matches' => 'Konfirmasi password tidak sesuai dengan password.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            // Debug: tampilkan errors
            // dd($this->validator->getErrors());
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Cek tambahan: tidak boleh ada titik di password
        $password = $this->request->getPost('password');
        if (strpos($password, '.') !== false) {
            return redirect()->back()->withInput()->with('error', 'Password tidak boleh mengandung titik (.)');
        }

        $customerModel = new CustomerModel();
        
        // Generate ID Customer secara aman & unik
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
            $newId = 'C' . str_pad($newIdNum, 4, '0', STR_PAD_LEFT);
            $newIdNum++;
        } while ($customerModel->find($newId) !== null);

        // Gabungkan kode negara + nomor telepon
        $countryCode = $this->request->getPost('country_code') ?? '+62';
        $phoneNumber = $this->request->getPost('phone') ?? '';
        $fullPhone = $countryCode . $phoneNumber;

        $data = [
            'id' => $newId,
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $fullPhone,
            'status' => 'active',
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];

        try {
            $customerModel->insert($data);
            
            // ✅ Redirect ke schedule setelah registrasi berhasil
            return redirect()->to('/schedule')->with('success', 'Akun berhasil dibuat! Silahkan pilih jadwal booking Anda.');
            
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Method untuk handle POST dari landing page
    public function fromLanding()
    {
        // Cek apakah ada data dari landing
        if ($this->request->getPost('from_landing')) {
            // Bisa tambahkan logic khusus jika perlu
            session()->setFlashdata('info', 'Silahkan lengkapi data registrasi Anda');
        }
        
        return redirect()->to('/register');
    }
}