<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class AdminAccessController extends BaseController
{
    protected AdminModel $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    // =========================================================================
    // INDEX — Daftar admin + filter role + search + pagination + stats
    // =========================================================================
    public function index()
    {
        $perPage     = 10;
        $currentPage = max(1, (int) ($this->request->getGet('page') ?? 1));
        $keyword     = $this->request->getGet('q')    ?? '';
        $filterRole  = $this->request->getGet('role') ?? '';

        // --- Query builder ---
        $builder = $this->adminModel;
        if ($keyword !== '') {
            $builder = $builder->groupStart()
                ->like('name', $keyword)
                ->orLike('email', $keyword)
                ->groupEnd();
        }
        if ($filterRole !== '') {
            $builder = $builder->where('role', $filterRole);
        }

        $totalAdmins = $builder->countAllResults(false);
        $totalPages  = max(1, (int) ceil($totalAdmins / $perPage));
        $admins      = $builder
            ->orderBy('created_at', 'DESC')
            ->findAll($perPage, ($currentPage - 1) * $perPage);

        // --- Stats ---
        $statTotal      = $this->adminModel->countAll();
        $statSuperadmin = $this->adminModel->where('role', 'superadmin')->countAllResults();
        $statAdmin      = $this->adminModel->where('role', 'staff')->countAllResults();

        $data = [
            'title'          => 'Pengaturan Akses Admin | Admin Panel',
            'admins'         => $admins,
            'totalAdmins'    => $totalAdmins,
            'currentPage'    => $currentPage,
            'totalPages'     => $totalPages,
            'keyword'        => $keyword,
            'filterRole'     => $filterRole,
            'statTotal'      => $statTotal,
            'statSuperadmin' => $statSuperadmin,
            'statAdmin'      => $statAdmin,
            'loggedInId'     => session()->get('adminId'), // untuk proteksi hapus diri sendiri
        ];

        return view('Admin/AdminAccess/AdminAccessManajemen', $data);
    }

    // =========================================================================
    // CREATE — Form tambah admin
    // =========================================================================
    public function create()
    {
        return view('Admin/AdminAccess/AdminAccessForm', [
            'title'      => 'Tambah Admin Baru | Admin Panel',
            'validation' => \Config\Services::validation(),
        ]);
    }

    // =========================================================================
    // STORE — Simpan admin baru
    // =========================================================================
    public function store()
    {
        $rules = [
            'name'     => 'required|min_length[3]|max_length[100]|is_unique[admins.name]',
            'email'    => 'required|valid_email|is_unique[admins.email]',
            'password' => 'required|min_length[8]',
            'role'     => 'required|in_list[superadmin,staff]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Auto-generate ID: ADM-XXX — gunakan MAX untuk menghindari duplicate saat ada data dihapus
        $lastRow = $this->adminModel->db
            ->table('admins')
            ->select('id')
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get()->getRowArray();

        $lastNum = 0;
        if ($lastRow) {
            $lastNum = (int) preg_replace('/\D/', '', $lastRow['id']);
        }
        $newId = sprintf('ADM-%03d', $lastNum + 1);

        $this->adminModel->insert([
            'id'       => $newId,
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'role'     => $this->request->getPost('role'),
        ]);

        return redirect()->to(base_url('admin/settings'))
            ->with('success', "Admin {$newId} berhasil ditambahkan.");
    }

    // =========================================================================
    // EDIT — Form edit admin
    // =========================================================================
    public function edit(string $id)
    {
        $admin = $this->adminModel->find($id);
        if (!$admin) {
            return redirect()->to(base_url('admin/settings'))
                ->with('error', 'Admin tidak ditemukan.');
        }

        return view('Admin/AdminAccess/AdminAccessForm', [
            'title'      => 'Edit Admin | Admin Panel',
            'admin'      => $admin,
            'validation' => \Config\Services::validation(),
        ]);
    }

    // =========================================================================
    // UPDATE — Simpan perubahan admin (password opsional)
    // =========================================================================
    public function update(string $id)
    {
        $admin = $this->adminModel->find($id);
        if (!$admin) {
            return redirect()->to(base_url('admin/settings'))
                ->with('error', 'Admin tidak ditemukan.');
        }

        $rules = [
            'name'  => "required|min_length[3]|max_length[100]|is_unique[admins.name,id,{$id}]",
            'email' => "required|valid_email|is_unique[admins.email,id,{$id}]",
            'role'  => 'required|in_list[superadmin,staff]',
        ];

        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            $rules['password'] = 'min_length[8]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role'  => $this->request->getPost('role'),
        ];

        if (!empty($newPassword)) {
            $updateData['password'] = password_hash($newPassword, PASSWORD_BCRYPT);
        }

        $this->adminModel->update($id, $updateData);

        return redirect()->to(base_url('admin/settings'))
            ->with('success', 'Data admin berhasil diperbarui.');
    }

    // =========================================================================
    // DELETE — Hapus admin (tidak bisa hapus diri sendiri)
    // =========================================================================
    public function delete(string $id)
    {
        $admin = $this->adminModel->find($id);
        if (!$admin) {
            return redirect()->to(base_url('admin/settings'))
                ->with('error', 'Admin tidak ditemukan.');
        }

        // Proteksi: admin tidak boleh menghapus dirinya sendiri
        if ($id === session()->get('adminId')) {
            return redirect()->to(base_url('admin/settings'))
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        // Pastikan masih ada minimal 1 superadmin
        if (strtolower($admin['role']) === 'superadmin') {
            $superadminCount = $this->adminModel->where('role', 'superadmin')->countAllResults();
            if ($superadminCount <= 1) {
                return redirect()->to(base_url('admin/settings'))
                    ->with('error', 'Tidak dapat menghapus superadmin terakhir.');
            }
        }

        $this->adminModel->delete($id);

        return redirect()->to(base_url('admin/settings'))
            ->with('success', "Admin {$id} berhasil dihapus.");
    }
}
