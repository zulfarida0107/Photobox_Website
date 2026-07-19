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
        $framePerPage     = 10;
        $currentFramePage = max(1, (int) ($this->request->getGet('page') ?? 1));

        $catPerPage       = 10;
        $currentCatPage   = max(1, (int) ($this->request->getGet('cat_page') ?? 1));

        $filterPerPage    = 5; // limit to match Panduan Aset
        $currentFilterPage= max(1, (int) ($this->request->getGet('filter_page') ?? 1));

        $search   = $this->request->getGet('search') ?? '';
        $category = $this->request->getGet('category') ?? '';
        $catSearch = $this->request->getGet('cat_search') ?? '';
        $filterSearch = $this->request->getGet('filter_search') ?? '';
        $filterStatus = $this->request->getGet('filter_status') ?? '';

        // --- Frame query builder + Join category name ---
        $frameQuery = $this->frameModel
            ->select('frames.*, frame_categories.name as category_name, frame_layouts.name as layout_name')
            ->join('frame_categories', 'frame_categories.id = frames.category_id', 'left')
            ->join('frame_layouts', 'frame_layouts.id = frames.layout_id', 'left');

        if (!empty($search)) {
            $frameQuery->like('frames.name', $search);
        }

        if (!empty($category)) {
            $frameQuery->where('frames.category_id', $category);
        }

        $totalFrames      = $frameQuery->countAllResults(false);
        $totalFramePages  = max(1, (int) ceil($totalFrames / $framePerPage));
        $frames           = $frameQuery
            ->orderBy('frames.created_at', 'DESC')
            ->findAll($framePerPage, ($currentFramePage - 1) * $framePerPage);

        // --- Semua filter (Paginated) ---
        $filterQuery = $this->filterModel;
        if (!empty($filterSearch)) {
            $filterQuery->like('name', $filterSearch);
        }
        if ($filterStatus === '1' || $filterStatus === '0') {
            $filterQuery->where('is_active', (int)$filterStatus);
        }

        $totalFilters      = $filterQuery->countAllResults(false);
        $totalFilterPages  = max(1, (int) ceil($totalFilters / $filterPerPage));
        $filters = $filterQuery->orderBy('name', 'ASC')->findAll($filterPerPage, ($currentFilterPage - 1) * $filterPerPage);

        // --- Semua kategori frame (Paginated) ---
        $catBuilder = $this->frameModel->db->table('frame_categories');
        if (!empty($catSearch)) {
            $catBuilder->like('name', $catSearch);
        }
        $totalCategories = $catBuilder->countAllResults(false);
        $totalCategoryPages = max(1, (int) ceil($totalCategories / $catPerPage));
        $categoriesPaginated = $catBuilder->orderBy('name', 'ASC')->limit($catPerPage, ($currentCatPage - 1) * $catPerPage)->get()->getResultArray();

        // Kategori untuk dropdown filter/upload
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
            'totalFilters'      => $totalFilters,
            'currentFilterPage' => $currentFilterPage,
            'totalFilterPages'  => $totalFilterPages,
            'categories'        => $categories,
            'categoriesPaginated' => $categoriesPaginated,
            'totalCategories'   => $totalCategories,
            'currentCatPage'    => $currentCatPage,
            'totalCategoryPages'=> $totalCategoryPages,
            'search'            => $search,
            'selectedCategory'  => $category,
            'statTotalFrames'   => $statTotalFrames,
            'statTotalFilters'  => $statTotalFilters,
            'statActiveFilters' => $statActiveFilters,
            'statFramesThisMonth' => $statFramesThisMonth,
            'catSearch'         => $catSearch,
            'filterSearch'      => $filterSearch,
            'filterStatus'      => $filterStatus,
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
            'layout_id'   => 'permit_empty|max_length[20]',
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
        $count = $this->frameModel->countAll();
        $newId = sprintf('FRM-%03d', $count + 1);

        $this->frameModel->insert([
            'id'          => $newId,
            'name'        => $this->request->getPost('name'),
            'category_id' => $this->request->getPost('category_id') ?: null,
            'layout_id'   => $this->request->getPost('layout_id') ?: null,
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
        $layouts    = $this->frameModel->db->table('frame_layouts')->orderBy('name', 'ASC')->get()->getResultArray();
        return view('Admin/FrameAndFilter/FrameForm', [
            'title'      => 'Unggah Frame Baru | Admin Panel',
            'categories' => $categories,
            'layouts'    => $layouts,
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
        $layouts    = $this->frameModel->db->table('frame_layouts')->orderBy('name', 'ASC')->get()->getResultArray();

        return view('Admin/FrameAndFilter/FrameForm', [
            'title'      => 'Edit Frame | Admin Panel',
            'frame'      => $frame,
            'categories' => $categories,
            'layouts'    => $layouts,
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

        $updateData = [
            'name'        => $this->request->getPost('name'),
            'category_id' => $this->request->getPost('category_id') ?: null,
            'layout_id'   => $this->request->getPost('layout_id') ?: null,
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
        $count = $db->table('frame_categories')->countAllResults();
        $newId = sprintf('cat_custom_%02d', $count + 1);

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
