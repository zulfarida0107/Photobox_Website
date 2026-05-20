<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FrameModel;
use App\Models\FilterModel;

class FrameAndFilterController extends BaseController
{
    protected FrameModel  $frameModel;
    protected FilterModel $filterModel;

    public function __construct()
    {
        $this->frameModel  = new FrameModel();
        $this->filterModel = new FilterModel();
    }

    // =========================================================================
    // INDEX — Halaman utama: frame list + filter list + stats
    // =========================================================================
    public function index()
    {
        $perPage          = 8;
        $currentFramePage = max(1, (int) ($this->request->getGet('page') ?? 1));

        // --- Frame dengan pagination ---
        $totalFrames      = $this->frameModel->countAll();
        $totalFramePages  = max(1, (int) ceil($totalFrames / $perPage));
        $frames           = $this->frameModel
            ->orderBy('created_at', 'DESC')
            ->findAll($perPage, ($currentFramePage - 1) * $perPage);

        // --- Semua filter (tidak di-paginate) ---
        $filters = $this->filterModel->orderBy('name', 'ASC')->findAll();

        // --- Stats ---
        $statTotalFrames      = $this->frameModel->countAll();
        $statTotalFilters     = $this->filterModel->countAll();
        $statActiveFilters    = $this->filterModel->where('is_active', 1)->countAllResults();
        $statFramesThisMonth  = $this->frameModel
            ->where('created_at >=', date('Y-m-01 00:00:00'))
            ->countAllResults();

        $data = [
            'title'             => 'Manajemen Aset Desain | Admin Panel',
            'frames'            => $frames,
            'totalFrames'       => $totalFrames,
            'currentFramePage'  => $currentFramePage,
            'totalFramePages'   => $totalFramePages,
            'filters'           => $filters,
            'statTotalFrames'   => $statTotalFrames,
            'statTotalFilters'  => $statTotalFilters,
            'statActiveFilters' => $statActiveFilters,
            'statFramesThisMonth' => $statFramesThisMonth,
        ];

        return view('Admin/FrameAndFilterManajemen', $data);
    }

    // =========================================================================
    // FRAME — Upload / Store
    // =========================================================================
    public function storeFrame()
    {
        $rules = [
            'name'       => 'required|min_length[3]|max_length[100]',
            'frame_file' => 'uploaded[frame_file]|max_size[frame_file,2048]|ext_in[frame_file,png,jpg,jpeg]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to(base_url('admin/frames'))
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $file     = $this->request->getFile('frame_file');
        $newName  = $file->getRandomName();
        $file->move(FCPATH . 'uploads/frames/', $newName);

        // Generate ID: FRM-XXX
        $count = $this->frameModel->countAll();
        $newId = sprintf('FRM-%03d', $count + 1);

        $this->frameModel->insert([
            'id'        => $newId,
            'name'      => $this->request->getPost('name'),
            'file_path' => 'uploads/frames/' . $newName,
        ]);

        return redirect()->to(base_url('admin/frames'))
            ->with('success', 'Frame baru berhasil diunggah.');
    }

    // =========================================================================
    // FRAME — Create form
    // =========================================================================
    public function createFrame()
    {
        return view('Admin/FrameForm', [
            'title'      => 'Unggah Frame Baru | Admin Panel',
            'validation' => \Config\Services::validation(),
        ]);
    }

    // =========================================================================
    // FRAME — Edit form
    // =========================================================================
    public function editFrame(string $id)
    {
        $frame = $this->frameModel->find($id);
        if (!$frame) {
            return redirect()->to(base_url('admin/frames'))
                ->with('error', 'Frame tidak ditemukan.');
        }

        return view('Admin/FrameForm', [
            'title'      => 'Edit Frame | Admin Panel',
            'frame'      => $frame,
            'validation' => \Config\Services::validation(),
        ]);
    }

    // =========================================================================
    // FRAME — Update
    // =========================================================================
    public function updateFrame(string $id)
    {
        $frame = $this->frameModel->find($id);
        if (!$frame) {
            return redirect()->to(base_url('admin/frames'))
                ->with('error', 'Frame tidak ditemukan.');
        }

        $updateData = ['name' => $this->request->getPost('name')];

        // Ganti file jika ada upload baru
        $file = $this->request->getFile('frame_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $rules = ['frame_file' => 'max_size[frame_file,2048]|ext_in[frame_file,png,jpg,jpeg]'];
            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()
                    ->with('error', implode(' ', $this->validator->getErrors()));
            }
            // Hapus file lama
            if (!empty($frame['file_path']) && file_exists(FCPATH . $frame['file_path'])) {
                unlink(FCPATH . $frame['file_path']);
            }
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/frames/', $newName);
            $updateData['file_path'] = 'uploads/frames/' . $newName;
        }

        $this->frameModel->update($id, $updateData);

        return redirect()->to(base_url('admin/frames'))
            ->with('success', 'Frame berhasil diperbarui.');
    }

