<?php

namespace App\Controllers\User_Booking;

use App\Models\CustomerModel;
use CodeIgniter\Controller;

class LoginController extends Controller
{
    public function index()
    {
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
                'customer_password' => $password,
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

    public function forgotPassword()
    {
        $data = [
            'title' => 'Lupa Password - CuitBox Studio'
        ];
        return view('User_Booking/forgot_password', $data);
    }

    public function processForgotPassword()
    {
        $rules = [
            'email' => 'required|valid_email'
        ];

        $messages = [
            'email' => [
                'required' => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $customerModel = new CustomerModel();
        $user = $customerModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Email tidak terdaftar di sistem kami.');
        }

        // Generate temporary password (8 characters)
        $newPassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
        
        // Update database
        $customerModel->update($user['id'], [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);

        // Send email
        $emailConfig = config('Email');
        $emailService = \Config\Services::email();
        $emailService->initialize($emailConfig);
        
        $emailService->setFrom('cuitbox.studio@gmail.com', 'CuitBox Studio');
        $emailService->setCRLF("\r\n");
        $emailService->setNewline("\r\n");
        $emailService->setMailType('html');
        $emailService->setTo($email);
        $emailService->setSubject('Reset Password - CuitBox Studio');

        $htmlMessage = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                body { font-family: "Plus Jakarta Sans", Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 20px; }
                .container { max-width: 500px; margin: 0 auto; background: #ffffff; border-radius: 16px; padding: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); text-align: center; }
                .title { font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 16px; }
                .text { color: #64748b; font-size: 14px; margin-bottom: 24px; }
                .password-box { background: #f1f5f9; padding: 16px; border-radius: 12px; font-size: 24px; font-weight: 800; color: #0f172a; letter-spacing: 2px; margin-bottom: 24px; }
                .warning { font-size: 12px; color: #ef4444; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="title">Reset Password Berhasil</div>
                <div class="text">Halo ' . esc($user['name']) . ',<br><br>Seseorang telah meminta untuk mereset password akun CuitBox Anda. Berikut adalah password sementara Anda yang baru:</div>
                <div class="password-box">' . $newPassword . '</div>
                <div class="warning">Segera login dan ganti password ini demi keamanan akun Anda.</div>
            </div>
        </body>
        </html>
        ';

        $emailService->setMessage($htmlMessage);
        
        if ($emailService->send(false)) {
            return redirect()->to('/login')->with('success', 'Password baru telah dikirim ke email Anda. Silakan cek Inbox atau folder Spam.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal mengirim email reset password. Silakan coba lagi nanti.');
        }
    }
}