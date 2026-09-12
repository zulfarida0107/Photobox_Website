<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<main class="pt-16 flex-1 flex flex-col w-full">
    <div class="p-6 flex-1 flex flex-col w-full">
        <!-- Breadcrumbs -->
        <nav aria-label="Breadcrumb" class="flex mb-6 text-xs font-medium text-slate-500">
            <ol class="flex items-center space-x-2">
                <li>
                    <a class="hover:text-blue-600 flex items-center gap-1 transition-colors" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
                </li>
                <li class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[10px]">chevron_right</span>
                    <span class="text-blue-700 font-bold">Manajemen Cetak</span>
                </li>
            </ol>
        </nav>

        <!-- Hero Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-h1 text-h1 text-on-surface">Manajemen Opsi Cetak</h2>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Konfigurasi produk layanan CuitBox Studio dan variasi opsi cetak tambahan.</p>
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

        <div class="flex flex-col gap-6 w-full flex-1">

            <!-- Quick Stats Row -->
            <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Opsi Cetak Aktif -->
                <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-green-600 bg-green-50 w-10 h-10 flex items-center justify-center rounded-full material-symbols-outlined text-[18px]">print</span>
                    </div>
                    <p class="text-sm font-medium text-slate-500">Opsi Cetak Aktif</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1"><?= str_pad($statTotalPrintOptions ?? 0, 2, '0', STR_PAD_LEFT) ?></h3>
                </div>
                <!-- Rata-rata Harga Tambahan -->
                <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-orange-600 bg-orange-50 w-10 h-10 flex items-center justify-center rounded-full material-symbols-outlined text-[18px]">payments</span>
                    </div>
                    <p class="text-sm font-medium text-slate-500">Rata-rata Harga</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1">
                        Rp <?= number_format(($statAvgPrice ?? 0) / 1000, 0) ?>k
                    </h3>
                </div>
                <!-- Total Transaksi -->
                <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-blue-600 bg-blue-50 w-10 h-10 flex items-center justify-center rounded-full material-symbols-outlined text-[18px]">receipt_long</span>
                    </div>
                    <p class="text-sm font-medium text-slate-500">Terjual Bulan Ini</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1"><?= str_pad($statTotalBookings ?? 0, 2, '0', STR_PAD_LEFT) ?></h3>
                </div>
                <!-- Strategi Harga -->
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 p-5 rounded-xl text-white relative overflow-hidden shadow-sm flex flex-col justify-center h-full">
                    <span class="material-symbols-outlined absolute -right-2 -bottom-2 text-6xl text-white/10">lightbulb</span>
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-white/20 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-widest backdrop-blur-sm">Insight Tip</span>
                            <span class="text-[10px] text-blue-200 font-semibold">Bulan Ini</span>
                        </div>
                        <p class="text-xs text-blue-100 leading-relaxed font-medium">
                            Opsi <strong>2 Cetak</strong> &amp; <strong>4 Cetak</strong> paling diminati bulan ini. Berikan harga menarik!
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tabel Opsi Cetak -->
            <div class="w-full bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden flex flex-col flex-1">
                <div class="px-6 pt-5 pb-3 border-b border-slate-100 bg-white">
                    <!-- Judul + Tombol Tambah -->
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-1 h-6 bg-blue-900 rounded-full"></div>
                            <h4 class="font-h2 text-h2 text-on-surface">Daftar Cetak</h4>
                        </div>
                        <button type="button" onclick="openCreateModal()"
                           class="bg-blue-900 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 hover:bg-blue-800 transition-colors shadow-sm whitespace-nowrap">
                            <span class="material-symbols-outlined text-[18px]">print</span>
                            Tambah Cetak
                        </button>
                    </div>
                    <!-- Filter Cetak -->
                    <form id="optFilterForm" method="get" action="<?= base_url('admin/print-options') ?>" class="flex gap-3 flex-1">
                        <!-- Search Bar -->
                        <div class="relative flex-1">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
                            <input type="text" name="search_opt" value="<?= esc($searchOpt ?? '') ?>"
                                   placeholder="Cari ID / Nama Opsi Cetak..."
                                   onchange="this.form.submit()"
                                   class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-900 bg-white transition-all" />
                        </div>
                        <!-- Dropdown Filter Harga -->
                        <div class="relative min-w-[170px]">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[16px] pointer-events-none">filter_list</span>
                            <select name="sort_opt" onchange="this.form.submit()" class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-lg text-sm appearance-none outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-900 bg-white cursor-pointer">
                                <option value="semua" <?= ($sortOpt ?? 'semua') == 'semua' ? 'selected' : '' ?>>Semua Harga</option>
                                <option value="lebih50" <?= ($sortOpt ?? '') == 'lebih50' ? 'selected' : '' ?>>Harga &gt; 50k</option>
                                <option value="kurang50" <?= ($sortOpt ?? '') == 'kurang50' ? 'selected' : '' ?>>Harga &lt; 50k</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-y border-slate-100 bg-white text-slate-500 text-xs font-bold uppercase tracking-wider">
                                <th class="px-6 py-4 font-bold">ID Cetak</th>
                                <th class="px-6 py-4 font-bold">Nama Opsi</th>
                                <th class="px-6 py-4 font-bold text-center">Cetak</th>
                                <th class="px-6 py-4 font-bold text-center">Terjual</th>
                                <th class="px-6 py-4 font-bold text-right">Harga</th>
                                <th class="px-6 py-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 bg-white">
                            <?php if (!empty($printOptions)): ?>
                                <?php foreach ($printOptions as $opt): ?>
                                    <tr class="hover:bg-slate-50 transition-colors group">
                                        <td class="px-6 py-4 font-mono text-xs font-semibold text-slate-900">
                                            #<?= esc($opt['id']) ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-bold text-slate-800 text-sm"><?= esc(!empty($opt['name']) ? $opt['name'] : ($opt['copies'] . ' Lembar Cetak')) ?></p>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-xs font-medium text-slate-600 bg-slate-100 px-2.5 py-1 rounded-full inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[14px]">file_copy</span> <?= esc($opt['copies']) ?>x
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[14px]">shopping_cart</span> <?= esc($opt['total_used'] ?? 0) ?>x
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-slate-700">
                                            Rp <?= number_format($opt['extra_price'] ?? 0, 0, ',', '.') ?>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center gap-2">
                                                <!-- Detail -->
                                                <button type="button" title="Detail"
                                                        onclick="openDetailModal('<?= esc($opt['id'], 'js') ?>', '<?= esc($opt['name'] ?? 'Opsi Cetak', 'js') ?>', <?= (int)$opt['copies'] ?>, <?= (int)($opt['extra_price'] ?? 0) ?>, <?= (int)($opt['total_used'] ?? 0) ?>)"
                                                        class="p-2 text-green-600 hover:bg-green-50 rounded transition-all">
                                                    <span class="material-symbols-outlined text-lg">visibility</span>
                                                </button>
                                                <!-- Edit -->
                                                <button type="button" title="Edit"
                                                        onclick="openEditModal('<?= esc($opt['id'], 'js') ?>', '<?= esc($opt['name'] ?? 'Opsi Cetak', 'js') ?>', <?= (int)$opt['copies'] ?>, <?= (int)($opt['extra_price'] ?? 0) ?>)"
                                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded transition-all">
                                                    <span class="material-symbols-outlined text-lg">edit</span>
                                                </button>
                                                <!-- Hapus -->
                                                <button type="button" title="Hapus"
                                                        onclick="openUniversalDeleteModal('<?= base_url('admin/print-options/' . $opt['id'] . '/delete') ?>', '<?= esc($opt['name'] ?? 'Opsi', 'js') ?>', 'Hapus Opsi Cetak?')"
                                                        class="p-2 text-red-500 hover:bg-red-50 rounded transition-all">
                                                    <span class="material-symbols-outlined text-lg">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-sm text-slate-400 italic">
                                        Idle / Kosong. <a href="<?= base_url('admin/print-options/create') ?>" class="text-primary font-semibold not-italic hover:underline">Tambah sekarang</a>.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Cetak -->
                <div class="mt-auto p-6 bg-white text-slate-500 text-xs flex justify-between items-center border-t border-slate-100">
                    <span>Menampilkan <?= count($printOptions ?? []) ?> dari <?= $totalPrintOptions ?? 0 ?> opsi</span>
                    <div class="flex gap-2">
                        <a href="<?= base_url('admin/print-options?opt_page=' . max(1, ($currentOptPage ?? 1) - 1) . '&sort_opt=' . ($sortOpt ?? 'termurah')) ?>"
                           class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentOptPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                            <span class="material-symbols-outlined text-sm">chevron_left</span>
                        </a>
                        <?php for ($i = 1; $i <= ($totalOptPages ?? 1); $i++): ?>
                            <a href="<?= base_url('admin/print-options?opt_page=' . $i . '&sort_opt=' . ($sortOpt ?? 'termurah')) ?>"
                               class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-colors <?= $i === ($currentOptPage ?? 1) ? 'border border-blue-900 bg-blue-900 text-white' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                        <a href="<?= base_url('admin/print-options?opt_page=' . min(($totalOptPages ?? 1), ($currentOptPage ?? 1) + 1) . '&sort_opt=' . ($sortOpt ?? 'termurah')) ?>"
                           class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentOptPage ?? 1) >= ($totalOptPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                            <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?= $this->include('Admin/layouts/footer') ?>
</main>

<!-- Toast Notification -->
<div id="toastNotif" class="fixed bottom-6 right-6 z-[300] flex items-center gap-3 bg-slate-900 text-white px-5 py-3 rounded-2xl shadow-2xl opacity-0 translate-y-4 transition-all duration-300 pointer-events-none">
    <span id="toastIcon" class="material-symbols-outlined text-green-400 text-[20px]">check_circle</span>
    <span id="toastMsg" class="text-sm font-semibold">Data berhasil dihapus.</span>
</div>

<!-- Modal Detail Opsi Cetak -->
<div id="detailModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="detailModalContent">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-white">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600">print</span>
                Detail Opsi Cetak
            </h4>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 flex flex-col items-center">
            <!-- Avatar/Initials Wrapper -->
            <div id="detailAvatar" class="w-20 h-20 rounded-full flex items-center justify-center font-bold text-2xl mb-5 shadow-inner">
                <span id="detailAvatarText"></span>
            </div>

            <!-- Details List -->
            <div class="w-full space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-200 text-left text-sm mb-6">
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">ID Opsi</span>
                    <span class="font-mono text-primary font-bold" id="detailId"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">Nama Opsi</span>
                    <span class="text-slate-800 font-semibold" id="detailTitle"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">Jumlah Cetak</span>
                    <span class="text-slate-800 font-semibold" id="detailCopies"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">Harga Tambahan</span>
                    <span class="text-slate-800 font-semibold" id="detailPrice"></span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold text-slate-500">Terjual Bulan Ini</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-green-100 text-green-800" id="detailSold"></span>
                </div>
            </div>
            
            <!-- Close Button -->
            <button type="button" onclick="closeDetailModal()" class="w-full py-2.5 bg-primary hover:bg-primary-container text-white font-semibold text-sm rounded-lg transition-colors shadow-md">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal Tambah Opsi Cetak -->
<div id="createModal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-300">
    <div id="createModalContent" class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 scale-95 transition-transform duration-300 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-blue-900 text-[22px]">print</span>
                <h3 class="font-bold text-slate-800 text-base">Tambah Opsi Cetak</h3>
            </div>
            <button onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-700 transition-colors">
                <span class="material-symbols-outlined text-[22px]">close</span>
            </button>
        </div>
        <form method="post" action="<?= base_url('admin/print-options') ?>" class="p-6 space-y-5">
            <?= csrf_field() ?>
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Jumlah Lembar (Copies)</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[18px]">file_copy</span>
                    <input type="number" name="copies" required min="1" placeholder="Contoh: 2"
                           class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Harga Tambahan (Rp)</label>
                <div class="relative">
                    <span class="absolute left-4 top-2.5 text-slate-400 font-bold text-sm">Rp</span>
                    <input type="number" name="extra_price" required min="0" placeholder="Contoh: 5000"
                           class="w-full pl-12 pr-4 py-2.5 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeCreateModal()" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-sm rounded-xl transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-blue-900 hover:bg-blue-800 text-white font-bold text-sm rounded-xl transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">save</span>Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Opsi Cetak -->
<div id="editModal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-300">
    <div id="editModalContent" class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 scale-95 transition-transform duration-300 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-blue-900 text-[22px]">edit</span>
                <h3 class="font-bold text-slate-800 text-base">Edit Opsi Cetak</h3>
            </div>
        </div>
        <form id="editForm" method="post" action="" class="p-6 space-y-5">
            <?= csrf_field() ?>
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">ID Opsi</label>
                <input type="text" id="editIdDisplay" readonly class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-400 outline-none" />
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Jumlah Lembar (Copies)</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[18px]">file_copy</span>
                    <input type="number" name="copies" id="editCopies" required min="1"
                           class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Harga Tambahan (Rp)</label>
                <div class="relative">
                    <span class="absolute left-4 top-2.5 text-slate-400 font-bold text-sm">Rp</span>
                    <input type="number" name="extra_price" id="editPrice" required min="0"
                           class="w-full pl-12 pr-4 py-2.5 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeEditModal()" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-sm rounded-xl transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-blue-900 hover:bg-blue-800 text-white font-bold text-sm rounded-xl transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">save</span>Simpan
                </button>
            </div>
        </form>
    </div>
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

    // Detail Modal
    const detailModal = document.getElementById('detailModal');
    const detailModalContent = document.getElementById('detailModalContent');
    function openDetailModal(id, name, copies, price, sold) {
        document.getElementById('detailTitle').textContent = name;
        document.getElementById('detailId').textContent = '#' + id;
        document.getElementById('detailCopies').textContent = copies + 'x Cetak';
        document.getElementById('detailPrice').textContent = 'Rp ' + price.toLocaleString('id-ID');
        document.getElementById('detailSold').textContent = sold + ' transaksi';
        
        const avatarEl = document.getElementById('detailAvatar');
        const initials = name.substring(0, 2).toUpperCase();
        document.getElementById('detailAvatarText').textContent = initials;
        
        // Randomize or pick matching color classes for the avatar
        avatarEl.className = 'w-20 h-20 rounded-full flex items-center justify-center font-bold text-2xl mb-5 shadow-inner bg-blue-100 text-blue-700';

        detailModal.classList.remove('hidden');
        setTimeout(() => {
            detailModalContent.classList.remove('scale-95', 'opacity-0');
            detailModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
    function closeDetailModal() {
        detailModalContent.classList.remove('scale-100', 'opacity-100');
        detailModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => { detailModal.classList.add('hidden'); }, 300);
    }
    detailModal.addEventListener('click', e => { if (e.target === detailModal) closeDetailModal(); });

    // Create Modal
    const createModal = document.getElementById('createModal');
    const createModalContent = document.getElementById('createModalContent');
    function openCreateModal() {
        createModal.classList.remove('hidden'); createModal.classList.add('flex');
        void createModal.offsetWidth;
        createModal.classList.remove('opacity-0');
        createModalContent.classList.remove('scale-95'); createModalContent.classList.add('scale-100');
    }
    function closeCreateModal() {
        createModal.classList.add('opacity-0');
        createModalContent.classList.remove('scale-100'); createModalContent.classList.add('scale-95');
        setTimeout(() => { createModal.classList.add('hidden'); createModal.classList.remove('flex'); }, 300);
    }
    createModal.addEventListener('click', e => { if (e.target === createModal) closeCreateModal(); });

    // Edit Modal
    const editModal = document.getElementById('editModal');
    const editModalContent = document.getElementById('editModalContent');
    const editForm = document.getElementById('editForm');
    function openEditModal(id, name, copies, price) {
        document.getElementById('editIdDisplay').value = '#' + id;
        document.getElementById('editCopies').value = copies;
        document.getElementById('editPrice').value = price;
        editForm.action = '<?= base_url('admin/print-options') ?>/' + id + '/update';
        editModal.classList.remove('hidden'); editModal.classList.add('flex');
        void editModal.offsetWidth;
        editModal.classList.remove('opacity-0');
        editModalContent.classList.remove('scale-95'); editModalContent.classList.add('scale-100');
    }
    function closeEditModal() {
        editModal.classList.add('opacity-0');
        editModalContent.classList.remove('scale-100'); editModalContent.classList.add('scale-95');
        setTimeout(() => { editModal.classList.add('hidden'); editModal.classList.remove('flex'); }, 300);
    }
    editModal.addEventListener('click', e => { if (e.target === editModal) closeEditModal(); });

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
