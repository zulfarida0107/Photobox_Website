<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

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

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <h2 class="font-h1 text-h1 text-on-surface">Manajemen Mesin Booth</h2>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Kelola status operasional dan inventaris perangkat keras Anda.</p>
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

        <!-- Stats Bento Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Total Mesin -->
            <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">devices</span>
                    <span class="text-blue-600 font-label-bold text-label-bold">Total</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Total Mesin</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= $statTotal ?? 0 ?> Unit</h3>
            </div>
            <!-- Aktif -->
            <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-emerald-600 bg-emerald-50 p-2 rounded-lg material-symbols-outlined">check_circle</span>
                    <span class="text-emerald-600 font-label-bold text-label-bold">
                        <?= $statTotal > 0 ? round(($statActive / $statTotal) * 100) : 0 ?>%
                    </span>
                </div>
                <p class="text-sm font-medium text-slate-500">Aktif Sekarang</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= $statActive ?? 0 ?> Unit</h3>
            </div>
            <!-- Maintenance -->
            <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-tertiary bg-tertiary-fixed p-2 rounded-lg material-symbols-outlined">build</span>
                    <span class="text-tertiary font-label-bold text-label-bold">
                        <?= ($statMaintenance ?? 0) > 0 ? 'High Priority' : 'Normal' ?>
                    </span>
                </div>
                <p class="text-sm font-medium text-slate-500">Maintenance</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= $statMaintenance ?? 0 ?> Unit</h3>
            </div>
            <!-- Antrean Aktif -->
            <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-secondary bg-secondary-fixed p-2 rounded-lg material-symbols-outlined">queue</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Total Antrean</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= $statTotalQueue ?? 0 ?></h3>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- TABEL 1: DAFTAR MESIN BOOTH -->
        <!-- ================================================================= -->
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
            <div class="p-6 bg-white flex flex-col sm:flex-row gap-3">
                <form method="get" action="<?= base_url('admin/booths') ?>" class="flex flex-col md:flex-row flex-1 gap-3 w-full">
                    <!-- Search Input -->
                    <div class="relative flex-1">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[20px]">search</span>
                        <input type="text" name="q" value="<?= esc($keyword ?? '') ?>" placeholder="Cari ID atau Nama Booth..."
                               class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white transition-all"/>
                    </div>
                    
                    <!-- Status Filter -->
                    <select name="status" onchange="this.form.submit()"
                            class="w-full md:w-48 px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white transition-all text-slate-600">
                        <option value="" <?= ($filterStatus ?? '') === '' ? 'selected' : '' ?>>Semua Status</option>
                        <option value="active"      <?= ($filterStatus ?? '') === 'active'      ? 'selected' : '' ?>>Aktif</option>
                        <option value="maintenance" <?= ($filterStatus ?? '') === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                        <option value="inactive"    <?= ($filterStatus ?? '') === 'inactive'    ? 'selected' : '' ?>>Nonaktif</option>
                    </select>

                    <!-- Reset button if search or filter is active -->
                    <?php if (!empty($keyword) || !empty($filterStatus)): ?>
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
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Lokasi</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Kapasitas</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Alamat IP</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase">Sesi Aktif</th>
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
                                        'maintenance' => ['bg-tertiary-fixed text-tertiary border border-tertiary/30', 'bg-tertiary', 'Maintenance'],
                                        'inactive'    => ['bg-red-50 text-red-600 border border-red-200', 'bg-red-500', 'Non-Aktif'],
                                    ];
                                    $st = $statusMap[$booth['status']] ?? ['bg-slate-100 text-slate-500', 'bg-slate-400', ucfirst($booth['status'])];
                                ?>
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="p-4 font-data-tabular text-data-tabular text-primary font-semibold text-center"><?= esc($booth['id']) ?></td>
                                    <td class="p-4 font-body-base text-body-base text-on-surface text-center"><?= esc($booth['name']) ?></td>
                                    <td class="p-4 font-body-base text-body-base text-slate-700 text-center">
                                        <?= !empty($booth['location_name']) ? esc($booth['location_name']) : '<span class="text-slate-400 italic">Pusat</span>' ?>
                                    </td>
                                    <td class="p-4 font-data-tabular text-data-tabular text-slate-700 font-semibold text-center">
                                        <?= esc($booth['capacity'] ?? 4) ?>
                                    </td>
                                    <td class="p-4 font-mono text-xs text-slate-600 text-center"><?= esc($booth['ip_address'] ?? '-') ?></td>
                                    <td class="p-4">
                                        <?php if (!empty($booth['active_booking_id'])): ?>
                                            <div class="flex items-center gap-2">
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
                                        <span class="text-lg font-black text-slate-500"><?= esc($booth['current_queue'] ?? 0) ?></span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold <?= $st[0] ?>">
                                            <span class="w-2 h-2 <?= $st[1] ?> rounded-full flex-shrink-0"></span>
                                            <?= $st[2] ?>
                                        </span>
                                    </td>
                                    <td class="p-4 font-data-tabular text-data-tabular text-outline text-center">
                                        <?= date('d M Y, H:i', strtotime($booth['created_at'])) ?>
                                    </td>
                                    <td class="p-4 text-center flex justify-center gap-2">
                                        <button type="button" onclick="showBoothDetailModal('<?= esc($booth['id'], 'js') ?>', '<?= esc($booth['name'], 'js') ?>', '<?= esc($booth['location_name'] ?? 'Pusat', 'js') ?>', '<?= esc($booth['ip_address'] ?? '-', 'js') ?>', '<?= esc($booth['capacity'] ?? 4, 'js') ?>', '<?= date('d M Y, H:i', strtotime($booth['created_at'])) ?>', '<?= date('d M Y, H:i', strtotime($booth['updated_at'])) ?>', '<?= $booth['status'] ?>', '<?= esc($booth['current_queue'] ?? 0, 'js') ?>', '<?= esc($booth['active_booking_id'] ?? '', 'js') ?>')"
                                            class="inline p-1.5 text-emerald-600 hover:bg-emerald-50 rounded transition-colors" title="Detail Booth">
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
                                                    class="inline p-1.5 text-tertiary hover:bg-tertiary-fixed rounded transition-colors" title="Set Maintenance"
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

            <!-- Pagination Booths -->
            <div class="mt-auto p-6 bg-white border-t border-slate-100 flex items-center justify-between">
                <span class="text-body-sm text-outline">
                    Menampilkan <?= count($booths ?? []) ?> dari <?= $totalBooths ?? 0 ?> mesin
                </span>
                <div class="flex gap-2">
                    <a href="<?= base_url('admin/booths?page=' . max(1, ($currentPage ?? 1) - 1) . '&q=' . ($keyword ?? '') . '&status=' . ($filterStatus ?? '') . '&loc_page=' . ($currentLocPage ?? 1) . '&loc_q=' . ($locationSearch ?? '')) ?>"
                       class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-sm">chevron_left</span>
                    </a>
                    <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                        <a href="<?= base_url('admin/booths?page=' . $i . '&q=' . ($keyword ?? '') . '&status=' . ($filterStatus ?? '') . '&loc_page=' . ($currentLocPage ?? 1) . '&loc_q=' . ($locationSearch ?? '')) ?>"
                           class="w-8 h-8 flex items-center justify-center border rounded-lg text-sm font-bold transition-colors <?= $i === ($currentPage ?? 1) ? 'border-primary bg-primary text-white' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    <a href="<?= base_url('admin/booths?page=' . min(($totalPages ?? 1), ($currentPage ?? 1) + 1) . '&q=' . ($keyword ?? '') . '&status=' . ($filterStatus ?? '') . '&loc_page=' . ($currentLocPage ?? 1) . '&loc_q=' . ($locationSearch ?? '')) ?>"
                       class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentPage ?? 1) >= ($totalPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- TABEL 2: DAFTAR TITIK LOKASI CABANG -->
        <!-- ================================================================= -->
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm mb-8">
            
            <!-- Location Table Header -->
            <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center bg-white gap-4">
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="w-1 h-6 bg-primary rounded-full"></div>
                    <h4 class="font-h2 text-h2 text-on-surface">Daftar Titik Lokasi Cabang</h4>
                </div>
                <button type="button" onclick="openAddLocationModal()"
                   class="bg-primary text-white px-4 py-2 rounded-lg font-label-bold text-label-bold flex items-center gap-2 hover:bg-primary-container transition-colors shadow-sm whitespace-nowrap w-full md:w-auto justify-center">
                    <span class="material-symbols-outlined text-[18px]">add_location_alt</span>
                    Tambah Lokasi
                </button>
            </div>

            <!-- Location Search Bar -->
            <div class="p-6 bg-white flex flex-col sm:flex-row gap-3">
                <form method="get" action="<?= base_url('admin/booths') ?>" class="flex flex-col sm:flex-row flex-1 gap-3 w-full">
                    <input type="hidden" name="page" value="<?= esc($currentPage ?? 1) ?>" />
                    <input type="hidden" name="q" value="<?= esc($keyword ?? '') ?>" />
                    <input type="hidden" name="status" value="<?= esc($filterStatus ?? '') ?>" />
                    <div class="relative flex-1">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[20px]">search</span>
                        <input type="text" name="loc_q" value="<?= esc($locationSearch ?? '') ?>" placeholder="Cari nama lokasi, kota, atau alamat..."
                               class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white transition-all"/>
                    </div>
                    <?php if (!empty($locationSearch)): ?>
                        <a href="<?= base_url('admin/booths?page=' . ($currentPage ?? 1) . '&q=' . ($keyword ?? '') . '&status=' . ($filterStatus ?? '')) ?>" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm font-semibold rounded-lg flex items-center justify-center transition-colors">
                            Reset
                        </a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Location Data Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="border-y border-slate-100 bg-white">
                        <tr>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">ID Lokasi</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Lokasi</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Wilayah</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase">Alamat Lengkap</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Koordinat</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Unit Booth</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Status</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 bg-white">
                        <?php if (!empty($locations)): ?>
                            <?php foreach ($locations as $loc): ?>
                                <?php
                                    $locStatus = $loc['status'] ?? 'active';
                                    $locActive = ($locStatus === 'active');
                                    $stLoc = $locActive
                                        ? ['bg-emerald-50 text-emerald-600 border border-emerald-200', 'bg-emerald-500', 'Aktif']
                                        : ['bg-red-50 text-red-600 border border-red-200', 'bg-red-500', 'Non-Aktif'];
                                ?>
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="p-4 font-data-tabular text-data-tabular text-primary font-semibold text-center">
                                        <?= esc($loc['id']) ?>
                                    </td>
                                    <td class="p-4 font-body-base text-body-base text-on-surface text-center">
                                        <?= esc($loc['name']) ?>
                                    </td>
                                    <td class="p-4 font-body-base text-body-base text-slate-700 text-center">
                                        <?= esc($loc['city'] ?? '-') ?>
                                    </td>
                                    <td class="p-4 text-xs text-slate-600 max-w-xs truncate" title="<?= esc($loc['address'] ?? '-') ?>">
                                        <?= esc($loc['address'] ?? '-') ?>
                                    </td>
                                    <td class="p-4 font-mono text-xs text-slate-600 text-center">
                                        <?php if (!empty($loc['latitude']) && !empty($loc['longitude'])): ?>
                                            <?= number_format((float)$loc['latitude'], 4) ?>, <?= number_format((float)$loc['longitude'], 4) ?>
                                        <?php else: ?>
                                            <span class="text-slate-400 italic">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="text-lg font-black text-slate-500"><?= esc($loc['booth_count'] ?? 0) ?></span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold <?= $stLoc[0] ?>">
                                            <span class="w-2 h-2 <?= $stLoc[1] ?> rounded-full flex-shrink-0"></span>
                                            <?= $stLoc[2] ?>
                                        </span>
                                    </td>
                                    <td class="p-4 text-center flex justify-center gap-2">
                                        <button type="button"
                                                onclick="showLocationDetailModal('<?= esc($loc['id'], 'js') ?>', '<?= esc($loc['name'], 'js') ?>', '<?= esc($loc['city'] ?? '', 'js') ?>', '<?= esc($loc['address'] ?? '', 'js') ?>', '<?= esc($loc['latitude'] ?? '', 'js') ?>', '<?= esc($loc['longitude'] ?? '', 'js') ?>', '<?= esc($locStatus, 'js') ?>', '<?= esc($loc['booth_count'] ?? 0, 'js') ?>')"
                                                class="inline p-1.5 text-emerald-600 hover:bg-emerald-50 rounded transition-colors" title="Detail Lokasi">
                                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                                        </button>
                                        <a href="<?= base_url('admin/locations/' . $loc['id'] . '/edit') ?>"
                                           class="inline p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit Lokasi">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                        <form action="<?= base_url('admin/locations/' . $loc['id'] . '/toggle') ?>" method="post" class="inline">
                                            <?= csrf_field() ?>
                                            <?php if ($locActive): ?>
                                                <button type="submit" class="inline p-1.5 text-tertiary hover:bg-tertiary-fixed rounded transition-colors" title="Nonaktifkan Lokasi">
                                                    <span class="material-symbols-outlined text-[20px]">pause_circle</span>
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" class="inline p-1.5 text-emerald-600 hover:bg-emerald-50 rounded transition-colors" title="Aktifkan Lokasi">
                                                    <span class="material-symbols-outlined text-[20px]">play_circle</span>
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                        <button type="button"
                                                class="inline p-1.5 text-red-500 hover:bg-red-50 rounded transition-colors" title="Hapus Lokasi"
                                                onclick="openUniversalDeleteModal('<?= base_url('admin/locations/' . $loc['id'] . '/delete') ?>', '<?= esc($loc['name'], 'js') ?>', 'Hapus Lokasi Cabang?')">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="p-10 text-center text-sm text-slate-400">
                                    Belum ada lokasi cabang terdaftar.
                                    <button type="button" onclick="openAddLocationModal()" class="text-primary font-semibold ml-1">Tambah sekarang</button>.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Locations -->
            <div class="mt-auto p-6 bg-white border-t border-slate-100 flex items-center justify-between">
                <span class="text-body-sm text-outline">
                    Menampilkan <?= count($locations ?? []) ?> dari <?= $totalLocations ?? 0 ?> lokasi
                </span>
                <div class="flex gap-2">
                    <a href="<?= base_url('admin/booths?page=' . ($currentPage ?? 1) . '&q=' . ($keyword ?? '') . '&status=' . ($filterStatus ?? '') . '&loc_page=' . max(1, ($currentLocPage ?? 1) - 1) . '&loc_q=' . ($locationSearch ?? '')) ?>"
                       class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentLocPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-sm">chevron_left</span>
                    </a>
                    <?php for ($i = 1; $i <= ($totalLocPages ?? 1); $i++): ?>
                        <a href="<?= base_url('admin/booths?page=' . ($currentPage ?? 1) . '&q=' . ($keyword ?? '') . '&status=' . ($filterStatus ?? '') . '&loc_page=' . $i . '&loc_q=' . ($locationSearch ?? '')) ?>"
                           class="w-8 h-8 flex items-center justify-center border rounded-lg text-sm font-bold transition-colors <?= $i === ($currentLocPage ?? 1) ? 'border-primary bg-primary text-white' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    <a href="<?= base_url('admin/booths?page=' . ($currentPage ?? 1) . '&q=' . ($keyword ?? '') . '&status=' . ($filterStatus ?? '') . '&loc_page=' . min(($totalLocPages ?? 1), ($currentLocPage ?? 1) + 1) . '&loc_q=' . ($locationSearch ?? '')) ?>"
                       class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentLocPage ?? 1) >= ($totalLocPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- PETA GEOGRAFIS DAN PANEL STATUS OPERASIONAL (ORIGINAL RESTORED) -->
        <!-- ================================================================= -->
        <div class="mt-8 mb-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Map Preview -->
            <div class="lg:col-span-2 bg-white border border-outline-variant rounded-xl shadow-sm p-6">
                <h3 class="font-h2 text-h2 mb-4">Sebaran Geografis Mesin</h3>
                <!-- Container Peta Leaflet Interaktif -->
                <div id="map" class="h-64 bg-slate-100 rounded-lg overflow-hidden border border-outline-variant relative" style="z-index: 1;"></div>
            </div>

            <!-- Alerts Panel -->
            <div class="bg-white border border-outline-variant rounded-xl shadow-sm p-6 flex flex-col justify-between">
                <div>
                    <h3 class="font-h2 text-h2 mb-2">Perlu Perhatian</h3>
                    <?php if (!empty($maintenanceBooths)): ?>
                        <p class="text-body-sm text-outline mb-4"><?= count($maintenanceBooths) ?> mesin dalam mode Maintenance.</p>
                        <div class="space-y-3">
                            <?php foreach ($maintenanceBooths as $mb): ?>
                                <div class="flex items-center space-x-3 p-3 bg-error-container/20 border-l-4 border-error rounded">
                                    <span class="material-symbols-outlined text-error">warning</span>
                                    <div>
                                        <p class="font-label-bold text-label-bold text-on-error-container"><?= esc($mb['id']) ?></p>
                                        <p class="text-[11px] text-on-error-container"><?= esc($mb['name']) ?> — Maintenance Diperlukan</p>
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
                   class="mt-6 w-full py-2 text-center border border-primary text-primary hover:bg-primary-container hover:text-white transition-all rounded-lg font-label-bold text-label-bold block">
                    Lihat Log Maintenance
                </a>
            </div>
        </div>

    </div>
    <?= $this->include('Admin/layouts/footer') ?>
</main>

<!-- ========================================================================= -->
<!-- MODALS -->
<!-- ========================================================================= -->

<!-- MODAL TAMBAH LOKASI -->
<div id="addLocationModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-lg w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="addLocationModalContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-white">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">add_location_alt</span>
                Tambah Lokasi Baru
            </h4>
            <button type="button" onclick="closeAddLocationModal()" class="text-slate-400 hover:text-slate-600">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="<?= base_url('admin/locations') ?>" method="post" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <div>
                <label for="loc_name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lokasi / Mall / Cabang *</label>
                <input type="text" name="name" id="loc_name" required placeholder="Contoh: Margo City Mall"
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 bg-white" />
            </div>
            <div>
                <label for="loc_city" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kota / Wilayah</label>
                <input type="text" name="city" id="loc_city" placeholder="Contoh: Depok"
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 bg-white" />
            </div>
            <div>
                <label for="loc_address" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                <textarea name="address" id="loc_address" rows="2" placeholder="Contoh: Jl. Margonda Raya No.358, Kemiri Muka, Kecamatan Beji"
                          class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 bg-white"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="loc_latitude" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Latitude</label>
                    <input type="text" name="latitude" id="loc_latitude" placeholder="Contoh: -6.373111"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 bg-white" />
                </div>
                <div>
                    <label for="loc_longitude" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Longitude</label>
                    <input type="text" name="longitude" id="loc_longitude" placeholder="Contoh: 106.834722"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 bg-white" />
                </div>
            </div>
            <div>
                <label for="loc_status" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status Operasional</label>
                <select name="status" id="loc_status" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 bg-white">
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </select>
            </div>
            <div class="flex gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeAddLocationModal()" class="flex-1 py-2.5 border border-slate-200 text-slate-700 font-semibold text-sm rounded-lg hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 bg-primary hover:bg-primary-container text-white font-semibold text-sm rounded-lg shadow-md transition-colors flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan Lokasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DETAIL LOKASI -->
<div id="locationDetailModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-4xl w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300 flex flex-col" id="locationDetailModalContent">
        
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-white">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">visibility</span>
                Detail Titik Lokasi Cabang
            </h4>
        </div>
        
        <div class="p-6 flex flex-col items-center bg-white">
            <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Details List 1: Informasi Cabang & Wilayah -->
                <div>
                    <h5 class="w-full text-left font-bold text-slate-700 mb-2 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-lg">storefront</span>
                        Informasi Cabang
                    </h5>
                    <div class="w-full space-y-3 bg-slate-50 p-5 rounded-2xl border border-slate-200 text-left text-sm shadow-sm h-full">
                        <div class="flex justify-between border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">ID Lokasi</span>
                            <span class="font-mono text-slate-800 font-bold" id="detail_loc_id"></span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">Nama Lokasi</span>
                            <span class="text-slate-800 font-semibold" id="detail_loc_name"></span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">Kota / Wilayah</span>
                            <span class="text-slate-800 font-semibold" id="detail_loc_city"></span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">Alamat Lengkap</span>
                            <span class="text-slate-800 text-xs font-medium text-right max-w-[200px]" id="detail_loc_address"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-slate-500">Koordinat Geografis</span>
                            <span class="font-mono text-slate-800 text-xs font-semibold" id="detail_loc_coords"></span>
                        </div>
                    </div>
                </div>

                <!-- Details List 2: Status & Kapasitas Unit -->
                <div>
                    <h5 class="w-full text-left font-bold text-slate-700 mb-2 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-lg">query_stats</span>
                        Status & Kapasitas
                    </h5>
                    <div class="w-full space-y-3 bg-slate-50 p-5 rounded-2xl border border-slate-200 text-left text-sm shadow-sm h-full">
                        <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">Status Operasional</span>
                            <span id="detail_loc_status_badge"></span>
                        </div>
                        <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">Total Unit Mesin</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-lg font-black text-slate-700" id="detail_loc_booth_count"></span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span class="font-semibold text-slate-500">Keterangan Lokasi</span>
                            <div class="w-full p-3 bg-white border border-slate-200 rounded-lg text-xs text-slate-600">
                                Titik lokasi ini terhubung ke peta geografis reservasi. Menonaktifkan lokasi ini akan otomatis menonaktifkan seluruh mesin booth yang terdaftar di dalamnya.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="w-full mt-16">
                <button type="button" onclick="closeLocationDetailModal()" class="w-full py-3 bg-primary hover:bg-primary-container text-white font-semibold text-sm rounded-xl transition-colors shadow-md">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div id="qrModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-sm w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="qrModalContent">
        <!-- Modal Header -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-white">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-purple-600">qr_code_2</span>
                QR Code Kiosk Booth
            </h4>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 flex flex-col items-center text-center">
            <p class="text-xs font-semibold text-purple-600 bg-purple-50 px-2.5 py-1 rounded-full mb-3" id="modalBoothId"></p>
            <h3 class="font-bold text-slate-800 text-base mb-1" id="modalBoothName"></h3>
            <p class="text-xs text-slate-400 mb-6">Scan QR untuk membuka halaman booking dengan booth ini terpilih otomatis.</p>
            
            <!-- QR Container -->
            <div class="p-3 bg-white border border-slate-200 rounded-2xl shadow-sm mb-6 flex items-center justify-center">
                <img id="qrImageElement" src="" alt="QR Code" class="w-48 h-48" />
            </div>

            <!-- URL Input (Copy link) -->
            <div class="w-full flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-lg p-2 mb-6">
                <input type="text" id="qrUrlInput" readonly class="bg-transparent text-xs text-slate-600 w-full outline-none select-all font-mono pl-1" />
                <button type="button" onclick="copyQrUrl()" class="px-2.5 py-1.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-md text-xs font-semibold flex items-center gap-1 shadow-sm transition-all" id="copyBtn">
                    <span class="material-symbols-outlined text-[14px]">content_copy</span>
                    <span>Copy</span>
                </button>
            </div>
            
            <!-- Action buttons -->
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

<!-- Maintenance / Activate Confirmation Modal -->
<div id="maintenanceModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="maintenanceModalContent">
        <div class="p-5 border-b border-slate-100 flex items-center bg-white">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                Konfirmasi Aksi Booth
            </h4>
        </div>
        <div class="p-6 text-center">
            <div id="maintModalIconWrap" class="w-14 h-14 rounded-full bg-tertiary-fixed flex items-center justify-center mx-auto mb-4">
                <span id="maintModalIcon" class="material-symbols-outlined text-4xl text-tertiary">engineering</span>
            </div>
            <h3 id="maintModalTitle" class="text-lg font-black text-slate-800 mb-2">Set ke Maintenance?</h3>
            <p id="maintModalDesc" class="text-sm text-slate-500 leading-relaxed mb-6"></p>
            <form id="maintenanceForm" method="post">
                <?= csrf_field() ?>
                <div class="flex gap-3">
                    <button type="button" onclick="closeMaintenanceModal()"
                            class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-sm rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="maintConfirmBtn"
                            class="flex-1 py-2.5 bg-tertiary hover:opacity-90 text-white font-bold text-sm rounded-lg shadow-md transition-all">
                        Ya, Set Maintenance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Booth Detail Modal -->
<div id="boothDetailModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-4xl w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300 flex flex-col" id="boothDetailModalContent">
        
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-white">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">visibility</span>
                Detail Mesin Booth
            </h4>
        </div>
        
        <div class="p-6 flex flex-col items-center bg-white">
            <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Details List 1: Informasi Perangkat -->
                <div>
                    <h5 class="w-full text-left font-bold text-slate-700 mb-2 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-lg">nest_display</span>
                        Informasi Perangkat
                    </h5>

                    <div class="w-full space-y-3 bg-slate-50 p-5 rounded-2xl border border-slate-200 text-left text-sm shadow-sm h-full">
                        <div class="flex justify-between border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">ID Booth</span>
                            <span class="font-mono text-slate-800 font-bold" id="modalBdId"></span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">Nama Mesin</span>
                            <span class="text-slate-800 font-semibold" id="modalBdName"></span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">Lokasi Cabang</span>
                            <span class="text-slate-800 font-semibold" id="modalBdLocation"></span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">Kapasitas Sesi</span>
                            <span class="text-slate-800 font-semibold" id="modalBdCapacity"></span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">Alamat IP</span>
                            <span class="font-mono text-slate-800 font-semibold" id="modalBdIp"></span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">Tanggal Terdaftar</span>
                            <span class="text-slate-800 font-semibold" id="modalBdCreated"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-slate-500">Terakhir Diperbarui</span>
                            <span class="text-slate-800 font-semibold" id="modalBdUpdated"></span>
                        </div>
                    </div>
                </div>

                <!-- Details List 2: Status Operasional -->
                <div>
                    <h5 class="w-full text-left font-bold text-slate-700 mb-2 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-lg">query_stats</span>
                        Status Operasional
                    </h5>
                    <div class="w-full space-y-3 bg-slate-50 p-5 rounded-2xl border border-slate-200 text-left text-sm shadow-sm h-full">
                        <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">Status Operasional</span>
                            <span id="modalBdStatus" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold">
                                <span id="modalBdStatusDot" class="w-2 h-2 rounded-full mr-2"></span>
                                <span id="modalBdStatusText"></span>
                            </span>
                        </div>
                        <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">Antrean Saat Ini</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-lg font-black text-slate-700" id="modalBdQueue"></span>
                                <span class="text-[10px] text-slate-400 font-semibold">Pelanggan</span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span class="font-semibold text-slate-500">Sesi Aktif Kiosk</span>
                            <div id="modalBdActiveSessionContainer" class="w-full"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="w-full mt-16">
                <button type="button" onclick="closeBoothDetailModal()" class="w-full py-3 bg-primary hover:bg-primary-container text-white font-semibold text-sm rounded-xl transition-colors shadow-md">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/40 backdrop-blur-sm opacity-0 transition-opacity duration-300">
    <div id="deleteConfirmModalContent" class="bg-white rounded-3xl p-10 max-w-lg w-full mx-4 border border-slate-100 transform scale-95 opacity-0 transition-all duration-300 flex flex-col items-center text-center">
        <div class="w-20 h-20 rounded-2xl bg-red-50/70 flex items-center justify-center mb-6">
            <span class="material-symbols-outlined text-[32px] text-red-500">delete</span>
        </div>
        <h3 class="text-2xl font-black text-slate-900 mb-3">Konfirmasi Hapus</h3>
        <p class="text-[16px] text-slate-500 leading-relaxed mb-8">
            Apakah Anda yakin ingin menghapus data ini?<br>Tindakan ini tidak dapat dibatalkan.
        </p>
        <div class="flex gap-4 w-full">
            <button onclick="closeUniversalDeleteModal()" class="flex-1 py-3.5 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold text-base rounded-2xl transition-all">Batal</button>
            <form id="deleteForm" method="post" action="" class="flex-1">
                <?= csrf_field() ?>
                <button type="submit" class="w-full py-3.5 bg-red-600 hover:bg-red-700 text-white font-bold text-base rounded-2xl transition-all">Hapus</button>
            </form>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize map centered in Indonesia
        const map = L.map('map').setView([-2.548926, 118.0148634], 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        const boothsData = <?= $boothsJson ?? '[]' ?>;
        let bounds = [];

        boothsData.forEach(function(booth) {
            if (booth.latitude && booth.longitude) {
                const lat = parseFloat(booth.latitude);
                const lng = parseFloat(booth.longitude);
                bounds.push([lat, lng]);

                const marker = L.marker([lat, lng]).addTo(map);
                
                const statusColor = booth.status === 'active' ? 'text-emerald-600' : 'text-slate-500';
                const statusLabel = booth.status === 'active' ? 'Aktif' : 'Nonaktif/Maintenance';

                marker.bindPopup(`
                    <div class="p-1 font-sans">
                        <h4 class="font-bold text-slate-800 text-sm mb-1">${booth.name}</h4>
                        <p class="text-xs text-slate-500 m-0">Status: <span class="font-semibold ${statusColor}">${statusLabel}</span></p>
                    </div>
                `);
            }
        });

        // Fit map bounds if there are booths with coordinates
        if (bounds.length > 0) {
            map.fitBounds(bounds, { padding: [30, 30] });
        }
    });

    // =========================================================================
    // MODAL LOKASI FUNCTIONS
    // =========================================================================
    function openAddLocationModal() {
        const modal = document.getElementById('addLocationModal');
        const content = document.getElementById('addLocationModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeAddLocationModal() {
        const modal = document.getElementById('addLocationModal');
        const content = document.getElementById('addLocationModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function openEditLocationModal(id, name, city, address, lat, lng, status) {
        const modal = document.getElementById('editLocationModal');
        const content = document.getElementById('editLocationModalContent');
        const form = document.getElementById('editLocationForm');

        form.action = `<?= base_url('admin/locations') ?>/${id}/update`;
        document.getElementById('edit_loc_id').value = id;
        document.getElementById('edit_loc_name').value = name;
        document.getElementById('edit_loc_city').value = city;
        document.getElementById('edit_loc_address').value = address;
        document.getElementById('edit_loc_latitude').value = lat;
        document.getElementById('edit_loc_longitude').value = lng;
        document.getElementById('edit_loc_status').value = status;

        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeEditLocationModal() {
        const modal = document.getElementById('editLocationModal');
        const content = document.getElementById('editLocationModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function showLocationDetailModal(id, name, city, address, lat, lng, status, boothCount) {
        const modal = document.getElementById('locationDetailModal');
        const content = document.getElementById('locationDetailModalContent');

        document.getElementById('detail_loc_id').textContent = id;
        document.getElementById('detail_loc_name').textContent = name;
        document.getElementById('detail_loc_city').textContent = city || '-';
        document.getElementById('detail_loc_booth_count').textContent = boothCount + ' Unit';
        document.getElementById('detail_loc_address').textContent = address || '-';
        
        let coordText = '-';
        if (lat && lng) {
            coordText = `Lat: ${lat}, Lng: ${lng}`;
        }
        document.getElementById('detail_loc_coords').textContent = coordText;

        const badgeWrap = document.getElementById('detail_loc_status_badge');
        if (status === 'active') {
            badgeWrap.innerHTML = '<span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200"><span class="w-2 h-2 bg-emerald-500 rounded-full"></span>Aktif</span>';
        } else {
            badgeWrap.innerHTML = '<span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-200"><span class="w-2 h-2 bg-red-500 rounded-full"></span>Non-Aktif</span>';
        }

        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeLocationDetailModal() {
        const modal = document.getElementById('locationDetailModal');
        const content = document.getElementById('locationDetailModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    // =========================================================================
    // BOOTH FUNCTIONS (QR, DETAIL, MAINTENANCE, DELETE)
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

        // Reset copy button state
        copyBtn.innerHTML = '<span class="material-symbols-outlined text-[14px]">content_copy</span><span>Copy</span>';
        copyBtn.className = 'px-2.5 py-1.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-md text-xs font-semibold flex items-center gap-1 shadow-sm transition-all';

        const targetUrl = `<?= base_url('customer/booking') ?>?booth_id=${encodeURIComponent(boothId)}`;
        const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(targetUrl)}`;

        modalBoothId.textContent = boothId;
        modalBoothName.textContent = boothName;
        qrImage.src = qrApiUrl;
        qrUrlInput.value = targetUrl;
        downloadBtn.href = qrApiUrl;

        // Show Modal
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
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
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
        const modal   = document.getElementById('maintenanceModal');
        const content = document.getElementById('maintenanceModalContent');
        const form    = document.getElementById('maintenanceForm');

        const isMaintenance = (action === 'maintenance');

        document.getElementById('maintModalIcon').textContent    = isMaintenance ? 'engineering' : 'play_circle';
        document.getElementById('maintModalIconWrap').className  = isMaintenance
            ? 'w-14 h-14 rounded-full bg-tertiary-fixed flex items-center justify-center mx-auto mb-4'
            : 'w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4';
        document.getElementById('maintModalIcon').className      = isMaintenance
            ? 'material-symbols-outlined text-4xl text-tertiary'
            : 'material-symbols-outlined text-4xl text-emerald-600';
        document.getElementById('maintModalTitle').textContent   = isMaintenance ? 'Set ke Maintenance?' : 'Aktifkan Kembali?';
        document.getElementById('maintModalDesc').innerHTML      = isMaintenance
            ? `Mesin <strong>${boothName}</strong> akan dialihkan ke mode <span class="font-bold text-tertiary">Maintenance</span> dan tidak dapat menerima reservasi baru.`
            : `Mesin <strong>${boothName}</strong> akan diaktifkan kembali ke status <span class="font-bold text-emerald-600">Aktif</span>.`;

        const confirmBtn = document.getElementById('maintConfirmBtn');
        confirmBtn.className = isMaintenance
            ? 'flex-1 py-2.5 bg-tertiary hover:opacity-90 text-white font-bold text-sm rounded-lg shadow-md transition-all'
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
        const modal   = document.getElementById('maintenanceModal');
        const content = document.getElementById('maintenanceModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function showBoothDetailModal(id, name, locationName, ip, capacity, created, updated, status, queue, activeBookingId) {
        const modal = document.getElementById('boothDetailModal');
        const content = document.getElementById('boothDetailModalContent');
        
        document.getElementById('modalBdId').textContent = id;
        document.getElementById('modalBdName').textContent = name;
        document.getElementById('modalBdLocation').textContent = locationName || 'Pusat';
        document.getElementById('modalBdCapacity').textContent = (capacity || 4) + ' Orang';
        document.getElementById('modalBdIp').textContent = ip;
        document.getElementById('modalBdCreated').textContent = created;
        document.getElementById('modalBdUpdated').textContent = updated;
        
        const statusEl = document.getElementById('modalBdStatus');
        const dotEl = document.getElementById('modalBdStatusDot');
        const textEl = document.getElementById('modalBdStatusText');
        
        if (status === 'active') {
            statusEl.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800';
            dotEl.className = 'w-2 h-2 rounded-full mr-2 bg-emerald-500';
            textEl.textContent = 'Aktif';
        } else if (status === 'maintenance') {
            statusEl.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800';
            dotEl.className = 'w-2 h-2 rounded-full mr-2 bg-amber-500';
            textEl.textContent = 'Maintenance';
        } else {
            statusEl.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700';
            dotEl.className = 'w-2 h-2 rounded-full mr-2 bg-slate-500';
            textEl.textContent = 'Nonaktif';
        }
        
        document.getElementById('modalBdQueue').textContent = queue;
        
        const sessionContainer = document.getElementById('modalBdActiveSessionContainer');
        if (activeBookingId && activeBookingId.trim() !== '') {
            const baseUrl = '<?= base_url('admin/booths/') ?>';
            const formAction = baseUrl + id + '/reset-session';
            const csrfHtml = '<?= csrf_field() ?>';
            
            sessionContainer.innerHTML = `
                <div class="flex items-center gap-2 bg-blue-50 border border-blue-100 rounded-lg p-3">
                    <span class="material-symbols-outlined text-blue-600">meeting_room</span>
                    <div>
                        <p class="text-xs font-bold text-blue-800">#${activeBookingId}</p>
                        <p class="text-[10px] text-blue-500">Sesi Kiosk Berjalan</p>
                    </div>
                    <form action="${formAction}" method="post" class="ml-auto">
                        ${csrfHtml}
                        <button type="submit" class="p-1.5 bg-white border border-blue-200 text-red-600 rounded-lg hover:bg-red-50 hover:border-red-200 transition-colors shadow-sm" title="Reset Sesi Paksa" onclick="return confirm('Paksa keluar customer dari sesi ini?')">
                            <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                        </button>
                    </form>
                </div>
            `;
        } else {
            sessionContainer.innerHTML = '<p class="text-xs text-slate-400 font-medium italic">Tidak ada sesi aktif (Idle)</p>';
        }

        // Show Modal
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
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function openUniversalDeleteModal(url, name, titleText) {
        const modal = document.getElementById('deleteConfirmModal');
        const content = document.getElementById('deleteConfirmModalContent');
        
        document.getElementById('deleteForm').action = url;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Trigger animation
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeUniversalDeleteModal() {
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

<?php $this->endSection(); ?>
