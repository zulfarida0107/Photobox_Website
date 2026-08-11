<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FrameModel;
use App\Models\FilterModel;
use App\Models\FrameLayoutModel;

class FrameAndFilterController extends BaseController
{
    protected FrameModel       $frameModel;
    protected FilterModel      $filterModel;
    protected FrameLayoutModel $layoutModel;

    public function __construct()
    {
        $this->frameModel  = new FrameModel();
        $this->filterModel = new FilterModel();
        $this->layoutModel = new FrameLayoutModel();
    }

    // =========================================================================
    // INDEX — Halaman utama: frame list + filter list + stats
    // =========================================================================
    public function index()
    {
        $framePerPage     = 10;
        $currentFramePage = max(1, (int) ($this->request->getGet('page') ?? 1));

        $catPerPage       = 5;
        $currentCatPage   = max(1, (int) ($this->request->getGet('cat_page') ?? 1));

        $filterPerPage    = 5; // limit to match Panduan Aset
        $currentFilterPage= max(1, (int) ($this->request->getGet('filter_page') ?? 1));

        $search   = $this->request->getGet('search') ?? '';
        $category = $this->request->getGet('category') ?? '';
        $catSearch = $this->request->getGet('cat_search') ?? '';
        $filterSearch = $this->request->getGet('filter_search') ?? '';
        $filterStatus = $this->request->getGet('filter_status') ?? '';

        // --- Frame query builder ---
        $frameQuery = $this->frameModel->select('frames.*');

        if (!empty($search)) {
            $frameQuery->like('frames.name', $search);
        }

        if (!empty($category)) {
            $frameQuery->where('frames.category', $category);
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

        // --- Semua layout frame (Paginated) ---
        $layoutPerPage       = 5;
        $currentLayoutPage   = max(1, (int) ($this->request->getGet('layout_page') ?? 1));
        $layoutSearch        = $this->request->getGet('layout_search') ?? '';

        $layoutQuery = $this->layoutModel;
        if (!empty($layoutSearch)) {
            $layoutQuery->groupStart()
                ->like('name', $layoutSearch)
                ->orLike('id', $layoutSearch)
                ->groupEnd();
        }
        $totalLayouts       = $layoutQuery->countAllResults(false);
        $totalLayoutPages   = max(1, (int) ceil($totalLayouts / $layoutPerPage));
        $layouts            = $layoutQuery->orderBy('name', 'ASC')->findAll($layoutPerPage, ($currentLayoutPage - 1) * $layoutPerPage);

        // Kategori dari Enum
        $categories = [
            ['id' => 'pastel', 'name' => 'Pastel'],
            ['id' => 'cute', 'name' => 'Cute'],
            ['id' => 'layout', 'name' => 'Layout'],
            ['id' => 'minimalist', 'name' => 'Minimalist'],
            ['id' => 'newspaper', 'name' => 'Newspaper'],
            ['id' => 'magazine', 'name' => 'Magazine'],
            ['id' => 'retro 90s', 'name' => 'Retro 90s'],
            ['id' => 'y2k', 'name' => 'Y2K']
        ];
        $allLayouts = $this->layoutModel->orderBy('name', 'ASC')->findAll();

        // --- Stats ---
        $statTotalFrames      = $this->frameModel->countAll();
        $statTotalFilters     = $this->filterModel->countAll();
        $statTotalLayouts     = $this->layoutModel->countAll();
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
            'layouts'           => $layouts,
            'allLayouts'        => $allLayouts,
            'totalLayouts'      => $totalLayouts,
            'currentLayoutPage' => $currentLayoutPage,
            'totalLayoutPages'  => $totalLayoutPages,
            'layoutSearch'      => $layoutSearch,
            'search'            => $search,
            'selectedCategory'  => $category,
            'statTotalFrames'   => $statTotalFrames,
            'statTotalFilters'  => $statTotalFilters,
            'statTotalLayouts'  => $statTotalLayouts,
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
            'name'        => 'required|min_length[3]|max_length[100]|is_unique[frames.name]',
            'category'    => 'permit_empty|in_list[pastel,cute,layout,minimalist,newspaper,magazine,retro 90s,y2k]',
            'price'       => 'permit_empty|numeric',
            'frame_file'  => 'uploaded[frame_file]|max_size[frame_file,2048]|ext_in[frame_file,png,jpg,jpeg]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to(base_url('admin/frames'))
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $file = $this->request->getFile('frame_file');
        
        $name = $this->request->getPost('name');
        $photoCount = 0; // Removed layout logic

        // Generate ID: frame_001
        $lastFrame = $this->frameModel->db->table('frames')
                        ->like('id', 'frame_', 'after')
                        ->orderBy('id', 'DESC')
                        ->get()->getRowArray();
        $num = 1;
        if ($lastFrame && preg_match('/frame_(\d+)/', $lastFrame['id'], $matches)) {
            $num = (int)$matches[1] + 1;
        }
        $newId = sprintf('frame_%03d', $num);

        $safeName = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '_', $name), '_'));
        $ext = $file->getExtension();
        $newName = "frame_cuitbox_{$safeName}_{$photoCount}.{$ext}";

        $file->move(FCPATH . 'uploads/frames/', $newName);

        $this->frameModel->insert([
            'id'          => $newId,
            'name'        => $name,
            'category'    => $this->request->getPost('category') ?: 'minimalist',
            'price'       => $this->request->getPost('price') ?: 0,
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
        $categories = [
            ['id' => 'pastel', 'name' => 'Pastel'],
            ['id' => 'cute', 'name' => 'Cute'],
            ['id' => 'layout', 'name' => 'Layout'],
            ['id' => 'minimalist', 'name' => 'Minimalist'],
            ['id' => 'newspaper', 'name' => 'Newspaper'],
            ['id' => 'magazine', 'name' => 'Magazine'],
            ['id' => 'retro 90s', 'name' => 'Retro 90s'],
            ['id' => 'y2k', 'name' => 'Y2K']
        ];
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

        $categories = [
            ['id' => 'pastel', 'name' => 'Pastel'],
            ['id' => 'cute', 'name' => 'Cute'],
            ['id' => 'layout', 'name' => 'Layout'],
            ['id' => 'minimalist', 'name' => 'Minimalist'],
            ['id' => 'newspaper', 'name' => 'Newspaper'],
            ['id' => 'magazine', 'name' => 'Magazine'],
            ['id' => 'retro 90s', 'name' => 'Retro 90s'],
            ['id' => 'y2k', 'name' => 'Y2K']
        ];

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
            return redirect()->to(base_url('admin/frames#table-frame'))
                ->with('error', 'Frame tidak ditemukan.');
        }

        $rules = [
            'name'        => "required|min_length[3]|max_length[100]|is_unique[frames.name,id,{$id}]",
            'category'    => 'permit_empty|in_list[pastel,cute,layout,minimalist,newspaper,magazine,retro 90s,y2k]',
            'price'       => 'permit_empty|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $name = $this->request->getPost('name');
        $photoCount = 0; // Removed layout logic
        $safeName = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '_', $name), '_'));

        $updateData = [
            'name'        => $name,
            'category'    => $this->request->getPost('category') ?: 'minimalist',
            'price'       => $this->request->getPost('price') ?: 0,
            'is_active'   => 1, // aktifkan jika sebelumnya disync & nonaktif
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
            $ext = $file->getExtension();
            $newName = "frame_cuitbox_{$safeName}_{$photoCount}.{$ext}";
            $file->move(FCPATH . 'uploads/frames/', $newName);
            $updateData['file_path'] = 'uploads/frames/' . $newName;
        } else {
            // Rename file jika nama/layout berubah
            if (!empty($frame['file_path']) && file_exists(FCPATH . $frame['file_path'])) {
                $ext = pathinfo($frame['file_path'], PATHINFO_EXTENSION);
                $expectedName = "frame_cuitbox_{$safeName}_{$photoCount}.{$ext}";
                if (basename($frame['file_path']) !== $expectedName) {
                    rename(FCPATH . $frame['file_path'], FCPATH . 'uploads/frames/' . $expectedName);
                    $updateData['file_path'] = 'uploads/frames/' . $expectedName;
                }
            }
        }

        $this->frameModel->update($id, $updateData);

        return redirect()->to(base_url('admin/frames#table-frame'))
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

        return redirect()->to(base_url('admin/frames#table-frame'))
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
            'name'      => 'required|min_length[3]|max_length[100]|is_unique[filters.name]',
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

        return redirect()->to(base_url('admin/frames#table-frame'))
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
            'name'      => "required|min_length[3]|max_length[100]|is_unique[filters.name,id,{$id}]",
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
            'name' => 'required|min_length[3]|max_length[100]|is_unique[frame_categories.name]',
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
            'name' => "required|min_length[3]|max_length[100]|is_unique[frame_categories.name,id,{$id}]",
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
        
        // Categories are now enum in frames table, so no frames update needed here.

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

    // =========================================================================
    // LAYOUT — Store
    // =========================================================================
    public function storeLayout()
    {
        $rules = [
            'id'           => 'required|min_length[3]|max_length[50]|is_unique[frame_layouts.id]',
            'name'         => 'required|min_length[3]|max_length[100]|is_unique[frame_layouts.name]',
            'photo_count'  => 'required|numeric',
            'aspect_ratio' => 'permit_empty|max_length[20]',
            'layout_image' => 'permit_empty|max_size[layout_image,2048]|ext_in[layout_image,png,jpg,jpeg]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to(base_url('admin/frames'))
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $imagePath = null;
        $file = $this->request->getFile('layout_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/layouts/', $newName);
            $imagePath = 'uploads/layouts/' . $newName;
        }

        $this->layoutModel->insert([
            'id'               => $this->request->getPost('id'),
            'name'             => $this->request->getPost('name'),
            'photo_count'      => (int) $this->request->getPost('photo_count'),
            'aspect_ratio'     => $this->request->getPost('aspect_ratio') ?: null,
            'slot_coordinates' => $this->request->getPost('slot_coordinates') ?: null,
            'image_path'       => $imagePath,
        ]);

        return redirect()->to(base_url('admin/frames'))
            ->with('success', 'Layout frame baru berhasil ditambahkan.');
    }

    // =========================================================================
    // LAYOUT — Update
    // =========================================================================
    public function updateLayout(string $id)
    {
        $layout = $this->layoutModel->find($id);
        if (!$layout) {
            return redirect()->to(base_url('admin/frames'))->with('error', 'Layout tidak ditemukan.');
        }

        $rules = [
            'name'         => "required|min_length[3]|max_length[100]|is_unique[frame_layouts.name,id,{$id}]",
            'photo_count'  => 'required|numeric',
            'aspect_ratio' => 'permit_empty|max_length[20]',
            'layout_image' => 'permit_empty|max_size[layout_image,2048]|ext_in[layout_image,png,jpg,jpeg]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to(base_url('admin/frames'))
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $updateData = [
            'name'             => $this->request->getPost('name'),
            'photo_count'      => (int) $this->request->getPost('photo_count'),
            'aspect_ratio'     => $this->request->getPost('aspect_ratio') ?: null,
            'slot_coordinates' => $this->request->getPost('slot_coordinates') ?: null,
        ];

        $file = $this->request->getFile('layout_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if (!empty($layout['image_path']) && file_exists(FCPATH . $layout['image_path'])) {
                unlink(FCPATH . $layout['image_path']);
            }
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/layouts/', $newName);
            $updateData['image_path'] = 'uploads/layouts/' . $newName;
        }

        $this->layoutModel->update($id, $updateData);

        return redirect()->to(base_url('admin/frames'))->with('success', 'Layout frame berhasil diperbarui.');
    }

    // =========================================================================
    // LAYOUT — Delete
    // =========================================================================
    public function deleteLayout(string $id)
    {
        $layout = $this->layoutModel->find($id);
        if (!$layout) {
            return redirect()->to(base_url('admin/frames'))->with('error', 'Layout tidak ditemukan.');
        }

        if (!empty($layout['image_path']) && file_exists(FCPATH . $layout['image_path'])) {
            unlink(FCPATH . $layout['image_path']);
        }

        // Layouts are no longer tied to frames via layout_id.

        $this->layoutModel->delete($id);

        return redirect()->to(base_url('admin/frames'))->with('success', 'Layout frame berhasil dihapus.');
    }

    // =========================================================================
    // SYNC ASSETS — Auto-Sync Files from Folders
    // =========================================================================
    public function syncAssets()
    {
        $now = date('Y-m-d H:i:s');
        
        // 1. Sync Layouts
        $layoutDir = FCPATH . 'uploads/layouts/';
        if (is_dir($layoutDir)) {
            $files = array_diff(scandir($layoutDir), ['.', '..', 'index.html']);
            foreach ($files as $file) {
                if (is_file($layoutDir . $file)) {
                    $path = 'uploads/layouts/' . $file;
                    $exists = $this->frameModel->db->table('frame_layouts')->where('image_path', $path)->countAllResults();
                    if ($exists == 0) {
                        $name = pathinfo($file, PATHINFO_FILENAME);
                        $this->frameModel->db->table('frame_layouts')->insert([
                            'id' => 'layout_' . substr(md5($file), 0, 8),
                            'name' => ucwords(str_replace('_', ' ', $name)),
                            'photo_count' => 0,
                            'aspect_ratio' => '1:1',
                            'image_path' => $path,
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                    }
                }
            }
        }

        // 2. Sync Frames
        $frameDir = FCPATH . 'uploads/frames/';
        if (is_dir($frameDir)) {
            $lastFrame = $this->frameModel->db->table('frames')
                            ->like('id', 'frame_', 'after')
                            ->orderBy('id', 'DESC')
                            ->get()->getRowArray();
            $num = 1;
            if ($lastFrame && preg_match('/frame_(\d+)/', $lastFrame['id'], $matches)) {
                $num = (int)$matches[1] + 1;
            }

            $files = array_diff(scandir($frameDir), ['.', '..', 'index.html']);
            foreach ($files as $file) {
                if (is_file($frameDir . $file)) {
                    $path = 'uploads/frames/' . $file;
                    $exists = $this->frameModel->where('file_path', $path)->countAllResults();
                    if ($exists == 0) {
                        $newId = sprintf('frame_%03d', $num);

                        $rawName = pathinfo($file, PATHINFO_FILENAME);
                        if (is_numeric(substr($rawName, 0, 8))) {
                            $name = 'Synced Frame ' . $num;
                        } else {
                            $name = ucwords(str_replace(['_', 'cuitbox'], [' ', ''], $rawName));
                        }
                        
                        $safeName = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '_', $name), '_'));
                        $ext = pathinfo($file, PATHINFO_EXTENSION);
                        $newName = "frame_cuitbox_{$safeName}_0.{$ext}";
                        
                        // Cek jika nama baru bentrok
                        if (file_exists($frameDir . $newName) && $newName !== $file) {
                            $newName = "frame_cuitbox_{$safeName}_{$num}_0.{$ext}";
                        }

                        rename($frameDir . $file, $frameDir . $newName);
                        $newPath = 'uploads/frames/' . $newName;

                        $this->frameModel->insert([
                            'id' => $newId,
                            'name' => trim($name) ?: 'New Frame',
                            'file_path' => $newPath,
                            'is_active' => 0, // nonaktif agar tidak crash kiosk
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                        $num++;
                    }
                }
            }
        }

        return redirect()->to(base_url('admin/frames'))->with('success', 'Sinkronisasi file berhasil dilakukan. File baru diubah statusnya menjadi Nonaktif.');
    }
}