    // =========================================================================
    // FRAME — Delete
    // =========================================================================
    public function deleteFrame(string $id)
    {
        $frame = $this->frameModel->find($id);
        if (!$frame) {
            return redirect()->to(base_url('admin/frames'))
                ->with('error', 'Frame tidak ditemukan.');
        }

        // Hapus file fisik
        if (!empty($frame['file_path']) && file_exists(FCPATH . $frame['file_path'])) {
            unlink(FCPATH . $frame['file_path']);
        }

        $this->frameModel->delete($id);

        return redirect()->to(base_url('admin/frames'))
            ->with('success', 'Frame berhasil dihapus.');
    }

    // =========================================================================
    // FILTER — Create form
    // =========================================================================
    public function createFilter()
    {
        return view('Admin/FilterForm', [
            'title'      => 'Tambah Filter | Admin Panel',
            'validation' => \Config\Services::validation(),
        ]);
    }

    // =========================================================================
    // FILTER — Store
    // =========================================================================
    public function storeFilter()
    {
        $rules = [
            'name'      => 'required|min_length[3]|max_length[100]',
            'css_value' => 'required|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $count = $this->filterModel->countAll();
        $newId = sprintf('FLT-%02d', $count + 1);

        $this->filterModel->insert([
            'id'        => $newId,
            'name'      => $this->request->getPost('name'),
            'css_value' => $this->request->getPost('css_value'),
            'is_active' => (bool) $this->request->getPost('is_active'),
        ]);

        return redirect()->to(base_url('admin/frames'))
            ->with('success', 'Filter baru berhasil ditambahkan.');
    }

    // =========================================================================
    // FILTER — Edit form
    // =========================================================================
    public function editFilter(string $id)
    {
        $filter = $this->filterModel->find($id);
        if (!$filter) {
            return redirect()->to(base_url('admin/frames'))
                ->with('error', 'Filter tidak ditemukan.');
        }

        return view('Admin/FilterForm', [
            'title'      => 'Edit Filter | Admin Panel',
            'filter'     => $filter,
            'validation' => \Config\Services::validation(),
        ]);
    }

    // =========================================================================
    // FILTER — Update
    // =========================================================================
    public function updateFilter(string $id)
    {
        $filter = $this->filterModel->find($id);
        if (!$filter) {
            return redirect()->to(base_url('admin/frames'))
                ->with('error', 'Filter tidak ditemukan.');
        }

        $rules = [
            'name'      => 'required|min_length[3]|max_length[100]',
            'css_value' => 'required|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->filterModel->update($id, [
            'name'      => $this->request->getPost('name'),
            'css_value' => $this->request->getPost('css_value'),
            'is_active' => (bool) $this->request->getPost('is_active'),
        ]);

        return redirect()->to(base_url('admin/frames'))
            ->with('success', 'Filter berhasil diperbarui.');
    }

    // =========================================================================
    // FILTER — Toggle aktif/nonaktif
    // =========================================================================
    public function toggleFilter(string $id)
    {
        $filter = $this->filterModel->find($id);
        if (!$filter) {
            return redirect()->to(base_url('admin/frames'))
                ->with('error', 'Filter tidak ditemukan.');
        }

        $this->filterModel->update($id, ['is_active' => !$filter['is_active']]);

        $status = !$filter['is_active'] ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->to(base_url('admin/frames'))
            ->with('success', "Filter {$filter['name']} berhasil {$status}.");
    }

    // =========================================================================
    // FILTER — Delete
    // =========================================================================
    public function deleteFilter(string $id)
    {
        $filter = $this->filterModel->find($id);
        if (!$filter) {
            return redirect()->to(base_url('admin/frames'))
                ->with('error', 'Filter tidak ditemukan.');
        }

        $this->filterModel->delete($id);

        return redirect()->to(base_url('admin/frames'))
            ->with('success', 'Filter berhasil dihapus.');
    }
}
