<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PackageModel;
use App\Models\PrintOptionModel;

class PackageAndPrintOptionController extends BaseController
{
    protected PackageModel      $packageModel;
    protected PrintOptionModel  $printOptionModel;

    public function __construct()
    {
        $this->packageModel     = new PackageModel();
        $this->printOptionModel = new PrintOptionModel();
    }

    // =========================================================================
    // INDEX — Daftar paket + opsi cetak + stats
    // =========================================================================
    public function index()
    {
        $perPage       = 8;
        $currentPage   = max(1, (int) ($this->request->getGet('page') ?? 1));
        $keyword       = $this->request->getGet('q') ?? '';

        // --- Query Paket ---
        $pkgBuilder = $this->packageModel;
        if ($keyword !== '') {
            $pkgBuilder = $pkgBuilder->like('name', $keyword);
        }
        $totalPackages  = $pkgBuilder->countAllResults(false);
        $totalPkgPages  = max(1, (int) ceil($totalPackages / $perPage));
        $packages       = $pkgBuilder
            ->orderBy('created_at', 'DESC')
            ->findAll($perPage, ($currentPage - 1) * $perPage);

        // --- Semua opsi cetak (sidebar kanan, tidak di-paginate) ---
        $printOptions = $this->printOptionModel->orderBy('extra_price', 'ASC')->findAll();

        // --- Stats ---
        $statTotalPackages    = $this->packageModel->countAll();
        $statTotalPrintOptions = $this->printOptionModel->countAll();

        // Rata-rata harga paket
        $avgRow = $this->packageModel->db
            ->table('packages')
            ->select('AVG(base_price) AS avg_price')
            ->get()->getRowArray();
        $statAvgPrice = round($avgRow['avg_price'] ?? 0);

        // Paket terpopuler (paling banyak di-booking)
        $topPkg = $this->packageModel->db
            ->table('bookings')
            ->select('packages.name, COUNT(bookings.id) AS total')
            ->join('packages', 'packages.id = bookings.package_id', 'left')
            ->groupBy('bookings.package_id')
            ->orderBy('total', 'DESC')
            ->limit(1)
            ->get()->getRowArray();
        $statTopPackage = $topPkg['name'] ?? 'N/A';

        $data = [
            'title'               => 'Manajemen Paket & Cetak | Admin Panel',
            'packages'            => $packages,
            'totalPackages'       => $totalPackages,
            'currentPkgPage'      => $currentPage,
            'totalPkgPages'       => $totalPkgPages,
            'keyword'             => $keyword,
            'printOptions'        => $printOptions,
            'statTotalPackages'   => $statTotalPackages,
            'statTotalPrintOptions' => $statTotalPrintOptions,
            'statAvgPrice'        => $statAvgPrice,
            'statTopPackage'      => $statTopPackage,
        ];

        return view('Admin/PackageAndPrintOptionManajemen', $data);
    }

    // =========================================================================
    // PACKAGE — Create
    // =========================================================================
    public function createPackage()
    {
        return view('Admin/PackageForm', [
            'title'      => 'Tambah Paket Baru | Admin Panel',
            'validation' => \Config\Services::validation(),
        ]);
    }

    // =========================================================================
    // PACKAGE — Store
    // =========================================================================
    public function storePackage()
    {
        $rules = [
            'name'        => 'required|min_length[3]|max_length[100]',
            'photo_count' => 'required|is_natural_no_zero',
            'duration'    => 'required|is_natural_no_zero',
            'base_price'  => 'required|is_natural_no_zero',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Generate ID: PKT-XXX
        $count = $this->packageModel->countAll();
        $newId = sprintf('PKT-%03d', $count + 1);

        $this->packageModel->insert([
            'id'          => $newId,
            'name'        => $this->request->getPost('name'),
            'photo_count' => (int) $this->request->getPost('photo_count'),
            'duration'    => (int) $this->request->getPost('duration'),
            'base_price'  => (int) $this->request->getPost('base_price'),
        ]);

        return redirect()->to(base_url('admin/packages'))
            ->with('success', 'Paket baru berhasil ditambahkan.');
    }

    // =========================================================================
    // PACKAGE — Edit
    // =========================================================================
    public function editPackage(string $id)
    {
        $package = $this->packageModel->find($id);

        if (!$package) {
            return redirect()->to(base_url('admin/packages'))
                ->with('error', 'Paket tidak ditemukan.');
        }

        return view('Admin/PackageForm', [
            'title'      => 'Edit Paket | Admin Panel',
            'package'    => $package,
            'validation' => \Config\Services::validation(),
        ]);
    }

    // =========================================================================
    // PACKAGE — Update
    // =========================================================================
    public function updatePackage(string $id)
    {
        $package = $this->packageModel->find($id);
        if (!$package) {
            return redirect()->to(base_url('admin/packages'))
                ->with('error', 'Paket tidak ditemukan.');
        }

        $rules = [
            'name'        => 'required|min_length[3]|max_length[100]',
            'photo_count' => 'required|is_natural_no_zero',
            'duration'    => 'required|is_natural_no_zero',
            'base_price'  => 'required|is_natural_no_zero',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->packageModel->update($id, [
            'name'        => $this->request->getPost('name'),
            'photo_count' => (int) $this->request->getPost('photo_count'),
            'duration'    => (int) $this->request->getPost('duration'),
            'base_price'  => (int) $this->request->getPost('base_price'),
        ]);

        return redirect()->to(base_url('admin/packages'))
            ->with('success', 'Paket berhasil diperbarui.');
    }

    // =========================================================================
    // PACKAGE — Delete
    // =========================================================================
    public function deletePackage(string $id)
    {
        $package = $this->packageModel->find($id);
        if (!$package) {
            return redirect()->to(base_url('admin/packages'))
                ->with('error', 'Paket tidak ditemukan.');
        }

        $this->packageModel->delete($id);

        return redirect()->to(base_url('admin/packages'))
            ->with('success', 'Paket berhasil dihapus.');
    }

    // =========================================================================
    // PACKAGE — Export CSV
    // =========================================================================
    public function exportPackages()
    {
        $packages = $this->packageModel->orderBy('created_at', 'DESC')->findAll();

        $filename = 'packages_export_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Nama Paket', 'Jumlah Foto', 'Durasi (Menit)', 'Harga Dasar']);
        foreach ($packages as $p) {
            fputcsv($output, [
                $p['id'], $p['name'], $p['photo_count'],
                $p['duration'], $p['base_price'],
            ]);
        }
        fclose($output);
        exit;
    }

