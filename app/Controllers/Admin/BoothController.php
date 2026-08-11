<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BoothModel;
// use App\Models\LocationModel;
use App\Models\ThemeModel;

class BoothController extends BaseController
{
    protected BoothModel $boothModel;
    // protected LocationModel $locationModel;
    protected ThemeModel $themeModel;

    public function __construct()
    {
        $this->boothModel    = new BoothModel();
        // $this->locationModel = new LocationModel();
        $this->themeModel    = new ThemeModel();
    }

    // =========================================================================
    // INDEX — Daftar booth & lokasi + filter + search + pagination + stats
    // =========================================================================
    public function index()
    {
        $perPage      = 10;
        $currentPage  = max(1, (int) ($this->request->getGet('page')   ?? 1));
        $keyword      = $this->request->getGet('q')      ?? '';
        $filterStatus = $this->request->getGet('status') ?? '';
        $filterLoc    = $this->request->getGet('location_id') ?? '';

        // --- Query builder for booths ---
        $builder = $this->boothModel
            ->select('booths.*, locations.name AS location_name, locations.city AS location_city')
            ->join('locations', 'locations.id = booths.location_id', 'left');

        if ($keyword !== '') {
            $builder = $builder->groupStart()
                ->like('booths.id', $keyword)
                ->orLike('booths.name', $keyword)
                ->orLike('booths.theme', $keyword)
                ->orLike('locations.name', $keyword)
                ->groupEnd();
        }
        if ($filterStatus !== '') {
            $builder = $builder->where('booths.status', $filterStatus);
        }
        if ($filterLoc !== '') {
            $builder = $builder->where('booths.location_id', $filterLoc);
        }

        $totalBooths = $builder->countAllResults(false);
        $totalPages  = max(1, (int) ceil($totalBooths / $perPage));

        $booths = $builder
            ->orderBy('booths.created_at', 'DESC')
            ->findAll($perPage, ($currentPage - 1) * $perPage);

        // --- Locations Data ---
        // FITUR LOKASI SEMENTARA DI-NONAKTIFKAN (FOKUS 1 TITIK)
        /*
        $locationsRaw = $this->locationModel->orderBy('created_at', 'DESC')->findAll();
        $locations = array_map(function ($loc) {
            $loc['booth_count'] = $this->locationModel->countBoothsAt($loc['id']);
            $loc['themes']      = $this->locationModel->getThemes($loc);
            return $loc;
        }, $locationsRaw);
        */
        $locationsRaw = [];
        $locations = [];

        // --- Stats ---
        $statTotal       = $this->boothModel->countAll();
        $statActive      = $this->boothModel->where('status', 'active')->countAllResults();
        $statMaintenance = $this->boothModel->where('status', 'maintenance')->countAllResults();
        $statTotalQueue  = $this->boothModel->db
            ->table('booths')
            ->select('SUM(current_queue) AS total')
            ->get()->getRowArray()['total'] ?? 0;
        $statTotalLocations = count($locations);

        // Booth dalam maintenance untuk panel alert
        $maintenanceBooths = $this->boothModel
            ->where('status', 'maintenance')
            ->orderBy('updated_at', 'DESC')
            ->findAll(5);

        // Fetch all locations for mapping
        // FITUR LOKASI SEMENTARA DI-NONAKTIFKAN (FOKUS 1 TITIK)
        /*
        $locationsJson = json_encode(array_map(function ($loc) {
            return [
                'name'      => $loc['name'],
                'status'    => $loc['status'],
                'latitude'  => $loc['latitude'] ? (float) $loc['latitude'] : null,
                'longitude' => $loc['longitude'] ? (float) $loc['longitude'] : null,
                'booth_count' => $this->locationModel->countBoothsAt($loc['id']),
            ];
        }, $locationsRaw));
        */
        $locationsJson = '[]';

        $data = [
            'title'              => 'Manajemen Mesin Booth & Lokasi | Admin Panel',
            'booths'             => $booths,
            'locations'          => $locations,
            'availableThemes'    => $this->themeModel->getAllThemesAssoc(),
            'allThemes'          => $this->themeModel->findAll(),
            'locationsJson'      => $locationsJson,
            'totalBooths'        => $totalBooths,
            'currentPage'        => $currentPage,
            'totalPages'         => $totalPages,
            'keyword'            => $keyword,
            'filterStatus'       => $filterStatus,
            'filterLoc'          => $filterLoc,
            'statTotal'          => $statTotal,
            'statActive'         => $statActive,
            'statMaintenance'    => $statMaintenance,
            'statTotalQueue'     => $statTotalQueue,
            'statTotalLocations' => $statTotalLocations,
            'maintenanceBooths'  => $maintenanceBooths,
        ];

        return view('Admin/Booth/BoothManajemen', $data);
    }

