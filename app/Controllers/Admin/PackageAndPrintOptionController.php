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
        $perPage       = 10;
        $currentPage   = max(1, (int) ($this->request->getGet('page') ?? 1));
        $keyword       = $this->request->getGet('q') ?? '';

        $optPage       = max(1, (int) ($this->request->getGet('opt_page') ?? 1));
        $keywordOpt    = $this->request->getGet('q_opt') ?? '';

        // --- Query Paket ---
        $pkgBuilder = $this->packageModel;
        if ($keyword !== '') {
            $pkgBuilder = $pkgBuilder->like('name', $keyword);
        }
        $totalPackages  = $pkgBuilder->countAllResults(false);
        $totalPkgPages  = max(1, (int) ceil($totalPackages / $perPage));
        
        // PENTING: reset SELECT setelah countAllResults
        $pkgBuilder->select('*');

        $packages       = $pkgBuilder
            ->orderBy('created_at', 'DESC')
            ->findAll($perPage, ($currentPage - 1) * $perPage);

        // --- Query Opsi Cetak (dipaginasi) ---
        $optBuilder = $this->printOptionModel;
        if ($keywordOpt !== '') {
            $optBuilder = $optBuilder->like('name', $keywordOpt);
        }
        $totalPrintOptions = $optBuilder->countAllResults(false);
        $totalOptPages     = max(1, (int) ceil($totalPrintOptions / $perPage));
        $optBuilder->select('*');
        $printOptions = $optBuilder
            ->orderBy('extra_price', 'ASC')
            ->findAll($perPage, ($optPage - 1) * $perPage);

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
            'totalPrintOptions'   => $totalPrintOptions,
            'currentOptPage'      => $optPage,
            'totalOptPages'       => $totalOptPages,
            'keywordOpt'          => $keywordOpt,
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
            'name'        => 'required|min_length[3]|max_length[100]',
            'photo_count' => 'required|is_natural_no_zero',
            'duration'    => 'required|is_natural_no_zero',
            'base_price'  => 'required|is_natural_no_zero',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Generate ID: PKT-XXX yang kebal dari crash hapus data
        $allPackages = $this->packageModel->select('id')->findAll();
        $maxNum = 0;
        foreach ($allPackages as $p) {
            $num = (int) substr($p['id'], 4);
            if ($num > $maxNum) {
                $maxNum = $num;
            }
        }
        $newId = sprintf('PKT-%03d', $maxNum + 1);

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

        // Mencegah data loss: Cek apakah paket ini sudah pernah dipesan (ada di bookings)
        $bookingModel = new \App\Models\BookingModel();
        $isUsed = $bookingModel->where('package_id', $id)->countAllResults() > 0;
        if ($isUsed) {
            return redirect()->to(base_url('admin/packages'))
                ->with('error', 'Penghapusan ditolak! Paket ini sedang digunakan pada data reservasi pelanggan.');
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

        // Nama file dinamis dengan format Hari, Tanggal Bulan Tahun, Jam-Menit-Detik
        $hariIndo = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $bulanIndo = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $hari = $hariIndo[date('w')];
        $tanggal = date('d');
        $bulan = $bulanIndo[date('n')];
        $tahun = date('Y');
        $waktu = date('H-i-s');
        $filename = "Laporan Paket Dan Cetak {$hari}, {$tanggal} {$bulan} {$tahun}, {$waktu}.csv";

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        
        // Tambahkan UTF-8 BOM dan deklarasi separator khusus agar rapi di Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        fputs($output, "sep=,\n");
        
        fputcsv($output, ['ID', 'Nama Paket', 'Jumlah Foto', 'Durasi (Menit)', 'Harga']);
        foreach ($packages as $p) {
            fputcsv($output, [
                $p['id'], $p['name'], $p['photo_count'],
                $p['duration'], 'Rp ' . number_format($p['base_price'], 0, ',', '.'),
            ]);
        }
        
        // Tanda Tangan Pertanggungjawaban
        fputcsv($output, []); // Baris kosong
        fputcsv($output, []); // Baris kosong
        fputcsv($output, ['--- RIWAYAT PERTANGGUNGJAWABAN EKSPOR ---']);
        fputcsv($output, ['Dihasilkan Oleh:', session()->get('adminName') ?? 'Admin (Sistem)']);
        fputcsv($output, ['Waktu Unduh:', date('Y-m-d H:i:s')]);
        fputcsv($output, ['Catatan Khusus:', 'Laporan ini dihasilkan secara otomatis oleh sistem CuitBox Studio dan sah sebagai bukti audit operasional.']);

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
            'copies'      => 'required|is_natural_no_zero',
            'extra_price' => 'required|is_natural',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Generate ID: OPT-XX yang kebal dari crash hapus data
        $allOpts = $this->printOptionModel->select('id')->findAll();
        $maxNum = 0;
        foreach ($allOpts as $o) {
            $num = (int) substr($o['id'], 4);
            if ($num > $maxNum) {
                $maxNum = $num;
            }
        }
        $newId = sprintf('OPT-%02d', $maxNum + 1);

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

        // Mencegah data loss/kebingungan: Cek apakah opsi cetak dipakai di riwayat pembayaran
        $paymentModel = new \App\Models\PaymentModel();
        $isUsed = $paymentModel->where('print_option_id', $id)->countAllResults() > 0;
        if ($isUsed) {
            return redirect()->to(base_url('admin/packages'))
                ->with('error', 'Penghapusan ditolak! Opsi cetak ini terikat dengan data riwayat pembayaran.');
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

        return view('Admin/PackageAndPrintOption/PrintOptionManajemen', [
            'title'        => 'Kelola Opsi Cetak | Admin Panel',
            'printOptions' => $printOptions,
        ]);
    }
}
