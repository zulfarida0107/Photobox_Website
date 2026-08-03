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
        $perPage = 10;
        $currentPkgPage = max(1, (int) ($this->request->getGet('page') ?? 1));
        $currentOptPage = max(1, (int) ($this->request->getGet('opt_page') ?? 1));
        $keyword       = $this->request->getGet('q') ?? '';
        $sortPkg       = $this->request->getGet('sort_pkg') ?? 'terbaru';
        $sortOpt       = $this->request->getGet('sort_opt') ?? 'termurah';

        // --- Query Paket ---
        $db = \Config\Database::connect();
        $pkgBuilder = $db->table('packages');
        if ($keyword !== '') {
            $pkgBuilder->groupStart()->like('name', $keyword)->orLike('id', $keyword)->groupEnd();
        }
        if ($sortPkg == 'lebih50') {
            $pkgBuilder->where('base_price >', 50000);
        } elseif ($sortPkg == 'kurang50') {
            $pkgBuilder->where('base_price <=', 50000);
        }
        $totalPackages = $pkgBuilder->countAllResults(false);
        $totalPkgPages = max(1, (int) ceil($totalPackages / $perPage));
        $currentPkgPage = min($currentPkgPage, $totalPkgPages);

        $pkgBuilder->select('*');
        if ($sortPkg == 'lebih50' || $sortPkg == 'kurang50') {
            $pkgBuilder->orderBy('base_price', 'ASC');
        } else {
            $pkgBuilder->orderBy('created_at', 'DESC');
        }
        $packages = $pkgBuilder->limit($perPage, ($currentPkgPage - 1) * $perPage)->get()->getResultArray();

        // --- Query Opsi Cetak ---
        $optBuilder = $db->table('print_options');
        if ($sortOpt == 'lebih50') {
            $optBuilder->where('extra_price >', 50000);
        } elseif ($sortOpt == 'kurang50') {
            $optBuilder->where('extra_price <=', 50000);
        }
        $totalPrintOptions = $optBuilder->countAllResults(false);
        $totalOptPages = max(1, (int) ceil($totalPrintOptions / $perPage));
        $currentOptPage = min($currentOptPage, $totalOptPages);

        $optBuilder->select('*')->orderBy('extra_price', 'ASC');
        $printOptions = $optBuilder->limit($perPage, ($currentOptPage - 1) * $perPage)->get()->getResultArray();

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
            ->limit(10)
            ->get()->getRowArray();
        $statTopPackage = $topPkg['name'] ?? 'N/A';

        $data = [
            'title'               => 'Manajemen Paket & Cetak | Admin Panel',
            'packages'            => $packages,
            'totalPackages'       => $totalPackages,
            'currentPkgPage'      => $currentPkgPage,
            'totalPkgPages'       => $totalPkgPages,
            'totalPrintOptions'   => $totalPrintOptions,
            'currentOptPage'      => $currentOptPage,
            'totalOptPages'       => $totalOptPages,
            'keyword'             => $keyword,
            'sortPkg'             => $sortPkg,
            'sortOpt'             => $sortOpt,
            'printOptions'        => $printOptions,
            'statTotalPackages'   => $statTotalPackages,
            'statTotalPrintOptions' => $statTotalPrintOptions,
            'statAvgPrice'        => $statAvgPrice,
            'statTopPackage'      => $statTopPackage,
        ];

        return view('Admin/PackageAndPrintOption/PackageAndPrintOptionManajemen', $data);
    }

    // =========================================================================
    // PACKAGE — Create
    // =========================================================================
    public function createPackage()
    {
        return view('Admin/PackageAndPrintOption/PackageForm', [
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
            'name'        => 'required|min_length[3]|max_length[100]|is_unique[packages.name]',
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

        return view('Admin/PackageAndPrintOption/PackageForm', [
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
            'name'        => "required|min_length[3]|max_length[100]|is_unique[packages.name,id,{$id}]",
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
        $printOptions = $this->printOptionModel->orderBy('extra_price', 'ASC')->findAll();

        $filename = 'Laporan_Paket_Cetak_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $output = fopen('php://output', 'w');
        fputs($output, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));
        fputs($output, "sep=;\n");

        $adminName = session('adminName') ?? session('admin_name') ?? 'Admin';
        $adminRole = session('adminRole') ?? session('admin_role') ?? 'Unknown Role';
        $adminEmail = session('adminEmail');
        
        if (!$adminEmail && session('adminId')) {
            $adminModel = new \App\Models\AdminModel();
            $adminData = $adminModel->find(session('adminId'));
            if ($adminData) {
                $adminEmail = $adminData['email'];
                $adminName = $adminData['name'];
            }
        }
        $adminEmail = $adminEmail ?? 'No Email';
        
        $penanggungJawab = $adminName . ' (' . $adminRole . ') - ' . $adminEmail;

        $currentTime = date('d M Y, H:i:s');
        $currentDate = date('d F Y');

        fputcsv($output, ['LAPORAN PAKET & CETAK'], ';');
        fputcsv($output, [], ';');
        fputcsv($output, ['Penanggung Jawab: ' . $penanggungJawab], ';');
        fputcsv($output, ['Waktu Realtime: ' . $currentTime . ' WIB'], ';');
        fputcsv($output, ['Keterangan Tanggal: Diekspor pada ' . $currentDate], ';');
        fputcsv($output, [], ';');

        fputcsv($output, ['DAFTAR PAKET LAYANAN', '', '', '', '', '', 'DAFTAR CETAK'], ';');
        fputcsv($output, ['ID Paket', 'Nama Paket', 'Jumlah Foto', 'Durasi (Menit)', 'Harga Dasar (Rp)', '', 'ID Opsi', 'Nama Opsi', 'Jumlah Copies', 'Harga Tambahan (Rp)'], ';');
        
        $max_rows = max(count($packages), count($printOptions));
        for ($i = 0; $i < $max_rows; $i++) {
            $p = $packages[$i] ?? null;
            $opt = $printOptions[$i] ?? null;
            
            $row = [];
            if ($p) {
                $row[] = $p['id'];
                $row[] = $p['name'];
                $row[] = $p['photo_count'];
                $row[] = $p['duration'];
                $row[] = $p['base_price'];
            } else {
                $row = array_merge($row, ['', '', '', '', '']);
            }
            
            $row[] = ''; // Separator column
            
            if ($opt) {
                $name = isset($opt['name']) ? $opt['name'] : 'Opsi Cetak';
                $row[] = $opt['id'];
                $row[] = $name;
                $row[] = $opt['copies'];
                $row[] = $opt['extra_price'];
            } else {
                $row = array_merge($row, ['', '', '', '']);
            }
            
            fputcsv($output, $row, ';');
        }

        fclose($output);
        exit;
    }

    // =========================================================================
    // PRINT OPTION — Create
    // =========================================================================
    public function createPrintOption()
    {
        return view('Admin/PackageAndPrintOption/PrintOptionForm', [
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
            'copies'      => 'required|is_natural_no_zero|is_unique[print_options.copies]',
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

        return view('Admin/PackageAndPrintOption/PrintOptionForm', [
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
            'copies'      => "required|is_natural_no_zero|is_unique[print_options.copies,id,{$id}]",
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

        return redirect()->to(base_url('admin/packages'));
    }
}
