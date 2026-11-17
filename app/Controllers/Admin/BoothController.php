<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BoothModel;
use App\Models\LocationModel;

class BoothController extends BaseController
{
    protected BoothModel $boothModel;
    protected LocationModel $locationModel;

    public function __construct()
    {
        $this->boothModel    = new BoothModel();
        $this->locationModel = new LocationModel();
    }

    // =========================================================================
    // INDEX — Daftar booth & Lokasi + filter + search + pagination + stats
    // =========================================================================
    public function index()
    {
        $perPage      = 10;
        $currentPage  = max(1, (int) ($this->request->getGet('page')   ?? 1));
        $keyword      = $this->request->getGet('q')      ?? '';
        $filterStatus = $this->request->getGet('status') ?? '';
        $locationSearch = $this->request->getGet('loc_q') ?? '';

        // --- Query builder Booths ---
        $builder = $this->boothModel
            ->select('booths.*, locations.name AS location_name, locations.city AS location_city')
            ->join('locations', 'locations.id = booths.location_id', 'left');

        if ($keyword !== '') {
            $builder = $builder->groupStart()
                ->like('booths.id', $keyword)
                ->orLike('booths.name', $keyword)
                ->orLike('locations.name', $keyword)
                ->groupEnd();
        }
        if ($filterStatus !== '') {
            $builder = $builder->where('booths.status', $filterStatus);
        }

        $totalBooths = $builder->countAllResults(false);
        $totalPages  = max(1, (int) ceil($totalBooths / $perPage));

        $booths = $builder
            ->orderBy('booths.id', 'ASC')
            ->findAll($perPage, ($currentPage - 1) * $perPage);

        // --- Stats Booths ---
        $statTotal       = $this->boothModel->countAll();
        $statActive      = $this->boothModel->where('status', 'active')->countAllResults();
        $statMaintenance = $this->boothModel->where('status', 'maintenance')->countAllResults();
        $statTotalQueue  = $this->boothModel->db
            ->table('booths')
            ->select('SUM(current_queue) AS total')
            ->get()->getRowArray()['total'] ?? 0;

        // Booth dalam maintenance untuk panel alert
        $maintenanceBooths = $this->boothModel
            ->where('status', 'maintenance')
            ->orderBy('updated_at', 'DESC')
            ->findAll(5);

        // --- Query Locations with Pagination ---
        $locPerPage        = 10;
        $currentLocPage    = max(1, (int) ($this->request->getGet('loc_page') ?? 1));
        $locBuilder = $this->locationModel
            ->select('locations.*, COUNT(booths.id) AS booth_count')
            ->join('booths', 'booths.location_id = locations.id', 'left')
            ->groupBy('locations.id');

        if ($locationSearch !== '') {
            $locBuilder = $locBuilder->groupStart()
                ->like('locations.id', $locationSearch)
                ->orLike('locations.name', $locationSearch)
                ->orLike('locations.city', $locationSearch)
                ->orLike('locations.address', $locationSearch)
                ->groupEnd();
        }

        $totalLocations = $locBuilder->countAllResults(false);
        $totalLocPages  = max(1, (int) ceil($totalLocations / $locPerPage));

        $locations = $locBuilder
            ->orderBy('locations.id', 'ASC')
            ->findAll($locPerPage, ($currentLocPage - 1) * $locPerPage);

        $statTotalLocations  = $this->locationModel->countAll();
        $statActiveLocations = $this->locationModel->where('status', 'active')->countAllResults();

        // Fetch all booths and locations for mapping
        $allBooths = $this->boothModel
            ->select('booths.*, locations.name AS location_name, locations.latitude AS loc_lat, locations.longitude AS loc_lng')
            ->join('locations', 'locations.id = booths.location_id', 'left')
            ->findAll();

        $boothsJson = json_encode(array_map(function($b) {
            $lat = !empty($b['latitude']) ? (float)$b['latitude'] : (!empty($b['loc_lat']) ? (float)$b['loc_lat'] : null);
            $lng = !empty($b['longitude']) ? (float)$b['longitude'] : (!empty($b['loc_lng']) ? (float)$b['loc_lng'] : null);
            return [
                'name'          => $b['name'] ?? '',
                'location_name' => $b['location_name'] ?? 'Pusat Studio',
                'status'        => $b['status'] ?? 'active',
                'latitude'      => $lat,
                'longitude'     => $lng,
            ];
        }, $allBooths));

        $locationsJson = json_encode(array_map(function($l) {
            return [
                'id'        => $l['id'],
                'name'      => $l['name'],
                'city'      => $l['city'] ?? '',
                'address'   => $l['address'] ?? '',
                'status'    => $l['status'] ?? 'active',
                'latitude'  => !empty($l['latitude']) ? (float)$l['latitude'] : null,
                'longitude' => !empty($l['longitude']) ? (float)$l['longitude'] : null,
                'booth_count' => (int)($l['booth_count'] ?? 0),
            ];
        }, $locations));

        $data = [
            'title'               => 'Manajemen Mesin Booth | Admin Panel',
            'booths'              => $booths,
            'boothsJson'          => $boothsJson,
            'totalBooths'         => $totalBooths,
            'currentPage'         => $currentPage,
            'totalPages'          => $totalPages,
            'keyword'             => $keyword,
            'filterStatus'        => $filterStatus,
            'statTotal'           => $statTotal,
            'statActive'          => $statActive,
            'statMaintenance'     => $statMaintenance,
            'statTotalQueue'      => $statTotalQueue,
            'maintenanceBooths'   => $maintenanceBooths,
            // Locations data
            'locations'           => $locations,
            'locationsJson'       => $locationsJson,
            'locationSearch'      => $locationSearch,
            'totalLocations'      => $totalLocations,
            'currentLocPage'      => $currentLocPage,
            'totalLocPages'       => $totalLocPages,
            'statTotalLocations'  => $statTotalLocations,
            'statActiveLocations' => $statActiveLocations,
        ];

        return view('Admin/Booth/BoothManajemen', $data);
    }

