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
                <a href="<?= base_url('admin/packages/create') ?>"
                   class="flex items-center gap-2 bg-primary text-on-primary px-4 py-2 rounded font-label-bold text-label-bold hover:opacity-90 transition-opacity">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    Tambah Paket Baru
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

            <!-- Tabel Paket Layanan (8 kolom) -->
            <div class="col-span-8 bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden flex flex-col">
                <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-6 bg-primary rounded-full"></div>
                        <h4 class="font-h2 text-h2 text-on-surface">Daftar Paket Layanan</h4>
                    </div>
                    <form method="get" action="<?= base_url('admin/packages') ?>">
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-2 text-[18px] text-slate-400">search</span>
                            <input name="q" value="<?= esc($keyword ?? '') ?>"
                                   class="pl-9 pr-4 py-1.5 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"
                                   placeholder="Cari paket..." type="text"/>
                        </div>
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 text-slate-500 font-label-bold text-label-bold uppercase tracking-wider">
                                <th class="px-6 py-4">ID Paket</th>
                                <th class="px-6 py-4">Nama Paket</th>
                                <th class="px-6 py-4">Foto / Durasi</th>
                                <th class="px-6 py-4 text-right">Harga</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if (!empty($packages)): ?>
                                <?php foreach ($packages as $pkg): ?>
                                    <tr class="hover:bg-blue-50/30 transition-colors group">
                                        <td class="px-6 py-4 font-mono text-xs text-slate-500"><?= esc($pkg['id']) ?></td>
                                        <td class="px-6 py-4 font-bold text-on-surface"><?= esc($pkg['name']) ?></td>
                                        <td class="px-6 py-4 text-body-sm text-slate-500">
                                            <span class="bg-slate-100 px-2 py-1 rounded text-xs font-medium text-slate-600">
                                                <?= esc($pkg['photo_count']) ?> Foto
                                            </span>
                                            <span class="ml-1 bg-slate-100 px-2 py-1 rounded text-xs font-medium text-slate-600">
                                                <?= esc($pkg['duration']) ?> Mnt
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-primary">
                                            Rp <?= number_format($pkg['base_price'], 0, ',', '.') ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <a href="<?= base_url('admin/packages/' . $pkg['id'] . '/edit') ?>"
                                                   class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-md" title="Edit">
                                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                                </a>
                                                <form action="<?= base_url('admin/packages/' . $pkg['id'] . '/delete') ?>" method="post" class="inline">
                                                    <?= csrf_field() ?>
                                                    <button type="button"
                                                            class="p-1.5 text-error hover:bg-error-container rounded-md" title="Hapus"
                                                            onclick="Swal.fire({title: 'Hapus paket ini?', text: 'Tindakan ini mungkin mempengaruhi data lain.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">
                                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-400">
                                        Belum ada paket. <a href="<?= base_url('admin/packages/create') ?>" class="text-primary font-semibold">Tambah sekarang</a>.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Paket -->
                <div class="p-4 bg-slate-50 text-slate-500 text-xs flex justify-between items-center border-t border-slate-100">
                    <span>Menampilkan <?= count($packages ?? []) ?> dari <?= $totalPackages ?? 0 ?> paket</span>
                    <div class="flex gap-1">
                        <?php for ($i = 1; $i <= ($totalPkgPages ?? 1); $i++): ?>
                            <a href="<?= base_url('admin/packages?page=' . $i . '&q=' . ($keyword ?? '')) ?>"
                               class="w-8 h-8 flex items-center justify-center border border-slate-200 rounded text-xs font-bold
                                      <?= $i === ($currentPkgPage ?? 1) ? 'bg-blue-600 text-white border-blue-600' : 'hover:bg-white' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <!-- Tabel Opsi Cetak (4 kolom) -->
            <div class="col-span-4 bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden flex flex-col">
                <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-6 bg-tertiary rounded-full"></div>
                        <h4 class="font-h2 text-h2 text-on-surface">Opsi Cetak</h4>
                    </div>
                    <a href="<?= base_url('admin/print-options/create') ?>"
                       class="text-primary hover:bg-blue-50 p-1 rounded transition-colors" title="Tambah Opsi Cetak">
                        <span class="material-symbols-outlined">add</span>
                    </a>
                </div>
                <div class="flex-1 overflow-y-auto max-h-[440px]">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 text-slate-500 font-label-bold text-label-bold uppercase tracking-wider sticky top-0">
                                <th class="px-5 py-4">Opsi</th>
                                <th class="px-5 py-4 text-center">Copies</th>
                                <th class="px-5 py-4 text-right">Harga</th>
                                <th class="px-5 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if (!empty($printOptions)): ?>
                                <?php foreach ($printOptions as $opt): ?>
                                    <tr class="hover:bg-slate-50 transition-colors group">
                                        <td class="px-5 py-4">
                                            <p class="font-bold text-on-surface text-sm"><?= esc($opt['name'] ?? 'Opsi Cetak') ?></p>
                                            <p class="text-[10px] text-slate-400 font-mono"><?= esc($opt['id']) ?></p>
                                        </td>
                                        <td class="px-5 py-4 text-center">
                                            <span class="text-xs text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full">
                                                <?= esc($opt['copies']) ?> Lbr
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-right font-bold text-on-surface">
                                            Rp <?= number_format(($opt['extra_price'] ?? 0) / 1000, 0) ?>k
                                        </td>
                                        <td class="px-5 py-4 text-center">
                                            <div class="flex justify-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <a href="<?= base_url('admin/print-options/' . $opt['id'] . '/edit') ?>"
                                                   class="p-1 text-blue-600 hover:bg-blue-100 rounded" title="Edit">
                                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                                </a>
                                                <form action="<?= base_url('admin/print-options/' . $opt['id'] . '/delete') ?>" method="post" class="inline">
                                                    <?= csrf_field() ?>
                                                    <button type="button" class="p-1 text-error hover:bg-error-container rounded" title="Hapus"
                                                            onclick="Swal.fire({title: 'Hapus opsi cetak ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">
                                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="px-5 py-8 text-center text-sm text-slate-400">
                                        Belum ada opsi cetak.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- <div class="p-4 text-center bg-blue-50 border-t border-slate-100">
                    <a href="<?= base_url('admin/print-options') ?>" class="text-xs font-bold text-primary hover:underline">
                        Kelola Detail Opsi
                    </a>
                </div> -->
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



<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php $this->endSection(); ?>
