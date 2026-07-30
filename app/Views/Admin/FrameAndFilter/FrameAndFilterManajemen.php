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
            <div id="flash-message-success" class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm font-medium flex items-center gap-2">
                <span class="material-symbols-outlined text-green-500">check_circle</span>
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div id="flash-message-error" class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm font-medium flex items-center gap-2">
                <span class="material-symbols-outlined text-red-500">error</span>
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>
        
        <script>
            setTimeout(() => {
                const ids = ['flash-message-success', 'flash-message-error'];
                ids.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.remove();
                });
            }, 1000);
        </script>

        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
            <div>
                <h2 class="font-h1 text-h1 text-on-surface">Manajemen Aset Desain</h2>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Kelola aset desain seperti frame foto dan filter warna.</p>
            </div>
            <button type="button" onclick="openSyncConfirmModal()" class="bg-primary text-white px-4 py-2 rounded-lg text-xs font-bold flex items-center gap-2 hover:bg-blue-800 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[16px]">sync</span>
                Sinkronisasi Seluruh Aset
            </button>
        </div>

        <!-- Bento Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Total Frame -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">wallpaper</span>
                </div>
                <p class="text-xs font-medium text-slate-500">Total Frame</p>
                <h3 class="text-xl font-black text-slate-900 mt-1"><?= esc($statTotalFrames ?? 0) ?></h3>
            </div>
            <!-- Frame Bulan Ini -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">event</span>
                </div>
                <p class="text-xs font-medium text-slate-500">Frame Bulan Ini</p>
                <h3 class="text-xl font-black text-slate-900 mt-1"><?= esc($statFramesThisMonth ?? 0) ?></h3>
            </div>
            <!-- Total Layout -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">grid_view</span>
                </div>
                <p class="text-xs font-medium text-slate-500">Total Layout</p>
                <h3 class="text-xl font-black text-slate-900 mt-1"><?= esc($statTotalLayouts ?? 0) ?></h3>
            </div>
        </div>

                                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
 
            <div class="lg:col-span-12 flex flex-col h-full">
