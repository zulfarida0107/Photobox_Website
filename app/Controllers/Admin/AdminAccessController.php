<?php

/**
 * Berkas: app/Controllers/Admin/AdminAccessController.php
 * Proyek: CuitBox Studio - Photobox Website & Management System
 * Author: Zulfarida (Lead Backend & Web Developer)
 * 
 * Standar Kompetensi Kerja Nasional Indonesia (SKKNI):
 * - Unit SKKNI: J.620100.015.01 (Menyusun Fungsi, File atau Sumber Daya Pemrograman dalam Organisasi yang Rapi)
 * - Unit SKKNI: J.620100.016.01 (Menulis Kode dengan Prinsip Sesuai Guidelines dan Best Practices)
 * - Unit SKKNI: J.620100.017.02 (Mengimplementasikan Pemrograman Terstruktur)
 * 
 * Deskripsi:
 * Mengelola hak akses administrator (Role-Based Access Control / RBAC) yang mencakup
 * superadmin, admin, dan staff. Mengimplementasikan operasi CRUD data pengguna sistem,
 * paginasi dinamis, filter peran, proteksi penghapusan akun mandiri, enkripsi Bcrypt,
 * serta pembuatan ID otomatis berformat 'ADM-XXX'.
 */

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use CodeIgniter\HTTP\RedirectResponse;

/**
 * Class AdminAccessController
 *
 * Mengendalikan modul manajemen pengguna pengelola sistem dan pengaturan hak akses.
 *
 * @package App\Controllers\Admin
 */
class AdminAccessController extends BaseController
{
    /**
     * Instance model AdminModel untuk akses tabel basis data admins
     *
     * @var AdminModel
     */
    protected AdminModel $adminModel;

    /**
     * Inisialisasi controller dan instansiasi dependensi model
     */
    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    /**
     * Menampilkan daftar seluruh administrator dengan fitur pencarian, filter, dan paginasi
     *
     * Alur kerja:
     * 1. Menangkap parameter query string: nomor halaman, kata kunci pencarian, dan filter role
     * 2. Membangun query builder dinamis dengan klausa LIKE untuk nama/email dan WHERE untuk role
     * 3. Menghitung total data dan total halaman paginasi
     * 4. Melakukan pengambilan subset data halaman aktif dengan pengurutan numerik
     * 5. Menghitung statistik ringkasan per role untuk kartu indikator antarmuka
     *
     * @return string Render tampilan view AdminAccessManajemen
     */
    public function index()
    {
        // 1. Inisialisasi parameter paginasi dan filter
        $perPage     = 10;
        $currentPage = max(1, (int) ($this->request->getGet('page') ?? 1));
        $keyword     = trim((string) ($this->request->getGet('q') ?? ''));
        $filterRole  = trim((string) ($this->request->getGet('role') ?? ''));

        // 2. Query Builder untuk penyaringan data administrator
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

        // 3. Kalkulasi metrik paginasi
        $totalAdmins = $builder->countAllResults(false);
        $totalPages  = max(1, (int) ceil($totalAdmins / $perPage));
        $admins      = $builder
            ->orderBy("CAST(REGEXP_REPLACE(id, '[^0-9]', '') AS UNSIGNED)", 'ASC', false)
            ->orderBy('id', 'ASC')
            ->findAll($perPage, ($currentPage - 1) * $perPage);

        // 4. Kalkulasi metrik statistik per peran (Superadmin, Admin, Staff)
        $statTotal      = $this->adminModel->countAll();
        $statSuperadmin = $this->adminModel->where('role', 'superadmin')->countAllResults();
        $statAdmin      = $this->adminModel->where('role', 'admin')->countAllResults();
        $statStaff      = $this->adminModel->where('role', 'staff')->countAllResults();

        // 5. Pembungkusan data untuk kebutuhan render antarmuka (UI)
        $data = [
            'title'               => 'Pengaturan Akses Admin | Admin Panel',
            'admins'              => $admins,
            'totalAdmins'         => $totalAdmins,
            'currentPage'         => $currentPage,
            'totalPages'          => $totalPages,
            'keyword'             => $keyword,
            'filterRole'          => $filterRole,
            'statTotal'           => $statTotal,
            'statSuperadmin'      => $statSuperadmin,
            'statAdmin'           => $statAdmin,
            'statStaff'           => $statStaff,
            'loggedInId'          => session()->get('adminId'), // Proteksi agar tidak menghapus akun sendiri
            'canManageSuperadmin' => $this->isSuperadmin(),
        ];

        return view('Admin/AdminAccess/AdminAccessManajemen', $data);
    }

