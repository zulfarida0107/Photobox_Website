<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // ============================================================
    // REGISTER - TAMPILKAN FORM
    // ============================================================
    
    public function register()
    {
        // ✅ Path yang benar: 'user/register' (tanpa 'Views/')
        return view('user/register');
    }

    // ============================================================
    // REGISTER - PROSES SIMPAN DATA
    // ============================================================
    
    public function doRegister()
    {
        // Ambil data dari form
        $name = $this->request->getPost('nama');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('konfirmasi');

        // Validasi sederhana
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'konfirmasi' => 'required|matches[password]'
        ]);

        if (!$validation->run($this->request->getPost())) {
            // Jika validasi gagal, kembali ke form dengan error
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Simpan ke database
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'is_active' => 1
        ];

        $this->userModel->save($data);

        // Redirect ke halaman login atau sukses
        return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}