<!-- Koleksi Frame -->
                <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm flex flex-col h-full">
                    <div class="p-6 flex justify-between items-center bg-white border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-1 h-6 bg-primary rounded-full"></div>
                            <h4 class="font-bold text-lg text-slate-800">Daftar Koleksi Frame</h4>
                        </div>
                        <a href="<?= base_url('admin/frames/create') ?>"
                           class="bg-primary text-white px-4 py-2 rounded-lg text-xs font-bold flex items-center gap-2 hover:bg-blue-800 transition-colors">
                            <span class="material-symbols-outlined text-[16px]">add_photo_alternate</span>
                            Tambah Frame
                        </a>
                    </div>
                    
                    <!-- Search & Filter Bar -->
                    <div class="p-6 bg-white flex flex-col sm:flex-row gap-3">
                        <form method="get" action="<?= base_url('admin/frames') ?>" class="flex flex-col sm:flex-row flex-1 gap-3">
                            <!-- Search Input -->
                            <div class="relative flex-1">
                                <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[20px]">search</span>
                                <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="Cari nama frame..."
                                       class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white transition-all"/>
                            </div>
                            
                            <!-- Category Filter -->
                            <select name="category" onchange="this.form.submit()"
                                    class="px-4 py-2 w-full sm:w-64 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white transition-all text-slate-600 font-medium cursor-pointer">
                                <option value="">Semua Kategori</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= esc($cat['id']) ?>" <?= ($selectedCategory ?? '') === $cat['id'] ? 'selected' : '' ?>>
                                            <?= esc($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            
                            <!-- Reset button -->
                            <?php if (!empty($search) || !empty($selectedCategory)): ?>
                                <a href="<?= base_url('admin/frames') ?>" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-lg flex items-center transition-colors">
                                    Reset
                                </a>
                            <?php endif; ?>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-center">
                            <thead class="border-y border-slate-100 bg-white">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-center">ID Frame</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-center">Nama Frame</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-center">Layout</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-center">Kategori</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-center">Preview</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 bg-white">
                                <?php if (!empty($frames)): ?>
                                    <?php foreach ($frames as $frame): ?>
                                        <tr class="hover:bg-slate-50 transition-colors group">
                                            <td class="px-6 py-4 font-mono text-xs font-semibold text-slate-900">#<?= esc($frame['id']) ?></td>
                                            <td class="px-6 py-4 text-sm font-semibold text-slate-700"><?= esc($frame['name']) ?></td>
                                            <td class="px-6 py-4 text-xs font-medium text-slate-600">
                                                <?= esc($frame['layout_name'] ?? 'Tanpa Layout') ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2 py-1 rounded bg-blue-50 text-blue-700 text-[10px] font-bold tracking-wide uppercase">
                                                    <?= esc($frame['category_name'] ?? 'Uncategorized') ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 flex justify-center">
                                                <div class="w-14 h-9 rounded border border-slate-200 bg-slate-100 overflow-hidden shadow-sm">
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
                                            <td class="px-6 py-4 align-middle">
                                                <div class="flex justify-center items-center gap-2">
                                                    <button type="button"
                                                            class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded transition-colors" title="Lihat Preview"
                                                            onclick="showPreviewModal('<?= esc($frame['id'], 'js') ?>', '<?= esc($frame['name'], 'js') ?>', '<?= esc($frame['category_name'] ?? 'Uncategorized', 'js') ?>', '<?= base_url($frame['file_path']) ?>', '<?= date('d M Y, H:i', strtotime($frame['created_at'])) ?>')">
                                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                                    </button>
                                                    <a href="<?= base_url('admin/frames/' . $frame['id'] . '/edit') ?>"
                                                       class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                                    </a>
                                                    <form action="<?= base_url('admin/frames/' . $frame['id'] . '/toggle') ?>" method="post" class="inline">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="transition-transform hover:scale-110" title="Toggle Status Aktif">
                                                            <span class="material-symbols-outlined text-[24px] <?= $frame['is_active'] ? 'text-green-500' : 'text-slate-300' ?>">
                                                                <?= $frame['is_active'] ? 'toggle_on' : 'toggle_off' ?>
                                                            </span>
                                                        </button>
                                                    </form>
                                                    <button type="button"
                                                            class="p-1.5 text-red-500 hover:bg-red-50 rounded transition-colors" title="Hapus"
                                                            onclick="openUniversalDeleteModal('<?= base_url('admin/frames/' . $frame['id'] . '/delete') ?>', 'Apakah Anda yakin ingin menghapus frame ini?<br>Tindakan ini tidak dapat dibatalkan.')">
                                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-400">
                                            Belum ada frame. <a href="<?= base_url('admin/frames/create') ?>" class="text-primary font-bold">Unggah sekarang</a>.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Frame -->
                    <div class="mt-auto p-6 bg-white border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-slate-500 font-medium">
                            Menampilkan <?= count($frames ?? []) ?> dari <?= $totalFrames ?? 0 ?> Frame
                        </span>
                        <div class="flex gap-2">
                            <a href="<?= base_url('admin/frames?page=' . max(1, ($currentFramePage ?? 1) - 1) . '&search=' . urlencode($search ?? '') . '&category=' . urlencode($selectedCategory ?? '')) ?>"
                               class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentFramePage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                                <span class="material-symbols-outlined text-sm">chevron_left</span>
                            </a>
                            <?php 
                                $startP = max(1, ($currentFramePage ?? 1) - 2);
                                $endP = min(($totalFramePages ?? 1), ($currentFramePage ?? 1) + 2);
                                if ($endP - $startP < 4) {
                                    if ($startP == 1) $endP = min(($totalFramePages ?? 1), 5);
                                    if ($endP == ($totalFramePages ?? 1)) $startP = max(1, ($totalFramePages ?? 1) - 4);
                                }
                                for($p = $startP; $p <= $endP; $p++):
                            ?>
                                <?php if($p == ($currentFramePage ?? 1)): ?>
                                    <div class="w-8 h-8 flex items-center justify-center bg-primary text-white font-bold text-xs rounded-lg shadow-sm">
                                        <?= $p ?>
                                    </div>
                                <?php else: ?>
                                    <a href="<?= base_url('admin/frames?page=' . $p . '&search=' . urlencode($search ?? '') . '&category=' . urlencode($selectedCategory ?? '')) ?>" class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-500 font-bold text-xs">
                                        <?= $p ?>
                                    </a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <a href="<?= base_url('admin/frames?page=' . min(($totalFramePages ?? 1), ($currentFramePage ?? 1) + 1) . '&search=' . urlencode($search ?? '') . '&category=' . urlencode($selectedCategory ?? '')) ?>"
                               class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentFramePage ?? 1) >= ($totalFramePages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                                <span class="material-symbols-outlined text-sm">chevron_right</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Layout Frame -->
            <div class="lg:col-span-12 mt-2">
                <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm flex flex-col">
                    <div class="p-6 flex justify-between items-center bg-white border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-1 h-6 bg-primary rounded-full"></div>
                            <h4 class="font-bold text-lg text-slate-800">Daftar Layout Frame</h4>
                        </div>
                        <button type="button" onclick="openAddLayoutModal()"
                                class="bg-primary text-white px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5 hover:bg-blue-800 transition-colors whitespace-nowrap">
                            <span class="material-symbols-outlined text-[16px]">grid_on</span>
                            Tambah Layout
                        </button>
                    </div>
                    
                    <div class="p-6 bg-white flex flex-col sm:flex-row gap-3">
                        <form method="get" action="<?= base_url('admin/frames') ?>" class="flex flex-col sm:flex-row flex-1 gap-3">
                            <div class="relative flex-1">
                                <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[20px]">search</span>
                                <input type="text" name="layout_search" value="<?= esc($layoutSearch ?? '') ?>" placeholder="Cari ID atau nama layout..."
                                       class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white transition-all"/>
                            </div>
                            <?php if (!empty($layoutSearch)): ?>
                                <a href="<?= base_url('admin/frames') ?>" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-lg flex items-center transition-colors">
                                    Reset
                                </a>
                            <?php endif; ?>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-center">
                            <thead class="border-y border-slate-100 bg-white">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-center">ID Layout</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-center">Diagram Preview</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-center">Nama Layout</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-center">Jumlah Foto</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-center">Rasio</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 bg-white">
                                <?php if (!empty($layouts)): ?>
                                    <?php foreach ($layouts as $ly): ?>
                                        <tr class="hover:bg-slate-50 transition-colors group">
                                            <td class="px-6 py-4 font-mono text-xs font-semibold text-slate-900">#<?= esc($ly['id']) ?></td>
                                            <td class="px-6 py-4 flex justify-center">
                                                <div class="w-12 h-16 rounded border border-slate-200 bg-slate-50 overflow-hidden shadow-sm flex items-center justify-center p-1">
                                                    <?php if (!empty($ly['image_path'])): ?>
                                                        <img alt="<?= esc($ly['name']) ?> diagram"
                                                             class="max-w-full max-h-full object-contain"
                                                             src="<?= base_url($ly['image_path']) ?>"/>
                                                    <?php else: ?>
                                                        <span class="material-symbols-outlined text-slate-300 text-lg">grid_on</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm font-semibold text-slate-700"><?= esc($ly['name']) ?></td>
                                            <td class="px-6 py-4 text-xs font-bold text-blue-600">
                                                <span class="px-2.5 py-1 bg-blue-50 border border-blue-100 rounded-full">
                                                    <?= esc($ly['photo_count']) ?> Foto
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-xs font-mono font-medium text-slate-600">
                                                <?= esc($ly['aspect_ratio'] ?? '1:1') ?>
                                            </td>
                                            <td class="px-6 py-4 align-middle">
                                                <div class="flex justify-center items-center gap-2">
                                                    <button type="button"
                                                            class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded transition-colors" title="Lihat Diagram Layout"
                                                            onclick="showPreviewLayoutModal('<?= esc($ly['id'], 'js') ?>', '<?= esc($ly['name'], 'js') ?>', '<?= esc($ly['photo_count'], 'js') ?>', '<?= esc($ly['aspect_ratio'] ?? '1:1', 'js') ?>', '<?= !empty($ly['image_path']) ? base_url($ly['image_path']) : '' ?>')">
                                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                                    </button>
                                                    <button type="button"
                                                            onclick="openEditLayoutModal('<?= esc($ly['id'], 'js') ?>', '<?= esc($ly['name'], 'js') ?>', '<?= esc($ly['photo_count'], 'js') ?>', '<?= esc($ly['aspect_ratio'] ?? '', 'js') ?>', '<?= esc($ly['slot_coordinates'] ?? '', 'js') ?>', '<?= !empty($ly['image_path']) ? base_url($ly['image_path']) : '' ?>')"
                                                            class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit Layout">
                                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                                    </button>
                                                    <button type="button" class="p-1.5 text-red-500 hover:bg-red-50 rounded transition-colors" title="Hapus Layout"
                                                            onclick="openUniversalDeleteModal('<?= base_url('admin/layouts/' . $ly['id'] . '/delete') ?>', 'Apakah Anda yakin ingin menghapus layout ini?<br>Frame yang menggunakan layout ini akan diubah menjadi tanpa layout.')">
                                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-400">
                                            Belum ada layout frame.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Kategori -->
                    <div class="mt-auto p-4 bg-white border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-slate-500 font-medium">
                            Menampilkan <?= count($layouts ?? []) ?> dari <?= $totalLayouts ?? 0 ?> Layout
                        </span>
                        <div class="flex gap-2">
                            <a href="<?= base_url('admin/frames?layout_page=' . max(1, ($currentLayoutPage ?? 1) - 1)) ?>"
                               class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentLayoutPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                                <span class="material-symbols-outlined text-sm">chevron_left</span>
                            </a>
                            <?php 
                                $startP = max(1, ($currentLayoutPage ?? 1) - 2);
                                $endP = min(($totalLayoutPages ?? 1), ($currentLayoutPage ?? 1) + 2);
                                if ($endP - $startP < 4) {
                                    if ($startP == 1) $endP = min(($totalLayoutPages ?? 1), 5);
                                    if ($endP == ($totalLayoutPages ?? 1)) $startP = max(1, ($totalLayoutPages ?? 1) - 4);
                                }
                                for($p = $startP; $p <= $endP; $p++):
                            ?>
                                <?php if($p == ($currentLayoutPage ?? 1)): ?>
                                    <div class="w-8 h-8 flex items-center justify-center bg-primary text-white font-bold text-xs rounded-lg shadow-sm">
                                        <?= $p ?>
                                    </div>
                                <?php else: ?>
                                    <a href="<?= base_url('admin/frames?layout_page=' . $p) ?>" class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-500 font-bold text-xs">
                                        <?= $p ?>
                                    </a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <a href="<?= base_url('admin/frames?layout_page=' . min(($totalLayoutPages ?? 1), ($currentLayoutPage ?? 1) + 1)) ?>"
                               class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentLayoutPage ?? 1) >= ($totalLayoutPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                                <span class="material-symbols-outlined text-sm">chevron_right</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panduan Aset -->
            <div class="lg:col-span-6 flex flex-col h-full">
                <div class="p-6 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-800 text-white shadow-sm overflow-hidden relative group h-full flex flex-col justify-between">
                    <div class="relative z-10">
                        <h3 class="text-xl font-black mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined">menu_book</span>
                            Panduan Aset
                        </h3>
                        <ul class="grid grid-cols-1 gap-3.5 mt-4">
                            <li class="flex items-start gap-3 bg-white/10 p-3.5 rounded-xl backdrop-blur-sm border border-white/10">
                                <span class="material-symbols-outlined text-blue-200 mt-0.5 text-[18px]">aspect_ratio</span>
                                <div>
                                    <p class="text-sm font-bold text-white">Aspek Rasio</p>
                                    <p class="text-xs text-blue-100 mt-0.5">Pastikan frame memiliki rasio 4:6 atau 1:1.</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3 bg-white/10 p-3.5 rounded-xl backdrop-blur-sm border border-white/10">
                                <span class="material-symbols-outlined text-blue-200 mt-0.5 text-[18px]">layers_clear</span>
                                <div>
                                    <p class="text-sm font-bold text-white">Transparansi</p>
                                    <p class="text-xs text-blue-100 mt-0.5">Area tengah harus transparan (Alpha Channel).</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3 bg-white/10 p-3.5 rounded-xl backdrop-blur-sm border border-white/10">
                                <span class="material-symbols-outlined text-blue-200 mt-0.5 text-[18px]">compress</span>
                                <div>
                                    <p class="text-sm font-bold text-white">Optimasi</p>
                                    <p class="text-xs text-blue-100 mt-0.5">Ukuran file maksimal 2MB per frame.</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3 bg-white/10 p-3.5 rounded-xl backdrop-blur-sm border border-white/10">
                                <span class="material-symbols-outlined text-blue-200 mt-0.5 text-[18px]">imagesmode</span>
                                <div>
                                    <p class="text-sm font-bold text-white">Format File</p>
                                    <p class="text-xs text-blue-100 mt-0.5">Wajib menggunakan format PNG transparan berkualitas tinggi.</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3 bg-white/10 p-3.5 rounded-xl backdrop-blur-sm border border-white/10">
                                <span class="material-symbols-outlined text-blue-200 mt-0.5 text-[18px]">category</span>
                                <div>
                                    <p class="text-sm font-bold text-white">Kategorisasi</p>
                                    <p class="text-xs text-blue-100 mt-0.5">Selalu tentukan kategori untuk mempermudah pencarian.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-700"></div>
                </div>
            </div>

            <!-- Tabel Kategori Frame -->
            <div class="lg:col-span-6 flex flex-col h-full">
                <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm flex flex-col h-full">
                    <div class="p-6 flex justify-between items-center bg-white border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-1 h-6 bg-primary rounded-full"></div>
                            <h4 class="font-bold text-lg text-slate-800">Daftar Kategori</h4>
                        </div>
                        <button type="button" onclick="openAddCategoryModal()"
                                class="bg-primary text-white px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5 hover:bg-blue-800 transition-colors whitespace-nowrap">
                            <span class="material-symbols-outlined text-[16px]">category</span>
                            Tambah Kategori
                        </button>
                    </div>
                    <div class="p-4 bg-white border-b border-slate-100">
                        <form method="get" action="<?= base_url('admin/frames') ?>" class="w-full">
                            <div class="relative w-full">
                                <span class="material-symbols-outlined absolute left-3 top-2 text-slate-400 text-[18px]">search</span>
                                <input type="text" name="cat_search" value="<?= esc($catSearch ?? '') ?>" placeholder="Cari kategori..."
                                       class="w-full pl-8 pr-3 py-1.5 border border-slate-200 rounded-lg text-xs outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white transition-all"/>
                            </div>
                        </form>
                    </div>
                    <div class="overflow-x-auto flex-grow">
                        <table class="w-full text-center">
                            <thead class="border-y border-slate-100 bg-white">
                                <tr>
                                    <th class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-center">ID</th>
                                    <th class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-center">Nama Kategori</th>
                                    <th class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 bg-white">
                                <?php if (!empty($categoriesPaginated)): ?>
                                    <?php foreach ($categoriesPaginated as $cat): ?>
                                        <tr class="hover:bg-slate-50 transition-colors group">
                                            <td class="px-4 py-3.5 font-mono text-xs font-semibold text-slate-900 text-center">#<?= esc($cat['id']) ?></td>
                                            <td class="px-4 py-3.5 text-xs font-semibold text-slate-700 text-center"><?= esc($cat['name']) ?></td>
                                            <td class="px-4 py-3.5 align-middle">
                                                <div class="flex justify-center items-center gap-2">
                                                    <button type="button" 
                                                            onclick="openEditCategoryModal('<?= esc($cat['id'], 'js') ?>', '<?= esc($cat['name'], 'js') ?>')"
                                                            class="p-1 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                                    </button>
                                                    <button type="button" class="p-1 text-red-500 hover:bg-red-50 rounded transition-colors" title="Hapus Kategori"
                                                            onclick="openUniversalDeleteModal('<?= base_url('admin/categories/' . $cat['id'] . '/delete') ?>', 'Apakah Anda yakin ingin menghapus kategori ini?<br>Semua frame dalam kategori ini akan diubah menjadi uncategorized.')">
                                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="px-4 py-8 text-center text-xs text-slate-400">
                                            Belum ada kategori.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Kategori -->
                    <div class="mt-auto p-4 bg-white border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-slate-500 font-medium">
                            Menampilkan <?= count($categoriesPaginated ?? []) ?> dari <?= $totalCategories ?? 0 ?> Kategori
                        </span>
                        <div class="flex gap-1.5">
                            <a href="<?= base_url('admin/frames?cat_page=' . max(1, ($currentCatPage ?? 1) - 1)) ?>"
                               class="w-7 h-7 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentCatPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                                <span class="material-symbols-outlined text-sm">chevron_left</span>
                            </a>
                            <?php 
                                $startP = max(1, ($currentCatPage ?? 1) - 2);
                                $endP = min(($totalCategoryPages ?? 1), ($currentCatPage ?? 1) + 2);
                                if ($endP - $startP < 4) {
                                    if ($startP == 1) $endP = min(($totalCategoryPages ?? 1), 5);
                                    if ($endP == ($totalCategoryPages ?? 1)) $startP = max(1, ($totalCategoryPages ?? 1) - 4);
                                }
                                for($p = $startP; $p <= $endP; $p++):
                            ?>
                                <?php if($p == ($currentCatPage ?? 1)): ?>
                                    <div class="w-7 h-7 flex items-center justify-center bg-primary text-white font-bold text-[10px] rounded-lg shadow-sm">
                                        <?= $p ?>
                                    </div>
                                <?php else: ?>
                                    <a href="<?= base_url('admin/frames?cat_page=' . $p) ?>" class="w-7 h-7 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-500 font-bold text-[10px]">
                                        <?= $p ?>
                                    </a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <a href="<?= base_url('admin/frames?cat_page=' . min(($totalCategoryPages ?? 1), ($currentCatPage ?? 1) + 1)) ?>"
                               class="w-7 h-7 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentCatPage ?? 1) >= ($totalCategoryPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                                <span class="material-symbols-outlined text-sm">chevron_right</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Custom -->
    <div id="deleteConfirmModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/40 backdrop-blur-sm opacity-0 transition-opacity duration-300">
        <div id="deleteConfirmModalContent" class="bg-white rounded-3xl p-10 max-w-lg w-full mx-4 border border-slate-100 transform scale-95 opacity-0 transition-all duration-300 flex flex-col items-center text-center">
            
            <div class="w-20 h-20 rounded-2xl bg-red-50/70 flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-[32px] text-red-500">delete</span>
            </div>
            
            <h3 class="text-2xl font-black text-slate-900 mb-3">Konfirmasi Hapus</h3>
            <p class="text-[16px] text-slate-500 leading-relaxed mb-8" id="deleteConfirmText">
                Apakah Anda yakin ingin menghapus data ini?<br>Tindakan ini tidak dapat dibatalkan.
            </p>
            
            <div class="flex gap-4 w-full">
                <button onclick="closeDeleteModal()" class="flex-1 py-3.5 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold text-base rounded-2xl transition-all">Batal</button>
                <form id="deleteForm" method="post" action="" class="flex-1">
                    <?= csrf_field() ?>
                    <button type="submit" class="w-full py-3.5 bg-red-600 hover:bg-red-700 text-white font-bold text-base rounded-2xl transition-all shadow-none">Hapus</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Konfirmasi Sinkronisasi Custom -->
    <div id="syncConfirmModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/40 backdrop-blur-sm opacity-0 transition-opacity duration-300">
        <div id="syncConfirmModalContent" class="bg-white rounded-3xl p-10 max-w-lg w-full mx-4 border border-slate-100 transform scale-95 opacity-0 transition-all duration-300 flex flex-col items-center text-center">
            
            <div class="w-20 h-20 rounded-2xl bg-blue-50/70 flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-[32px] text-blue-500">sync</span>
            </div>
            
            <h3 class="text-2xl font-black text-slate-900 mb-3">Sinkronisasi Aset</h3>
            <p class="text-[16px] text-slate-500 leading-relaxed mb-8">
                Mulai sinkronisasi file lokal?<br>Seluruh folder Aset (Frame & Layout) akan dipindai. File baru akan otomatis masuk dan diset nonaktif.
            </p>
            
            <div class="flex gap-4 w-full">
                <button onclick="closeSyncModal()" class="flex-1 py-3.5 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold text-base rounded-2xl transition-all">Batal</button>
                <form method="post" action="<?= base_url('admin/frames/sync') ?>" class="flex-1">
                    <?= csrf_field() ?>
                    <button type="submit" class="w-full py-3.5 bg-primary hover:bg-blue-700 text-white font-bold text-base rounded-2xl transition-all shadow-none">Mulai Sinkronisasi</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        function openSyncConfirmModal() {
            const modal = document.getElementById('syncConfirmModal');
            const content = document.getElementById('syncConfirmModalContent');
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeSyncModal() {
            const modal = document.getElementById('syncConfirmModal');
            const content = document.getElementById('syncConfirmModalContent');
            
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }

        function openUniversalDeleteModal(actionUrl, message = 'Apakah Anda yakin ingin menghapus data ini?<br>Tindakan ini tidak dapat dibatalkan.') {
            const modal = document.getElementById('deleteConfirmModal');
            const content = document.getElementById('deleteConfirmModalContent');
            const form = document.getElementById('deleteForm');
            const textEl = document.getElementById('deleteConfirmText');
            
            form.action = actionUrl;
            textEl.innerHTML = message;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteConfirmModal');
            const content = document.getElementById('deleteConfirmModalContent');
            
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }
    </script>
    <?= $this->include('Admin/layouts/footer') ?>
</main>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="previewModalContent">
        <!-- Modal Header -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-white">
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
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-white">
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
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-white">
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

<!-- Add Layout Modal -->
<div id="addLayoutModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-lg w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="addLayoutModalContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-white">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">grid_view</span>
                Tambah Layout Frame
            </h4>
            <button type="button" onclick="closeAddLayoutModal()" class="text-slate-400 hover:text-slate-600">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form action="<?= base_url('admin/layouts') ?>" method="post" enctype="multipart/form-data" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="layout_id_input" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">ID Layout *</label>
                    <input type="text" name="id" id="layout_id_input" required placeholder="layout_6_custom"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
                </div>
                <div>
                    <label for="layout_name_input" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Layout *</label>
                    <input type="text" name="name" id="layout_name_input" required placeholder="6 Grid Custom"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="photo_count_input" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jumlah Foto *</label>
                    <input type="number" name="photo_count" id="photo_count_input" required value="6" min="1" max="20"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
                </div>
                <div>
                    <label for="aspect_ratio_input" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Rasio Foto</label>
                    <input type="text" name="aspect_ratio" id="aspect_ratio_input" placeholder="2:3"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
                </div>
            </div>
            <div>
                <label for="layout_image_input" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Gambar Diagram Layout (PNG/JPG)</label>
                <input type="file" name="layout_image" id="layout_image_input" accept="image/png,image/jpeg"
                       class="w-full border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-600 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
            </div>

            <div class="flex gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeAddLayoutModal()" class="flex-1 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-sm rounded-lg transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2 bg-primary text-white font-semibold text-sm rounded-lg hover:bg-blue-800 transition-colors shadow-md">
                    Simpan Layout
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Layout Modal -->
<div id="editLayoutModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-lg w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="editLayoutModalContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-white">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">edit</span>
                Edit Layout Frame
            </h4>
            <button type="button" onclick="closeEditLayoutModal()" class="text-slate-400 hover:text-slate-600">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form id="editLayoutForm" action="" method="post" enctype="multipart/form-data" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">ID Layout</label>
                    <input type="text" id="edit_layout_id_display" disabled class="w-full border border-slate-200 rounded-lg px-4 py-2 text-sm bg-slate-100 text-slate-500 font-mono" />
                </div>
                <div>
                    <label for="edit_layout_name_input" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Layout *</label>
                    <input type="text" name="name" id="edit_layout_name_input" required
                           class="w-full border border-slate-200 rounded-lg px-4 py-2 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="edit_photo_count_input" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jumlah Foto *</label>
                    <input type="number" name="photo_count" id="edit_photo_count_input" required min="1" max="20"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
                </div>
                <div>
                    <label for="edit_aspect_ratio_input" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Rasio Foto</label>
                    <input type="text" name="aspect_ratio" id="edit_aspect_ratio_input"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
                </div>
            </div>
            <div>
                <label for="edit_layout_image_input" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ganti Diagram Layout (Opsional)</label>
                <input type="file" name="layout_image" id="edit_layout_image_input" accept="image/png,image/jpeg"
                       class="w-full border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-600 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
            </div>

            <div class="flex gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeEditLayoutModal()" class="flex-1 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-sm rounded-lg transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2 bg-primary text-white font-semibold text-sm rounded-lg hover:bg-blue-800 transition-colors shadow-md">
                    Perbarui Layout
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Preview Layout Diagram Modal -->
<div id="previewLayoutModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="previewLayoutModalContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-white">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">visibility</span>
                Detail &amp; Preview Layout
            </h4>
        </div>
        <div class="p-6 flex flex-col items-center">
            <div class="w-full h-80 bg-slate-50 border border-slate-200 rounded-xl overflow-hidden flex items-center justify-center p-4 mb-5 shadow-inner">
                <img id="modalLayoutImage" src="" alt="Layout Diagram Preview" class="max-w-full max-h-full object-contain drop-shadow" />
            </div>
            <div class="w-full space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-200 text-left text-sm mb-6">
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">ID Layout</span>
                    <span class="font-mono text-slate-800 font-bold" id="modalLayoutId"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">Nama Layout</span>
                    <span class="text-slate-800 font-semibold" id="modalLayoutName"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">Jumlah Foto</span>
                    <span class="text-blue-700 font-bold" id="modalLayoutPhotoCount"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">Rasio Foto</span>
                    <span class="font-mono text-slate-800" id="modalLayoutAspectRatio"></span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold text-slate-500">Koordinat Slot</span>
                    <span class="font-bold text-slate-500" id="modalLayoutCoordinates">Menghitung...</span>
                </div>
            </div>
            <button type="button" onclick="closePreviewLayoutModal()" class="w-full py-2.5 bg-primary hover:bg-blue-800 text-white font-semibold text-sm rounded-lg transition-colors shadow-md">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function openAddLayoutModal() {
        const modal = document.getElementById('addLayoutModal');
        const content = document.getElementById('addLayoutModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeAddLayoutModal() {
        const modal = document.getElementById('addLayoutModal');
        const content = document.getElementById('addLayoutModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function openEditLayoutModal(id, name, photoCount, aspectRatio, slotCoordinates, imagePath) {
        const modal = document.getElementById('editLayoutModal');
        const content = document.getElementById('editLayoutModalContent');
        const form = document.getElementById('editLayoutForm');
        
        form.action = `<?= base_url('admin/layouts') ?>/${id}/update`;
        document.getElementById('edit_layout_id_display').value = id;
        document.getElementById('edit_layout_name_input').value = name;
        document.getElementById('edit_photo_count_input').value = photoCount;
        document.getElementById('edit_aspect_ratio_input').value = aspectRatio;


        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeEditLayoutModal() {
        const modal = document.getElementById('editLayoutModal');
        const content = document.getElementById('editLayoutModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function showPreviewLayoutModal(id, name, photoCount, aspectRatio, imagePath) {
        const modal = document.getElementById('previewLayoutModal');
        const content = document.getElementById('previewLayoutModalContent');
        
        document.getElementById('modalLayoutId').textContent = '#' + id;
        document.getElementById('modalLayoutName').textContent = name;
        document.getElementById('modalLayoutPhotoCount').textContent = photoCount + ' Foto';
        document.getElementById('modalLayoutAspectRatio').textContent = aspectRatio || '1:1';

        const imgEl = document.getElementById('modalLayoutImage');
        const coordEl = document.getElementById('modalLayoutCoordinates');
        coordEl.innerHTML = '<span class="text-slate-400">Menghitung...</span>';

        if (imagePath) {
            imgEl.src = imagePath;
            imgEl.classList.remove('hidden');
            
            // Run Flood Fill Algorithm
            const img = new Image();
            img.crossOrigin = 'Anonymous';
            img.onload = function() {
                const canvas = document.createElement('canvas');
                const w = img.width;
                const h = img.height;
                canvas.width = w;
                canvas.height = h;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, w, h);
                
                try {
                    const imageData = ctx.getImageData(0, 0, w, h);
                    const data = imageData.data;
                    const visited = new Uint8Array(w * h);
                    const holes = [];

                    for (let y = 0; y < h; y++) {
                        for (let x = 0; x < w; x++) {
                            const index = (y * w + x) * 4;
                            const alpha = data[index + 3];
                            const visitedIndex = y * w + x;

                            if (alpha < 50 && !visited[visitedIndex]) {
                                let minX = x;
                                let maxX = x;
                                let minY = y;
                                let maxY = y;

                                const queue = [[x, y]];
                                visited[visitedIndex] = 1;
                                let head = 0;

                                while (head < queue.length) {
                                    const [cx, cy] = queue[head++];

                                    const neighbors = [
                                        [cx + 1, cy],
                                        [cx - 1, cy],
                                        [cx, cy + 1],
                                        [cx, cy - 1]
                                    ];

                                    for (let i = 0; i < neighbors.length; i++) {
                                        const nx = neighbors[i][0];
                                        const ny = neighbors[i][1];

                                        if (nx < 0 || ny < 0 || nx >= w || ny >= h) continue;

                                        const nextIndex = ny * w + nx;
                                        if (visited[nextIndex]) continue;

                                        const pixel = (nextIndex * 4) + 3;
                                        if (data[pixel] < 50) {
                                            visited[nextIndex] = 1;
                                            queue.push([nx, ny]);

                                            minX = Math.min(minX, nx);
                                            maxX = Math.max(maxX, nx);
                                            minY = Math.min(minY, ny);
                                            maxY = Math.max(maxY, ny);
                                        }
                                    }
                                }

                                const wHole = maxX - minX;
                                const hHole = maxY - minY;
                                if (wHole > 10 && hHole > 10) {
                                    holes.push({ x: minX, y: minY, w: wHole, h: hHole });
                                }
                            }
                        }
                    }
                    if (holes.length > 0) {
                        coordEl.innerHTML = '<span class="text-green-600 font-bold">Terdeteksi (' + holes.length + ')</span>';
                    } else {
                        coordEl.innerHTML = '<span class="text-red-500 font-bold">Tidak Terdeteksi</span>';
                    }
                } catch(e) {
                    coordEl.innerHTML = '<span class="text-red-500">Gagal (Error)</span>';
                }
            };
            img.src = imagePath;

        } else {
            imgEl.src = '';
            imgEl.classList.add('hidden');
            coordEl.innerHTML = '<span class="text-slate-400">Tidak ada gambar</span>';
        }

        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closePreviewLayoutModal() {
        const modal = document.getElementById('previewLayoutModal');
        const content = document.getElementById('previewLayoutModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

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
