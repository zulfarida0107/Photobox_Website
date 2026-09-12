<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PrintOptionModel;

class PackageAndPrintOptionController extends BaseController
{
    protected PrintOptionModel  $printOptionModel;

    public function __construct()
    {
        $this->printOptionModel = new PrintOptionModel();
    }

    // =========================================================================
    // INDEX — Daftar opsi cetak + stats
    // =========================================================================
    public function index()
    {
        $perPage = 5;
        $currentOptPage = max(1, (int) ($this->request->getGet('opt_page') ?? 1));
        $sortOpt        = $this->request->getGet('sort_opt') ?? 'semua';
        $searchOpt      = $this->request->getGet('search_opt') ?? '';

        $db = \Config\Database::connect();

        // --- Query Opsi Cetak ---
        $optBuilder = $db->table('print_options')
            ->select('print_options.*, COUNT(bookings.id) as total_used')
            ->join('bookings', 'bookings.print_option_id = print_options.id AND MONTH(bookings.created_at) = MONTH(NOW()) AND YEAR(bookings.created_at) = YEAR(NOW())', 'left')
            ->groupBy('print_options.id');

        $totalBuilder = $db->table('print_options');

        if ($sortOpt == 'lebih50') {
            $optBuilder->where('print_options.extra_price >', 50000);
            $totalBuilder->where('extra_price >', 50000);
        } elseif ($sortOpt == 'kurang50') {
            $optBuilder->where('print_options.extra_price <=', 50000);
            $totalBuilder->where('extra_price <=', 50000);
        }

        if (!empty($searchOpt)) {
            $optBuilder->groupStart()
                ->like('print_options.id', $searchOpt)
                ->orLike('print_options.copies', $searchOpt)
                ->groupEnd();
            $totalBuilder->groupStart()
                ->like('id', $searchOpt)
                ->orLike('copies', $searchOpt)
                ->groupEnd();
        }
        
        $totalPrintOptions = $totalBuilder->countAllResults(false);
        $totalOptPages = max(1, (int) ceil($totalPrintOptions / $perPage));
        $currentOptPage = min($currentOptPage, $totalOptPages);

        $optBuilder->orderBy('print_options.copies', 'ASC');
        $printOptions = $optBuilder->limit($perPage, ($currentOptPage - 1) * $perPage)->get()->getResultArray();

        $statTotalPrintOptions = $this->printOptionModel->countAll();

        // Rata-rata Harga Tambahan
        $avgRow = $this->printOptionModel->db
            ->table('print_options')
            ->select('AVG(extra_price) AS avg_price')
            ->get()->getRowArray();
        $statAvgPrice = round($avgRow['avg_price'] ?? 0);

        // Opsi Terpopuler (bulan ini)
        $topOpt = $this->printOptionModel->db
            ->table('bookings')
            ->select('print_options.copies AS print_copies, COUNT(bookings.id) AS total')
            ->join('print_options', 'print_options.id = bookings.print_option_id', 'left')
            ->where('MONTH(bookings.created_at) = MONTH(NOW())')
            ->where('YEAR(bookings.created_at) = YEAR(NOW())')
            ->groupBy('bookings.print_option_id')
            ->orderBy('total', 'DESC')
            ->limit(1)
            ->get()->getRowArray();
        $statTopOption = isset($topOpt['print_copies']) ? $topOpt['print_copies'] . ' Copies' : 'N/A';

        // Total Transaksi Cetak bulan ini
        $statTotalBookings = $this->printOptionModel->db
            ->table('bookings')
            ->where('print_option_id IS NOT NULL')
            ->where('MONTH(created_at) = MONTH(NOW())')
            ->where('YEAR(created_at) = YEAR(NOW())')
            ->countAllResults();

        $data = [
            'title'               => 'Manajemen Opsi Cetak | Admin Panel',
            'totalPrintOptions'   => $totalPrintOptions,
            'currentOptPage'      => $currentOptPage,
            'totalOptPages'       => $totalOptPages,
            'sortOpt'             => $sortOpt,
            'searchOpt'           => $searchOpt,
            'printOptions'        => $printOptions,
            'statTotalPrintOptions' => $statTotalPrintOptions,
            'statAvgPrice'          => $statAvgPrice,
            'statTopOption'         => $statTopOption,
            'statTotalBookings'     => $statTotalBookings,
        ];

        return view('Admin/PackageAndPrintOption/PackageAndPrintOptionManajemen', $data);
    }

