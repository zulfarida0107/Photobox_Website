<?php

namespace App\Controllers\User_Booking;

use App\Models\CustomerModel;
use CodeIgniter\Controller;

class LoginController extends Controller
{
    public function index()
    {
        // Jika sudah login, redirect ke schedule
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/schedule');
        }

        $data = [
            'title' => 'Login - CuitBox Studio'
        ];
        return view('User_Booking/login', $data);
    }

    public function auth()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]'
        ];

        $messages = [
            'email' => [
                'required' => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.'
            ],
            'password' => [
                'required' => 'Password wajib diisi.',
                'min_length' => 'Password minimal 8 karakter.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $customerModel = new CustomerModel();
        $user = $customerModel->where('email', $email)->first();

        // Cek apakah user ditemukan dan password cocok
        if ($user && password_verify($password, $user['password'])) {
            // Cek status akun
            if ($user['status'] !== 'active') {
                return redirect()->back()->withInput()->with('error', 'Akun Anda tidak aktif. Silakan hubungi admin.');
            }

            // Set session
            session()->set([
                'customer_id' => $user['id'],
                'customer_name' => $user['name'],
                'customer_email' => $user['email'],
                'customer_phone' => $user['phone'],
                'isLoggedIn' => true
            ]);

            // Redirect ke schedule dengan pesan sukses
            return redirect()->to('/schedule')->with('success', 'Selamat datang kembali, ' . $user['name'] . '!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }
    }

    public function logout()
    {
        // Hapus semua session
        session()->destroy();
        
        // Redirect ke login dengan pesan
        return redirect()->to('/login')->with('success', 'Anda telah berhasil logout.');
    }
}