    /**
     * Menampilkan formulir pendaftaran admin atau staf baru
     *
     * @return string Render tampilan formulir input admin
     */
    public function create()
    {
        return view('Admin/AdminAccess/AdminAccessForm', [
            'title'               => 'Tambah Admin Baru | Admin Panel',
            'validation'          => \Config\Services::validation(),
            'canManageSuperadmin' => $this->isSuperadmin(),
        ]);
    }

    /**
     * Menyimpan data administrator baru ke dalam basis data
     *
     * Alur kerja:
     * 1. Validasi hak akses penambahan peran superadmin
     * 2. Validasi input: keunikan nama dan email, format email, panjang minimal password
     * 3. Penentuan ID baru secara otomatis ('ADM-001', 'ADM-002', dst.) berbasis nomor urut tertinggi
     * 4. Enkripsi password menggunakan algoritma Bcrypt via password_hash()
     * 5. Eksekusi query INSERT ke tabel basis data
     *
     * @return RedirectResponse Redirect ke tabel manajemen akses dengan flash data status
     */
    public function store()
    {
        // 1. Tentukan batasan pilihan role berdasarkan hierarki pengguna aktif
        $allowedRoles = $this->isSuperadmin() ? ['superadmin', 'admin', 'staff'] : ['admin', 'staff'];
        $rules = [
            'name'     => 'required|min_length[3]|max_length[100]|is_unique[admins.name]',
            'email'    => 'required|valid_email|is_unique[admins.email]',
            'password' => 'required|min_length[8]',
            'role'     => 'required|in_list[' . implode(',', $allowedRoles) . ']',
        ];

        // 2. Evaluasi validasi data masukan
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // 3. Generate primary key unik dengan format ADM-XXX berurutan
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

        // 4. Masukkan rekaman baru dengan kata sandi ter-hash
        $this->adminModel->insert([
            'id'       => $newId,
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_BCRYPT),
            'role'     => $this->request->getPost('role'),
        ]);

