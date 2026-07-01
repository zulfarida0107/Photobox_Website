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
                <a href="<?= base_url('admin/packages/export') ?>"
                   class="flex items-center gap-2 border border-blue-700 text-blue-700 bg-white px-4 py-2 rounded font-label-bold text-label-bold hover:bg-blue-50 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">cloud_download</span>
                    Ekspor Laporan
                </a>
            </div>
        </div>

        <!-- Flash messages (handled globally in main.php) -->

        <div class="grid grid-cols-12 gap-6">

            <!-- Quick Stats Row -->
            <div class="col-span-12 grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <!-- Total Paket -->
                <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">inventory_2</span>
                    </div>
                    <p class="text-sm font-medium text-slate-500">Total Paket</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1"><?= str_pad($statTotalPackages ?? 0, 2, '0', STR_PAD_LEFT) ?></h3>
                </div>
                <!-- Opsi Cetak Aktif -->
                <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">print</span>
                    </div>
                    <p class="text-sm font-medium text-slate-500">Opsi Cetak Aktif</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1"><?= str_pad($statTotalPrintOptions ?? 0, 2, '0', STR_PAD_LEFT) ?></h3>
                </div>
                <!-- Rata-rata Harga -->
                <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">payments</span>
                    </div>
                    <p class="text-sm font-medium text-slate-500">Rata-rata Harga Paket</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1">
                        Rp <?= number_format(($statAvgPrice ?? 0) / 1000, 0) ?>k
                    </h3>
                </div>
                <!-- Paket Terpopuler -->
                <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">star</span>
                        <span class="bg-blue-100 text-blue-700 text-[10px] px-2 py-0.5 rounded-full font-bold">TOP</span>
                    </div>
                    <p class="text-sm font-medium text-slate-500">Paket Terpopuler</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1"><?= esc($statTopPackage ?? 'N/A') ?></h3>
                </div>
            </div>

            <!-- Tabel Paket Layanan -->
            <div class="col-span-7 bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden flex flex-col">
                <!-- Header Card -->
                <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-white">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-6 bg-[#0047a0] rounded-full"></div>
                        <h4 class="font-h2 text-h2 text-slate-800">Daftar Paket Layanan</h4>
                    </div>
                    <a href="<?= base_url('admin/packages/create') ?>"
                       class="flex items-center gap-2 bg-[#0047a0] text-white px-4 py-2 rounded font-label-bold text-sm hover:opacity-90 transition-opacity shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                        Tambah Paket Baru
                    </a>
                </div>
                <!-- Action Bar -->
                <div class="p-4 border-b border-slate-100 flex gap-4 bg-white">
                    <form method="get" action="<?= base_url('admin/packages') ?>" class="flex-1">
                        <input type="hidden" name="opt_page" value="<?= esc($currentOptPage ?? 1) ?>">
                        <input type="hidden" name="q_opt" value="<?= esc($keywordOpt ?? '') ?>">
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[18px] text-slate-400">search</span>
                            <input name="q" value="<?= esc($keyword ?? '') ?>"
                                   class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"
                                   placeholder="Cari nama paket..." type="text"/>
                        </div>
                    </form>
                    <div class="w-48 relative">
                        <select class="w-full appearance-none bg-white border border-slate-200 text-slate-600 py-2 pl-4 pr-10 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <option>Semua Kategori</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-[18px] text-slate-400 pointer-events-none">expand_more</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white border-b border-slate-100 text-slate-400 text-[11px] font-bold uppercase tracking-wider">
                                <th class="px-6 py-4">ID PAKET</th>
                                <th class="px-6 py-4">NAMA PAKET</th>
                                <th class="px-6 py-4 text-center">DURASI</th>
                                <th class="px-6 py-4 text-center">HARGA</th>
                                <th class="px-6 py-4 text-center">STATUS</th>
                                <th class="px-6 py-4 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if (!empty($packages)): ?>
                                <?php foreach ($packages as $pkg): ?>
                                    <tr class="bg-white hover:bg-slate-50 transition-colors group">
                                        <td class="px-6 py-4 text-sm text-slate-500 font-mono"><?= esc($pkg['id']) ?></td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-800 text-sm"><?= esc($pkg['name']) ?></div>
                                            <div class="mt-1">
                                                <span class="inline-block bg-slate-100 text-slate-600 text-[10px] px-2 py-0.5 rounded-full font-medium">
                                                    <?= esc($pkg['photo_count']) ?> Foto
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-sm font-medium text-slate-600">
                                                <?= esc($pkg['duration']) ?> Menit
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="font-bold text-slate-800 text-sm">
                                                Rp <?= number_format($pkg['base_price'], 0, ',', '.') ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-block bg-green-100 text-green-700 text-[10px] px-3 py-1 rounded-full font-bold">
                                                Aktif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center space-x-1">
                                            <button type="button" class="inline-block text-emerald-500 hover:bg-emerald-50 p-1.5 rounded transition-colors" title="Lihat">
                                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                                            </button>
                                            <a href="<?= base_url('admin/packages/' . $pkg['id'] . '/edit') ?>"
                                               class="inline-block text-blue-600 hover:bg-blue-50 p-1.5 rounded transition-colors" title="Edit">
                                                <span class="material-symbols-outlined text-[20px]">edit</span>
                                            </a>
                                            <button type="button" class="inline-block text-slate-400 hover:bg-slate-50 p-1.5 rounded transition-colors" title="Non-aktifkan">
                                                <span class="material-symbols-outlined text-[20px]">toggle_on</span>
                                            </button>
                                            <button type="button"
                                                    class="inline-block text-red-500 hover:bg-red-50 p-1.5 rounded transition-colors" title="Hapus"
                                                    onclick="openUniversalDeleteModal('<?= base_url('admin/packages/' . $pkg['id'] . '/delete') ?>', '<?= esc($pkg['name'], 'js') ?>', 'Hapus Paket?')">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-400">
                                        Belum ada paket. <a href="<?= base_url('admin/packages/create') ?>" class="text-primary font-semibold">Tambah sekarang</a>.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Paket -->
                <div class="p-4 bg-white text-slate-500 text-xs flex justify-between items-center border-t border-slate-100">
                    <span>Menampilkan <?= count($packages ?? []) ?> dari <?= $totalPackages ?? 0 ?> paket</span>
                    <div class="flex gap-1">
                        <a href="<?= base_url('admin/packages?page=' . max(1, ($currentPkgPage ?? 1) - 1) . '&q=' . ($keyword ?? '') . '&opt_page=' . ($currentOptPage ?? 1) . '&q_opt=' . ($keywordOpt ?? '')) ?>"
                           class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentPkgPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                            <span class="material-symbols-outlined text-sm">chevron_left</span>
                        </a>
                        <?php for ($i = 1; $i <= ($totalPkgPages ?? 1); $i++): ?>
                            <a href="<?= base_url('admin/packages?page=' . $i . '&q=' . ($keyword ?? '') . '&opt_page=' . ($currentOptPage ?? 1) . '&q_opt=' . ($keywordOpt ?? '')) ?>"
                               class="w-8 h-8 flex items-center justify-center border rounded text-sm font-bold transition-colors <?= $i === ($currentPkgPage ?? 1) ? 'border-[#0047a0] bg-[#0047a0] text-white' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                        <a href="<?= base_url('admin/packages?page=' . min(($totalPkgPages ?? 1), ($currentPkgPage ?? 1) + 1) . '&q=' . ($keyword ?? '') . '&opt_page=' . ($currentOptPage ?? 1) . '&q_opt=' . ($keywordOpt ?? '')) ?>"
                           class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentPkgPage ?? 1) >= ($totalPkgPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                            <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tabel Opsi Cetak -->
            <div class="col-span-5 bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden flex flex-col">
                <!-- Header Card -->
                <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-white">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-6 bg-[#0047a0] rounded-full"></div>
                        <h4 class="font-h2 text-h2 text-slate-800">Opsi Cetak</h4>
                    </div>
                    <a href="<?= base_url('admin/print-options/create') ?>"
                       class="flex items-center gap-2 bg-[#0047a0] text-white px-4 py-2 rounded font-label-bold text-sm hover:opacity-90 transition-opacity shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">print</span>
                        Tambah Opsi
                    </a>
                </div>
                <!-- Action Bar -->
                <div class="p-4 border-b border-slate-100 flex gap-4 bg-white">
                    <form method="get" action="<?= base_url('admin/packages') ?>" class="flex-1">
                        <input type="hidden" name="page" value="<?= esc($currentPkgPage ?? 1) ?>">
                        <input type="hidden" name="q" value="<?= esc($keyword ?? '') ?>">
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[18px] text-slate-400">search</span>
                            <input name="q_opt" value="<?= esc($keywordOpt ?? '') ?>"
                                   class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"
                                   placeholder="Cari opsi cetak..." type="text"/>
                        </div>
                    </form>
                    <div class="w-48 relative">
                        <select class="w-full appearance-none bg-white border border-slate-200 text-slate-600 py-2 pl-4 pr-10 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <option>Semua Status</option>
                            <option>Aktif</option>
                            <option>Non-Aktif</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-[18px] text-slate-400 pointer-events-none">expand_more</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white border-b border-slate-100 text-slate-400 text-[11px] font-bold uppercase tracking-wider">
                                <th class="px-6 py-4">ID OPSI</th>
                                <th class="px-6 py-4">NAMA OPSI</th>
                                <th class="px-6 py-4 text-center">HARGA TAMBAHAN</th>
                                <th class="px-6 py-4 text-center">STATUS</th>
                                <th class="px-6 py-4 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if (!empty($printOptions)): ?>
                                <?php foreach ($printOptions as $opt): ?>
                                    <tr class="bg-white hover:bg-slate-50 transition-colors group">
                                        <td class="px-6 py-4 text-sm text-slate-500 font-mono"><?= esc($opt['id']) ?></td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-800 text-sm"><?= esc($opt['name'] ?? 'Opsi Cetak') ?></div>
                                            <div class="mt-1">
                                                <span class="inline-block bg-slate-100 text-slate-600 text-[10px] px-2 py-0.5 rounded-full font-medium">
                                                    <?= esc($opt['copies']) ?> Lembar
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="font-bold text-slate-800 text-sm">
                                                Rp <?= number_format(($opt['extra_price'] ?? 0), 0, ',', '.') ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-block bg-green-100 text-green-700 text-[10px] px-3 py-1 rounded-full font-bold">
                                                Aktif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center space-x-1">
                                            <button type="button" class="inline-block text-emerald-500 hover:bg-emerald-50 p-1.5 rounded transition-colors" title="Lihat">
                                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                                            </button>
                                            <a href="<?= base_url('admin/print-options/' . $opt['id'] . '/edit') ?>"
                                               class="inline-block text-blue-600 hover:bg-blue-50 p-1.5 rounded transition-colors" title="Edit">
                                                <span class="material-symbols-outlined text-[20px]">edit</span>
                                            </a>
                                            <button type="button" class="inline-block text-slate-400 hover:bg-slate-50 p-1.5 rounded transition-colors" title="Non-aktifkan">
                                                <span class="material-symbols-outlined text-[20px]">toggle_on</span>
                                            </button>
                                            <button type="button" class="inline-block text-red-500 hover:bg-red-50 p-1.5 rounded transition-colors" title="Hapus"
                                                    onclick="openUniversalDeleteModal('<?= base_url('admin/print-options/' . $opt['id'] . '/delete') ?>', '<?= esc($opt['name'] ?? ('Opsi ' . $opt['copies'] . ' Lembar'), 'js') ?>', 'Hapus Opsi Cetak?')">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-400">
                                        Belum ada opsi cetak.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Opsi Cetak -->
                <div class="p-4 bg-white text-slate-500 text-xs flex justify-between items-center border-t border-slate-100">
                    <span>Menampilkan <?= count($printOptions ?? []) ?> dari <?= $totalPrintOptions ?? 0 ?> opsi cetak</span>
                    <div class="flex gap-1">
                        <a href="<?= base_url('admin/packages?opt_page=' . max(1, ($currentOptPage ?? 1) - 1) . '&q_opt=' . ($keywordOpt ?? '') . '&page=' . ($currentPkgPage ?? 1) . '&q=' . ($keyword ?? '')) ?>"
                           class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentOptPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                            <span class="material-symbols-outlined text-sm">chevron_left</span>
                        </a>
                        <?php for ($i = 1; $i <= ($totalOptPages ?? 1); $i++): ?>
                            <a href="<?= base_url('admin/packages?opt_page=' . $i . '&q_opt=' . ($keywordOpt ?? '') . '&page=' . ($currentPkgPage ?? 1) . '&q=' . ($keyword ?? '')) ?>"
                               class="w-8 h-8 flex items-center justify-center border rounded text-sm font-bold transition-colors <?= $i === ($currentOptPage ?? 1) ? 'border-[#0047a0] bg-[#0047a0] text-white' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                        <a href="<?= base_url('admin/packages?opt_page=' . min(($totalOptPages ?? 1), ($currentOptPage ?? 1) + 1) . '&q_opt=' . ($keywordOpt ?? '') . '&page=' . ($currentPkgPage ?? 1) . '&q=' . ($keyword ?? '')) ?>"
                           class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentOptPage ?? 1) >= ($totalOptPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
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
                    <button type="button" onclick="Swal.fire('Fitur Mendatang', 'Fitur pembuatan campaign promosi sedang dalam tahap pengembangan.', 'info')" class="mt-8 bg-white text-blue-700 font-label-bold text-label-bold py-2 rounded shadow-lg hover:bg-slate-50 transition-colors">
                        Buat Campaign
                    </button>
                </div>
                <!-- Layout Preview -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm col-span-2 flex items-center gap-6">
                    <div class="w-32 h-32 rounded-xl overflow-hidden flex-shrink-0 bg-white border border-slate-100 flex items-center justify-center p-2">
                        <img alt="Package Visual Preview"
                             class="w-full h-full object-contain"
                             src="<?= base_url('assets/images/blank_photo_strip_white.png') ?>"/>
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



<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php $this->endSection(); ?>