    // =========================================================================
    // CREATE — Form tambah booth
    // =========================================================================
    public function create()
    {
        // $locations = $this->locationModel->getActiveLocations();
        $locations = [];

        return view('Admin/Booth/BoothForm', [
            'title'           => 'Tambah Booth Baru | Admin Panel',
            'locations'       => $locations,
            'availableThemes' => $this->themeModel->getAllThemesAssoc(),
            'validation'      => \Config\Services::validation(),
        ]);
    }

    // =========================================================================
    // STORE — Simpan booth baru
    // =========================================================================
    public function store()
    {
        $rules = [
            'name'        => 'required|min_length[3]|max_length[100]|is_unique[booths.name]',
            'location_id' => 'permit_empty|string',
            'theme'       => 'permit_empty|string',
            'ip_address'  => 'permit_empty|valid_ip',
            'latitude'    => 'permit_empty|numeric',
            'longitude'   => 'permit_empty|numeric',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Auto-generate ID: booth_001
        $count = $this->boothModel->countAll();
        $newId = sprintf('booth_%03d', $count + 1);

        $this->boothModel->insert([
            'id'            => $newId,
            'location_id'   => $this->request->getPost('location_id') ?: null,
            'name'          => $this->request->getPost('name'),
            'theme'         => $this->request->getPost('theme') ?: null,
            'ip_address'    => $this->request->getPost('ip_address') ?: null,
            'status'        => 'active',
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
            ->select('booths.*, locations.name AS location_name, locations.city AS location_city')
            ->join('locations', 'locations.id = booths.location_id', 'left')
            ->find($id);

        if (! $booth) {
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
        if (! $booth) {
            return redirect()->to(base_url('admin/booths'))
                ->with('error', 'Booth tidak ditemukan.');
        }

        // $locations = $this->locationModel->getActiveLocations();
        $locations = [];

        return view('Admin/Booth/BoothForm', [
            'title'           => 'Edit Booth | Admin Panel',
            'booth'           => $booth,
            'locations'       => $locations,
            'availableThemes' => $this->themeModel->getAllThemesAssoc(),
            'validation'      => \Config\Services::validation(),
        ]);
    }

    // =========================================================================
    // UPDATE — Simpan perubahan booth
    // =========================================================================
    public function update(string $id)
    {
        $booth = $this->boothModel->find($id);
        if (! $booth) {
            return redirect()->to(base_url('admin/booths'))
                ->with('error', 'Booth tidak ditemukan.');
        }

        $rules = [
            'name'        => "required|min_length[3]|max_length[100]|is_unique[booths.name,id,{$id}]",
            'location_id' => 'permit_empty|string',
            'theme'       => 'permit_empty|string',
            'ip_address'  => 'permit_empty|valid_ip',
            'status'      => 'required|in_list[active,maintenance,inactive]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $statusInput = $this->request->getPost('status');

        $this->boothModel->update($id, [
            'location_id' => $this->request->getPost('location_id') ?: null,
            'name'        => $this->request->getPost('name'),
            'theme'       => $this->request->getPost('theme') ?: null,
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
        if (! $booth) {
            return redirect()->to(base_url('admin/booths'))
                ->with('error', 'Booth tidak ditemukan.');
        }

        $this->boothModel->update($id, ['status' => 'maintenance']);

        return redirect()->to(base_url('admin/booths'))
            ->with('success', "Booth {$id} dipindahkan ke mode Maintenance.");
    }

    // =========================================================================
    // ACTIVATE — Set status kembali ke 'active'
    // =========================================================================
    public function activate(string $id)
    {
        $booth = $this->boothModel->find($id);
        if (! $booth) {
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
        if (! $booth) {
            return redirect()->to(base_url('admin/booths'))
                ->with('error', 'Booth tidak ditemukan.');
        }

        $this->boothModel->delete($id);

        return redirect()->to(base_url('admin/booths'))
            ->with('success', "Booth {$id} berhasil dihapus.");
    }

    // =========================================================================
    // LOCATION CRUD METHODS
    // =========================================================================

    public function locationsIndex()
    {
        return redirect()->to(base_url('admin/booths'));
    }

    public function createLocation()
    {
        return view('Admin/Booth/LocationForm', [
            'title'           => 'Tambah Lokasi Baru | Admin Panel',
            'availableThemes' => $this->themeModel->getAllThemesAssoc(),
            'validation'      => \Config\Services::validation(),
        ]);
    }

    public function showLocation(string $id)
    {
        $location = $this->locationModel->find($id);
        if (! $location) {
            return redirect()->to(base_url('admin/booths'))
                ->with('error', 'Lokasi tidak ditemukan.');
        }

        $boothsAtLocation = $this->boothModel->where('location_id', $id)->findAll();

        return view('Admin/Booth/LocationDetail', [
            'title'            => 'Detail Lokasi | Admin Panel',
            'location'         => $location,
            'boothsAtLocation' => $boothsAtLocation,
            'availableThemes'  => LocationModel::AVAILABLE_THEMES,
        ]);
    }

    public function editLocation(string $id)
    {
        $location = $this->locationModel->find($id);
        if (! $location) {
            return redirect()->to(base_url('admin/booths'))
                ->with('error', 'Lokasi tidak ditemukan.');
        }

        return view('Admin/Booth/LocationForm', [
            'title'           => 'Edit Lokasi | Admin Panel',
            'location'        => $location,
            'availableThemes' => $this->themeModel->getAllThemesAssoc(),
            'validation'      => \Config\Services::validation(),
        ]);
    }

    public function storeLocation()
    {
        $rules = [
            'name'    => 'required|min_length[3]|max_length[150]|is_unique[locations.name]',
            'city'    => 'permit_empty|max_length[100]',
            'address' => 'permit_empty|string',
            'latitude'=> 'permit_empty|numeric',
            'longitude'=> 'permit_empty|numeric',
            'preview_images' => 'permit_empty',
            'preview_images.*' => 'is_image[preview_images]|mime_in[preview_images,image/jpg,image/jpeg,image/png,image/webp]|max_size[preview_images,2048]'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $count = $this->locationModel->countAll();
        $newId = sprintf('lokasi_%03d', $count + 1);

        $themesInput = $this->request->getPost('themes') ?? [];

        // Upload Preview Images
        $previewImages = [];
        if ($files = $this->request->getFiles()) {
            if (isset($files['preview_images'])) {
                $images = is_array($files['preview_images']) ? $files['preview_images'] : [$files['preview_images']];
                foreach ($images as $img) {
                    if ($img->isValid() && ! $img->hasMoved()) {
                        $newName = $img->getRandomName();
                        $img->move(FCPATH . 'uploads/locations', $newName);
                        $previewImages[] = 'uploads/locations/' . $newName;
                    }
                }
            }
        }

        $this->locationModel->insert([
            'id'               => $newId,
            'name'             => $this->request->getPost('name'),
            'city'             => $this->request->getPost('city') ?: null,
            'address'          => $this->request->getPost('address') ?: null,
            'themes_available' => json_encode(array_values($themesInput)),
            'status'           => 'active',
            'latitude'         => $this->request->getPost('latitude') ?: null,
            'longitude'        => $this->request->getPost('longitude') ?: null,
            'preview_images'   => !empty($previewImages) ? json_encode($previewImages) : null,
        ]);

        return redirect()->to(base_url('admin/booths'))
            ->with('success', "Lokasi {$newId} ({$this->request->getPost('name')}) berhasil ditambahkan.");
    }

    public function updateLocation(string $id)
    {
        $location = $this->locationModel->find($id);
        if (! $location) {
            return redirect()->to(base_url('admin/booths'))
                ->with('error', 'Lokasi tidak ditemukan.');
        }

        $rules = [
            'name'    => "required|min_length[3]|max_length[150]|is_unique[locations.name,id,{$id}]",
            'city'    => 'permit_empty|max_length[100]',
            'address' => 'permit_empty|string',
            'latitude'=> 'permit_empty|numeric',
            'longitude'=> 'permit_empty|numeric',
            'status'  => 'required|in_list[active,inactive]',
            'preview_images' => 'permit_empty',
            'preview_images.*' => 'is_image[preview_images]|mime_in[preview_images,image/jpg,image/jpeg,image/png,image/webp]|max_size[preview_images,2048]'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $themesInput = $this->request->getPost('themes') ?? [];
        
        $updateData = [
            'name'             => $this->request->getPost('name'),
            'city'             => $this->request->getPost('city') ?: null,
            'address'          => $this->request->getPost('address') ?: null,
            'themes_available' => json_encode(array_values($themesInput)),
            'status'           => $this->request->getPost('status'),
            'latitude'         => $this->request->getPost('latitude') ?: null,
            'longitude'        => $this->request->getPost('longitude') ?: null,
        ];

        // Upload Preview Images
        if ($files = $this->request->getFiles()) {
            $previewImages = [];
            if (isset($files['preview_images'])) {
                $images = is_array($files['preview_images']) ? $files['preview_images'] : [$files['preview_images']];
                $hasValidImage = false;
                foreach ($images as $img) {
                    if ($img->isValid() && ! $img->hasMoved()) {
                        $hasValidImage = true;
                        $newName = $img->getRandomName();
                        $img->move(FCPATH . 'uploads/locations', $newName);
                        $previewImages[] = 'uploads/locations/' . $newName;
                    }
                }
                
                if ($hasValidImage) {
                    // Optional: remove old images
                    $oldImages = json_decode($location['preview_images'] ?? '[]', true) ?: [];
                    foreach ($oldImages as $oldImg) {
                        if (file_exists(FCPATH . $oldImg)) {
                            unlink(FCPATH . $oldImg);
                        }
                    }
                    $updateData['preview_images'] = json_encode($previewImages);
                }
            }
        }

        $this->locationModel->update($id, $updateData);

        return redirect()->to(base_url('admin/booths'))
            ->with('success', "Lokasi {$id} berhasil diperbarui.");
    }

    public function deleteLocation(string $id)
    {
        $location = $this->locationModel->find($id);
        if (! $location) {
            return redirect()->to(base_url('admin/booths'))
                ->with('error', 'Lokasi tidak ditemukan.');
        }

        // Check if booths belong to this location
        $boothCount = $this->locationModel->countBoothsAt($id);
        if ($boothCount > 0) {
            return redirect()->to(base_url('admin/booths'))
                ->with('error', "Gagal menghapus: Lokasi ini masih memiliki {$boothCount} booth terdaftar. Pindahkan atau hapus booth tersebut terlebih dahulu.");
        }

        $this->locationModel->delete($id);

        return redirect()->to(base_url('admin/booths'))
            ->with('success', "Lokasi {$id} berhasil dihapus.");
    }

    // =========================================================================
    // EXPORT — Download CSV semua booth
    // =========================================================================
    public function export()
    {
        $booths = $this->boothModel
            ->select('booths.*, locations.name AS location_name')
            ->join('locations', 'locations.id = booths.location_id', 'left')
            ->orderBy('booths.created_at', 'DESC')
            ->findAll();

        $filename = 'Laporan_Mesin_Booth_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $output = fopen('php://output', 'w');
        fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
        fputs($output, "sep=;\n");

        $adminName  = session('adminName') ?? session('admin_name') ?? 'Admin';
        $adminRole  = session('adminRole') ?? session('admin_role') ?? 'Unknown Role';
        $adminEmail = session('adminEmail');

        if (! $adminEmail && session('adminId')) {
            $adminModel = new \App\Models\AdminModel();
            $adminData  = $adminModel->find(session('adminId'));
            if ($adminData) {
                $adminEmail = $adminData['email'];
                $adminName  = $adminData['name'];
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

        fputcsv($output, ['ID Booth', 'Nama Mesin', 'Lokasi', 'Tema Fisik', 'Status', 'Antrean Sekarang', 'Terdaftar'], ';');
        foreach ($booths as $b) {
            fputcsv($output, [
                $b['id'],
                $b['name'],
                $b['location_name'] ?? 'TIDAK SET',
                $b['theme'] ? ucfirst($b['theme']) : 'TIDAK SET',
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
        if (! $booth) {
            return redirect()->to(base_url('admin/booths'))
                ->with('error', 'Booth tidak ditemukan.');
        }

        $this->boothModel->update($id, [
            'active_booking_id' => null,
        ]);

        return redirect()->to(base_url('admin/booths'))
            ->with('success', "Sesi aktif pada booth {$booth['name']} berhasil di-reset.");
    }

    // =========================================================================
    // THEMES CRUD METHODS
    // =========================================================================

    public function storeTheme()
    {
        $rules = [
            'label' => 'required|string',
            'preview_image' => 'uploaded[preview_image]|max_size[preview_image,2048]|is_image[preview_image]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan tema: ' . implode(', ', $this->validator->getErrors()));
        }

        $label = $this->request->getPost('label');
        
        $key = strtolower(preg_replace('/[^a-z0-9]/i', '_', $label));
        $key = preg_replace('/_+/', '_', trim($key, '_'));

        if ($this->themeModel->find($key)) {
            return redirect()->back()->with('error', 'Tema "' . $label . '" sudah ada. Silakan gunakan nama yang berbeda.');
        }

        // Handle File Upload
        $file = $this->request->getFile('preview_image');
        $newName = null;
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/themes', $newName);
        }

        $this->themeModel->insert([
            'key' => $key,
            'label' => $label,
            'preview_image' => $newName
        ]);

        return redirect()->to(base_url('admin/booths?tab=themes'))->with('success', 'Tema "' . $label . '" berhasil ditambahkan.');
    }

    public function editTheme(string $key)
    {
        $theme = $this->themeModel->find($key);
        if (!$theme) {
            return redirect()->to(base_url('admin/booths?tab=themes'))->with('error', 'Tema tidak ditemukan.');
        }

        return view('Admin/Booth/ThemeForm', [
            'theme' => $theme
        ]);
    }

    public function updateTheme(string $key)
    {
        $theme = $this->themeModel->find($key);
        if (!$theme) {
            return redirect()->back()->with('error', 'Tema tidak ditemukan.');
        }

        $rules = [
            'label' => 'required|string',
        ];

        if ($this->request->getFile('preview_image')->isValid()) {
            $rules['preview_image'] = 'max_size[preview_image,2048]|is_image[preview_image]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui tema: ' . implode(', ', $this->validator->getErrors()));
        }

        $label = $this->request->getPost('label');
        
        $data = [
            'label' => $label,
        ];

        // Handle File Upload
        $file = $this->request->getFile('preview_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Delete old image
            if (!empty($theme['preview_image']) && file_exists(FCPATH . 'uploads/themes/' . $theme['preview_image'])) {
                unlink(FCPATH . 'uploads/themes/' . $theme['preview_image']);
            }
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/themes', $newName);
            $data['preview_image'] = $newName;
        }

        $this->themeModel->update($key, $data);

        return redirect()->to(base_url('admin/booths?tab=themes'))->with('success', 'Tema "' . $label . '" berhasil diperbarui.');
    }

    public function deleteTheme(string $key)
    {
        $theme = $this->themeModel->find($key);
        if (!$theme) {
            return redirect()->back()->with('error', 'Tema tidak ditemukan.');
        }
        
        if (!empty($theme['preview_image']) && file_exists(FCPATH . 'uploads/themes/' . $theme['preview_image'])) {
            unlink(FCPATH . 'uploads/themes/' . $theme['preview_image']);
        }

        $this->themeModel->delete($key);
        return redirect()->to(base_url('admin/booths'))->with('success', 'Tema "' . $theme['label'] . '" berhasil dihapus.');
    }
}
