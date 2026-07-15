import re

file_path = 'app/Controllers/Admin/PackageAndPrintOptionController.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Update index method for pagination and sorting
old_index = """    public function index()
    {
        $perPage = 10;
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
        $printOptions = $this->printOptionModel->orderBy('extra_price', 'ASC')->findAll();"""

new_index = """    public function index()
    {
        $perPage = 10;
        $currentPkgPage = max(1, (int) ($this->request->getGet('page') ?? 1));
        $currentOptPage = max(1, (int) ($this->request->getGet('opt_page') ?? 1));
        $keyword       = $this->request->getGet('q') ?? '';
        $sortPkg       = $this->request->getGet('sort_pkg') ?? 'terbaru';
        $sortOpt       = $this->request->getGet('sort_opt') ?? 'termurah';

        // --- Query Paket ---
        $pkgBuilder = $this->packageModel;
        if ($keyword !== '') {
            $pkgBuilder = $pkgBuilder->like('name', $keyword);
        }
        $totalPackages  = $pkgBuilder->countAllResults(false);
        $totalPkgPages  = max(1, (int) ceil($totalPackages / $perPage));
        
        if ($sortPkg == 'termurah') {
            $pkgBuilder->orderBy('base_price', 'ASC');
        } elseif ($sortPkg == 'termahal') {
            $pkgBuilder->orderBy('base_price', 'DESC');
        } else {
            $pkgBuilder->orderBy('created_at', 'DESC');
        }
        
        $packages       = $pkgBuilder->findAll($perPage, ($currentPkgPage - 1) * $perPage);

        // --- Query Opsi Cetak (Pagination) ---
        $optBuilder = $this->printOptionModel;
        $totalPrintOptions  = $optBuilder->countAllResults(false);
        $totalOptPages  = max(1, (int) ceil($totalPrintOptions / $perPage));
        
        if ($sortOpt == 'termahal') {
            $optBuilder->orderBy('extra_price', 'DESC');
        } else {
            $optBuilder->orderBy('extra_price', 'ASC');
        }
        
        $printOptions = $optBuilder->findAll($perPage, ($currentOptPage - 1) * $perPage);"""
content = content.replace(old_index, new_index)

# Ensure new variables are passed to view
old_return = """        return view('Admin/PackageAndPrintOption/PackageAndPrintOptionManajemen', [
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
        ]);"""
new_return = """        return view('Admin/PackageAndPrintOption/PackageAndPrintOptionManajemen', [
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
        ]);"""
content = content.replace(old_return, new_return)


# 2. Update exportPackages method for side-by-side format
old_export = """    public function exportPackages()
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

        fputcsv($output, ['DAFTAR PAKET LAYANAN'], ';');
        fputcsv($output, ['ID Paket', 'Nama Paket', 'Jumlah Foto', 'Durasi (Menit)', 'Harga Dasar (Rp)'], ';');
        foreach ($packages as $p) {
            fputcsv($output, [
                $p['id'], $p['name'], $p['photo_count'],
                $p['duration'], $p['base_price'],
            ], ';');
        }

        fputcsv($output, [], ';');
        
        fputcsv($output, ['DAFTAR CETAK'], ';');
        fputcsv($output, ['ID Opsi', 'Nama Opsi', 'Jumlah Copies', 'Harga Tambahan (Rp)'], ';');
        foreach ($printOptions as $opt) {
            $name = isset($opt['name']) ? $opt['name'] : 'Opsi Cetak';
            fputcsv($output, [
                $opt['id'], $name, $opt['copies'],
                $opt['extra_price']
            ], ';');
        }

        fclose($output);
        exit;
    }"""
new_export = """    public function exportPackages()
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
    }"""
content = content.replace(old_export, new_export)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("SUCCESS")