        return redirect()->to(base_url('admin/settings#table-admin'))
            ->with('success', 'Admin berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir untuk menyunting data administrator yang ada
     *
     * @param string $id Pengenal unik (Primary Key) akun admin
     * @return string|RedirectResponse Render formulir edit atau redirect jika data tidak valid
     */
    public function edit(string $id)
    {
        // 1. Temukan record admin berdasarkan ID
        $admin = $this->adminModel->find($id);
        if (!$admin) {
            return redirect()->to(base_url('admin/settings'))
                ->with('error', 'Admin tidak ditemukan.');
        }

        // 2. Cegah non-superadmin memodifikasi data akun superadmin
        if (!$this->isSuperadmin() && strtolower($admin['role']) === 'superadmin') {
            return redirect()->to(base_url('admin/settings'))
                ->with('error', 'Data superadmin hanya dapat diubah oleh superadmin.');
        }

        return view('Admin/AdminAccess/AdminAccessForm', [
            'title'               => 'Edit Admin | Admin Panel',
            'admin'               => $admin,
            'validation'          => \Config\Services::validation(),
            'canManageSuperadmin' => $this->isSuperadmin(),
        ]);
    }

    /**
     * Memperbarui data profil dan peran administrator pada basis data
     *
     * @param string $id Pengenal unik (Primary Key) akun admin
     * @return RedirectResponse Redirect ke tabel manajemen akses dengan status pesan
     */
    public function update(string $id)
    {
        $admin = $this->adminModel->find($id);
        if (!$admin) {
            return redirect()->to(base_url('admin/settings'))
                ->with('error', 'Admin tidak ditemukan.');
        }

        // Verifikasi izin modifikasi entitas superadmin
        if (!$this->isSuperadmin() && strtolower($admin['role']) === 'superadmin') {
            return redirect()->to(base_url('admin/settings'))
                ->with('error', 'Data superadmin hanya dapat diubah oleh superadmin.');
        }

        // Aturan validasi dengan pengecualian ID saat ini (is_unique ignore)
        $allowedRoles = $this->isSuperadmin() ? ['superadmin', 'admin', 'staff'] : ['admin', 'staff'];
        $rules = [
            'name'  => "required|min_length[3]|max_length[100]|is_unique[admins.name,id,{$id}]",
            'email' => "required|valid_email|is_unique[admins.email,id,{$id}]",
            'role'  => 'required|in_list[' . implode(',', $allowedRoles) . ']',
        ];

        // Validasi opsional jika pengguna ingin memperbarui password
        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            $rules['password'] = 'min_length[8]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Proteksi: tidak boleh mencabut peran superadmin jika hanya tersisa 1 di sistem
        if (
            strtolower($admin['role']) === 'superadmin'
            && $this->request->getPost('role') !== 'superadmin'
            && $this->adminModel->where('role', 'superadmin')->countAllResults() <= 1
        ) {
            return redirect()->to(base_url('admin/settings'))
                ->with('error', 'Tidak dapat menurunkan role superadmin terakhir.');
        }

        // Susun payload data pembaruan
        $updateData = [
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role'  => $this->request->getPost('role'),
        ];

        if (!empty($newPassword)) {
            $updateData['password'] = password_hash((string) $newPassword, PASSWORD_BCRYPT);
        }

        $this->adminModel->update($id, $updateData);

        return redirect()->to(base_url('admin/settings#table-admin'))
            ->with('success', 'Data admin berhasil diperbarui.');
    }

    /**
     * Menghapus akun administrator dari sistem dengan aturan proteksi keamanan
     *
     * Proteksi keamanan yang diterapkan:
     * 1. Pengguna aktif tidak diizinkan menghapus akun miliknya sendiri
     * 2. Non-superadmin tidak diizinkan menghapus akun bertipe superadmin
     * 3. Akun superadmin terakhir di sistem dilarang keras untuk dihapus
     *
     * @param string $id Pengenal unik akun admin yang akan dihapus
     * @return RedirectResponse Redirect ke tabel manajemen akses dengan status pesan
     */
    public function delete(string $id)
    {
        $admin = $this->adminModel->find($id);
        if (!$admin) {
            return redirect()->to(base_url('admin/settings#table-admin'))
                ->with('error', 'Admin tidak ditemukan.');
        }

        if (!$this->isSuperadmin() && strtolower($admin['role']) === 'superadmin') {
            return redirect()->to(base_url('admin/settings'))
                ->with('error', 'Data superadmin hanya dapat dihapus oleh superadmin.');
        }

        // Proteksi 1: Admin dilarang menghapus akun dirinya sendiri saat sesi aktif
        if ($id === session()->get('adminId')) {
            return redirect()->to(base_url('admin/settings#table-admin'))
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        // Proteksi 2: Pastikan ketersediaan minimal 1 akun superadmin di basis data
        if (strtolower($admin['role']) === 'superadmin') {
            $superadminCount = $this->adminModel->where('role', 'superadmin')->countAllResults();
            if ($superadminCount <= 1) {
                return redirect()->to(base_url('admin/settings#table-admin'))
                    ->with('error', 'Tidak dapat menghapus superadmin terakhir.');
            }
        }

        // Eksekusi penghapusan rekaman
        $this->adminModel->delete($id);

        return redirect()->to(base_url('admin/settings#table-admin'))
            ->with('success', 'Admin berhasil dihapus.');
    }

    /**
     * Memeriksa apakah administrator yang sedang terautentikasi memiliki peran superadmin
     *
     * @return bool Bernilai true jika sesi aktif adalah superadmin
     */
    private function isSuperadmin(): bool
    {
        return strtolower((string) (session()->get('adminRole') ?? '')) === 'superadmin';
    }
}
