<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
/* Hide scrollbar for Chrome, Safari and Opera */
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
/* Hide scrollbar for IE, Edge and Firefox */
.scrollbar-hide {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}
</style>

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
                    <span class="text-blue-700 font-bold">Manajemen Mesin Booth</span>
                </li>
            </ol>
        </nav>

        <!-- Header Section dengan Judul & Deskripsi Baru -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <h2 class="font-h1 text-h1 text-on-surface">Manajemen Mesin Booth</h2>
                <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Kelola unit mesin booth terpasang, daftar lokasi operasional, serta pemantauan status unit dalam satu halaman.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="<?= base_url('admin/booths/export') ?>" onclick="showExportNotif()"
                   class="bg-white border-2 border-blue-600 text-blue-600 px-4 py-2 rounded-lg font-label-bold text-label-bold flex items-center gap-2 hover:bg-blue-50 hover:border-blue-700 hover:text-blue-700 transition-colors shadow-sm whitespace-nowrap">
                    <span class="material-symbols-outlined text-[18px]">cloud_download</span>
                    Ekspor Laporan
                </a>
            </div>
        </div>

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
            }, 3000);
        </script>
        <?php if (!empty(session()->getFlashdata('errors'))): ?>
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm font-medium">
                <ul class="list-disc list-inside space-y-1">
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- 1. Stats Bento Grid (4 Grid) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <!-- Total Mesin -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">devices</span>
                    <span class="text-blue-600 font-label-bold text-xs">Total</span>
                </div>
                <p class="text-xs font-medium text-slate-500">Total Mesin</p>
                <h3 class="text-xl font-black text-slate-900 mt-1"><?= $statTotal ?? 0 ?> Unit</h3>
            </div>
            <!-- Total Lokasi -->
            <?php if (false): // FITUR LOKASI SEMENTARA DI-NONAKTIFKAN (FOKUS 1 TITIK) ?>
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">location_on</span>
                    <span class="text-blue-600 font-label-bold text-xs">Cabang</span>
                </div>
                <p class="text-xs font-medium text-slate-500">Total Lokasi</p>
                <h3 class="text-xl font-black text-slate-900 mt-1"><?= $statTotalLocations ?? 0 ?> Lokasi</h3>
            </div>
            <?php endif; ?>
            <!-- Aktif -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-emerald-600 bg-emerald-50 p-2 rounded-lg material-symbols-outlined">check_circle</span>
                    <span class="text-emerald-600 font-label-bold text-xs">
                        <?= $statTotal > 0 ? round(($statActive / $statTotal) * 100) : 0 ?>%
                    </span>
                </div>
                <p class="text-xs font-medium text-slate-500">Aktif Sekarang</p>
                <h3 class="text-xl font-black text-slate-900 mt-1"><?= $statActive ?? 0 ?> Unit</h3>
            </div>
            <!-- Maintenance -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-amber-600 bg-amber-50 p-2 rounded-lg material-symbols-outlined">build</span>
                    <span class="text-amber-600 font-label-bold text-xs">
                        <?= ($statMaintenance ?? 0) > 0 ? 'High' : 'Normal' ?>
                    </span>
                </div>
                <p class="text-xs font-medium text-slate-500">Maintenance</p>
                <h3 class="text-xl font-black text-slate-900 mt-1"><?= $statMaintenance ?? 0 ?> Unit</h3>
            </div>
            <!-- Antrean Aktif -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-indigo-600 bg-indigo-50 p-2 rounded-lg material-symbols-outlined">queue</span>
                </div>
                <p class="text-xs font-medium text-slate-500">Total Antrean</p>
                <h3 class="text-xl font-black text-slate-900 mt-1"><?= $statTotalQueue ?? 0 ?></h3>
            </div>
        </div>

        <!-- 2. Table Daftar Mesin Booth (Di Atas) -->
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm mb-8">
            
            <!-- Table Header -->
            <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center bg-white gap-4">
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="w-1 h-6 bg-primary rounded-full"></div>
                    <h4 class="font-h2 text-h2 text-on-surface">Daftar Mesin Booth</h4>
                </div>
                <a href="<?= base_url('admin/booths/create') ?>"
                   class="bg-primary text-white px-4 py-2 rounded-lg font-label-bold text-label-bold flex items-center gap-2 hover:bg-primary-container transition-colors shadow-sm whitespace-nowrap w-full md:w-auto justify-center">
                    <span class="material-symbols-outlined text-[18px]">devices</span>
                    Tambah Booth
                </a>
            </div>

            <!-- Search & Filter Bar -->
            <div class="p-6 bg-white flex flex-col sm:flex-row gap-3 border-b border-slate-100">
                <form method="get" action="<?= base_url('admin/booths') ?>" class="flex flex-col md:flex-row flex-1 gap-3 w-full">
                    <!-- Search Input -->
                    <div class="relative flex-1">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[20px]">search</span>
                        <input type="text" name="q" value="<?= esc($keyword ?? '') ?>" placeholder="Cari ID, Nama Booth, Tema, Lokasi..."
                               class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white transition-all"/>
                    </div>

                    <!-- Location Filter -->
                    <?php if (false): // FITUR LOKASI SEMENTARA DI-NONAKTIFKAN (FOKUS 1 TITIK) ?>
                    <select name="location_id" onchange="this.form.submit()"
                            class="w-full md:w-48 px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white transition-all text-slate-600">
                        <option value="" <?= ($filterLoc ?? '') === '' ? 'selected' : '' ?>>Semua Lokasi</option>
                        <?php foreach ($locations as $l): ?>
                            <option value="<?= esc($l['id']) ?>" <?= ($filterLoc ?? '') === $l['id'] ? 'selected' : '' ?>>
                                <?= esc($l['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php endif; ?>
                    
                    <!-- Status Filter -->
                    <select name="status" onchange="this.form.submit()"
                            class="w-full md:w-44 px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white transition-all text-slate-600">
                        <option value="" <?= ($filterStatus ?? '') === '' ? 'selected' : '' ?>>Semua Status</option>
                        <option value="active"      <?= ($filterStatus ?? '') === 'active'      ? 'selected' : '' ?>>Aktif</option>
                        <option value="maintenance" <?= ($filterStatus ?? '') === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                        <option value="inactive"    <?= ($filterStatus ?? '') === 'inactive'    ? 'selected' : '' ?>>Nonaktif</option>
                    </select>

                    <!-- Reset button if search or filter is active -->
                    <?php if (!empty($keyword) || !empty($filterStatus) || !empty($filterLoc)): ?>
                        <a href="<?= base_url('admin/booths') ?>" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm font-semibold rounded-lg flex items-center justify-center transition-colors">
                            Reset
                        </a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Data Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="border-y border-slate-100 bg-white">
                        <tr>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">ID Booth</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Nama Mesin</th>
                            <?php if (false): // FITUR LOKASI SEMENTARA DI-NONAKTIFKAN (FOKUS 1 TITIK) ?>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Nama Lokasi</th>
                            <?php endif; ?>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Tema</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Alamat IP</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Sesi Aktif</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Antrean</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Status</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Waktu Daftar</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 bg-white">
                        <?php if (!empty($booths)): ?>
                            <?php foreach ($booths as $booth): ?>
                                <?php
                                    $statusMap = [
                                        'active'      => ['bg-emerald-50 text-emerald-600 border border-emerald-200', 'bg-emerald-500', 'Aktif'],
                                        'maintenance' => ['bg-amber-50 text-amber-600 border border-amber-200', 'bg-amber-500', 'Maintenance'],
                                        'inactive'    => ['bg-red-50 text-red-600 border border-red-200', 'bg-red-500', 'Non-Aktif'],
                                    ];
                                    $st = $statusMap[$booth['status']] ?? ['bg-slate-100 text-slate-500', 'bg-slate-400', ucfirst($booth['status'])];
                                ?>
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="p-4 font-mono font-bold text-blue-600 text-center text-xs"><?= esc($booth['id']) ?></td>
                                    <td class="p-4 font-body-base text-body-base font-bold text-on-surface text-center"><?= esc($booth['name']) ?></td>
                                    <?php if (false): // FITUR LOKASI SEMENTARA DI-NONAKTIFKAN (FOKUS 1 TITIK) ?>
                                    <td class="p-4 text-xs font-medium text-slate-600 text-center">
                                        <?php if (!empty($booth['location_name'])): ?>
                                            <span class="inline-flex items-center gap-1 text-slate-700 font-semibold">
                                                <span class="material-symbols-outlined text-[14px] text-primary">location_on</span>
                                                <?= esc($booth['location_name']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-slate-400 italic">Belum diset</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php endif; ?>
                                    <td class="p-4 text-center">
                                        <?php if (!empty($booth['theme'])): ?>
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200 capitalize">
                                                <?= esc($availableThemes[$booth['theme']] ?? ucfirst($booth['theme'])) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-xs text-slate-400 italic">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4 font-mono text-xs text-slate-600 text-center"><?= esc($booth['ip_address'] ?? '-') ?></td>
                                    <td class="p-4 text-center">
                                        <?php if (!empty($booth['active_booking_id'])): ?>
                                            <div class="flex items-center justify-center gap-2">
                                                <span class="inline-flex items-center px-2 py-1 rounded bg-blue-50 text-blue-700 text-xs font-semibold">
                                                    <span class="material-symbols-outlined text-[14px] mr-1">meeting_room</span>
                                                    #<?= esc($booth['active_booking_id']) ?>
                                                </span>
                                                <form action="<?= base_url('admin/booths/' . $booth['id'] . '/reset-session') ?>" method="post" class="inline">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="p-1 text-red-500 hover:bg-red-50 rounded transition-colors" title="Forced Reset Sesi" onclick="return confirm('Paksa keluar customer dari sesi ini dan reset layar booth?')">
                                                        <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                                                    </button>
                                                </form>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-xs text-slate-400 font-medium">Idle / Kosong</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="text-base font-black text-slate-600"><?= esc($booth['current_queue'] ?? 0) ?></span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold <?= $st[0] ?>">
                                            <span class="w-2 h-2 <?= $st[1] ?> rounded-full flex-shrink-0"></span>
                                            <?= $st[2] ?>
                                        </span>
                                    </td>
                                    <td class="p-4 font-data-tabular text-data-tabular text-outline text-center">
                                        <?= date('d M Y, H:i', strtotime($booth['created_at'])) ?>
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" onclick="showBoothDetailModal('<?= esc($booth['id'], 'js') ?>', '<?= esc($booth['name'], 'js') ?>', '<?= esc($booth['location_name'] ?? 'Belum diset', 'js') ?>', '<?= esc($booth['theme'] ? ($availableThemes[$booth['theme']] ?? $booth['theme']) : 'Belum diset', 'js') ?>', '<?= esc($booth['ip_address'] ?? '-', 'js') ?>', '<?= date('d M Y, H:i', strtotime($booth['created_at'])) ?>', '<?= $booth['status'] ?>', '<?= esc($booth['current_queue'] ?? 0, 'js') ?>')"
                                               class="inline p-1.5 text-emerald-600 hover:bg-emerald-50 rounded transition-colors" title="Detail Mesin Booth">
                                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                                            </button>
                                            <button type="button"
                                                    class="inline p-1.5 text-purple-600 hover:bg-purple-50 rounded transition-colors" title="Generate QR Code"
                                                    onclick="showQrModal('<?= esc($booth['id'], 'js') ?>', '<?= esc($booth['name'], 'js') ?>')">
                                                <span class="material-symbols-outlined text-[20px]">qr_code</span>
                                            </button>
                                            <a href="<?= base_url('admin/booths/' . $booth['id'] . '/edit') ?>"
                                               class="inline p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                                                <span class="material-symbols-outlined text-[20px]">edit</span>
                                            </a>
                                            <?php if ($booth['status'] === 'active'): ?>
                                                <button type="button"
                                                        class="inline p-1.5 text-amber-600 hover:bg-amber-50 rounded transition-colors" title="Set Maintenance"
                                                        onclick="showMaintenanceModal('<?= esc($booth['id'], 'js') ?>', '<?= esc($booth['name'], 'js') ?>', 'maintenance')">
                                                    <span class="material-symbols-outlined text-[20px]">engineering</span>
                                                </button>
                                            <?php else: ?>
                                                <button type="button"
                                                        class="inline p-1.5 text-emerald-600 hover:bg-emerald-50 rounded transition-colors" title="Aktifkan Kembali"
                                                        onclick="showMaintenanceModal('<?= esc($booth['id'], 'js') ?>', '<?= esc($booth['name'], 'js') ?>', 'activate')">
                                                    <span class="material-symbols-outlined text-[20px]">play_circle</span>
                                                </button>
                                            <?php endif; ?>
                                            <button type="button"
                                                    class="inline p-1.5 text-red-500 hover:bg-red-50 rounded transition-colors" title="Hapus"
                                                    onclick="openUniversalDeleteModal('<?= base_url('admin/booths/' . $booth['id'] . '/delete') ?>', '<?= esc($booth['name'], 'js') ?>', 'Hapus Mesin Booth?')">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="p-10 text-center text-sm text-slate-400">
                                    Belum ada booth terdaftar.
                                    <a href="<?= base_url('admin/booths/create') ?>" class="text-primary font-semibold ml-1">Tambah sekarang</a>.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-auto p-6 bg-white border-t border-slate-100 flex items-center justify-between">
                <span class="text-body-sm text-outline">
                    Menampilkan <?= count($booths ?? []) ?> dari <?= $totalBooths ?? 0 ?> Mesin
                </span>
                <div class="flex gap-2">
                    <a href="<?= base_url('admin/booths?page=' . max(1, ($currentPage ?? 1) - 1) . '&q=' . ($keyword ?? '') . '&status=' . ($filterStatus ?? '') . '&location_id=' . ($filterLoc ?? '')) ?>"
                       class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-sm">chevron_left</span>
                    </a>
                    <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                        <a href="<?= base_url('admin/booths?page=' . $i . '&q=' . ($keyword ?? '') . '&status=' . ($filterStatus ?? '') . '&location_id=' . ($filterLoc ?? '')) ?>"
                           class="w-8 h-8 flex items-center justify-center border rounded-lg text-sm font-bold transition-colors <?= $i === ($currentPage ?? 1) ? 'border-primary bg-primary text-white' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    <a href="<?= base_url('admin/booths?page=' . min(($totalPages ?? 1), ($currentPage ?? 1) + 1) . '&q=' . ($keyword ?? '') . '&status=' . ($filterStatus ?? '') . '&location_id=' . ($filterLoc ?? '')) ?>"
                       class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentPage ?? 1) >= ($totalPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                </div>
            </div>
        </div>

        <?php if (false): // FITUR LOKASI SEMENTARA DI-NONAKTIFKAN (FOKUS 1 TITIK) ?>
        <!-- 3. Table Daftar Lokasi Booth (Di Bawah) -->
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm mb-8">
            <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center bg-white gap-4">
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="w-1 h-6 bg-primary rounded-full"></div>
                    <div>
                        <h4 class="font-h2 text-h2 text-on-surface">Daftar Lokasi Booth</h4>
                    </div>
                </div>
                <a href="<?= base_url('admin/locations/create') ?>"
                   class="bg-primary text-white px-4 py-2 rounded-lg font-label-bold text-label-bold flex items-center gap-2 hover:bg-primary-container transition-colors shadow-sm whitespace-nowrap w-full md:w-auto justify-center">
                    <span class="material-symbols-outlined text-[18px]">add_location_alt</span>
                    Tambah Lokasi
                </a>
            </div>

            <!-- Filter & Search Bar Lokasi -->
            <div class="p-6 bg-white flex flex-col sm:flex-row gap-3 border-b border-slate-100">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[20px]">search</span>
                    <input type="text" id="locSearchInput" onkeyup="filterLocationTable()" placeholder="Cari ID Lokasi, Nama Lokasi, Kota..."
                           class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white transition-all"/>
                </div>
                <select id="locStatusFilter" onchange="filterLocationTable()"
                        class="w-full md:w-48 px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white transition-all text-slate-600">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </select>
            </div>

            <!-- Location Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left" id="locationTableElement">
                    <thead class="border-y border-slate-100 bg-white">
                        <tr>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">ID Lokasi</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Nama Lokasi</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Preview Lokasi</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Kota</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Alamat</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Tema</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Jumlah Booth</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Status</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 bg-white">
                        <?php if (!empty($locations)): ?>
                            <?php foreach ($locations as $loc): ?>
                                <?php 
                                    $thLabels = [];
                                    if (!empty($loc['themes'])) {
                                        foreach ($loc['themes'] as $tk) {
                                            $thLabels[] = $availableThemes[$tk] ?? $tk;
                                        }
                                    }
                                    $themesString = !empty($thLabels) ? implode(', ', $thLabels) : 'Semua Tema';
                                ?>
                                <tr class="hover:bg-blue-50/30 transition-colors loc-table-row" data-name="<?= esc(strtolower($loc['name'] . ' ' . $loc['id'] . ' ' . ($loc['city'] ?? ''))) ?>" data-status="<?= esc($loc['status'] ?? 'active') ?>">
                                    <td class="p-4 font-mono font-bold text-primary text-center text-xs"><?= esc($loc['id']) ?></td>
                                    <td class="p-4 font-body-base text-body-base font-bold text-on-surface text-center"><?= esc($loc['name']) ?></td>
                                    <td class="p-4 text-center">
                                        <?php 
                                            $previewImgs = json_decode($loc['preview_images'] ?? '[]', true) ?: [];
                                            if (!empty($previewImgs)): 
                                        ?>
                                            <div class="flex overflow-x-auto snap-x snap-mandatory scrollbar-hide space-x-2 w-32 mx-auto rounded-lg" style="height: 60px;">
                                                <?php foreach($previewImgs as $img): ?>
                                                    <img src="<?= base_url(esc($img)) ?>" alt="Preview" class="snap-center w-32 h-[60px] object-cover flex-shrink-0 rounded-lg border border-slate-200">
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="w-32 h-[60px] mx-auto bg-slate-100 rounded-lg flex items-center justify-center border border-slate-200">
                                                <span class="material-symbols-outlined text-slate-400 text-[20px]">image</span>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4 text-xs font-semibold text-slate-700 text-center"><?= esc($loc['city'] ?? '-') ?></td>
                                    <td class="p-4 text-xs text-slate-600 text-center max-w-xs mx-auto truncate"><?= esc($loc['address'] ?? '-') ?></td>
                                    <td class="p-4 text-center">
                                        <div class="grid grid-cols-2 gap-1.5 max-w-[170px] mx-auto">
                                            <?php if (!empty($loc['themes'])): ?>
                                                <?php foreach ($loc['themes'] as $thKey): ?>
                                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200 capitalize text-center truncate">
                                                        <?= esc($availableThemes[$thKey] ?? $thKey) ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span class="text-xs text-slate-400 italic col-span-2">Semua Tema</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-primary border border-blue-200">
                                            <?= $loc['booth_count'] ?? 0 ?> Unit
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <?php if (($loc['status'] ?? 'active') === 'active'): ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                                <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                                Aktif
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-200">
                                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                                Non-Aktif
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" onclick="showLocationDetailModal('<?= esc($loc['id'], 'js') ?>', '<?= esc($loc['name'], 'js') ?>', '<?= esc($loc['city'] ?? '-', 'js') ?>', '<?= esc($loc['address'] ?? '-', 'js') ?>', '<?= esc($themesString, 'js') ?>', '<?= esc($loc['booth_count'] ?? 0, 'js') ?>', '<?= esc($loc['status'] ?? 'active', 'js') ?>', '<?= esc($loc['preview_images'] ?? '[]', 'js') ?>')"
                                                    class="inline p-1.5 text-emerald-600 hover:bg-emerald-50 rounded transition-colors" title="Detail Lokasi Booth">
                                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                                            </button>
                                            <a href="<?= base_url('admin/locations/' . $loc['id'] . '/edit') ?>"
                                               class="inline p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit Lokasi">
                                                <span class="material-symbols-outlined text-[20px]">edit</span>
                                            </a>
                                            <button type="button" onclick="openUniversalDeleteModal('<?= base_url('admin/locations/' . $loc['id'] . '/delete') ?>', '<?= esc($loc['name'], 'js') ?>', 'Hapus Lokasi ini?')"
                                                    class="inline p-1.5 text-red-500 hover:bg-red-50 rounded transition-colors" title="Hapus Lokasi">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="p-8 text-center text-sm text-slate-400">
                                    Belum ada lokasi terdaftar. Silakan klik tombol "Tambah Lokasi" di atas.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 bg-white border-t border-slate-100 flex items-center justify-between text-body-sm text-outline">
                <span>Total <?= count($locations ?? []) ?> Lokasi Terdaftar</span>
            </div>
        </div>
        <?php endif; ?>

        <!-- ==================== Section 3: Daftar Tema ==================== -->
        <div class="mt-8 mb-6 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="px-8 py-5 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-8 bg-primary rounded-full"></div>
                    <h3 class="text-[20px] font-bold text-on-surface">Daftar Tema</h3>
                </div>
                <button type="button" onclick="document.getElementById('modalTambahTema').classList.remove('hidden')"
                        class="px-5 py-2.5 bg-primary hover:bg-primary-container text-white text-sm font-semibold rounded-lg transition-colors flex items-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">add_box</span>
                    Tambah Tema
                </button>
            </div>

            <!-- Search Bar -->
            <div class="p-6 pb-2">
                <div class="relative w-full">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                    <input type="text" id="searchTema" placeholder="Cari ID atau nama tema..."
                           class="w-full pl-12 pr-4 py-3 text-sm border border-slate-200 rounded-xl outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all bg-slate-50/50" />
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto p-6 pt-2">
                <table class="w-full text-center border-collapse" id="tableTema">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-4 px-4 whitespace-nowrap text-center">ID TEMA</th>
                            <th class="py-4 px-4 whitespace-nowrap text-center">PREVIEW TEMA</th>
                            <th class="py-4 px-4 whitespace-nowrap text-center">NAMA TEMA</th>
                            <th class="py-4 px-4 whitespace-nowrap text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700 divide-y divide-slate-50">
                        <?php if (!empty($allThemes)): ?>
                            <?php foreach ($allThemes as $theme): ?>
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="py-4 px-4 font-mono font-bold text-blue-600 text-center">
                                        #<?= esc($theme['key']) ?>
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        <?php if (!empty($theme['preview_image'])): ?>
                                            <div class="w-16 h-16 mx-auto rounded-lg overflow-hidden border border-slate-200 shadow-sm bg-slate-50">
                                                <img src="<?= base_url('uploads/themes/' . esc($theme['preview_image'])) ?>" alt="<?= esc($theme['label']) ?>" class="w-full h-full object-cover">
                                            </div>
                                        <?php else: ?>
                                            <div class="w-16 h-16 mx-auto rounded-lg overflow-hidden border border-slate-200 border-dashed bg-slate-50 flex items-center justify-center text-slate-400">
                                                <span class="material-symbols-outlined">image_not_supported</span>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-4 px-4 font-semibold text-slate-800 text-center">
                                        <?= esc($theme['label']) ?>
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        <div class="flex items-center justify-center gap-3">
                                            <button type="button" onclick="showPreviewTema('<?= base_url('uploads/themes/' . esc($theme['preview_image'] ?? '')) ?>', '<?= esc($theme['label'], 'js') ?>', '<?= esc($theme['key'], 'js') ?>')" class="text-emerald-500 hover:text-emerald-600 transition-colors" title="Lihat">
                                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                                            </button>
                                            <button type="button" onclick="editTema('<?= esc($theme['key'], 'js') ?>', '<?= esc($theme['label'], 'js') ?>', '<?= base_url('uploads/themes/' . esc($theme['preview_image'] ?? '')) ?>')" class="text-blue-500 hover:text-blue-600 transition-colors" title="Edit">
                                                <span class="material-symbols-outlined text-[20px]">edit</span>
                                            </button>
                                            <form method="post" action="<?= base_url('admin/themes/' . $theme['key'] . '/delete') ?>" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tema ini?')">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="text-rose-500 hover:text-rose-600 transition-colors" title="Hapus">
                                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="py-12 text-center text-slate-500">
                                    <span class="material-symbols-outlined text-4xl mb-2 text-slate-300">grid_off</span>
                                    <p>Belum ada tema yang terdaftar.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Footer -->
            <div class="p-5 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 bg-slate-50/50">
                <span>Menampilkan total <?= count($allThemes ?? []) ?> Tema</span>
            </div>
        </div>

        <!-- ==================== Section 4: Maintenance Overview ==================== -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Sebaran Geografis Mesin (Map Container) -->
            <div class="bg-white border border-outline-variant rounded-xl shadow-sm p-6 col-span-2">
                <h3 class="font-h3 text-h3 mb-4">Sebaran Geografis Mesin</h3>
                <div class="h-64 rounded-xl border border-outline-variant bg-slate-100 overflow-hidden relative z-0">
                    <div id="map" class="w-full h-full"></div>
                </div>
            </div>

            <!-- Perlu Perhatian -->
            <div class="bg-white border border-outline-variant rounded-xl shadow-sm p-6 col-span-1 flex flex-col h-full">
                <h3 class="font-h3 text-h3 mb-4">Perlu Perhatian</h3>
                <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                    <?php if (!empty($maintenanceBooths)): ?>
                        <p class="text-body-sm text-outline mb-4"><?= count($maintenanceBooths) ?> mesin dalam mode Maintenance.</p>
                        <div class="space-y-3">
                            <?php foreach ($maintenanceBooths as $mb): ?>
                                <div class="flex items-center space-x-3 p-3 bg-red-50 border-l-4 border-red-500 rounded">
                                    <span class="material-symbols-outlined text-red-500">warning</span>
                                    <div>
                                        <p class="font-bold text-xs text-red-800"><?= esc($mb['id']) ?></p>
                                        <p class="text-[11px] text-red-600"><?= esc($mb['name']) ?> — Maintenance Diperlukan</p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="flex items-center space-x-3 p-3 bg-green-50 border-l-4 border-green-500 rounded">
                            <span class="material-symbols-outlined text-green-600">check_circle</span>
                            <p class="text-sm text-green-700 font-medium">Semua mesin berjalan normal.</p>
                        </div>
                    <?php endif; ?>
                </div>
                <a href="<?= base_url('admin/booths?status=maintenance') ?>"
                   class="mt-6 w-full py-2 text-center border border-primary text-primary hover:bg-blue-700 hover:text-white transition-all rounded-lg font-label-bold text-label-bold block">
                    Lihat Log Maintenance
                </a>
            </div>
        </div>

    </div>
    <?= $this->include('Admin/layouts/footer') ?>
</main>

<!-- Modal Pop-Up Detail Mesin Booth (Sesuai Desain Frame Preview) -->
<div id="boothDetailModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="boothDetailModalContent">
        <!-- Modal Header -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-white">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">visibility</span>
                Detail Mesin Booth
            </h4>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 flex flex-col items-center">
            <div class="w-full space-y-3 text-xs border border-slate-100 p-4 rounded-xl bg-slate-50/50 mb-6">
                <div class="flex justify-between border-b border-slate-200/60 pb-2">
                    <span class="text-slate-500">ID Booth</span>
                    <span class="font-bold font-mono text-slate-800" id="detailBoothId"></span>
                </div>
                <div class="flex justify-between border-b border-slate-200/60 pb-2">
                    <span class="text-slate-500">Nama Mesin</span>
                    <span class="font-bold text-slate-800" id="detailBoothName"></span>
                </div>
                <div class="flex justify-between border-b border-slate-200/60 pb-2">
                    <span class="text-slate-500">Nama Lokasi</span>
                    <span class="font-bold text-slate-800" id="detailBoothLocation"></span>
                </div>
                <div class="flex justify-between border-b border-slate-200/60 pb-2">
                    <span class="text-slate-500">Tema</span>
                    <span class="font-bold text-slate-800 capitalize" id="detailBoothTheme"></span>
                </div>
                <div class="flex justify-between border-b border-slate-200/60 pb-2">
                    <span class="text-slate-500">Alamat IP</span>
                    <span class="font-bold font-mono text-slate-800" id="detailBoothIp"></span>
                </div>
                <div class="flex justify-between border-b border-slate-200/60 pb-2">
                    <span class="text-slate-500">Antrean Saat Ini</span>
                    <span class="font-bold text-slate-800" id="detailBoothQueue"></span>
                </div>
                <div class="flex justify-between border-b border-slate-200/60 pb-2">
                    <span class="text-slate-500">Status Operasional</span>
                    <span id="detailBoothStatus"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Didaftarkan Pada</span>
                    <span class="font-bold text-slate-800" id="detailBoothCreated"></span>
                </div>
            </div>
            
            <button type="button" onclick="closeBoothDetailModal()" class="w-full py-2.5 bg-primary hover:bg-blue-700 text-white font-bold text-sm rounded-lg shadow-md transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal Pop-Up Detail Lokasi (Sesuai Desain Frame Preview) -->
<div id="locationDetailModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-2xl w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="locationDetailModalContent">
        <!-- Modal Header -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-white">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">visibility</span>
                Detail & Preview Lokasi
            </h4>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div class="flex flex-col md:flex-row gap-6 items-stretch">
                <!-- Preview Image -->
                <div class="w-full md:w-1/2 rounded-xl overflow-hidden border border-slate-200 bg-slate-50 flex-shrink-0 relative group p-2 flex flex-col justify-center min-h-[300px] h-full">
                    <img id="detailLocImg" src="" alt="Preview Lokasi" class="w-full h-full object-contain hidden">
                    
                    <div id="detailLocNoImg" class="w-full h-full min-h-[300px] flex items-center justify-center text-slate-400 flex-col gap-2">
                        <span class="material-symbols-outlined text-4xl">image</span>
                        <span class="text-sm font-medium">Tidak ada foto</span>
                    </div>

                    <!-- Slider Controls -->
                    <button type="button" id="locImgPrev" onclick="slideLocImg(-1)" class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center bg-white/90 text-slate-700 hover:text-primary rounded-full shadow-md opacity-0 group-hover:opacity-100 transition-opacity hidden">
                        <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                    </button>
                    <button type="button" id="locImgNext" onclick="slideLocImg(1)" class="absolute right-4 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center bg-white/90 text-slate-700 hover:text-primary rounded-full shadow-md opacity-0 group-hover:opacity-100 transition-opacity hidden">
                        <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                    </button>
                    
                    <!-- Indicator -->
                    <div id="locImgIndicator" class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-1.5 hidden">
                    </div>
                </div>
                
                <!-- Info Section -->
                <div class="w-full md:w-1/2 space-y-4 text-sm">
                    <div class="border-b border-slate-100 pb-3">
                        <span class="block text-xs font-semibold text-slate-400 mb-1">ID Lokasi</span>
                        <span class="font-mono font-medium text-slate-700 bg-slate-100 px-2.5 py-1 rounded-md" id="detailLocId"></span>
                    </div>
                    <div class="border-b border-slate-100 pb-3">
                        <span class="block text-xs font-semibold text-slate-400 mb-1">Nama Lokasi</span>
                        <span class="font-bold text-lg text-slate-800" id="detailLocName"></span>
                    </div>
                    <div class="border-b border-slate-100 pb-3">
                        <span class="block text-xs font-semibold text-slate-400 mb-1">Kota</span>
                        <span class="font-semibold text-slate-700" id="detailLocCity"></span>
                    </div>
                    <div class="border-b border-slate-100 pb-3">
                        <span class="block text-xs font-semibold text-slate-400 mb-1">Alamat</span>
                        <span class="text-slate-700" id="detailLocAddress"></span>
                    </div>
                    <div class="border-b border-slate-100 pb-3">
                        <span class="block text-xs font-semibold text-slate-400 mb-1">Tema</span>
                        <span class="font-bold text-slate-800 capitalize" id="detailLocThemes"></span>
                    </div>
                    <div class="flex items-center justify-between pt-1">
                        <div>
                            <span class="block text-xs font-semibold text-slate-400 mb-1">Status Lokasi</span>
                            <span class="font-bold" id="detailLocStatus"></span>
                        </div>
                        <div class="text-right">
                            <span class="block text-xs font-semibold text-slate-400 mb-1">Jumlah Booth</span>
                            <span class="font-bold text-slate-800" id="detailLocBoothCount"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="p-5 border-t border-slate-100 bg-slate-50/50">
            <button type="button" onclick="closeLocationDetailModal()" class="w-full py-2.5 bg-primary hover:bg-blue-700 text-white font-bold text-sm rounded-lg shadow-md transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div id="qrModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-sm w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="qrModalContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-white">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-purple-600">qr_code_2</span>
                QR Code Kiosk Booth
            </h4>
        </div>
        <div class="p-6 flex flex-col items-center text-center">
            <p class="text-xs font-semibold text-purple-600 bg-purple-50 px-2.5 py-1 rounded-full mb-3" id="modalBoothId"></p>
            <h3 class="font-bold text-slate-800 text-base mb-1" id="modalBoothName"></h3>
            <p class="text-xs text-slate-400 mb-6">Scan QR untuk membuka halaman booking dengan booth ini terpilih otomatis.</p>
            <div class="p-3 bg-white border border-slate-200 rounded-2xl shadow-sm mb-6 flex items-center justify-center">
                <img id="qrImageElement" src="" alt="QR Code" class="w-48 h-48" />
            </div>
            <div class="w-full flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-lg p-2 mb-6">
                <input type="text" id="qrUrlInput" readonly class="bg-transparent text-xs text-slate-600 w-full outline-none select-all font-mono pl-1" />
                <button type="button" onclick="copyQrUrl()" class="px-2.5 py-1.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-md text-xs font-semibold flex items-center gap-1 shadow-sm transition-all" id="copyBtn">
                    <span class="material-symbols-outlined text-[14px]">content_copy</span>
                    <span>Copy</span>
                </button>
            </div>
            <div class="w-full flex gap-3">
                <a id="downloadQrBtn" href="" download="qr_code.png" class="flex-1 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold text-sm rounded-lg text-center shadow-md transition-colors flex items-center justify-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    Download PNG
                </a>
                <button type="button" onclick="closeQrModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-sm rounded-lg transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Maintenance Modal -->
<div id="maintenanceModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="maintenanceModalContent">
        <div class="p-6 text-center">
            <div id="maintModalIconWrap" class="w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center mx-auto mb-4">
                <span id="maintModalIcon" class="material-symbols-outlined text-4xl text-amber-600">engineering</span>
            </div>
            <h3 id="maintModalTitle" class="font-bold text-slate-800 text-lg mb-2">Set ke Maintenance?</h3>
            <p id="maintModalDesc" class="text-xs text-slate-500 mb-6"></p>
            <form id="maintenanceForm" method="post" class="flex gap-3">
                <?= csrf_field() ?>
                <button type="button" onclick="closeMaintenanceModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-sm rounded-lg transition-colors">
                    Batal
                </button>
                <button type="submit" id="maintConfirmBtn" class="flex-1 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-sm rounded-lg shadow-md transition-all">
                    Ya, Set Maintenance
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Tema -->
<div id="modalTambahTema" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full mx-4 overflow-hidden transform transition-all">
        <form action="<?= base_url('admin/themes') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-white">
                <h4 class="font-bold text-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#0B215E]">add_box</span>
                    Tambah Tema
                </h4>
                <button type="button" onclick="document.getElementById('modalTambahTema').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Tema <span class="text-rose-500">*</span></label>
                    <input type="text" name="label" placeholder="contoh: Pastel Wonderland" required class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg outline-none focus:border-[#0B215E] bg-slate-50">
                    <p class="text-[10px] text-slate-400 mt-1">ID Tema akan dibuat otomatis dari nama ini.</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Preview Tema (Gambar) <span class="text-rose-500">*</span></label>
                    <input type="file" name="preview_image" accept="image/*" required class="w-full text-sm text-slate-700 bg-white border border-slate-200 p-1 rounded">
                </div>
            </div>

            <div class="p-5 border-t border-slate-100 flex justify-end gap-3 bg-slate-50/50">
                <button type="button" onclick="document.getElementById('modalTambahTema').classList.add('hidden')" class="px-5 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2 bg-[#0B215E] hover:bg-[#1a3683] text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">Simpan Tema</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Tema -->
<div id="modalEditTema" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-2xl w-full mx-4 overflow-hidden transform transition-all">
        <form id="formEditTema" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <!-- Modal Header -->
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-white">
                <h4 class="font-bold text-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-500">edit</span>
                    Edit & Preview Tema
                </h4>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <div class="flex flex-col md:flex-row gap-6 items-stretch">
                    <!-- Preview Image -->
                    <div class="w-full md:w-1/2 rounded-xl overflow-hidden border border-slate-200 bg-slate-50 flex-shrink-0 p-2 flex flex-col justify-center min-h-[300px] h-full">
                        <img id="editTemaImg" src="" alt="Preview" class="w-full h-full object-contain hidden">
                        <div id="editTemaNoImg" class="w-full h-full min-h-[300px] flex items-center justify-center text-slate-400 flex-col gap-2">
                            <span class="material-symbols-outlined text-4xl">image</span>
                            <span class="text-sm font-medium">Tidak ada foto</span>
                        </div>
                    </div>
                    
                    <!-- Info Section -->
                    <div class="w-full md:w-1/2 space-y-4 text-sm">
                        <div class="border-b border-slate-100 pb-3">
                            <label class="block text-xs font-semibold text-slate-400 mb-1">ID Tema</label>
                            <span class="font-mono font-medium text-slate-700 bg-slate-100 px-2.5 py-1 rounded-md" id="editTemaKeyDisplay"></span>
                            <input type="hidden" id="editTemaKey" name="key">
                        </div>
                        <div class="border-b border-slate-100 pb-3">
                            <label for="editTemaLabel" class="block text-xs font-semibold text-slate-400 mb-1">Nama Tema <span class="text-rose-500">*</span></label>
                            <input type="text" id="editTemaLabel" name="label" required class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg outline-none focus:border-blue-500 bg-slate-50 font-bold text-slate-800">
                        </div>
                        <div class="border-b border-slate-100 pb-3">
                            <label class="block text-xs font-semibold text-slate-400 mb-1">Update Preview (Opsional)</label>
                            <input type="file" name="preview_image" accept="image/*" class="w-full text-sm text-slate-700 bg-white border border-slate-200 p-1 rounded">
                            <p class="text-[10px] text-slate-400 mt-1">Biarkan kosong jika tidak ingin mengubah gambar.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-5 border-t border-slate-100 flex justify-end gap-3 bg-slate-50/50">
                <button type="button" onclick="document.getElementById('modalEditTema').classList.add('hidden')" class="px-5 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">save</span> Simpan Tema
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Lihat Tema -->
<div id="modalLihatTema" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-2xl w-full mx-4 overflow-hidden transform transition-all">
        <!-- Modal Header -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-white">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">visibility</span>
                Detail & Preview Tema
            </h4>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div class="flex flex-col md:flex-row gap-6 items-stretch">
                <!-- Preview Image -->
                <div class="w-full md:w-1/2 rounded-xl overflow-hidden border border-slate-200 bg-slate-50 flex-shrink-0 p-2 flex flex-col justify-center min-h-[300px] h-full">
                    <img id="lihatTemaImg" src="" alt="Preview" class="w-full h-full object-contain">
                </div>
                
                <!-- Info Section -->
                <div class="w-full md:w-1/2 space-y-4 text-sm">
                    <div class="border-b border-slate-100 pb-3">
                        <span class="block text-xs font-semibold text-slate-400 mb-1">ID Tema</span>
                        <span class="font-mono font-medium text-slate-700 bg-slate-100 px-2.5 py-1 rounded-md" id="lihatTemaId"></span>
                    </div>
                    <div class="border-b border-slate-100 pb-3">
                        <span class="block text-xs font-semibold text-slate-400 mb-1">Nama Tema</span>
                        <span class="font-bold text-lg text-slate-800" id="lihatTemaLabel"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-5 border-t border-slate-100 bg-slate-50/50">
            <button type="button" onclick="closePreviewTema()" class="w-full py-2.5 bg-primary hover:bg-blue-700 text-white font-bold text-sm rounded-lg shadow-md transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Client-side search & filter for location table
    function filterLocationTable() {
        const q = (document.getElementById('locSearchInput').value || '').toLowerCase();
        const status = document.getElementById('locStatusFilter').value;
        const rows = document.querySelectorAll('.loc-table-row');

        rows.forEach(row => {
            const rowData = row.getAttribute('data-name') || '';
            const rowStatus = row.getAttribute('data-status') || '';

            const matchQ = q === '' || rowData.includes(q);
            const matchStatus = status === '' || rowStatus === status;

            if (matchQ && matchStatus) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Leaflet Map
        const map = L.map('map').setView([-2.548926, 118.0148634], 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        const locationsData = <?= $locationsJson ?? '[]' ?>;
        let bounds = [];

        locationsData.forEach(function(loc) {
            if (loc.latitude && loc.longitude) {
                const lat = parseFloat(loc.latitude);
                const lng = parseFloat(loc.longitude);
                bounds.push([lat, lng]);

                const marker = L.marker([lat, lng]).addTo(map);
                const statusColor = loc.status === 'active' ? 'text-emerald-600' : 'text-slate-500';
                const statusLabel = loc.status === 'active' ? 'Aktif' : 'Nonaktif/Maintenance';
                const boothCount = loc.booth_count || 0;

                marker.bindPopup(`
                    <div class="p-1 font-sans">
                        <h4 class="font-bold text-slate-800 text-sm mb-1">${loc.name}</h4>
                        <p class="text-xs text-slate-500 mb-1">Status: <span class="font-semibold ${statusColor}">${statusLabel}</span></p>
                        <p class="text-xs text-slate-500 m-0">Tersedia <span class="font-bold text-slate-700">${boothCount}</span> Mesin Booth</p>
                    </div>
                `);
            }
        });

        if (bounds.length > 0) {
            map.fitBounds(bounds, { padding: [30, 30] });
        }
    });

    // =========================================================================
    // BOOTH DETAIL MODAL (Pop-up Sesuai Gambar)
    // =========================================================================
    function showBoothDetailModal(id, name, location, theme, ip, created, status, queue) {
        document.getElementById('detailBoothId').textContent = id;
        document.getElementById('detailBoothName').textContent = name;
        document.getElementById('detailBoothLocation').textContent = location || 'Belum diset';
        document.getElementById('detailBoothTheme').textContent = theme || 'Belum diset';
        document.getElementById('detailBoothIp').textContent = ip || '-';
        document.getElementById('detailBoothQueue').textContent = (queue || '0') + ' Pelanggan';
        document.getElementById('detailBoothCreated').textContent = created;

        const statusEl = document.getElementById('detailBoothStatus');
        if (status === 'active') {
            statusEl.className = 'font-bold text-emerald-600';
            statusEl.textContent = 'Aktif';
        } else if (status === 'maintenance') {
            statusEl.className = 'font-bold text-amber-600';
            statusEl.textContent = 'Maintenance';
        } else {
            statusEl.className = 'font-bold text-red-600';
            statusEl.textContent = 'Nonaktif';
        }

        const modal = document.getElementById('boothDetailModal');
        const content = document.getElementById('boothDetailModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeBoothDetailModal() {
        const modal = document.getElementById('boothDetailModal');
        const content = document.getElementById('boothDetailModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    // =========================================================================
    // LOCATION DETAIL MODAL (Pop-up Sesuai Gambar)
    // =========================================================================
    let currentLocImages = [];
    let currentLocImgIdx = 0;

    function showLocationDetailModal(id, name, city, address, themes, boothCount, status, previewImagesStr) {
        document.getElementById('detailLocId').textContent = id;
        document.getElementById('detailLocName').textContent = name;
        document.getElementById('detailLocCity').textContent = city || '-';
        document.getElementById('detailLocAddress').textContent = address || '-';
        document.getElementById('detailLocThemes').textContent = themes || 'Semua Tema';
        document.getElementById('detailLocBoothCount').textContent = boothCount + ' Unit';

        const statusEl = document.getElementById('detailLocStatus');
        if (status === 'active') {
            statusEl.className = 'font-bold text-emerald-600';
            statusEl.textContent = 'Aktif';
        } else {
            statusEl.className = 'font-bold text-red-600';
            statusEl.textContent = 'Nonaktif';
        }

        try {
            currentLocImages = JSON.parse(previewImagesStr || '[]');
        } catch(e) {
            currentLocImages = [];
        }
        
        currentLocImgIdx = 0;
        updateLocImgDisplay();

        const modal = document.getElementById('locationDetailModal');
        const content = document.getElementById('locationDetailModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
    
    function updateLocImgDisplay() {
        const imgEl = document.getElementById('detailLocImg');
        const noImgEl = document.getElementById('detailLocNoImg');
        const prevBtn = document.getElementById('locImgPrev');
        const nextBtn = document.getElementById('locImgNext');
        const indicator = document.getElementById('locImgIndicator');
        
        if (!currentLocImages || currentLocImages.length === 0) {
            imgEl.src = '';
            imgEl.classList.add('hidden');
            noImgEl.classList.remove('hidden');
            prevBtn.classList.add('hidden');
            nextBtn.classList.add('hidden');
            indicator.classList.add('hidden');
            return;
        }
        
        imgEl.classList.remove('hidden');
        noImgEl.classList.add('hidden');
        imgEl.src = '<?= base_url() ?>' + currentLocImages[currentLocImgIdx];
        
        if (currentLocImages.length > 1) {
            prevBtn.classList.remove('hidden');
            nextBtn.classList.remove('hidden');
            indicator.classList.remove('hidden');
            
            let indHtml = '';
            for (let i = 0; i < currentLocImages.length; i++) {
                if (i === currentLocImgIdx) {
                    indHtml += '<div class="w-2 h-2 rounded-full bg-primary"></div>';
                } else {
                    indHtml += '<div class="w-2 h-2 rounded-full bg-slate-300"></div>';
                }
            }
            indicator.innerHTML = indHtml;
        } else {
            prevBtn.classList.add('hidden');
            nextBtn.classList.add('hidden');
            indicator.classList.add('hidden');
        }
    }
    
    function slideLocImg(dir) {
        if (!currentLocImages || currentLocImages.length <= 1) return;
        currentLocImgIdx += dir;
        if (currentLocImgIdx < 0) currentLocImgIdx = currentLocImages.length - 1;
        if (currentLocImgIdx >= currentLocImages.length) currentLocImgIdx = 0;
        updateLocImgDisplay();
    }

    function closeLocationDetailModal() {
        const modal = document.getElementById('locationDetailModal');
        const content = document.getElementById('locationDetailModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    // =========================================================================
    // QR MODAL & MAINTENANCE MODAL
    // =========================================================================
    function showQrModal(boothId, boothName) {
        const modal = document.getElementById('qrModal');
        const content = document.getElementById('qrModalContent');
        const modalBoothId = document.getElementById('modalBoothId');
        const modalBoothName = document.getElementById('modalBoothName');
        const qrImage = document.getElementById('qrImageElement');
        const qrUrlInput = document.getElementById('qrUrlInput');
        const downloadBtn = document.getElementById('downloadQrBtn');
        const copyBtn = document.getElementById('copyBtn');

        copyBtn.innerHTML = '<span class="material-symbols-outlined text-[14px]">content_copy</span><span>Copy</span>';
        copyBtn.className = 'px-2.5 py-1.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-md text-xs font-semibold flex items-center gap-1 shadow-sm transition-all';

        const targetUrl = `<?= base_url('customer/booking') ?>?booth_id=${encodeURIComponent(boothId)}`;
        const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(targetUrl)}`;

        modalBoothId.textContent = boothId;
        modalBoothName.textContent = boothName;
        qrImage.src = qrApiUrl;
        qrUrlInput.value = targetUrl;
        downloadBtn.href = qrApiUrl;

        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeQrModal() {
        const modal = document.getElementById('qrModal');
        const content = document.getElementById('qrModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function copyQrUrl() {
        const urlInput = document.getElementById('qrUrlInput');
        urlInput.select();
        document.execCommand('copy');
        const copyBtn = document.getElementById('copyBtn');
        copyBtn.innerHTML = '<span class="material-symbols-outlined text-[14px]">done</span><span>Copied!</span>';
        copyBtn.className = 'px-2.5 py-1.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-md text-xs font-semibold flex items-center gap-1 shadow-sm transition-all';
    }

    const maintenanceBaseUrl = '<?= base_url("admin/booths") ?>';

    function showMaintenanceModal(boothId, boothName, action) {
        const modal = document.getElementById('maintenanceModal');
        const content = document.getElementById('maintenanceModalContent');
        const form = document.getElementById('maintenanceForm');
        const isMaintenance = (action === 'maintenance');

        document.getElementById('maintModalIcon').textContent = isMaintenance ? 'engineering' : 'play_circle';
        document.getElementById('maintModalIconWrap').className = isMaintenance
            ? 'w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center mx-auto mb-4'
            : 'w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4';
        document.getElementById('maintModalIcon').className = isMaintenance
            ? 'material-symbols-outlined text-4xl text-amber-600'
            : 'material-symbols-outlined text-4xl text-emerald-600';
        document.getElementById('maintModalTitle').textContent = isMaintenance ? 'Set ke Maintenance?' : 'Aktifkan Kembali?';
        document.getElementById('maintModalDesc').innerHTML = isMaintenance
            ? `Mesin <strong>${boothName}</strong> akan dialihkan ke mode <span class="font-bold text-amber-600">Maintenance</span> dan tidak dapat menerima reservasi baru.`
            : `Mesin <strong>${boothName}</strong> akan diaktifkan kembali ke status <span class="font-bold text-emerald-600">Aktif</span>.`;

        const confirmBtn = document.getElementById('maintConfirmBtn');
        confirmBtn.className = isMaintenance
            ? 'flex-1 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-sm rounded-lg shadow-md transition-all'
            : 'flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-lg shadow-md transition-colors';
        confirmBtn.textContent = isMaintenance ? 'Ya, Set Maintenance' : 'Ya, Aktifkan';

        form.action = `${maintenanceBaseUrl}/${boothId}/${action === 'maintenance' ? 'maintenance' : 'activate'}`;

        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeMaintenanceModal() {
        const modal = document.getElementById('maintenanceModal');
        const content = document.getElementById('maintenanceModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }
    
    // =========================================================================
    // THEMES MODAL JS
    // =========================================================================

    function editTema(key, label, imgUrl) {
        document.getElementById('editTemaKeyDisplay').textContent = key;
        document.getElementById('editTemaKey').value = key;
        document.getElementById('editTemaLabel').value = label;
        document.getElementById('formEditTema').action = `<?= base_url('admin/themes') ?>/${key}/update`;
        
        const imgEl = document.getElementById('editTemaImg');
        const noImgEl = document.getElementById('editTemaNoImg');
        
        if (imgUrl && !imgUrl.endsWith('/themes/')) {
            imgEl.src = imgUrl;
            imgEl.classList.remove('hidden');
            noImgEl.classList.add('hidden');
        } else {
            imgEl.src = '';
            imgEl.classList.add('hidden');
            noImgEl.classList.remove('hidden');
        }
        
        document.getElementById('modalEditTema').classList.remove('hidden');
    }

    function showPreviewTema(imgUrl, label, key) {
        document.getElementById('lihatTemaLabel').textContent = label;
        document.getElementById('lihatTemaId').textContent = '#' + key;
        document.getElementById('lihatTemaImg').src = imgUrl;
        document.getElementById('modalLihatTema').classList.remove('hidden');
    }

    function closePreviewTema() {
        document.getElementById('modalLihatTema').classList.add('hidden');
    }

    // Search Theme
    const searchTemaInput = document.getElementById('searchTema');
    if (searchTemaInput) {
        searchTemaInput.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#tableTema tbody tr');
            
            rows.forEach(row => {
                if(row.children.length > 1) { // exclude empty message row
                    const id = row.children[0].textContent.toLowerCase();
                    const name = row.children[2].textContent.toLowerCase();
                    if (id.includes(query) || name.includes(query)) {
                        row.classList.remove('hidden');
                    } else {
                        row.classList.add('hidden');
                    }
                }
            });
        });
    }
</script>

<?php $this->endSection(); ?>
