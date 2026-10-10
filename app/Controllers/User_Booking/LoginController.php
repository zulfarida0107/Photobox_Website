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
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[8]'
        ];

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

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email    = $this->request->getPost('email');
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
                'customer_id'    => $user['id'],
                'customer_name'  => $user['name'],
                'customer_email' => $user['email'],
                'customer_phone' => $user['phone'],
                'isLoggedIn'     => true
            ]);

            // Redirect ke booth dengan pesan sukses
            return redirect()->to('/booth')->with('success', 'Selamat datang kembali, ' . $user['name'] . '!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }
    }

    public function logout()
    {
        session()->destroy();
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

        if (!$user) {
            // Berikan pesan ambigu untuk mencegah user enumeration
            return redirect()->to('/login')->with('success', 'Jika email terdaftar, instruksi reset password telah dikirim ke email Anda.');
        }

        // Generate token kriptografi aman (32 byte)
        $rawToken    = bin2hex(random_bytes(32));
        $tokenHash   = hash('sha256', $rawToken);
        $expiresAt   = date('Y-m-d H:i:s', time() + 3600); // 1 jam masa berlaku

        $customerModel->update($user['id'], [
            'reset_token'            => $tokenHash,
            'reset_token_expires_at' => $expiresAt,
        ]);

        // Send email with reset password link
        $resetLink = base_url('reset-password?token=' . $rawToken . '&email=' . urlencode($email));

        $emailService = \Config\Services::email();
        $emailService->setFrom('cuitboxstudio@gmail.com', 'CuitBox Studio');
        $emailService->setCRLF("\r\n");
        $emailService->setNewline("\r\n");
        $emailService->setMailType('html');
        $emailService->setTo($email);
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

    public function resetPassword()
    {
        $token = $this->request->getGet('token');
        $email = $this->request->getGet('email');

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

    public function processResetPassword()
    {
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

        $token    = $this->request->getPost('token');
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $customerModel = new CustomerModel();
        $tokenHash     = hash('sha256', $token);
        $user          = $customerModel->where('email', $email)
            ->where('reset_token', $tokenHash)
            ->where('reset_token_expires_at >=', date('Y-m-d H:i:s'))
            ->first();

        if (!$user) {
            return redirect()->to('/forgot-password')->with('error', 'Tautan reset password sudah tidak berlaku. Silakan ajukan ulang.');
        }

        $customerModel->update($user['id'], [
            'password'               => password_hash($password, PASSWORD_DEFAULT),
            'reset_token'            => null,
            'reset_token_expires_at' => null,
            'updated_at'             => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/login')->with('success', 'Password Anda berhasil diperbarui! Silakan masuk dengan password baru.');
    }
}