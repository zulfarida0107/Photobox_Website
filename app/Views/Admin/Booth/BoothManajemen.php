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
                    <span class="text-blue-700 font-bold">Daftar Mesin Booth</span>
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
                <a href="<?= base_url('admin/booths/export') ?>"
                   class="flex items-center gap-2 px-4 py-2.5 border border-primary text-primary rounded-lg text-xs font-semibold hover:bg-blue-50/50 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">cloud_download</span>
                    Ekspor Laporan
                </a>
            </div>
        </div>

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
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <!-- Total Mesin -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">devices</span>
                    <span class="text-blue-600 font-label-bold text-label-bold">Total</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Total Mesin</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= $statTotal ?? 0 ?> Unit</h3>
            </div>
            <!-- Aktif -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
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
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
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
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-slate-500 bg-slate-100 p-2 rounded-lg material-symbols-outlined">confirmation_number</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Total Antrean</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= $statTotalQueue ?? 0 ?></h3>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
            <!-- Table Header -->
            <div class="p-4 border-b border-slate-200 flex items-center justify-between bg-white">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-5 bg-primary rounded-full"></div>
                    <h4 class="font-h2 text-h2 text-on-surface text-base">Daftar Mesin Booth</h4>
                </div>
                <a href="<?= base_url('admin/booths/create') ?>"
                   class="bg-primary text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 hover:bg-primary-container transition-colors shadow-sm whitespace-nowrap">
                    <span class="material-symbols-outlined text-[16px]">devices</span>
                    Tambah Booth Baru
                </a>
            </div>

            <!-- Table Toolbar -->
            <div class="p-3 bg-slate-50/50 border-b border-slate-200">
                <form method="get" action="<?= base_url('admin/booths') ?>" class="flex flex-col sm:flex-row gap-3 items-center justify-between w-full">
                    <!-- Search Input -->
                    <div class="relative w-full sm:flex-1">
                        <span class="material-symbols-outlined absolute left-3 top-2 text-slate-400 text-[18px]">search</span>
                        <input type="text" name="q" value="<?= esc($keyword ?? '') ?>" placeholder="Cari ID atau Nama Booth..."
                               class="w-full pl-9 pr-3 py-1.5 border border-slate-200 rounded-lg text-xs outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white transition-all"/>
                    </div>
                    
                    <!-- Status Filter -->
                    <div class="relative w-full sm:w-60 flex items-center gap-2 bg-white border border-slate-200 px-3 py-1.5 rounded-lg text-xs shadow-sm">
                        <span class="material-symbols-outlined text-slate-400 text-sm">filter_list</span>
                        <select name="status" onchange="this.form.submit()"
                                class="border-none bg-transparent p-0 focus:ring-0 text-xs font-semibold text-slate-700 outline-none flex-1">
                            <option value="" <?= ($filterStatus ?? '') === '' ? 'selected' : '' ?>>Semua Status</option>
                            <option value="active"      <?= ($filterStatus ?? '') === 'active'      ? 'selected' : '' ?>>Aktif</option>
                            <option value="maintenance" <?= ($filterStatus ?? '') === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                            <option value="inactive"    <?= ($filterStatus ?? '') === 'inactive'    ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Data Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-center">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="p-4 font-label-bold text-label-bold text-slate-600 uppercase tracking-wider text-center">ID Booth</th>
                            <th class="p-4 font-label-bold text-label-bold text-slate-600 uppercase tracking-wider text-center">Nama Mesin</th>
                            <th class="p-4 font-label-bold text-label-bold text-slate-600 uppercase tracking-wider text-center">Alamat IP</th>
                            <th class="p-4 font-label-bold text-label-bold text-slate-600 uppercase tracking-wider text-center">Sesi Aktif</th>
                            <th class="p-4 font-label-bold text-label-bold text-slate-600 uppercase tracking-wider text-center">Antrean</th>
                            <th class="p-4 font-label-bold text-label-bold text-slate-600 uppercase tracking-wider text-center">Status</th>
                            <th class="p-4 font-label-bold text-label-bold text-slate-600 uppercase tracking-wider text-center">Waktu Daftar</th>
                            <th class="p-4 font-label-bold text-label-bold text-slate-600 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (!empty($booths)): ?>
                            <?php foreach ($booths as $booth): ?>
                                <?php
                                    $statusMap = [
                                        'active'      => ['bg-emerald-100 text-emerald-800', 'bg-emerald-500', 'Aktif'],
                                        'maintenance' => ['bg-amber-100 text-amber-800', 'bg-amber-500', 'Maintenance'],
                                        'inactive'    => ['bg-slate-100 text-slate-700', 'bg-slate-500', 'Nonaktif'],
                                    ];
                                    $st = $statusMap[$booth['status']] ?? ['bg-slate-100 text-slate-500', 'bg-slate-400', ucfirst($booth['status'])];
                                ?>
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="p-4 font-data-tabular text-data-tabular font-semibold text-center">
                                        <a href="<?= base_url('admin/booths/' . $booth['id']) ?>" class="text-primary hover:underline">
                                            <?= esc($booth['id']) ?>
                                        </a>
                                    </td>
                                    <td class="p-4 font-body-base text-body-base text-on-surface text-center"><?= esc($booth['name']) ?></td>
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
                                                    <button type="submit" class="p-1 text-purple-600 hover:bg-purple-50 rounded transition-colors" title="Forced Reset Sesi" onclick="return confirm('Paksa keluar customer dari sesi ini dan reset layar booth?')">
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
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $st[0] ?>">
                                            <span class="w-1.5 h-1.5 <?= $st[1] ?> rounded-full mr-1.5"></span>
                                            <?= $st[2] ?>
                                        </span>
                                    </td>
                                    <td class="p-4 font-data-tabular text-data-tabular text-outline text-center">
                                        <?= date('d M Y, H:i', strtotime($booth['created_at'])) ?>
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <!-- Detail: Mata Hijau -->
                                            <a href="<?= base_url('admin/booths/' . $booth['id']) ?>"
                                               class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Detail Booth">
                                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                                            </a>
                                            <!-- QR Code Kiosk: Layout Ungu -->
                                            <button type="button"
                                                    class="p-1.5 text-purple-600 hover:bg-purple-100 rounded-lg transition-colors" title="Generate QR Code"
                                                    onclick="showQrModal('<?= esc($booth['id'], 'js') ?>', '<?= esc($booth['name'], 'js') ?>')">
                                                <span class="material-symbols-outlined text-[20px]">qr_code</span>
                                            </button>
                                            <!-- Edit: Pensil Biru -->
                                            <a href="<?= base_url('admin/booths/' . $booth['id'] . '/edit') ?>"
                                               class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="Edit">
                                                <span class="material-symbols-outlined text-[20px]">edit</span>
                                            </a>
                                            <!-- Maintenance: Kunci Pas Jingga -->
                                            <?php if ($booth['status'] === 'active'): ?>
                                                <form action="<?= base_url('admin/booths/' . $booth['id'] . '/maintenance') ?>" method="post" class="inline">
                                                    <?= csrf_field() ?>
                                                    <button type="submit"
                                                            class="p-1.5 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors" title="Set Maintenance"
                                                            onclick="return confirm('Set booth ini ke mode Maintenance?')">
                                                        <span class="material-symbols-outlined text-[20px]">engineering</span>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <form action="<?= base_url('admin/booths/' . $booth['id'] . '/activate') ?>" method="post" class="inline">
                                                    <?= csrf_field() ?>
                                                    <button type="submit"
                                                            class="p-1.5 text-emerald-600 hover:bg-emerald-100 rounded-lg transition-colors" title="Aktifkan Kembali">
                                                        <span class="material-symbols-outlined text-[20px]">play_circle</span>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            <!-- Hapus: Tempat Sampah Merah -->
                                            <form action="<?= base_url('admin/booths/' . $booth['id'] . '/delete') ?>" method="post" class="inline">
                                                <?= csrf_field() ?>
                                                <button type="submit"
                                                        class="p-1.5 text-red-500 hover:bg-red-100 rounded-lg transition-colors" title="Hapus"
                                                        onclick="return confirm('Hapus booth ini? Data terkait booking & slot akan terpengaruh.')">
                                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="p-10 text-center text-sm text-slate-400">
                                    Belum ada booth terdaftar.
                                    <a href="<?= base_url('admin/booths/create') ?>" class="text-primary font-semibold ml-1">Tambah sekarang</a>.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                <span class="text-xs text-slate-500 font-medium">
                    Menampilkan <?= count($booths ?? []) ?> dari <?= $totalBooths ?? 0 ?> mesin
                </span>
                <div class="flex gap-1">
                    <a href="<?= base_url('admin/booths?page=' . max(1, ($currentPage ?? 1) - 1) . '&q=' . ($keyword ?? '') . '&status=' . ($filterStatus ?? '')) ?>"
                       class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded text-slate-400 hover:bg-slate-50 transition-colors <?= ($currentPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-sm">chevron_left</span>
                    </a>
                    <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                        <a href="<?= base_url('admin/booths?page=' . $i . '&q=' . ($keyword ?? '') . '&status=' . ($filterStatus ?? '')) ?>"
                           class="w-8 h-8 flex items-center justify-center border rounded text-sm font-bold transition-colors <?= $i === ($currentPage ?? 1) ? 'border-primary bg-primary text-white' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    <a href="<?= base_url('admin/booths?page=' . min(($totalPages ?? 1), ($currentPage ?? 1) + 1) . '&q=' . ($keyword ?? '') . '&status=' . ($filterStatus ?? '')) ?>"
                       class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded text-slate-400 hover:bg-slate-50 transition-colors <?= ($currentPage ?? 1) >= ($totalPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Map Preview + Alert Section -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
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

        <?= $this->include('Admin/layouts/footer') ?>
    </div>
</main>

<!-- QR Code Modal -->
<div id="qrModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-sm w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="qrModalContent">
        <!-- Modal Header -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-purple-600">qr_code_2</span>
                QR Code Kiosk Booth
            </h4>
            <button type="button" onclick="closeQrModal()" class="text-slate-400 hover:text-slate-600">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
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
</script>

<?php $this->endSection(); ?>