    // =========================================================================
    // PRINT OPTION — Create
    // =========================================================================
    public function createPrintOption()
    {
        return view('Admin/PrintOptionForm', [
            'title'      => 'Tambah Opsi Cetak | Admin Panel',
            'validation' => \Config\Services::validation(),
        ]);
    }

    // =========================================================================
    // PRINT OPTION — Store
    // =========================================================================
    public function storePrintOption()
    {
        $rules = [
            'copies'      => 'required|is_natural_no_zero',
            'extra_price' => 'required|is_natural',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $count = $this->printOptionModel->countAll();
        $newId = sprintf('OPT-%02d', $count + 1);

        $this->printOptionModel->insert([
            'id'          => $newId,
            'copies'      => (int) $this->request->getPost('copies'),
            'extra_price' => (int) $this->request->getPost('extra_price'),
        ]);

        return redirect()->to(base_url('admin/packages'))
            ->with('success', 'Opsi cetak baru berhasil ditambahkan.');
    }

    // =========================================================================
    // PRINT OPTION — Edit
    // =========================================================================
    public function editPrintOption(string $id)
    {
        $option = $this->printOptionModel->find($id);
        if (!$option) {
            return redirect()->to(base_url('admin/packages'))
                ->with('error', 'Opsi cetak tidak ditemukan.');
        }

        return view('Admin/PrintOptionForm', [
            'title'      => 'Edit Opsi Cetak | Admin Panel',
            'option'     => $option,
            'validation' => \Config\Services::validation(),
        ]);
    }

    // =========================================================================
    // PRINT OPTION — Update
    // =========================================================================
    public function updatePrintOption(string $id)
    {
        $option = $this->printOptionModel->find($id);
        if (!$option) {
            return redirect()->to(base_url('admin/packages'))
                ->with('error', 'Opsi cetak tidak ditemukan.');
        }

        $rules = [
            'copies'      => 'required|is_natural_no_zero',
            'extra_price' => 'required|is_natural',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->printOptionModel->update($id, [
            'copies'      => (int) $this->request->getPost('copies'),
            'extra_price' => (int) $this->request->getPost('extra_price'),
        ]);

        return redirect()->to(base_url('admin/packages'))
            ->with('success', 'Opsi cetak berhasil diperbarui.');
    }

    // =========================================================================
    // PRINT OPTION — Delete
    // =========================================================================
    public function deletePrintOption(string $id)
    {
        $option = $this->printOptionModel->find($id);
        if (!$option) {
            return redirect()->to(base_url('admin/packages'))
                ->with('error', 'Opsi cetak tidak ditemukan.');
        }

        $this->printOptionModel->delete($id);

        return redirect()->to(base_url('admin/packages'))
            ->with('success', 'Opsi cetak berhasil dihapus.');
    }

    // =========================================================================
    // PRINT OPTION — Index (halaman khusus opsi cetak)
    // =========================================================================
    public function printOptions()
    {
        $printOptions = $this->printOptionModel->orderBy('extra_price', 'ASC')->findAll();

        return view('Admin/PrintOptionManajemen', [
            'title'        => 'Kelola Opsi Cetak | Admin Panel',
            'printOptions' => $printOptions,
        ]);
    }
}
