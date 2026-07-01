<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<main class="pt-16 min-h-screen flex flex-col">
    <div class="p-6 flex-1">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-4">
            <a class="hover:text-blue-700" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <a class="hover:text-blue-700" href="<?= base_url('admin/booths') ?>">Manajemen Mesin Booth</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-blue-700">Detail Booth</span>
        </nav>

        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="font-h1 text-h1 text-on-surface">Detail Booth <?= esc($booth['id']) ?></h2>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Informasi lengkap, konfigurasi jaringan, dan status antrean mesin booth.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?= base_url('admin/booths/' . $booth['id'] . '/edit') ?>"
                   class="flex items-center gap-2 border border-slate-200 bg-white text-slate-700 px-4 py-2 rounded-lg font-semibold text-sm hover:bg-slate-50 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-sm">edit</span>
                    Edit Booth
                </a>
            </div>
        </div>

        <!-- Detail Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl">
            <!-- Main Info Card -->
            <div class="md:col-span-2 bg-white border border-outline-variant rounded-xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-outline-variant bg-slate-50/50 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">nest_display</span>
                    <h4 class="font-bold text-on-surface">Informasi Perangkat Mesin</h4>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-3 gap-4 border-b border-slate-100 pb-4">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">ID Booth</span>
                        <span class="col-span-2 text-sm text-slate-800 font-mono font-semibold"><?= esc($booth['id']) ?></span>
                    </div>

                    <div class="grid grid-cols-3 gap-4 border-b border-slate-100 pb-4">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Lokasi / Mesin</span>
                        <span class="col-span-2 text-sm text-slate-800 font-semibold"><?= esc($booth['name']) ?></span>
                    </div>

                    <div class="grid grid-cols-3 gap-4 border-b border-slate-100 pb-4">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Alamat IP Perangkat</span>
                        <span class="col-span-2 text-sm text-slate-800 font-mono"><?= esc($booth['ip_address'] ?? '-') ?></span>
                    </div>

                    <div class="grid grid-cols-3 gap-4 border-b border-slate-100 pb-4">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Koordinat Geografis</span>
                        <span class="col-span-2 text-sm text-slate-800 font-mono">
                            <?php if (!empty($booth['latitude']) && !empty($booth['longitude'])): ?>
                                Lat: <?= esc($booth['latitude']) ?>, Lng: <?= esc($booth['longitude']) ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </span>
                    </div>

                    <div class="grid grid-cols-3 gap-4 border-b border-slate-100 pb-4">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Terdaftar</span>
                        <span class="col-span-2 text-sm text-slate-800"><?= date('d M Y, H:i', strtotime($booth['created_at'])) ?></span>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Terakhir Diperbarui</span>
                        <span class="col-span-2 text-sm text-slate-800"><?= date('d M Y, H:i', strtotime($booth['updated_at'])) ?></span>
                    </div>
                </div>
            </div>

            <!-- Stats/Operational Card -->
            <div class="bg-white border border-outline-variant rounded-xl shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="p-6 border-b border-outline-variant bg-slate-50/50 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">query_stats</span>
                        <h4 class="font-bold text-on-surface">Status Operasional</h4>
                    </div>

                    <div class="p-6 space-y-6">
                        <!-- Status operational -->
                        <div>
                            <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status Operasional</span>
                            <?php if ($booth['status'] === 'active'): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                                    Aktif & Online
                                </span>
                            <?php elseif ($booth['status'] === 'maintenance'): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                    <span class="w-2 h-2 bg-amber-500 rounded-full mr-2"></span>
                                    Maintenance / Perbaikan
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                    <span class="w-2 h-2 bg-slate-500 rounded-full mr-2"></span>
                                    Nonaktif
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Current Queue -->
                        <div>
                            <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Antrean Saat Ini</span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-4xl font-black text-slate-700"><?= esc($booth['current_queue'] ?? 0) ?></span>
                                <span class="text-xs text-slate-400 font-semibold">Pelanggan Mengantre</span>
                            </div>
                        </div>

                        <!-- Active Session -->
                        <div>
                            <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Sesi Aktif Kiosk</span>
                            <?php if (!empty($booth['active_booking_id'])): ?>
                                <div class="flex items-center gap-2 bg-blue-50 border border-blue-100 rounded-lg p-3">
                                    <span class="material-symbols-outlined text-blue-600">meeting_room</span>
                                    <div>
                                        <p class="text-xs font-bold text-blue-800">#<?= esc($booth['active_booking_id']) ?></p>
                                        <p class="text-[10px] text-blue-500">Sesi Kiosk Berjalan</p>
                                    </div>
                                    <form action="<?= base_url('admin/booths/' . $booth['id'] . '/reset-session') ?>" method="post" class="ml-auto">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="p-1.5 bg-white border border-blue-200 text-red-600 rounded-lg hover:bg-red-50 hover:border-red-200 transition-colors shadow-sm" title="Reset Sesi Paksa" onclick="return confirm('Paksa keluar customer dari sesi ini?')">
                                            <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                                        </button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <p class="text-xs text-slate-400 font-medium italic">Tidak ada sesi aktif (Idle)</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <a href="<?= base_url('admin/booths') ?>" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">arrow_back</span>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <?= $this->include('Admin/layouts/footer') ?>
</main>

<?php $this->endSection(); ?>