    // =========================================================================
    // CREATE — Form tambah booth
    // =========================================================================
    public function create()
    {
        $locations = $this->locationModel->getActiveLocations();

        return view('Admin/Booth/BoothForm', [
            'title'      => 'Tambah Booth Baru | Admin Panel',
            'locations'  => $locations,
            'validation' => \Config\Services::validation(),
        ]);
    }

    // =========================================================================
    // STORE — Simpan booth baru
    // =========================================================================
    public function store()
    {
        $rules = [
            'name'        => 'required|min_length[3]|max_length[100]|is_unique[booths.name]',
            'location_id' => 'permit_empty',
            'capacity'    => 'permit_empty|is_natural_no_zero|less_than_equal_to[30]',
            'ip_address'  => 'permit_empty|valid_ip',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Auto-generate ID: PBX-XXXXX
        $count = $this->boothModel->countAll();
        $newId = sprintf('PBX-%05d', $count + 1);

        $this->boothModel->insert([
            'id'            => $newId,
            'location_id'   => $this->request->getPost('location_id') ?: null,
            'name'          => $this->request->getPost('name'),
            'capacity'      => (int) ($this->request->getPost('capacity') ?: 4),
            'ip_address'    => $this->request->getPost('ip_address') ?: null,
            'status'        => 'active',
            'current_queue' => 0,
        ]);

        return redirect()->to(base_url('admin/booths'))
            ->with('success', "Booth {$newId} berhasil ditambahkan.");
    }

    // =========================================================================
    // SHOW — Detail booth
    // =========================================================================
    public function show(string $id)
    {
        $booth = $this->boothModel
            ->select('booths.*, locations.name AS location_name, locations.city AS location_city, locations.address AS location_address')
            ->join('locations', 'locations.id = booths.location_id', 'left')
            ->find($id);

        if (!$booth) {
            return redirect()->to(base_url('admin/booths'))
                ->with('error', 'Booth tidak ditemukan.');
        }

        return view('Admin/Booth/BoothDetail', [
            'title' => 'Detail Booth | Admin Panel',
            'booth' => $booth,
        ]);
    }

    // =========================================================================
    // EDIT — Form edit booth
    // =========================================================================
    public function edit(string $id)
    {
        $booth = $this->boothModel->find($id);
        if (!$booth) {
            return redirect()->to(base_url('admin/booths'))
                ->with('error', 'Booth tidak ditemukan.');
        }

        $locations = $this->locationModel->getActiveLocations();

        return view('Admin/Booth/BoothForm', [
            'title'      => 'Edit Booth | Admin Panel',
            'booth'      => $booth,
            'locations'  => $locations,
            'validation' => \Config\Services::validation(),
        ]);
    }

    // =========================================================================
    // UPDATE — Simpan perubahan booth
    // =========================================================================
    public function update(string $id)
    {
        $booth = $this->boothModel->find($id);
        if (!$booth) {
            return redirect()->to(base_url('admin/booths'))
                ->with('error', 'Booth tidak ditemukan.');
        }

        $rules = [
            'name'        => "required|min_length[3]|max_length[100]|is_unique[booths.name,id,{$id}]",
            'location_id' => 'permit_empty',
            'capacity'    => 'permit_empty|is_natural_no_zero|less_than_equal_to[30]',
            'ip_address'  => 'permit_empty|valid_ip',
            'status'      => 'required|in_list[active,maintenance,inactive]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $statusInput = $this->request->getPost('status');

        $this->boothModel->update($id, [
            'location_id' => $this->request->getPost('location_id') ?: null,
            'name'        => $this->request->getPost('name'),
            'capacity'    => (int) ($this->request->getPost('capacity') ?: 4),
            'ip_address'  => $this->request->getPost('ip_address') ?: null,
            'status'      => $statusInput,
        ]);

        return redirect()->to(base_url('admin/booths'))
            ->with('success', 'Booth berhasil diperbarui.');
    }

    // =========================================================================
    // MAINTENANCE — Set status ke 'maintenance'
    // =========================================================================
    public function maintenance(string $id)
    {
        $booth = $this->boothModel->find($id);
        if (!$booth) {
            return redirect()->to(base_url('admin/booths'))
                ->with('error', 'Booth tidak ditemukan.');
        }

        $this->boothModel->update($id, ['status' => 'maintenance']);

        return redirect()->to(base_url('admin/booths'))
            ->with('success', "Status Booth {$id} diubah menjadi Maintenance.");
    }

    // =========================================================================
    // ACTIVATE — Set status kembali ke 'active'
    // =========================================================================
    public function activate(string $id)
    {
        $booth = $this->boothModel->find($id);
        if (!$booth) {
            return redirect()->to(base_url('admin/booths'))
                ->with('error', 'Booth tidak ditemukan.');
        }

        $this->boothModel->update($id, ['status' => 'active']);

        return redirect()->to(base_url('admin/booths'))
            ->with('success', "Booth {$id} berhasil diaktifkan kembali.");
    }

    // =========================================================================
    // DELETE — Hapus booth
    // =========================================================================
    public function delete(string $id)
    {
        $booth = $this->boothModel->find($id);
        if (!$booth) {
            return redirect()->to(base_url('admin/booths'))
                ->with('error', 'Booth tidak ditemukan.');
        }

        $this->boothModel->delete($id);

        return redirect()->to(base_url('admin/booths'))
            ->with('success', "Booth {$id} berhasil dihapus.");
    }

    // =========================================================================
    // EXPORT — Download CSV semua booth
    // =========================================================================
    public function export()
    {
        $booths = $this->boothModel
            ->select('booths.*, locations.name AS location_name')
            ->join('locations', 'locations.id = booths.location_id', 'left')
            ->orderBy('booths.id', 'ASC')
            ->findAll();

        $filename = 'Laporan_Mesin_Booth_' . date('Ymd_His') . '.csv';
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

        fputcsv($output, ['LAPORAN MESIN BOOTH'], ';');
        fputcsv($output, [], ';');
        fputcsv($output, ['Penanggung Jawab: ' . $penanggungJawab], ';');
        fputcsv($output, ['Waktu Realtime: ' . $currentTime . ' WIB'], ';');
        fputcsv($output, ['Keterangan Tanggal: Diekspor pada ' . $currentDate], ';');
        fputcsv($output, [], ';');

        fputcsv($output, ['ID Booth', 'Nama Mesin', 'Lokasi Cabang', 'Status', 'Antrean Sekarang', 'Terdaftar'], ';');
        foreach ($booths as $b) {
            fputcsv($output, [
                $b['id'],
                $b['name'],
                $b['location_name'] ?? 'Pusat',
                $b['status'],
                $b['current_queue'],
                $b['created_at'],
            ], ';');
        }
        fclose($output);
        exit;
    }

    // =========================================================================
    // RESET ACTIVE SESSION
    // =========================================================================
    public function resetActiveSession(string $id)
    {
        $booth = $this->boothModel->find($id);
        if (!$booth) {
            return redirect()->to(base_url('admin/booths'))
                ->with('error', 'Booth tidak ditemukan.');
        }

        $this->boothModel->update($id, [
            'active_booking_id' => null
        ]);

        return redirect()->to(base_url('admin/booths'))
            ->with('success', "Sesi aktif pada booth {$booth['name']} berhasil di-reset.");
    }

    // =========================================================================
    // LOCATION CRUD (SUB-FITUR TAB 7)
    // =========================================================================
    public function storeLocation()
    {
        $rules = [
            'name'      => 'required|min_length[3]|max_length[100]',
            'city'      => 'permit_empty|max_length[100]',
            'address'   => 'permit_empty|max_length[255]',
            'latitude'  => 'permit_empty|numeric',
            'longitude' => 'permit_empty|numeric',
            'status'    => 'permit_empty|in_list[active,inactive]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to(base_url('admin/booths'))->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $count = $this->locationModel->countAll();
        $newId = sprintf('LOC-%05d', $count + 1);

        $this->locationModel->insert([
            'id'        => $newId,
            'name'      => $this->request->getPost('name'),
            'city'      => $this->request->getPost('city') ?: null,
            'address'   => $this->request->getPost('address') ?: null,
            'latitude'  => $this->request->getPost('latitude') !== '' ? $this->request->getPost('latitude') : null,
            'longitude' => $this->request->getPost('longitude') !== '' ? $this->request->getPost('longitude') : null,
            'status'    => $this->request->getPost('status') ?: 'active',
        ]);

        return redirect()->to(base_url('admin/booths'))
            ->with('success', "Lokasi baru '{$this->request->getPost('name')}' ({$newId}) berhasil ditambahkan.");
    }

    public function editLocation(string $id)
    {
        $location = $this->locationModel->find($id);
        if (!$location) {
            return redirect()->to(base_url('admin/booths'))
                ->with('error', 'Data lokasi tidak ditemukan.');
        }

        $data = [
            'title'    => 'Edit Lokasi Cabang | Admin Panel',
            'location' => $location,
        ];

        return view('Admin/Booth/LocationForm', $data);
    }

    public function updateLocation(string $id)
    {
        $location = $this->locationModel->find($id);
        if (!$location) {
            return redirect()->to(base_url('admin/booths'))
                ->with('error', 'Data lokasi tidak ditemukan.');
        }

        $rules = [
            'name'      => 'required|min_length[3]|max_length[100]',
            'city'      => 'permit_empty|max_length[100]',
            'address'   => 'permit_empty|max_length[255]',
            'latitude'  => 'permit_empty|numeric',
            'longitude' => 'permit_empty|numeric',
            'status'    => 'required|in_list[active,inactive]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to(base_url('admin/booths'))->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $statusInput = $this->request->getPost('status');
        $this->locationModel->update($id, [
            'name'      => $this->request->getPost('name'),
            'city'      => $this->request->getPost('city') ?: null,
            'address'   => $this->request->getPost('address') ?: null,
            'latitude'  => $this->request->getPost('latitude') !== '' ? $this->request->getPost('latitude') : null,
            'longitude' => $this->request->getPost('longitude') !== '' ? $this->request->getPost('longitude') : null,
            'status'    => $statusInput,
        ]);

        // Otomatis sinkronkan status mesin booth jika status lokasi berubah
        if ($statusInput === 'inactive') {
            $this->boothModel->where('location_id', $id)->set(['status' => 'inactive'])->update();
        } elseif ($statusInput === 'active' && $location['status'] === 'inactive') {
            $this->boothModel->where('location_id', $id)->set(['status' => 'active'])->update();
        }

        return redirect()->to(base_url('admin/booths'))
            ->with('success', "Lokasi '{$location['name']}' berhasil diperbarui.");
    }

    public function deleteLocation(string $id)
    {
        $location = $this->locationModel->find($id);
        if (!$location) {
            return redirect()->to(base_url('admin/booths'))
                ->with('error', 'Data lokasi tidak ditemukan.');
        }

        // Unlink booths from this location
        $this->boothModel->where('location_id', $id)->set(['location_id' => null])->update();

        $this->locationModel->delete($id);

        return redirect()->to(base_url('admin/booths'))
            ->with('success', "Lokasi '{$location['name']}' berhasil dihapus.");
    }

    public function toggleLocation(string $id)
    {
        $location = $this->locationModel->find($id);
        if (!$location) {
            return redirect()->to(base_url('admin/booths'))
                ->with('error', 'Data lokasi tidak ditemukan.');
        }

        $newStatus = ($location['status'] === 'active') ? 'inactive' : 'active';
        $this->locationModel->update($id, ['status' => $newStatus]);

        // Otomatis sinkronkan status mesin booth yang terikat dengan lokasi ini
        if ($newStatus === 'inactive') {
            $affectedBooths = $this->boothModel->where('location_id', $id)->countAllResults();
            $this->boothModel->where('location_id', $id)->set(['status' => 'inactive'])->update();
            $msg = "Lokasi '{$location['name']}' berhasil dinonaktifkan. {$affectedBooths} mesin booth terkait otomatis diubah menjadi Non-Aktif.";
        } else {
            $affectedBooths = $this->boothModel->where('location_id', $id)->countAllResults();
            $this->boothModel->where('location_id', $id)->set(['status' => 'active'])->update();
            $msg = "Lokasi '{$location['name']}' berhasil diaktifkan. {$affectedBooths} mesin booth terkait otomatis diaktifkan kembali.";
        }

        return redirect()->to(base_url('admin/booths'))
            ->with('success', $msg);
    }
}
