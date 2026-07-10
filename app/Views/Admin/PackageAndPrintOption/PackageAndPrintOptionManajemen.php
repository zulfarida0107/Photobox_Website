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
                    <span class="text-blue-700 font-bold">Manajemen Paket &amp; Cetak</span>
                </li>
            </ol>
        </nav>

        <!-- Hero Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-h1 text-h1 text-on-surface">Manajemen Paket &amp; Cetak</h2>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Konfigurasi produk layanan CuitBox Studio dan variasi opsi cetak tambahan.</p>
            </div>
            <div class="flex gap-3">
                <a href="<?= base_url('admin/packages/export') ?>" onclick="showExportNotif()"
                   class="bg-white border-2 border-blue-600 text-blue-600 px-4 py-2 rounded-lg font-label-bold text-label-bold flex items-center gap-2 hover:bg-blue-50 hover:border-blue-700 hover:text-blue-700 transition-colors shadow-sm whitespace-nowrap">
                    <span class="material-symbols-outlined text-[18px]">cloud_download</span>
                    Ekspor Laporan
                </a>

            </div>
        </div>

        <!-- Flash messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div id="flash-message-success" class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm font-medium flex items-center gap-2">
                <span class="material-symbols-outlined text-green-600">check_circle</span>
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div id="flash-message-error" class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm font-medium flex items-center gap-2">
                <span class="material-symbols-outlined text-red-600">error</span>
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-12 gap-6">

            <!-- Quick Stats Row -->
            <div class="col-span-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Paket -->
                <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-blue-600 bg-blue-50 w-10 h-10 flex items-center justify-center rounded-full material-symbols-outlined text-[18px]">inventory_2</span>
                    </div>
                    <p class="text-sm font-medium text-slate-500">Total Paket</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1"><?= str_pad($statTotalPackages ?? 0, 2, '0', STR_PAD_LEFT) ?></h3>
                </div>
                <!-- Opsi Cetak Aktif -->
                <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-green-600 bg-green-50 w-10 h-10 flex items-center justify-center rounded-full material-symbols-outlined text-[18px]">print</span>
                    </div>
                    <p class="text-sm font-medium text-slate-500">Opsi Cetak Aktif</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1"><?= str_pad($statTotalPrintOptions ?? 0, 2, '0', STR_PAD_LEFT) ?></h3>
                </div>
                <!-- Rata-rata Harga -->
                <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-orange-600 bg-orange-50 w-10 h-10 flex items-center justify-center rounded-full material-symbols-outlined text-[18px]">payments</span>
                    </div>
                    <p class="text-sm font-medium text-slate-500">Rata-rata Harga</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1">
                        Rp <?= number_format(($statAvgPrice ?? 0) / 1000, 0) ?>k
                    </h3>
                </div>
                <!-- Paket Terpopuler -->
                <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-purple-600 bg-purple-50 w-10 h-10 flex items-center justify-center rounded-full material-symbols-outlined text-[18px]">star</span>
                        <span class="bg-purple-100 text-purple-700 text-[10px] px-2 py-0.5 rounded-full font-bold tracking-wider">TOP</span>
                    </div>
                    <p class="text-sm font-medium text-slate-500">Paket Terpopuler</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1"><?= esc($statTopPackage ?? 'N/A') ?></h3>
                </div>
            </div>

            <!-- Tabel Paket Layanan (8 kolom) -->
            <div class="col-span-8 bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden flex flex-col h-full">
                <div class="px-6 pt-5 pb-3 border-b border-slate-100 bg-white">
                    <!-- Judul + Tombol Tambah -->
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-1 h-6 bg-primary rounded-full"></div>
                            <h4 class="font-h2 text-h2 text-on-surface">Daftar Paket Layanan</h4>
                        </div>
                        <a href="<?= base_url('admin/packages/create') ?>" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 hover:bg-blue-800 transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                            Tambah Paket Layanan
                        </a>
                    </div>
                    <!-- Filter & Search langsung di bawah judul -->
                    <form id="pkgFilterForm" method="get" action="<?= base_url('admin/packages') ?>" class="flex flex-wrap gap-2">
                        <input type="hidden" name="opt_page" value="<?= esc($currentOptPage ?? 1) ?>">
                        <input type="hidden" name="sort_opt" value="<?= esc($sortOpt ?? 'termurah') ?>">
                        <div class="relative flex-1 min-w-[180px]">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[20px]">search</span>
                            <input name="q" value="<?= esc($keyword ?? '') ?>"
                                   class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"
                                   placeholder="Cari ID atau Nama Paket..." type="text"
                                   onkeydown="if(event.key==='Enter'){this.form.submit();}"/>
                        </div>
                        <div class="relative">
                            <select name="sort_pkg" onchange="this.form.submit()" class="px-4 py-2 border border-slate-200 rounded-lg text-sm appearance-none outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white cursor-pointer pr-8">
                                <option value="semua" <?= ($sortPkg ?? 'semua') == 'semua' ? 'selected' : '' ?>>Semua Harga</option>
                                <option value="lebih50" <?= ($sortPkg ?? '') == 'lebih50' ? 'selected' : '' ?>>Harga &gt; 50k</option>
                                <option value="kurang50" <?= ($sortPkg ?? '') == 'kurang50' ? 'selected' : '' ?>>Harga &lt; 50k</option>
                            </select>
                        </div>
                        <?php if (!empty($keyword) || ($sortPkg ?? 'terbaru') !== 'terbaru'): ?>
                            <a href="<?= base_url('admin/packages') ?>" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg flex items-center transition-colors" title="Reset">
                                Reset
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-y border-slate-100 bg-white text-slate-500 text-xs font-bold uppercase tracking-wider">
                                <th class="px-6 py-4 font-bold">ID Paket</th>
                                <th class="px-6 py-4 font-bold">Nama Paket</th>
                                <th class="px-6 py-4 font-bold text-center">Foto / Durasi</th>
                                <th class="px-6 py-4 font-bold text-right">Harga</th>
                                <th class="px-6 py-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 bg-white">
                            <?php if (!empty($packages)): ?>
                                <?php foreach ($packages as $pkg): ?>
                                    <tr class="hover:bg-blue-50/30 transition-colors group">
                                        <td class="px-6 py-4 font-mono text-sm text-slate-500"><?= esc($pkg['id']) ?></td>
                                        <td class="px-6 py-4 font-bold text-on-surface"><?= esc($pkg['name']) ?></td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="bg-slate-100 px-2 py-1 rounded-full text-xs font-medium text-slate-600 inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[14px]">photo_camera</span> <?= esc($pkg['photo_count']) ?>
                                            </span>
                                            <span class="ml-1 bg-slate-100 px-2 py-1 rounded-full text-xs font-medium text-slate-600 inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[14px]">timer</span> <?= esc($pkg['duration']) ?>m
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-slate-700">
                                            Rp <?= number_format($pkg['base_price'], 0, ',', '.') ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center gap-2 ">
                                                <a href="<?= base_url('admin/packages/' . $pkg['id'] . '/edit') ?>"
                                                   class="p-2 text-blue-600 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors" title="Edit">
                                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                                </a>
                                                <button type="button" 
                                                        class="p-2 text-red-600 hover:bg-red-50 hover:text-red-700 rounded-lg transition-colors" title="Hapus"
                                                        onclick="openUniversalDeleteModal('<?= base_url('admin/packages/' . $pkg['id'] . '/delete') ?>', '<?= esc($pkg['name'], 'js') ?>', 'Hapus Paket?')">
                                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-400 italic">
                                        Idle / Kosong. <a href="<?= base_url('admin/packages/create') ?>" class="text-primary font-semibold not-italic hover:underline">Tambah sekarang</a>.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Paket -->
                <div class="mt-auto p-6 bg-white text-slate-500 text-xs flex justify-between items-center border-t border-slate-100">
                    <span>Menampilkan <?= count($packages ?? []) ?> dari <?= $totalPackages ?? 0 ?> paket</span>
                    <div class="flex gap-2">
                        <a href="<?= base_url('admin/packages?page=' . max(1, ($currentPkgPage ?? 1) - 1) . '&sort_pkg=' . ($sortPkg ?? '') . '&opt_page=' . ($currentOptPage ?? 1) . '&sort_opt=' . ($sortOpt ?? '') . '&q=' . ($keyword ?? '')) ?>"
                           class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentPkgPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                            <span class="material-symbols-outlined text-sm">chevron_left</span>
                        </a>
                        <?php for ($i = 1; $i <= ($totalPkgPages ?? 1); $i++): ?>
                            <a href="<?= base_url('admin/packages?page=' . $i . '&sort_pkg=' . ($sortPkg ?? '') . '&opt_page=' . ($currentOptPage ?? 1) . '&sort_opt=' . ($sortOpt ?? '') . '&q=' . ($keyword ?? '')) ?>"
                               class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-colors <?= $i === ($currentPkgPage ?? 1) ? 'border border-primary bg-primary text-white' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                        <a href="<?= base_url('admin/packages?page=' . min(($totalPkgPages ?? 1), ($currentPkgPage ?? 1) + 1) . '&sort_pkg=' . ($sortPkg ?? '') . '&opt_page=' . ($currentOptPage ?? 1) . '&sort_opt=' . ($sortOpt ?? '') . '&q=' . ($keyword ?? '')) ?>"
                           class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentPkgPage ?? 1) >= ($totalPkgPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                            <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tabel Opsi Cetak (4 kolom) -->
            <div class="col-span-4 bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden flex flex-col">
                <div class="px-6 pt-5 pb-3 border-b border-slate-100 bg-white">
                    <!-- Judul + Tombol Tambah -->
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-1 h-6 bg-blue-900 rounded-full"></div>
                            <h4 class="font-h2 text-h2 text-on-surface">Daftar Cetak</h4>
                        </div>
                        <a href="<?= base_url('admin/print-options/create') ?>"
                           class="bg-blue-900 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 hover:bg-blue-800 transition-colors shadow-sm whitespace-nowrap">
                            <span class="material-symbols-outlined text-[18px]">print</span>
                            Tambah Cetak
                        </a>
                    </div>
                    <!-- Filter Cetak -->
                    <form id="optFilterForm" method="get" action="<?= base_url('admin/packages') ?>" class="flex gap-2">
                        <input type="hidden" name="q" value="<?= esc($keyword ?? '') ?>">
                        <input type="hidden" name="sort_pkg" value="<?= esc($sortPkg ?? 'terbaru') ?>">
                        <input type="hidden" name="page" value="<?= esc($currentPkgPage ?? 1) ?>">
                        <div class="relative flex-1">
                            <select name="sort_opt" onchange="this.form.submit()" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm appearance-none outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-900 bg-white cursor-pointer pr-8">
                                <option value="semua" <?= ($sortOpt ?? 'semua') == 'semua' ? 'selected' : '' ?>>Semua Harga</option>
                                <option value="lebih50" <?= ($sortOpt ?? '') == 'lebih50' ? 'selected' : '' ?>>Harga &gt; 50k</option>
                                <option value="kurang50" <?= ($sortOpt ?? '') == 'kurang50' ? 'selected' : '' ?>>Harga &lt; 50k</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="flex-1 overflow-y-auto max-h-[440px]">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-y border-slate-100 bg-white text-slate-500 text-xs font-bold uppercase tracking-wider sticky top-0">
                                <th class="px-5 py-4 font-bold">Opsi</th>
                                <th class="px-5 py-4 font-bold text-center">Copies</th>
                                <th class="px-5 py-4 font-bold text-right">Harga</th>
                                <th class="px-5 py-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 bg-white">
                            <?php if (!empty($printOptions)): ?>
                                <?php foreach ($printOptions as $opt): ?>
                                    <tr class="hover:bg-slate-50 transition-colors group">
                                        <td class="px-5 py-4">
                                            <p class="font-bold text-slate-800 text-sm"><?= esc($opt['name'] ?? 'Opsi Cetak') ?></p>
                                            <p class="text-[11px] text-slate-400 font-mono mt-0.5"><?= esc($opt['id']) ?></p>
                                        </td>
                                        <td class="px-5 py-4 text-center">
                                            <span class="text-xs font-medium text-slate-600 bg-slate-100 px-2.5 py-1 rounded-full inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[14px]">file_copy</span> <?= esc($opt['copies']) ?>
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-right font-bold text-slate-700">
                                            Rp <?= number_format(($opt['extra_price'] ?? 0) / 1000, 0) ?>k
                                        </td>
                                        <td class="px-5 py-4 text-center">
                                            <div class="flex justify-center gap-2 ">
                                                <a href="<?= base_url('admin/print-options/' . $opt['id'] . '/edit') ?>"
                                                   class="p-2 text-blue-600 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors" title="Edit">
                                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                                </a>
                                                <button type="button" 
                                                        class="p-2 text-red-600 hover:bg-red-50 hover:text-red-700 rounded-lg transition-colors" title="Hapus"
                                                        onclick="openUniversalDeleteModal('<?= base_url('admin/print-options/' . $opt['id'] . '/delete') ?>', '<?= esc($opt['name'] ?? 'Opsi', 'js') ?>', 'Hapus Opsi Cetak?')">
                                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="px-5 py-8 text-center text-sm text-slate-400 italic">
                                        Idle / Kosong.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Cetak -->
                <div class="mt-auto p-4 bg-white text-slate-500 text-xs flex justify-between items-center border-t border-slate-100">
                    <span>Menampilkan <?= count($printOptions ?? []) ?> dari <?= $totalPrintOptions ?? 0 ?> opsi</span>
                    <div class="flex gap-1.5">
                        <a href="<?= base_url('admin/packages?opt_page=' . max(1, ($currentOptPage ?? 1) - 1) . '&sort_opt=' . ($sortOpt ?? 'termurah') . '&page=' . ($currentPkgPage ?? 1) . '&sort_pkg=' . ($sortPkg ?? 'terbaru') . '&q=' . urlencode($keyword ?? '')) ?>"
                           class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentOptPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                            <span class="material-symbols-outlined text-sm">chevron_left</span>
                        </a>
                        <?php for ($i = 1; $i <= ($totalOptPages ?? 1); $i++): ?>
                            <a href="<?= base_url('admin/packages?opt_page=' . $i . '&sort_opt=' . ($sortOpt ?? 'termurah') . '&page=' . ($currentPkgPage ?? 1) . '&sort_pkg=' . ($sortPkg ?? 'terbaru') . '&q=' . urlencode($keyword ?? '')) ?>"
                               class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-colors <?= $i === ($currentOptPage ?? 1) ? 'border border-blue-900 bg-blue-900 text-white' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                        <a href="<?= base_url('admin/packages?opt_page=' . min(($totalOptPages ?? 1), ($currentOptPage ?? 1) + 1) . '&sort_opt=' . ($sortOpt ?? 'termurah') . '&page=' . ($currentPkgPage ?? 1) . '&sort_pkg=' . ($sortPkg ?? 'terbaru') . '&q=' . urlencode($keyword ?? '')) ?>"
                           class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentOptPage ?? 1) >= ($totalOptPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                            <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Promo & Preview Bento -->
            <div class="col-span-12 grid grid-cols-3 gap-6">
                <!-- Promotion Card -->
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 p-6 rounded-2xl text-white flex flex-col justify-between overflow-hidden relative">
                    <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-8xl text-white/10">auto_awesome</span>
                    <div>
                        <span class="bg-white/20 text-[10px] px-2 py-1 rounded-full font-bold uppercase tracking-widest">Promotion Tip</span>
                        <h4 class="mt-4 text-xl font-bold">Bundling Hemat!</h4>
                        <p class="text-sm text-blue-100 mt-2">Paket dengan durasi &gt; 30 menit cenderung meningkatkan konversi cetak tambahan hingga 25%.</p>
                    </div>
                    <button onclick="openCampaignModal()" class="mt-8 bg-white text-blue-700 font-label-bold text-label-bold py-2 rounded shadow-lg hover:bg-slate-50 transition-colors">
                        Buat Campaign
                    </button>
                </div>
                <!-- Layout Preview -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm col-span-2 flex items-center gap-6">
                    <div class="w-32 h-32 rounded-xl overflow-hidden flex-shrink-0 bg-slate-100">
                        <img alt="Package Visual Preview"
                             class="w-full h-full object-cover"
                             src="https://lh3.googleusercontent.com/aida-public/AB6AXuDsRc_XVNFoiEw0proeJDaYkr962949lBPH5XsK9c-9yDI_rkXV4HcYQNPcjCMsesLXR1FPqzuFDvmNGKNo9esVfOc41VSWOsAkUI5uIMbo-uUtpax_Jx3-1Buz1cZwCY2H356MamH3dIdB4NhMgkxLtd2VKFjV4nHJLMqLewtz07FPzOLL3zGBHZtt9sJCqOTTqtjnVb9EoCWQk8N8gkZwm4bsl6P8lGQrK0zfO1Yz91UIdKP_TFviduQSIbJuRskh6izc6oH1iiNV"/>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-h2 text-h2 text-on-surface">Visualisasi Layout Paket</h4>
                        <p class="text-slate-500 text-body-sm mt-1">Pastikan deskripsi paket mencerminkan hasil cetak akhir. Gunakan template standar untuk semua mesin booth agar hasil konsisten.</p>
                        <div class="mt-4 flex gap-2">
                            <span class="bg-slate-100 text-slate-600 text-[10px] px-2 py-1 rounded font-bold">LAYOUT A-1</span>
                            <span class="bg-slate-100 text-slate-600 text-[10px] px-2 py-1 rounded font-bold">GRID-4</span>
                            <span class="bg-slate-100 text-slate-600 text-[10px] px-2 py-1 rounded font-bold">POSTCARD</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?= $this->include('Admin/layouts/footer') ?>
</main>

<!-- Modal Buat Campaign (Coming Soon) -->
<div id="campaignModal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/40 backdrop-blur-sm opacity-0 transition-opacity duration-300">
    <div id="campaignModalContent" class="bg-white p-10 rounded-3xl shadow-2xl max-w-md w-full scale-95 transition-transform duration-300 relative mx-4 text-center">
        <!-- Dekorasi latar -->
        <div class="absolute inset-0 overflow-hidden rounded-3xl pointer-events-none">
            <div class="absolute -top-8 -right-8 w-48 h-48 bg-blue-50 rounded-full opacity-60"></div>
            <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-purple-50 rounded-full opacity-60"></div>
            <span class="material-symbols-outlined absolute -right-6 -bottom-6 text-9xl text-slate-100/40">auto_awesome</span>
        </div>
        <!-- Konten -->
        <div class="relative">
            <!-- Festive "Segera Hadir" Badge -->
            <div class="mb-6 flex justify-center">
                <span class="bg-gradient-to-r from-blue-600 to-purple-600 text-white text-xs font-black px-4 py-1.5 rounded-lg shadow-md tracking-wider uppercase transform -rotate-2">
                     🎉 Segera Hadir
                </span>
            </div>
            <h3 class="text-2xl font-black text-slate-900 mb-3">Fitur Campaign</h3>
            <p class="text-slate-500 text-sm leading-relaxed mb-6">
                Kami sedang menyiapkan fitur <b>Campaign &amp; Promosi</b> yang memungkinkan Anda membuat penawaran spesial, bundling paket, dan kampanye diskon langsung dari dashboard.
            </p>
            <div class="flex flex-col gap-3 mb-8">
                <div class="flex items-center gap-3 bg-slate-50 rounded-xl p-3 text-left">
                    <span class="material-symbols-outlined text-blue-500 text-[20px]">check_circle</span>
                    <span class="text-sm text-slate-600">Kelola promo &amp; diskon otomatis</span>
                </div>
                <div class="flex items-center gap-3 bg-slate-50 rounded-xl p-3 text-left">
                    <span class="material-symbols-outlined text-blue-500 text-[20px]">check_circle</span>
                    <span class="text-sm text-slate-600">Bundling paket cetak</span>
                </div>
                <div class="flex items-center gap-3 bg-slate-50 rounded-xl p-3 text-left">
                    <span class="material-symbols-outlined text-blue-500 text-[20px]">check_circle</span>
                    <span class="text-sm text-slate-600">Laporan performa kampanye</span>
                </div>
            </div>
            <button onclick="closeCampaignModal()" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 rounded-2xl transition-colors">
                Oke, Ditunggu!
            </button>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toastNotif" class="fixed bottom-6 right-6 z-[300] flex items-center gap-3 bg-slate-900 text-white px-5 py-3 rounded-2xl shadow-2xl opacity-0 translate-y-4 transition-all duration-300 pointer-events-none">
    <span id="toastIcon" class="material-symbols-outlined text-green-400 text-[20px]">check_circle</span>
    <span id="toastMsg" class="text-sm font-semibold">Data berhasil dihapus.</span>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteConfirmModal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-300">
    <div id="deleteConfirmModalContent" class="bg-white rounded-3xl shadow-2xl max-w-xl w-full mx-4 scale-95 transition-transform duration-300 overflow-hidden">
        <!-- Body -->
        <div class="px-10 pt-10 pb-8 text-center">
            <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-6 text-red-500">
                <span class="material-symbols-outlined text-[32px]">delete</span>
            </div>
            <h3 id="deleteConfirmTitle" class="text-xl font-black text-slate-900 mb-3">Konfirmasi Hapus</h3>
            <p id="deleteConfirmMsg" class="text-sm text-slate-500 leading-relaxed mb-8">
                Apakah Anda yakin ingin menghapus item ini?<br>Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex gap-4">
                <button onclick="closeDeleteModal()" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm py-3 rounded-2xl transition-colors">
                    Batal
                </button>
                <form id="deleteForm" method="post" action="" class="flex-1">
                    <?= csrf_field() ?>
                    <button type="submit" id="deleteSubmitBtn" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold text-sm py-3 rounded-2xl transition-colors">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    setTimeout(() => {
        const s = document.getElementById('flash-message-success');
        const e = document.getElementById('flash-message-error');
        if (s) s.remove(); if (e) e.remove();
    }, 1000);

    // Campaign Modal
    const campaignModal = document.getElementById('campaignModal');
    const campaignModalContent = document.getElementById('campaignModalContent');
    function openCampaignModal() {
        campaignModal.classList.remove('hidden');
        campaignModal.classList.add('flex');
        void campaignModal.offsetWidth;
        campaignModal.classList.remove('opacity-0');
        campaignModalContent.classList.remove('scale-95');
        campaignModalContent.classList.add('scale-100');
    }
    function closeCampaignModal() {
        campaignModal.classList.add('opacity-0');
        campaignModalContent.classList.remove('scale-100');
        campaignModalContent.classList.add('scale-95');
        setTimeout(() => { campaignModal.classList.add('hidden'); campaignModal.classList.remove('flex'); }, 300);
    }
    campaignModal.addEventListener('click', e => { if (e.target === campaignModal) closeCampaignModal(); });

    // Toast
    function showToast(message, isSuccess = true) {
        const toast = document.getElementById('toastNotif');
        const icon = document.getElementById('toastIcon');
        const msg = document.getElementById('toastMsg');
        icon.textContent = isSuccess ? 'check_circle' : 'error';
        icon.className = 'material-symbols-outlined text-[20px] ' + (isSuccess ? 'text-green-400' : 'text-red-400');
        msg.textContent = message;
        toast.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
        toast.classList.add('opacity-100', 'translate-y-0');
        setTimeout(() => { toast.classList.add('opacity-0', 'translate-y-4'); toast.classList.remove('opacity-100', 'translate-y-0'); }, 2500);
    }

    // Delete Modal
    const deleteModal = document.getElementById('deleteConfirmModal');
    const deleteModalContent = document.getElementById('deleteConfirmModalContent');
    const deleteForm = document.getElementById('deleteForm');
    function openUniversalDeleteModal(actionUrl, itemName, titleText) {
        titleText = titleText || "Konfirmasi Hapus";
        const msg = document.getElementById("deleteConfirmMsg");
        if (msg) msg.innerHTML = 'Apakah Anda yakin ingin menghapus <b class="text-slate-900">' + itemName + '</b>?<br>Tindakan ini tidak dapat dibatalkan.';
        const t = document.getElementById("deleteConfirmTitle");
        if (t) t.innerText = titleText;
        deleteForm.action = actionUrl;
        deleteModal.classList.remove("hidden"); deleteModal.classList.add("flex");
        void deleteModal.offsetWidth;
        deleteModal.classList.remove("opacity-0");
        deleteModalContent.classList.remove("scale-95"); deleteModalContent.classList.add("scale-100");
    }
    function closeDeleteModal() {
        deleteModal.classList.add('opacity-0');
        deleteModalContent.classList.remove('scale-100'); deleteModalContent.classList.add('scale-95');
        setTimeout(() => { deleteModal.classList.add('hidden'); deleteModal.classList.remove('flex'); }, 300);
    }
    deleteModal.addEventListener('click', e => { if (e.target === deleteModal) closeDeleteModal(); });
    deleteForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('deleteSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">progress_activity</span> Menghapus...';
        fetch(deleteForm.action, { method: 'POST', body: new FormData(deleteForm) })
            .then(() => { closeDeleteModal(); showToast('Data berhasil dihapus.'); setTimeout(() => location.reload(), 1500); })
            .catch(() => { closeDeleteModal(); showToast('Terjadi kesalahan.', false); });
    });
</script>

<?php $this->endSection(); ?>
