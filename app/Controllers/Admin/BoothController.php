<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BoothModel;

class BoothController extends BaseController
{
    protected BoothModel $boothModel;

    public function __construct()
    {
        $this->boothModel = new BoothModel();
    }

    // =========================================================================
    // INDEX — Daftar booth + filter + search + pagination + stats
    // =========================================================================
    public function index()
    {
        $perPage      = 10;
        $currentPage  = max(1, (int) ($this->request->getGet('page')   ?? 1));
        $keyword      = $this->request->getGet('q')      ?? '';
        $filterStatus = $this->request->getGet('status') ?? '';

        // --- Query builder ---
        $builder = $this->boothModel;
        if ($keyword !== '') {
            $builder = $builder->groupStart()
                ->like('id', $keyword)
                ->orLike('name', $keyword)
                ->groupEnd();
        }
        if ($filterStatus !== '') {
            $builder = $builder->where('status', $filterStatus);
        }

        $totalBooths = $builder->countAllResults(false);
        $totalPages  = max(1, (int) ceil($totalBooths / $perPage));

        $booths = $builder
            ->orderBy('created_at', 'DESC')
            ->findAll($perPage, ($currentPage - 1) * $perPage);

        // --- Stats ---
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

        // Fetch all booths for mapping
        $allBooths = $this->boothModel->findAll();
        $boothsJson = json_encode(array_map(function($b) {
            return [
                'name'      => $b['name'],
                'status'    => $b['status'],
                'latitude'  => $b['latitude'] ? (float)$b['latitude'] : null,
                'longitude' => $b['longitude'] ? (float)$b['longitude'] : null,
            ];
        }, $allBooths));

        $data = [
            'title'             => 'Manajemen Mesin Booth | Admin Panel',
            'booths'            => $booths,
            'boothsJson'        => $boothsJson,
            'totalBooths'       => $totalBooths,
            'currentPage'       => $currentPage,
            'totalPages'        => $totalPages,
            'keyword'           => $keyword,
            'filterStatus'      => $filterStatus,
            'statTotal'         => $statTotal,
            'statActive'        => $statActive,
            'statMaintenance'   => $statMaintenance,
            'statTotalQueue'    => $statTotalQueue,
            'maintenanceBooths' => $maintenanceBooths,
        ];

        return view('Admin/Booth/BoothManajemen', $data);
    }

    // =========================================================================
    // CREATE — Form tambah booth
    // =========================================================================
    public function create()
    {
        return view('Admin/Booth/BoothForm', [
            'title'      => 'Tambah Booth Baru | Admin Panel',
            'validation' => \Config\Services::validation(),
        ]);
    }

    // =========================================================================
    // STORE — Simpan booth baru
    // =========================================================================
    public function store()
    {
        $rules = [
            'name'       => 'required|min_length[3]|max_length[100]',
            'ip_address' => 'permit_empty|valid_ip',
            'latitude'   => 'permit_empty|numeric',
            'longitude'  => 'permit_empty|numeric',
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
            'name'          => $this->request->getPost('name'),
            'ip_address'    => $this->request->getPost('ip_address') ?: null,
            'latitude'      => $this->request->getPost('latitude') !== '' ? $this->request->getPost('latitude') : null,
            'longitude'     => $this->request->getPost('longitude') !== '' ? $this->request->getPost('longitude') : null,
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
        $booth = $this->boothModel->find($id);
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

        return view('Admin/Booth/BoothForm', [
            'title'      => 'Edit Booth | Admin Panel',
            'booth'      => $booth,
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
            'name'       => 'required|min_length[3]|max_length[100]',
            'ip_address' => 'permit_empty|valid_ip',
            'latitude'   => 'permit_empty|numeric',
            'longitude'  => 'permit_empty|numeric',
            'status'     => 'required|in_list[active,maintenance,inactive]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $statusInput = $this->request->getPost('status');

        $this->boothModel->update($id, [
            'name'       => $this->request->getPost('name'),
            'ip_address' => $this->request->getPost('ip_address') ?: null,
            'latitude'   => $this->request->getPost('latitude') !== '' ? $this->request->getPost('latitude') : null,
            'longitude'  => $this->request->getPost('longitude') !== '' ? $this->request->getPost('longitude') : null,
            'status'     => $statusInput,
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
            ->with('success', "Booth {$id} dipindahkan ke mode Maintenance.");
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
        $booths = $this->boothModel->orderBy('created_at', 'DESC')->findAll();

        $filename = 'booths_export_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID Booth', 'Nama Mesin', 'Status', 'Antrean Sekarang', 'Terdaftar']);
        foreach ($booths as $b) {
            fputcsv($output, [
                $b['id'],
                $b['name'],
                $b['status'],
                $b['current_queue'],
                $b['created_at'],
            ]);
        }
        fclose($output);
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
}
