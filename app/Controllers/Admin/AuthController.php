<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class AuthController extends BaseController
{
    protected $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    public function login()
    {
        // If already logged in, redirect to dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/admin/dashboard');
        }

        return view('Admin/auth/login', [
            'title' => 'Login - Admin CuitBox Studio'
        ]);
    }

    public function attemptLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $admin = $this->adminModel->where('email', $email)->first();

        if ($admin && password_verify($password, $admin['password'])) {
            // Set session
            session()->set([
                'adminId'    => $admin['id'],
                'adminName'  => $admin['name'],
                'adminEmail' => $admin['email'],
                'adminRole'  => $admin['role'],
                'isLoggedIn' => true,
            ]);

            // Set Remember Me Cookie (berlaku 30 hari dengan tanda tangan HMAC)
            if ($this->request->getPost('remember')) {
                helper('cookie');
                $signature = hash_hmac('sha256', $admin['id'] . $admin['password'], config('App')->baseURL);
                $cookieToken = base64_encode($admin['id'] . ':' . $signature);
                set_cookie('remember_admin', $cookieToken, 30 * 24 * 60 * 60);
            }

            return redirect()->to('/admin/dashboard')
                ->with('success', 'Selamat datang kembali, ' . esc($admin['name']) . '!');
        }

        return redirect()->back()
            ->with('error', 'Email atau password salah.')
            ->withInput();
    }

    public function logout()
    {
        session()->destroy();
        helper('cookie');
        delete_cookie('remember_admin');
        
        return redirect()->to('/admin/login')
            ->with('success', 'Anda telah berhasil logout.');
    }
}
