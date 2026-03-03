<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class UserController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // GET /user — daftar semua user (admin)
    public function index()
    {
        $data['users'] = $this->userModel->findAll();
        return view('user/index', $data);
    }

    // GET /user/add — form register
    public function add()
    {
        return view('user/add');
    }

    // POST /user/store — simpan user baru ke DB
    public function store()
    {
        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role'     => 'user',
        ];

        // Cek email sudah ada
        if ($this->userModel->findByEmail($data['email'])) {
            return redirect()->back()->with('error', 'Email sudah terdaftar.');
        }

        $this->userModel->insert($data); // otomatis hash password via beforeInsert
        session()->set([
            'user_id'   => $this->userModel->getInsertID(),
            'username'  => $data['username'],
            'email'     => $data['email'],
            'role'      => 'user',
        ]);

        return redirect()->to('/games')->with('success', 'Akun berhasil dibuat!');
    }

    // GET /user/login — form login
    public function login()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/games');
        }
        return view('user/login');
    }

    // POST /user/authenticate — proses login
    public function authenticate()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Email atau password salah.');
        }

        session()->set([
            'user_id'  => $user['id'],
            'username' => $user['username'],
            'email'    => $user['email'],
            'role'     => $user['role'],
        ]);

        return redirect()->to('/games')->with('success', 'Login berhasil!');
    }

    // GET /user/logout
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/user/login')->with('success', 'Berhasil logout.');
    }

    // GET /user/profile/:id — lihat profil
    public function profile($id)
    {
        $data['user'] = $this->userModel->find($id);
        if (!$data['user']) {
            return redirect()->to('/games')->with('error', 'User tidak ditemukan.');
        }
        return view('user/profile', $data);
    }

    // POST /user/show/:id — update profil sendiri
    public function show($id)
    {
        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
        ];

        $newPass = $this->request->getPost('password');
        if ($newPass) {
            $data['password'] = $newPass; // akan di-hash via beforeUpdate
        }

        $this->userModel->update($id, $data);
        return redirect()->to('/user/profile/' . $id)->with('success', 'Profil berhasil diupdate!');
    }

    // PUT /user/update/:id
    public function update($id)
    {
        $data = $this->request->getRawInput();
        $this->userModel->update($id, $data);
        return redirect()->back()->with('success', 'User berhasil diupdate.');
    }

    // DELETE /user/delete/:id
    public function delete($id)
    {
        $this->userModel->delete($id);
        return redirect()->to('/user')->with('success', 'User berhasil dihapus.');
    }
}
