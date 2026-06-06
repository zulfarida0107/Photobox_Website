<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CustomerModel;

class CustomerController extends BaseController
{
    protected CustomerModel $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
    }

    // =========================================================================
    // INDEX — Halaman utama daftar pelanggan
    // =========================================================================
    public function index()
    {
        $perPage       = 10;
        $currentPage   = (int) ($this->request->getGet('page') ?? 1);
        $filterStatus  = $this->request->getGet('status') ?? 'all'; // all | active | inactive
        $keyword       = $this->request->getGet('q') ?? '';

        // --- Query builder ---
        $builder = $this->customerModel;

        // Filter pencarian keyword (nama / email / phone)
        if ($keyword !== '') {
            $builder = $builder->groupStart()
                ->like('name', $keyword)
                ->orLike('email', $keyword)
                ->orLike('phone', $keyword)
                ->groupEnd();
        }

        // Catatan: CustomerModel saat ini tidak memiliki kolom 'status'.
        // Jika nanti kolom status ditambahkan ke tabel customers,
        // aktifkan blok filter berikut:
        // if ($filterStatus === 'active') {
        //     $builder = $builder->where('status', 'active');
        // } elseif ($filterStatus === 'inactive') {
        //     $builder = $builder->where('status', 'inactive');
        // }

        // Hitung total untuk pagination
        $totalAll      = $this->customerModel->countAll();
        $totalFiltered = $builder->countAllResults(false); // false = jangan reset query

        // Ambil data dengan pagination
        $customers = $builder
            ->orderBy('created_at', 'DESC')
            ->findAll($perPage, ($currentPage - 1) * $perPage);

        // --- Statistik card ---
        $totalCustomers  = $totalAll;
        // Placeholder — ganti dengan query nyata jika kolom status tersedia
        $totalActive     = $totalAll;
        $totalInactive   = 0;

        // Hitung total halaman
        $totalPages = (int) ceil($totalFiltered / $perPage);

        $data = [
            'title'           => 'Manajemen Pelanggan | Admin Panel',
            'customers'       => $customers,
            'totalCustomers'  => $totalCustomers,
            'totalActive'     => $totalActive,
            'totalInactive'   => $totalInactive,
            'totalFiltered'   => $totalFiltered,
            'currentPage'     => $currentPage,
            'totalPages'      => $totalPages,
            'perPage'         => $perPage,
            'filterStatus'    => $filterStatus,
            'keyword'         => $keyword,
        ];

        return view('Admin/Customer/CustomerManajemen', $data);
    }

    // =========================================================================
    // SHOW — Detail satu pelanggan (modal / halaman terpisah)
    // =========================================================================
    public function show(string $id)
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            return redirect()->to(base_url('admin/customers'))
                ->with('error', 'Pelanggan tidak ditemukan.');
        }

        $data = [
            'title'    => 'Detail Pelanggan | Admin Panel',
            'customer' => $customer,
        ];

        return view('Admin/Customer/CustomerDetail', $data);
    }

    // =========================================================================
    // CREATE — Form tambah pelanggan baru
    // =========================================================================
    public function create()
    {
        $data = [
            'title'      => 'Tambah Pelanggan | Admin Panel',
            'validation' => \Config\Services::validation(),
        ];

        return view('Admin/Customer/CustomerForm', $data);
    }

    // =========================================================================
    // STORE — Simpan pelanggan baru ke database
    // =========================================================================
    public function store()
    {
        $rules = [
            'name'  => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[customers.email]',
            'phone' => 'required|min_length[9]|max_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Generate ID unik: CUST-YYYY-XXX
        $year      = date('Y');
        $lastCount = $this->customerModel->where("id LIKE 'CUST-{$year}-%'")->countAllResults();
        $newId     = sprintf('CUST-%s-%03d', $year, $lastCount + 1);

        $this->customerModel->insert([
            'id'    => $newId,
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
        ]);

        return redirect()->to(base_url('admin/customers'))
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    // =========================================================================
    // EDIT — Form ubah data pelanggan
    // =========================================================================
    public function edit(string $id)
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            return redirect()->to(base_url('admin/customers'))
                ->with('error', 'Pelanggan tidak ditemukan.');
        }

        $data = [
            'title'      => 'Edit Pelanggan | Admin Panel',
            'customer'   => $customer,
            'validation' => \Config\Services::validation(),
        ];

        return view('Admin/Customer/CustomerForm', $data);
    }

    // =========================================================================
    // UPDATE — Simpan perubahan data pelanggan
    // =========================================================================
    public function update(string $id)
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            return redirect()->to(base_url('admin/customers'))
                ->with('error', 'Pelanggan tidak ditemukan.');
        }

        $rules = [
            'name'  => 'required|min_length[3]|max_length[100]',
            // is_unique diabaikan jika email tidak berubah
            'email' => "required|valid_email|is_unique[customers.email,id,{$id}]",
            'phone' => 'required|min_length[9]|max_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->customerModel->update($id, [
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
        ]);

        return redirect()->to(base_url('admin/customers'))
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    // =========================================================================
    // DESTROY — Hapus pelanggan
    // =========================================================================
    public function destroy(string $id)
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            return redirect()->to(base_url('admin/customers'))
                ->with('error', 'Pelanggan tidak ditemukan.');
        }

        $this->customerModel->delete($id);

        return redirect()->to(base_url('admin/customers'))
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}
