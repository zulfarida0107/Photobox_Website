<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<main class="pt-16 min-h-screen flex flex-col">
    <div class="p-6 flex-1">

        <!-- Breadcrumbs -->
        <nav aria-label="Breadcrumb" class="flex mb-6 text-xs font-medium text-slate-500">
            <ol class="flex items-center space-x-2">
                <li>
                    <a class="hover:text-blue-600 flex items-center gap-1 transition-colors" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
                </li>
                <li class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[10px]">chevron_right</span>
                    <span class="text-blue-700 font-bold">Manajemen Aset Desain</span>
                </li>
            </ol>
        </nav>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm font-medium">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm font-medium">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-h1 text-h1 text-on-surface">Manajemen Aset Desain</h2>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Kelola aset desain seperti frame foto dan filter warna.</p>
            </div>
        </div>

        

        <div class="grid grid-cols-12 gap-6">

            <!-- Quick Stats Row -->
            <div class="col-span-12 grid grid-cols-1 md:grid-cols-4 gap-4 mb-2">
                <!-- Total Frame -->
                <div class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">image</span>
                    </div>
                    <p class="text-sm font-medium text-slate-500">Total Frame</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1"><?= str_pad($statTotalFrames ?? 0, 2, '0', STR_PAD_LEFT) ?></h3>
                </div>
                <!-- Kategori Frame -->
                <div class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">category</span>
                    </div>
                    <p class="text-sm font-medium text-slate-500">Kategori Frame</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1"><?= str_pad(count($categories ?? []), 2, '0', STR_PAD_LEFT) ?></h3>
                </div>
                <!-- Total Filter -->
                <div class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">tune</span>
                    </div>
                    <p class="text-sm font-medium text-slate-500">Total Filter</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1"><?= str_pad($statTotalFilters ?? 0, 2, '0', STR_PAD_LEFT) ?></h3>
                </div>
                <!-- Filter Aktif -->
                <div class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">check_circle</span>
                        <span class="bg-blue-100 text-blue-700 text-[10px] px-2 py-0.5 rounded-full font-bold">AKTIF</span>
                    </div>
                    <p class="text-sm font-medium text-slate-500">Filter Aktif</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1"><?= str_pad($statActiveFilters ?? 0, 2, '0', STR_PAD_LEFT) ?></h3>
                </div>
            </div>

            <!-- ============================================================
                 KIRI: Tabel Frame (7 kolom)
            ============================================================ -->
            <section class="col-span-12 xl:col-span-7 h-full flex flex-col">
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm flex-1 flex flex-col">
                    <div class="p-5 border-b border-slate-200 flex justify-between items-center bg-white">
                        <div class="flex items-center gap-3">
                            <div class="w-1 h-6 bg-primary rounded-full"></div>
                            <h4 class="font-h2 text-h2 text-on-surface">Koleksi Frame</h4>
                        </div>
                        <a href="<?= base_url('admin/frames/create') ?>"
                           class="bg-primary text-white px-4 py-2 rounded-lg font-label-bold text-label-bold flex items-center gap-2 hover:bg-primary-container transition-colors">
                            <span class="material-symbols-outlined text-[18px]">add_photo_alternate</span>
                            Unggah Frame
                        </a>
                    </div>
                    
                    <!-- Search & Filter Bar -->
                    <div class="p-4 bg-slate-50/50 border-b border-slate-200 flex flex-col sm:flex-row gap-3">
                        <form method="get" action="<?= base_url('admin/frames') ?>" class="flex flex-1 gap-3">
                            <!-- Search Input -->
                            <div class="relative flex-1">
                                <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[20px]">search</span>
                                <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="Cari nama frame..."
                                       class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white transition-all"/>
                            </div>
                            
                            <!-- Category Filter -->
                            <select name="category" onchange="this.form.submit()"
                                    class="px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white transition-all text-slate-600">
                                <option value="">Semua Kategori</option>
                                <?php if (!empty($allCategories)): ?>
                                    <?php foreach ($allCategories as $cat): ?>
                                        <option value="<?= esc($cat['id']) ?>" <?= ($selectedCategory ?? '') === $cat['id'] ? 'selected' : '' ?>>
                                            <?= esc($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            
                            <!-- Reset button if search or category is active -->
                            <?php if (!empty($search) || !empty($selectedCategory)): ?>
                                <a href="<?= base_url('admin/frames') ?>" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm font-semibold rounded-lg flex items-center transition-colors">
                                    Reset
                                </a>
                            <?php endif; ?>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase">ID Frame</th>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Nama Frame</th>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Kategori</th>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Preview</th>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Status</th>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if (!empty($frames)): ?>
                                    <?php foreach ($frames as $frame): ?>
                                        <tr class="hover:bg-blue-50/30 transition-colors">
                                            <td class="px-6 py-4 font-data-tabular text-data-tabular"><?= esc($frame['id']) ?></td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="font-body-base text-body-base text-on-surface font-semibold"><?= esc($frame['name']) ?></div>
                                                <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                                                    <?= esc($frame['photo_count'] ?? 6) ?> Foto
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 font-body-sm text-body-sm text-slate-600 text-center">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded bg-blue-50 text-blue-700 text-xs font-semibold">
                                                    <?= esc($frame['category_name'] ?? 'Uncategorized') ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="w-16 h-10 rounded border border-slate-200 bg-slate-100 overflow-hidden mx-auto">
                                                    <?php if (!empty($frame['file_path'])): ?>
                                                        <img alt="<?= esc($frame['name']) ?> preview"
                                                             class="w-full h-full object-cover"
                                                             src="<?= base_url($frame['file_path']) ?>"/>
                                                    <?php else: ?>
                                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                            <span class="material-symbols-outlined text-sm">image</span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <?php if ($frame['is_active']): ?>
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Aktif</span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">Non-Aktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 text-center space-x-2">
                                                <button type="button"
                                                        class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded transition-colors" title="Lihat Preview"
                                                        onclick="showPreviewModal('<?= esc($frame['id'], 'js') ?>', '<?= esc($frame['name'], 'js') ?>', '<?= esc($frame['category_name'] ?? 'Uncategorized', 'js') ?>', '<?= base_url($frame['file_path']) ?>', '<?= date('d M Y, H:i', strtotime($frame['created_at'])) ?>', '<?= esc($frame['photo_count'] ?? 6, 'js') ?>')">
                                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                                </button>
                                                <?php $returnQuery = 'page=' . ($currentFramePage ?? 1) . '&cat_page=' . ($currentCatPage ?? 1) . '&filter_page=' . ($currentFilterPage ?? 1) . '&search=' . urlencode($search ?? '') . '&category=' . urlencode($selectedCategory ?? ''); ?>
                                                <a href="<?= base_url('admin/frames/' . $frame['id'] . '/edit') ?>?return=<?= esc(base_url('admin/frames?' . $returnQuery), 'url') ?>"
                                                   class="inline p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                                </a>
                                                <form action="<?= base_url('admin/frames/' . $frame['id'] . '/toggle') ?>" method="post" class="inline">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="_return_page" value="<?= $currentFramePage ?? 1 ?>">
                                                    <input type="hidden" name="_cat_page" value="<?= $currentCatPage ?? 1 ?>">
                                                    <input type="hidden" name="_filter_page" value="<?= $currentFilterPage ?? 1 ?>">
                                                    <input type="hidden" name="_search" value="<?= esc($search ?? '') ?>">
                                                    <input type="hidden" name="_category" value="<?= esc($selectedCategory ?? '') ?>">
                                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-green-600 rounded transition-colors" title="Toggle Status Aktif">
                                                        <span class="material-symbols-outlined text-[20px]">
                                                            <?= $frame['is_active'] ? 'toggle_on' : 'toggle_off' ?>
                                                        </span>
                                                    </button>
                                                </form>
                                                <button type="button"
                                                        class="p-1.5 text-red-500 hover:bg-red-50 rounded transition-colors" title="Hapus"
                                                        onclick="openUniversalDeleteModal('<?= base_url('admin/frames/' . $frame['id'] . '/delete') ?>', '<?= esc($frame['name'], 'js') ?>', 'Hapus Koleksi Frame?')">
                                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-400">
                                            Belum ada frame. <a href="<?= base_url('admin/frames/create') ?>" class="text-primary font-semibold">Unggah sekarang</a>.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Frame -->
                    <?php
                        $frameBaseParams = 'cat_page=' . ($currentCatPage ?? 1) . '&filter_page=' . ($currentFilterPage ?? 1) . '&search=' . urlencode($search ?? '') . '&category=' . urlencode($selectedCategory ?? '');
                    ?>
                    <div class="px-6 py-4 bg-slate-50/50 flex items-center justify-between">
                        <span class="text-xs text-slate-500">
                            Menampilkan <?= count($frames ?? []) ?> dari <?= $totalFrames ?? 0 ?> frame
                        </span>
                        <div class="flex gap-1">
                            <a href="<?= base_url('admin/frames?page=' . max(1, ($currentFramePage ?? 1) - 1) . '&' . $frameBaseParams) ?>"
                               class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentFramePage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                                <span class="material-symbols-outlined text-sm">chevron_left</span>
                            </a>
                            <?php for ($i = 1; $i <= ($totalFramePages ?? 1); $i++): ?>
                                <a href="<?= base_url('admin/frames?page=' . $i . '&' . $frameBaseParams) ?>"
                                   class="w-8 h-8 flex items-center justify-center border rounded text-sm font-bold transition-colors <?= $i === ($currentFramePage ?? 1) ? 'border-primary bg-primary text-white' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                            <a href="<?= base_url('admin/frames?page=' . min(($totalFramePages ?? 1), ($currentFramePage ?? 1) + 1) . '&' . $frameBaseParams) ?>"
                               class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentFramePage ?? 1) >= ($totalFramePages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                                <span class="material-symbols-outlined text-sm">chevron_right</span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ============================================================
                 KANAN: Filter Warna & Kategori Frame (5 kolom)
            ============================================================ -->
            <section class="col-span-12 xl:col-span-5 flex flex-col gap-6">

                <!-- Tabel Kategori Frame -->
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm flex flex-col">
                    <div class="p-5 border-b border-slate-200 flex justify-between items-center bg-white">
                        <div class="flex items-center gap-3">
                            <div class="w-1 h-6 bg-primary rounded-full"></div>
                            <h4 class="font-h2 text-h2 text-on-surface">Kategori Frame</h4>
                        </div>
                        <button type="button" onclick="openAddCategoryModal()"
                                class="bg-primary text-white px-4 py-2 rounded-lg font-label-bold text-label-bold flex items-center gap-2 hover:bg-primary-container transition-colors">
                            <span class="material-symbols-outlined text-[18px]">category</span>
                            Tambah Kategori
                        </button>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">ID</th>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Nama Kategori</th>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $cat): ?>
                                        <tr class="hover:bg-blue-50/30 transition-colors">
                                            <td class="px-6 py-4 font-data-tabular text-data-tabular text-center"><?= esc($cat['id']) ?></td>
                                            <td class="px-6 py-4 font-body-base text-body-base text-on-surface text-center"><?= esc($cat['name']) ?></td>
                                            <td class="px-6 py-4 text-center flex justify-center gap-2">
                                                <button type="button" 
                                                        onclick="openEditCategoryModal('<?= esc($cat['id'], 'js') ?>', '<?= esc($cat['name'], 'js') ?>')"
                                                        class="inline p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                                </button>
                                                <button type="button" class="inline p-1.5 text-red-500 hover:bg-red-50 rounded transition-colors" title="Hapus Kategori"
                                                        onclick="openUniversalDeleteModal('<?= base_url('admin/categories/' . $cat['id'] . '/delete') ?>', '<?= esc($cat['name'], 'js') ?>', 'Hapus Kategori Frame?')">
                                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="px-6 py-8 text-center text-sm text-slate-400">
                                            Belum ada kategori.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Kategori -->
                    <?php
                        $catBaseParams = 'page=' . ($currentFramePage ?? 1) . '&filter_page=' . ($currentFilterPage ?? 1) . '&search=' . urlencode($search ?? '') . '&category=' . urlencode($selectedCategory ?? '');
                    ?>
                    <div class="px-6 py-4 bg-slate-50/50 flex items-center justify-between border-t border-slate-200">
                        <span class="text-xs text-slate-500">
                            Menampilkan <?= count($categories ?? []) ?> dari <?= $totalCats ?? 0 ?> kategori
                        </span>
                        <div class="flex gap-1">
                            <a href="<?= base_url('admin/frames?cat_page=' . max(1, ($currentCatPage ?? 1) - 1) . '&' . $catBaseParams) ?>"
                               class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentCatPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                                <span class="material-symbols-outlined text-sm">chevron_left</span>
                            </a>
                            <?php for ($i = 1; $i <= ($totalCatPages ?? 1); $i++): ?>
                                <a href="<?= base_url('admin/frames?cat_page=' . $i . '&' . $catBaseParams) ?>"
                                   class="w-8 h-8 flex items-center justify-center border rounded text-sm font-bold transition-colors <?= $i === ($currentCatPage ?? 1) ? 'border-primary bg-primary text-white' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                            <a href="<?= base_url('admin/frames?cat_page=' . min(($totalCatPages ?? 1), ($currentCatPage ?? 1) + 1) . '&' . $catBaseParams) ?>"
                               class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentCatPage ?? 1) >= ($totalCatPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                                <span class="material-symbols-outlined text-sm">chevron_right</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tabel Filter Warna -->
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm flex flex-col">
                    <div class="p-5 border-b border-slate-200 flex justify-between items-center bg-white">
                        <div class="flex items-center gap-3">
                            <div class="w-1 h-6 bg-primary rounded-full"></div>
                            <h4 class="font-h2 text-h2 text-on-surface">Filter Warna</h4>
                        </div>
                        <a href="<?= base_url('admin/filters/create') ?>"
                           class="bg-primary text-white px-4 py-2 rounded-lg font-label-bold text-label-bold flex items-center gap-2 hover:bg-primary-container transition-colors">
                            <span class="material-symbols-outlined text-[18px]">tune</span>
                            Tambah Filter
                        </a>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">ID</th>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Nama Filter</th>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">CSS Value</th>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Status</th>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if (!empty($filters)): ?>
                                    <?php foreach ($filters as $filter): ?>
                                        <tr class="hover:bg-blue-50/30 transition-colors">
                                            <td class="px-6 py-4 font-data-tabular text-data-tabular text-center"><?= esc($filter['id']) ?></td>
                                            <td class="px-6 py-4 font-body-base text-body-base text-center"><?= esc($filter['name']) ?></td>
                                            <td class="px-6 py-4 text-center">
                                                <code class="bg-slate-100 px-2 py-1 rounded text-[11px] text-slate-600">
                                                    <?= esc($filter['css_value']) ?>
                                                </code>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <?php if ($filter['is_active']): ?>
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 uppercase">Aktif</span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-700 uppercase">Non-Aktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 text-center flex justify-center gap-1">
                                                <?php $filterReturnParams = 'page=' . ($currentFramePage ?? 1) . '&cat_page=' . ($currentCatPage ?? 1) . '&filter_page=' . ($currentFilterPage ?? 1) . '&search=' . urlencode($search ?? '') . '&category=' . urlencode($selectedCategory ?? ''); ?>
                                                <a href="<?= base_url('admin/filters/' . $filter['id'] . '/edit') ?>?return=<?= esc(base_url('admin/frames?' . $filterReturnParams), 'url') ?>"
                                                   class="inline p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                                                    <span class="material-symbols-outlined text-[20px]">settings</span>
                                                </a>
                                                <form action="<?= base_url('admin/filters/' . $filter['id'] . '/toggle') ?>" method="post" class="inline">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="_return_page" value="<?= $currentFramePage ?? 1 ?>">
                                                    <input type="hidden" name="_cat_page" value="<?= $currentCatPage ?? 1 ?>">
                                                    <input type="hidden" name="_filter_page" value="<?= $currentFilterPage ?? 1 ?>">
                                                    <input type="hidden" name="_search" value="<?= esc($search ?? '') ?>">
                                                    <input type="hidden" name="_category" value="<?= esc($selectedCategory ?? '') ?>">
                                                    <button type="submit" class="inline p-1.5 text-slate-400 hover:bg-slate-100 hover:text-green-600 rounded transition-colors" title="Toggle Aktif">
                                                        <span class="material-symbols-outlined text-[20px]">
                                                            <?= $filter['is_active'] ? 'toggle_on' : 'toggle_off' ?>
                                                        </span>
                                                    </button>
                                                </form>
                                                <button type="button" class="inline p-1.5 text-red-500 hover:bg-red-50 rounded transition-colors" title="Hapus Filter"
                                                        onclick="openUniversalDeleteModal('<?= base_url('admin/filters/' . $filter['id'] . '/delete') ?>?<?= $filterReturnParams ?>', '<?= esc($filter['name'], 'js') ?>', 'Hapus Filter Warna?')">
                                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-400">
                                            Belum ada filter.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Filter -->
                    <?php
                        $filterBaseParams = 'page=' . ($currentFramePage ?? 1) . '&cat_page=' . ($currentCatPage ?? 1) . '&search=' . urlencode($search ?? '') . '&category=' . urlencode($selectedCategory ?? '');
                    ?>
                    <div class="px-6 py-4 bg-slate-50/50 flex items-center justify-between border-t border-slate-200">
                        <span class="text-xs text-slate-500">
                            Menampilkan <?= count($filters ?? []) ?> dari <?= $totalFiltersTable ?? 0 ?> filter
                        </span>
                        <div class="flex gap-1">
                            <a href="<?= base_url('admin/frames?filter_page=' . max(1, ($currentFilterPage ?? 1) - 1) . '&' . $filterBaseParams) ?>"
                               class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentFilterPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                                <span class="material-symbols-outlined text-sm">chevron_left</span>
                            </a>
                            <?php for ($i = 1; $i <= ($totalFilterPages ?? 1); $i++): ?>
                                <a href="<?= base_url('admin/frames?filter_page=' . $i . '&' . $filterBaseParams) ?>"
                                   class="w-8 h-8 flex items-center justify-center border rounded text-sm font-bold transition-colors <?= $i === ($currentFilterPage ?? 1) ? 'border-primary bg-primary text-white' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                            <a href="<?= base_url('admin/frames?filter_page=' . min(($totalFilterPages ?? 1), ($currentFilterPage ?? 1) + 1) . '&' . $filterBaseParams) ?>"
                               class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentFilterPage ?? 1) >= ($totalFilterPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                                <span class="material-symbols-outlined text-sm">chevron_right</span>
                            </a>
                        </div>
                    </div>
                </div>

            </section>

        </div> <!-- End grid-cols-12 -->

        <!-- Panduan Aset (Full Width Bottom) -->
        <div class="bg-blue-600 rounded-xl p-6 shadow-sm mt-6 mb-6 flex flex-col md:flex-row gap-6 items-center">
            <div class="w-full md:w-1/4 flex justify-center order-2 md:order-1">
                <img src="<?= base_url('assets/images/memo_3d.png') ?>" alt="Panduan Aset" class="w-56 object-contain drop-shadow-2xl hover:scale-105 transition-transform duration-300">
            </div>
            <div class="w-full md:w-3/4 order-1 md:order-2">
                <div class="inline-block bg-white/20 text-white px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider mb-4">
                    PANDUAN ASET
                </div>
                <h3 class="text-2xl font-bold text-white mb-3">Panduan Pengunggahan Aset Desain</h3>
                <p class="text-blue-100 text-sm mb-6 leading-relaxed max-w-3xl">
                    Pastikan aset desain seperti frame dan filter memenuhi standar teknis untuk hasil cetak terbaik pada mesin booth. Gunakan format file yang tepat dan perhatikan resolusi sebelum mengunggah agar kualitas terjamin.
                </p>
                <button type="button" onclick="openPanduanModal()" class="inline-block bg-white text-blue-600 font-bold py-3 px-8 rounded-lg hover:bg-blue-50 transition-colors">
                    Baca Panduan Lengkap
                </button>
            </div>
        </div>

    </div>
    <?= $this->include('Admin/layouts/footer') ?>
</main>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="previewModalContent">
        <!-- Modal Header -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">visibility</span>
                Detail &amp; Preview Frame
            </h4>

        </div>
        
        <!-- Modal Body -->
        <div class="p-6 flex flex-col items-center">
            <!-- Full Frame Image Wrapper -->
            <div class="w-full h-64 bg-slate-50 border border-slate-200 rounded-xl overflow-hidden flex items-center justify-center p-4 mb-5 shadow-inner">
                <img id="modalFrameImage" src="" alt="Frame Full Preview" class="max-w-full max-h-full object-contain drop-shadow" />
            </div>

            <!-- Details List -->
            <div class="w-full space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-200 text-left text-sm mb-6">
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">ID Frame</span>
                    <span class="font-mono text-slate-800 font-bold" id="modalFrameId"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">Nama Frame</span>
                    <span class="text-slate-800 font-semibold" id="modalFrameName"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">Kategori</span>
                    <span class="text-slate-800 font-semibold" id="modalFrameCategory"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">Jumlah Foto</span>
                    <span class="text-slate-800 font-semibold" id="modalFramePhotoCount"></span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold text-slate-500">Waktu Unggah</span>
                    <span class="text-slate-800" id="modalFrameUploaded"></span>
                </div>
            </div>
            
            <!-- Close Button -->
            <button type="button" onclick="closePreviewModal()" class="w-full py-2.5 bg-primary hover:bg-primary-container text-white font-semibold text-sm rounded-lg transition-colors shadow-md">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function showPreviewModal(id, name, category, filePath, uploadedAt, photoCount) {
        const modal = document.getElementById('previewModal');
        const content = document.getElementById('previewModalContent');
        
        document.getElementById('modalFrameId').textContent = id;
        document.getElementById('modalFrameName').textContent = name;
        document.getElementById('modalFrameCategory').textContent = category;
        document.getElementById('modalFrameUploaded').textContent = uploadedAt;
        document.getElementById('modalFramePhotoCount').textContent = photoCount + " Foto";
        document.getElementById('modalFrameImage').src = filePath;

        // Show Modal
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closePreviewModal() {
        const modal = document.getElementById('previewModal');
        const content = document.getElementById('previewModalContent');

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>

<!-- Add Category Modal -->
<div id="addCategoryModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-sm w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="addCategoryModalContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">category</span>
                Tambah Kategori
            </h4>
            <button type="button" onclick="closeAddCategoryModal()" class="text-slate-400 hover:text-slate-600">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form action="<?= base_url('admin/categories') ?>" method="post" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <div>
                <label for="new_category_name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Kategori *</label>
                <input type="text" name="name" id="new_category_name" required placeholder="Contoh: Cyberpunk Neon"
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
            </div>
            <div class="flex gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeAddCategoryModal()" class="flex-1 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-sm rounded-lg transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2 bg-primary text-white font-semibold text-sm rounded-lg hover:bg-blue-800 transition-colors shadow-md">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-sm w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="editCategoryModalContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">edit</span>
                Edit Kategori
            </h4>
            <button type="button" onclick="closeEditCategoryModal()" class="text-slate-400 hover:text-slate-600">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form id="editCategoryForm" action="" method="post" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <div>
                <label for="edit_category_name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Kategori *</label>
                <input type="text" name="name" id="edit_category_name" required
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
            </div>
            <div class="flex gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeEditCategoryModal()" class="flex-1 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-sm rounded-lg transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2 bg-primary text-white font-semibold text-sm rounded-lg hover:bg-blue-800 transition-colors shadow-md">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddCategoryModal() {
        const modal = document.getElementById('addCategoryModal');
        const content = document.getElementById('addCategoryModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeAddCategoryModal() {
        const modal = document.getElementById('addCategoryModal');
        const content = document.getElementById('addCategoryModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function openEditCategoryModal(id, name) {
        const modal = document.getElementById('editCategoryModal');
        const content = document.getElementById('editCategoryModalContent');
        const form = document.getElementById('editCategoryForm');
        const input = document.getElementById('edit_category_name');
        
        form.action = `<?= base_url('admin/categories') ?>/${id}/update`;
        input.value = name;

        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeEditCategoryModal() {
        const modal = document.getElementById('editCategoryModal');
        const content = document.getElementById('editCategoryModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function openPanduanModal() {
        const modal = document.getElementById('panduanModal');
        const content = document.getElementById('panduanModalContent');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closePanduanModal() {
        const modal = document.getElementById('panduanModal');
        const content = document.getElementById('panduanModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }
</script>

<!-- Panduan Lengkap Modal -->
<div id="panduanModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl overflow-hidden transform transition-all scale-95 opacity-0 duration-300 flex flex-col max-h-full" id="panduanModalContent">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600">info</span>
                Panduan Pengunggahan Aset Desain
            </h3>
        </div>

        <!-- Body -->
        <div class="p-6 overflow-y-auto flex-1 text-slate-600 text-base space-y-6">
            
            <p>Tingkatkan kualitas hasil cetak Photo Box Anda dengan mengikuti standar teknis resolusi dan area potong.</p>

            <div>
                <h4 class="font-bold text-slate-800 mb-2 text-lg">Spesifikasi Standar Frame</h4>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>Format File:</strong> PNG (Alpha Channel)</li>
                    <li><strong>Rasio Aspek:</strong> 4:6 (Portrait) / 1:1</li>
                    <li><strong>Resolusi Min:</strong> 1200 × 1800 px</li>
                    <li><strong>Ukuran Maks:</strong> 2 MB / File</li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-slate-800 mb-2 text-lg">Area Transparan & Foto</h4>
                <p>Frame harus memiliki <strong>area transparan mutlak (Opacity 0%)</strong>. Sistem akan meletakkan jepretan kamera tepat di bawah layer transparan tersebut.</p>
                <p class="text-red-600 mt-2"><em>Perhatian: Jangan gunakan warna putih atau hitam polos sebagai placeholder foto. Sistem tidak mendeteksi warna sebagai transparansi.</em></p>
            </div>

            <div>
                <h4 class="font-bold text-slate-800 mb-2 text-lg">Konfigurasi Filter Warna CSS</h4>
                <p class="mb-3">Fitur filter warna memanfaatkan CSS Filters native browser. Berikut beberapa contoh:</p>
                <table class="w-full text-left border border-slate-200 rounded">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-3 py-2 font-semibold">Nama Efek</th>
                            <th class="px-3 py-2 font-semibold">Nilai CSS Filter</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <tr>
                            <td class="px-3 py-2">B&W Standard</td>
                            <td class="px-3 py-2"><code class="bg-slate-100 px-1 rounded">grayscale(100%) contrast(1.1)</code></td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2">Warm Vintage</td>
                            <td class="px-3 py-2"><code class="bg-slate-100 px-1 rounded">sepia(60%) contrast(1.1)</code></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex justify-end">
            <button type="button" onclick="closePanduanModal()" class="px-6 py-2.5 bg-blue-600 text-white hover:bg-blue-700 rounded transition-colors font-bold text-base shadow-sm hover:shadow">
                Tutup Panduan
            </button>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>