    // =========================================================================
    // PRINT OPTION — Export CSV
    // =========================================================================
    public function exportPrintOptions()
    {
        $printOptions = $this->printOptionModel->orderBy('extra_price', 'ASC')->findAll();

        $filename = 'Laporan_Cetak_' . date('Ymd_His') . '.csv';
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

        fputcsv($output, ['LAPORAN OPSI CETAK'], ';');
        fputcsv($output, [], ';');
        fputcsv($output, ['Penanggung Jawab: ' . $penanggungJawab], ';');
        fputcsv($output, ['Waktu Realtime: ' . $currentTime . ' WIB'], ';');
        fputcsv($output, ['Keterangan Tanggal: Diekspor pada ' . $currentDate], ';');
        fputcsv($output, [], ';');

        fputcsv($output, ['ID Opsi', 'Nama Opsi', 'Jumlah Copies', 'Harga Tambahan (Rp)'], ';');
        
        foreach ($printOptions as $opt) {
            $name = isset($opt['name']) ? $opt['name'] : ($opt['copies'] . ' Lembar');
            fputcsv($output, [
                $opt['id'],
                $name,
                $opt['copies'],
                $opt['extra_price']
            ], ';');
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
            'extra_price' => 'required|is_natural_no_zero',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $maxRow = $this->printOptionModel->select('MAX(CAST(id AS UNSIGNED)) as max_id')->first();
        $newId  = (string) (($maxRow['max_id'] ?? 0) + 1);

        $this->printOptionModel->insert([
            'id'          => $newId,
            'copies'      => (int) $this->request->getPost('copies'),
            'extra_price' => (int) $this->request->getPost('extra_price'),
        ]);

        return redirect()->to(base_url('admin/print-options'))
            ->with('success', 'Opsi cetak baru berhasil ditambahkan.');
    }

    // =========================================================================
    // PRINT OPTION — Edit
    // =========================================================================
    public function editPrintOption(string $id)
    {
        $option = $this->printOptionModel->find($id);
        if (!$option) {
            return redirect()->to(base_url('admin/print-options'))
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
            return redirect()->to(base_url('admin/print-options'))
                ->with('error', 'Opsi cetak tidak ditemukan.');
        }

        $rules = [
            'copies'      => "required|is_natural_no_zero|is_unique[print_options.copies,id,{$id}]",
            'extra_price' => 'required|is_natural_no_zero',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->printOptionModel->update($id, [
            'copies'      => (int) $this->request->getPost('copies'),
            'extra_price' => (int) $this->request->getPost('extra_price'),
        ]);

        return redirect()->to(base_url('admin/print-options'))
            ->with('success', 'Opsi cetak berhasil diperbarui.');
    }

    // =========================================================================
    // PRINT OPTION — Delete
    // =========================================================================
    public function deletePrintOption(string $id)
    {
        $option = $this->printOptionModel->find($id);
        if (!$option) {
            return redirect()->to(base_url('admin/print-options'))
                ->with('error', 'Opsi cetak tidak ditemukan.');
        }

        // Cek apakah opsi cetak masih dirujuk oleh booking/payment aktif
        $db             = \Config\Database::connect();
        $activeStatuses = ['paid', 'pending', 'confirmed'];
        $activeBookings = $db->table('bookings')
            ->whereIn('status', $activeStatuses)
            ->where('print_option_id', $id)
            ->countAllResults();

        if ($activeBookings > 0) {
            return redirect()->to(base_url('admin/print-options'))
                ->with('error', "Opsi cetak tidak dapat dihapus — masih digunakan oleh {$activeBookings} booking aktif. Batalkan booking tersebut terlebih dahulu.");
        }

        $this->printOptionModel->delete($id);

        return redirect()->to(base_url('admin/print-options'))
            ->with('success', 'Opsi cetak berhasil dihapus.');
    }
}
