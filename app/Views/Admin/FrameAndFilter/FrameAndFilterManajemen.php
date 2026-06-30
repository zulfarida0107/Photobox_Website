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

        <!-- Bento Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <!-- Total Frame -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">wallpaper</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Total Frame</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= esc($statTotalFrames ?? 0) ?></h3>
            </div>
            <!-- Frame Bulan Ini -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">event</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Frame Bulan Ini</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= esc($statFramesThisMonth ?? 0) ?></h3>
            </div>
            <!-- Total Filter -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">filter_b_and_w</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Total Filter</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= esc($statTotalFilters ?? 0) ?></h3>
            </div>
            <!-- Filter Aktif -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">check_circle</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Filter Aktif</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= esc($statActiveFilters ?? 0) ?></h3>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6">

            <!-- ============================================================
                 KIRI: Tabel Frame (7 kolom)
            ============================================================ -->
            <section class="col-span-12 xl:col-span-7">
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
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
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $cat): ?>
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
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600">ID Frame</th>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 text-center">Nama Frame</th>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 text-center">Kategori</th>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 text-center">Preview</th>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 text-center">Status</th>
                                    <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if (!empty($frames)): ?>
                                    <?php foreach ($frames as $frame): ?>
                                        <tr class="hover:bg-blue-50/30 transition-colors">
                                            <td class="px-6 py-4 font-data-tabular text-data-tabular"><?= esc($frame['id']) ?></td>
                                            <td class="px-6 py-4 font-body-base text-body-base text-on-surface text-center"><?= esc($frame['name']) ?></td>
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
                                                        onclick="showPreviewModal('<?= esc($frame['id'], 'js') ?>', '<?= esc($frame['name'], 'js') ?>', '<?= esc($frame['category_name'] ?? 'Uncategorized', 'js') ?>', '<?= base_url($frame['file_path']) ?>', '<?= date('d M Y, H:i', strtotime($frame['created_at'])) ?>')">
                                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                                </button>
                                                <a href="<?= base_url('admin/frames/' . $frame['id'] . '/edit') ?>"
                                                   class="inline p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                                </a>
                                                <form action="<?= base_url('admin/frames/' . $frame['id'] . '/toggle') ?>" method="post" class="inline">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-green-600 rounded transition-colors" title="Toggle Status Aktif">
                                                        <span class="material-symbols-outlined text-[20px]">
                                                            <?= $frame['is_active'] ? 'toggle_on' : 'toggle_off' ?>
                                                        </span>
                                                    </button>
                                                </form>
                                                <form action="<?= base_url('admin/frames/' . $frame['id'] . '/delete') ?>" method="post" class="inline">
                                                    <?= csrf_field() ?>
                                                    <button type="submit"
                                                            class="p-1.5 text-red-500 hover:bg-red-50 rounded transition-colors" title="Hapus"
                                                            >
                                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                                    </button>
                                                </form>
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
                    <div class="px-6 py-4 bg-slate-50/50 flex items-center justify-between">
                        <span class="text-xs text-slate-500">
                            Menampilkan <?= count($frames ?? []) ?> dari <?= $totalFrames ?? 0 ?> frame
                        </span>
                        <div class="flex gap-1">
                            <a href="<?= base_url('admin/frames?page=' . max(1, ($currentFramePage ?? 1) - 1) . '&search=' . urlencode($search ?? '') . '&category=' . urlencode($selectedCategory ?? '')) ?>"
                               class="p-1 border border-slate-300 rounded hover:bg-white transition-colors <?= ($currentFramePage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                                <span class="material-symbols-outlined text-sm">chevron_left</span>
                            </a>
                            <a href="<?= base_url('admin/frames?page=' . min(($totalFramePages ?? 1), ($currentFramePage ?? 1) + 1) . '&search=' . urlencode($search ?? '') . '&category=' . urlencode($selectedCategory ?? '')) ?>"
                               class="p-1 border border-slate-300 rounded hover:bg-white transition-colors <?= ($currentFramePage ?? 1) >= ($totalFramePages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                                <span class="material-symbols-outlined text-sm">chevron_right</span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ============================================================
                 KANAN: Tabel Filter + Stats (5 kolom)
            ============================================================ -->
            <section class="col-span-12 xl:col-span-5 flex flex-col gap-6">

                <!-- Tabel Filter Warna -->
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="p-5 border-b border-slate-200 flex justify-between items-center bg-white">
                        <div>
                            <h2 class="font-h2 text-h2 text-on-surface">Filter Warna</h2>
                            <p class="text-body-sm text-slate-500">Preset CSS untuk efek visual.</p>
                        </div>
                        <a href="<?= base_url('admin/filters/create') ?>"
                           class="border border-primary text-primary px-4 py-2 rounded-lg font-label-bold text-label-bold flex items-center gap-2 hover:bg-blue-50 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">tune</span>
                            Tambah Filter
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600">ID</th>
                                    <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600">Nama</th>
                                    <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600">CSS Value</th>
                                    <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600">Status</th>
                                    <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if (!empty($filters)): ?>
                                    <?php foreach ($filters as $filter): ?>
                                        <tr class="hover:bg-blue-50/30 transition-colors">
                                            <td class="px-4 py-4 font-data-tabular text-data-tabular"><?= esc($filter['id']) ?></td>
                                            <td class="px-4 py-4 font-body-base text-body-base"><?= esc($filter['name']) ?></td>
                                            <td class="px-4 py-4">
                                                <code class="bg-slate-100 px-2 py-1 rounded text-[11px] text-slate-600">
                                                    <?= esc($filter['css_value']) ?>
                                                </code>
                                            </td>
                                            <td class="px-4 py-4">
                                                <?php if ($filter['is_active']): ?>
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Aktif</span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">Non-Aktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-4 py-4 text-center flex justify-center gap-1">
                                                <a href="<?= base_url('admin/filters/' . $filter['id'] . '/edit') ?>"
                                                   class="inline p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                                                    <span class="material-symbols-outlined text-[20px]">settings</span>
                                                </a>
                                                <form action="<?= base_url('admin/filters/' . $filter['id'] . '/toggle') ?>" method="post" class="inline">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="inline p-1.5 text-slate-400 hover:bg-slate-100 hover:text-green-600 rounded transition-colors" title="Toggle Aktif">
                                                        <span class="material-symbols-outlined text-[20px]">
                                                            <?= $filter['is_active'] ? 'toggle_on' : 'toggle_off' ?>
                                                        </span>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-400">
                                            Belum ada filter.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Stats Bento -->
                <div class="p-6 rounded-xl bg-gradient-to-br from-blue-600 to-blue-800 text-white shadow-lg overflow-hidden relative group">
                    <div class="relative z-10">
                        <h3 class="text-h2 font-h2 mb-2">Statistik Aset</h3>
                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div class="bg-white/10 p-4 rounded-lg backdrop-blur-sm">
                                <p class="text-xs text-blue-100">Total Frame</p>
                                <p class="text-2xl font-bold"><?= number_format($statTotalFrames ?? 0) ?></p>
                            </div>
                            <div class="bg-white/10 p-4 rounded-lg backdrop-blur-sm">
                                <p class="text-xs text-blue-100">Filter Aktif</p>
                                <p class="text-2xl font-bold"><?= number_format($statActiveFilters ?? 0) ?></p>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center gap-2 text-xs text-blue-100">
                            <span class="material-symbols-outlined text-sm">info</span>
                            <span>Total Filter: <?= $statTotalFilters ?? 0 ?> | Frame bulan ini: <?= $statFramesThisMonth ?? 0 ?></span>
                        </div>
                    </div>
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-700"></div>
                </div>

                <!-- Tabel Kategori Frame -->
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="p-5 border-b border-slate-200 flex justify-between items-center bg-white">
                        <div>
                            <h2 class="font-h2 text-h2 text-on-surface">Kategori Frame</h2>
                            <p class="text-body-sm text-slate-500">Kelola kelompok &amp; tema bingkai foto.</p>
                        </div>
                        <button type="button" onclick="openAddCategoryModal()"
                                class="border border-primary text-primary px-4 py-2 rounded-lg font-label-bold text-label-bold flex items-center gap-2 hover:bg-blue-50 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">category</span>
                            Tambah Kategori
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 text-center">ID</th>
                                    <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 text-center">Nama Kategori</th>
                                    <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $cat): ?>
                                        <tr class="hover:bg-blue-50/30 transition-colors">
                                            <td class="px-4 py-4 font-data-tabular text-data-tabular text-center"><?= esc($cat['id']) ?></td>
                                            <td class="px-4 py-4 font-body-base text-body-base text-on-surface text-center"><?= esc($cat['name']) ?></td>
                                            <td class="px-4 py-4 text-center flex justify-center gap-2">
                                                <button type="button" 
                                                        onclick="openEditCategoryModal('<?= esc($cat['id'], 'js') ?>', '<?= esc($cat['name'], 'js') ?>')"
                                                        class="inline p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                                </button>
                                                <form action="<?= base_url('admin/categories/' . $cat['id'] . '/delete') ?>" method="post" class="inline">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="inline p-1.5 text-red-500 hover:bg-red-50 rounded transition-colors" title="Hapus Kategori"
                                                            onclick="return confirm('Hapus kategori ini? Semua frame dalam kategori ini akan diubah menjadi uncategorized.')">
                                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="px-4 py-8 text-center text-sm text-slate-400">
                                            Belum ada kategori.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- ============================================================
                 BAWAH: Upload Zone + Panduan
            ============================================================ -->
            <div class="col-span-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Upload Zone -->
                <form action="<?= base_url('admin/frames') ?>" method="post" enctype="multipart/form-data"
                      class="md:col-span-2 bg-white border border-dashed border-slate-300 rounded-xl p-10 flex flex-col items-center justify-center text-center group hover:border-blue-400 transition-colors">
                    <?= csrf_field() ?>
                    <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">cloud_upload</span>
                    </div>
                    <h4 class="font-bold text-on-surface mb-1">Seret &amp; Letakkan Frame Baru</h4>
                    <p class="text-body-sm text-slate-500 mb-4">Mendukung format PNG transparan, resolusi min. 1080px.</p>
                    <input type="text" name="name" placeholder="Nama Frame" required
                           class="mb-3 w-full max-w-xs px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none"/>
                    
                    <select name="category_id"
                            class="mb-4 w-full max-w-xs px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white text-slate-600">
                        <option value="">-- Pilih Kategori (Opsional) --</option>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= esc($cat['id']) ?>"><?= esc($cat['name']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>

                    <label class="cursor-pointer bg-slate-100 text-slate-700 px-6 py-2 rounded-full text-xs font-bold hover:bg-slate-200 transition-colors">
                        Pilih File
                        <input type="file" name="frame_file" accept="image/png,image/jpeg" class="hidden"/>
                    </label>
                    <button type="submit" class="mt-4 bg-primary text-white px-6 py-2 rounded-full text-xs font-bold hover:opacity-90 transition-opacity">
                        Upload Frame
                    </button>
                </form>

                <!-- Panduan Aset -->
                <div class="bg-white border border-slate-200 rounded-xl p-6">
                    <h4 class="font-h2 text-body-base font-bold mb-4">Panduan Aset</h4>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-blue-500 text-[18px]">check_circle</span>
                            <div>
                                <p class="text-xs font-bold text-on-surface">Aspek Rasio</p>
                                <p class="text-[11px] text-slate-500">Pastikan frame memiliki rasio 4:6 atau 1:1.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-blue-500 text-[18px]">check_circle</span>
                            <div>
                                <p class="text-xs font-bold text-on-surface">Transparansi</p>
                                <p class="text-[11px] text-slate-500">Area tengah harus transparan (Alpha Channel).</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-blue-500 text-[18px]">check_circle</span>
                            <div>
                                <p class="text-xs font-bold text-on-surface">Optimasi</p>
                                <p class="text-[11px] text-slate-500">Ukuran file maksimal 2MB per frame.</p>
                            </div>
                        </li>
                    </ul>
                </div>
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
    function showPreviewModal(id, name, category, filePath, uploadedAt) {
        const modal = document.getElementById('previewModal');
        const content = document.getElementById('previewModalContent');
        
        document.getElementById('modalFrameId').textContent = id;
        document.getElementById('modalFrameName').textContent = name;
        document.getElementById('modalFrameCategory').textContent = category;
        document.getElementById('modalFrameUploaded').textContent = uploadedAt;
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
</script>

<?php $this->endSection(); ?>
