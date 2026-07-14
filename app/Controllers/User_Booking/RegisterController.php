<?php

namespace App\Controllers\User_Booking;  // Perbaiki namespace

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
        return view('User_Booking/register', $data);  // Perbaiki path view
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[customers.email]',
            'password' => 'required|min_length[6]|max_length[50]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $customerModel = new CustomerModel();
        
        // Generate ID
        $lastCustomer = $customerModel->orderBy('id', 'DESC')->first();
        if ($lastCustomer) {
            $lastId = (int) substr($lastCustomer['id'], 1);
            $newId = 'C' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newId = 'C0001';
        }

        $data = [
            'id' => $newId,
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone') ?? '',
            'status' => 'active',
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
        ];

        try {
            $customerModel->insert($data);
            return redirect()->to('/login')->with('success', 'Akun berhasil dibuat! Silahkan login.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}