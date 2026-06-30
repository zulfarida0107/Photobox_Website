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

        $search   = $this->request->getGet('search') ?? '';
        $category = $this->request->getGet('category') ?? '';

        // --- Frame query builder + Join category name ---
        $frameQuery = $this->frameModel
            ->select('frames.*, frame_categories.name as category_name')
            ->join('frame_categories', 'frame_categories.id = frames.category_id', 'left');

        if (!empty($search)) {
            $frameQuery->like('frames.name', $search);
        }

        if (!empty($category)) {
            $frameQuery->where('frames.category_id', $category);
        }

        $totalFrames      = $frameQuery->countAllResults(false);
        $totalFramePages  = max(1, (int) ceil($totalFrames / $perPage));
        $frames           = $frameQuery
            ->orderBy('frames.created_at', 'DESC')
            ->findAll($perPage, ($currentFramePage - 1) * $perPage);

        // --- Semua filter (tidak di-paginate) ---
        $filters = $this->filterModel->orderBy('name', 'ASC')->findAll();

        // --- Semua kategori frame ---
        $categories = $this->frameModel->db->table('frame_categories')->orderBy('name', 'ASC')->get()->getResultArray();

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
            'categories'        => $categories,
            'search'            => $search,
            'selectedCategory'  => $category,
            'statTotalFrames'   => $statTotalFrames,
            'statTotalFilters'  => $statTotalFilters,
            'statActiveFilters' => $statActiveFilters,
            'statFramesThisMonth' => $statFramesThisMonth,
        ];

        return view('Admin/FrameAndFilter/FrameAndFilterManajemen', $data);
    }

    // =========================================================================
    // FRAME — Upload / Store
    // =========================================================================
    public function storeFrame()
    {
        $rules = [
            'name'        => 'required|min_length[3]|max_length[100]',
            'category_id' => 'permit_empty|max_length[20]',
            'photo_count' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[10]',
            'frame_file'  => 'uploaded[frame_file]|max_size[frame_file,2048]|ext_in[frame_file,png,jpg,jpeg]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to(base_url('admin/frames'))
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $file     = $this->request->getFile('frame_file');
        $newName  = $file->getRandomName();
        $file->move(FCPATH . 'uploads/frames/', $newName);

        // Generate ID: FRM-XXX
        $lastRow = $this->frameModel->orderBy('id', 'DESC')->first();
        $lastIdNumber = 0;
        if ($lastRow && preg_match('/FRM-(\d+)/', $lastRow['id'], $matches)) {
            $lastIdNumber = (int)$matches[1];
        }
        $newId = sprintf('FRM-%03d', $lastIdNumber + 1);

        $this->frameModel->insert([
            'id'          => $newId,
            'name'        => $this->request->getPost('name'),
            'category_id' => $this->request->getPost('category_id') ?: null,
            'photo_count' => (int) $this->request->getPost('photo_count'),
            'file_path'   => 'uploads/frames/' . $newName,
        ]);

        return redirect()->to(base_url('admin/frames'))
            ->with('success', 'Frame baru berhasil diunggah.');
    }

    // =========================================================================
    // FRAME — Create form
    // =========================================================================
    public function createFrame()
    {
        $categories = $this->frameModel->db->table('frame_categories')->orderBy('name', 'ASC')->get()->getResultArray();
        return view('Admin/FrameAndFilter/FrameForm', [
            'title'      => 'Unggah Frame Baru | Admin Panel',
            'categories' => $categories,
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

        $categories = $this->frameModel->db->table('frame_categories')->orderBy('name', 'ASC')->get()->getResultArray();

        return view('Admin/FrameAndFilter/FrameForm', [
            'title'      => 'Edit Frame | Admin Panel',
            'frame'      => $frame,
            'categories' => $categories,
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

        $rules = [
            'name'        => 'required|min_length[3]|max_length[100]',
            'category_id' => 'permit_empty|max_length[20]',
            'photo_count' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[10]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $updateData = [
            'name'        => $this->request->getPost('name'),
            'category_id' => $this->request->getPost('category_id') ?: null,
            'photo_count' => (int) $this->request->getPost('photo_count'),
        ];

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
        return view('Admin/FrameAndFilter/FilterForm', [
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

        $lastRow = $this->filterModel->orderBy('id', 'DESC')->first();
        $lastIdNumber = 0;
        if ($lastRow && preg_match('/FLT-(\d+)/', $lastRow['id'], $matches)) {
            $lastIdNumber = (int)$matches[1];
        }
        $newId = sprintf('FLT-%02d', $lastIdNumber + 1);

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

        return view('Admin/FrameAndFilter/FilterForm', [
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

    // =========================================================================
    // CATEGORY — Store
    // =========================================================================
    public function storeCategory()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to(base_url('admin/frames'))
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $db    = $this->frameModel->db;
        $customCategories = $db->table('frame_categories')
            ->like('id', 'cat_custom_')
            ->get()->getResultArray();
        $maxNum = 0;
        foreach ($customCategories as $cat) {
            if (preg_match('/cat_custom_(\d+)/', $cat['id'], $matches)) {
                $num = (int)$matches[1];
                if ($num > $maxNum) $maxNum = $num;
            }
        }
        $newId = sprintf('cat_custom_%02d', $maxNum + 1);

        $db->table('frame_categories')->insert([
            'id'         => $newId,
            'name'       => $this->request->getPost('name'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('admin/frames'))
            ->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    // =========================================================================
    // CATEGORY — Update
    // =========================================================================
    public function updateCategory(string $id)
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to(base_url('admin/frames'))
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $db = $this->frameModel->db;
        $db->table('frame_categories')->where('id', $id)->update([
            'name'       => $this->request->getPost('name'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('admin/frames'))
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    // =========================================================================
    // CATEGORY — Delete
    // =========================================================================
    public function deleteCategory(string $id)
    {
        $db = $this->frameModel->db;
        
        // Nullify the category_id on any frames belonging to this category
        $db->table('frames')->where('category_id', $id)->update(['category_id' => null]);

        $db->table('frame_categories')->where('id', $id)->delete();

        return redirect()->to(base_url('admin/frames'))
            ->with('success', 'Kategori berhasil dihapus.');
    }

    // =========================================================================
    // FRAME — Toggle aktif/nonaktif
    // =========================================================================
    public function toggleFrame(string $id)
    {
        $frame = $this->frameModel->find($id);
        if (!$frame) {
            return redirect()->to(base_url('admin/frames'))
                ->with('error', 'Frame tidak ditemukan.');
        }

        $this->frameModel->update($id, ['is_active' => !$frame['is_active']]);

        $status = !$frame['is_active'] ? 'diaktifkan (siap digunakan)' : 'dinonaktifkan (disembunyikan)';
        return redirect()->to(base_url('admin/frames'))
            ->with('success', "Frame {$frame['name']} berhasil {$status}.");
    }
}
