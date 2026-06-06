<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<main class="pt-16 min-h-screen flex flex-col">
    <div class="p-6 flex-1">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 mb-6 text-slate-500 text-xs font-medium">
            <a class="hover:text-primary transition-colors" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-slate-900">Mesin Booth</span>
        </nav>


        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
            <div>
                <h2 class="font-h1 text-h1 text-on-background">Daftar Mesin Booth</h2>
                <p class="font-body-sm text-body-sm text-outline">Kelola status operasional dan inventaris perangkat keras Anda.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="<?= base_url('admin/booths/create') ?>"
                   class="bg-primary text-on-primary px-4 py-2.5 rounded-lg font-label-bold text-label-bold flex items-center hover:bg-primary-container transition-colors shadow-sm">
                    <span class="material-symbols-outlined mr-2">add_circle</span>
                    Tambah Booth Baru
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

        <!-- Stats Bento Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Total Mesin -->
            <div class="bg-white border border-outline-variant p-5 rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="material-symbols-outlined text-blue-600 bg-blue-50 p-2 rounded-lg">devices</span>
                    <span class="text-blue-600 font-label-bold text-label-bold">Total</span>
                </div>
                <p class="text-outline text-label-bold uppercase">Total Mesin</p>
                <h3 class="text-2xl font-bold text-on-surface"><?= $statTotal ?? 0 ?> Unit</h3>
            </div>
            <!-- Aktif -->
            <div class="bg-white border border-outline-variant p-5 rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="material-symbols-outlined text-emerald-600 bg-emerald-50 p-2 rounded-lg">check_circle</span>
                    <span class="text-emerald-600 font-label-bold text-label-bold">
                        <?= $statTotal > 0 ? round(($statActive / $statTotal) * 100) : 0 ?>%
                    </span>
                </div>
                <p class="text-outline text-label-bold uppercase">Aktif Sekarang</p>
                <h3 class="text-2xl font-bold text-on-surface"><?= $statActive ?? 0 ?> Unit</h3>
            </div>
            <!-- Maintenance -->
            <div class="bg-white border border-outline-variant p-5 rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="material-symbols-outlined text-tertiary p-2 bg-tertiary-fixed rounded-lg">build</span>
                    <span class="text-tertiary font-label-bold text-label-bold">
                        <?= ($statMaintenance ?? 0) > 0 ? 'High Priority' : 'Normal' ?>
                    </span>
                </div>
                <p class="text-outline text-label-bold uppercase">Maintenance</p>
                <h3 class="text-2xl font-bold text-on-surface"><?= $statMaintenance ?? 0 ?> Unit</h3>
            </div>
            <!-- Antrean Aktif -->
            <div class="bg-white border border-outline-variant p-5 rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="material-symbols-outlined text-secondary bg-secondary-fixed p-2 rounded-lg">queue</span>
                </div>
                <p class="text-outline text-label-bold uppercase">Total Antrean</p>
                <h3 class="text-2xl font-bold text-on-surface"><?= $statTotalQueue ?? 0 ?></h3>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white border border-outline-variant rounded-xl shadow-sm overflow-hidden">
            <!-- Table Toolbar -->
            <div class="px-6 py-4 border-b border-outline-variant flex flex-col md:flex-row md:items-center justify-between gap-4">
                <form method="get" action="<?= base_url('admin/booths') ?>" class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative w-full md:w-96">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
                        <input name="q" value="<?= esc($keyword ?? '') ?>"
                               class="w-full pl-10 pr-4 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-body-sm font-body-sm"
                               placeholder="Cari ID atau Nama Booth..."/>
                    </div>
                    <select name="status" onchange="this.form.submit()"
                            class="px-3 py-2 border border-outline-variant rounded-lg text-body-sm text-secondary hover:bg-slate-50 transition-colors">
                        <option value="" <?= ($filterStatus ?? '') === '' ? 'selected' : '' ?>>Semua Status</option>
                        <option value="active"      <?= ($filterStatus ?? '') === 'active'      ? 'selected' : '' ?>>Aktif</option>
                        <option value="maintenance" <?= ($filterStatus ?? '') === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                        <option value="inactive"    <?= ($filterStatus ?? '') === 'inactive'    ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </form>
                <a href="<?= base_url('admin/booths/export') ?>"
                   class="px-3 py-2 border border-outline-variant rounded-lg flex items-center text-body-sm text-secondary hover:bg-slate-50 transition-colors whitespace-nowrap">
                    <span class="material-symbols-outlined mr-2">download</span> Export
                </a>
            </div>

            <!-- Data Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant">
                            <th class="p-4 font-label-bold text-label-bold text-on-surface uppercase tracking-wider">ID Booth</th>
                            <th class="p-4 font-label-bold text-label-bold text-on-surface uppercase tracking-wider">Nama Mesin</th>
                            <th class="p-4 font-label-bold text-label-bold text-on-surface uppercase tracking-wider text-center">Antrean</th>
                            <th class="p-4 font-label-bold text-label-bold text-on-surface uppercase tracking-wider">Status</th>
                            <th class="p-4 font-label-bold text-label-bold text-on-surface uppercase tracking-wider">Waktu Daftar</th>
                            <th class="p-4 font-label-bold text-label-bold text-on-surface uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        <?php if (!empty($booths)): ?>
                            <?php foreach ($booths as $booth): ?>
                                <?php
                                    $statusMap = [
                                        'active'      => ['bg-emerald-100 text-emerald-800', 'bg-emerald-500', 'Aktif'],
                                        'maintenance' => ['bg-tertiary-fixed text-on-tertiary-fixed-variant', 'bg-tertiary', 'Maintenance'],
                                        'inactive'    => ['bg-slate-100 text-slate-500', 'bg-slate-400', 'Nonaktif'],
                                    ];
                                    $st = $statusMap[$booth['status']] ?? ['bg-slate-100 text-slate-500', 'bg-slate-400', ucfirst($booth['status'])];
                                ?>
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="p-4 font-data-tabular text-data-tabular text-primary font-semibold"><?= esc($booth['id']) ?></td>
                                    <td class="p-4 font-body-base text-body-base text-on-surface"><?= esc($booth['name']) ?></td>
                                    <td class="p-4 text-center">
                                        <span class="text-lg font-black text-slate-500"><?= esc($booth['current_queue'] ?? 0) ?></span>
                                    </td>
                                    <td class="p-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $st[0] ?>">
                                            <span class="w-1.5 h-1.5 <?= $st[1] ?> rounded-full mr-1.5"></span>
                                            <?= $st[2] ?>
                                        </span>
                                    </td>
                                    <td class="p-4 font-data-tabular text-data-tabular text-outline">
                                        <?= date('d M Y, H:i', strtotime($booth['created_at'])) ?>
                                    </td>
                                    <td class="p-4 text-right">
                                        <div class="flex justify-end space-x-2">
                                            <a href="<?= base_url('admin/booths/' . $booth['id'] . '/edit') ?>"
                                               class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="Edit">
                                                <span class="material-symbols-outlined text-[20px]">edit</span>
                                            </a>
                                            <?php if ($booth['status'] === 'active'): ?>
                                                <form action="<?= base_url('admin/booths/' . $booth['id'] . '/maintenance') ?>" method="post" class="inline">
                                                    <?= csrf_field() ?>
                                                    <button type="submit"
                                                            class="p-1.5 text-slate-400 hover:bg-slate-100 rounded-lg transition-colors" title="Set Maintenance"
                                                            onclick="return confirm('Set booth ini ke mode Maintenance?')">
                                                        <span class="material-symbols-outlined text-[20px]">engineering</span>
                                                    </button>
                                                </form>
                                            <?php elseif ($booth['status'] === 'maintenance'): ?>
                                                <form action="<?= base_url('admin/booths/' . $booth['id'] . '/activate') ?>" method="post" class="inline">
                                                    <?= csrf_field() ?>
                                                    <button type="submit"
                                                            class="p-1.5 text-emerald-600 hover:bg-emerald-100 rounded-lg transition-colors" title="Aktifkan Kembali">
                                                        <span class="material-symbols-outlined text-[20px]">play_circle</span>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <form action="<?= base_url('admin/booths/' . $booth['id'] . '/activate') ?>" method="post" class="inline">
                                                    <?= csrf_field() ?>
                                                    <button type="submit"
                                                            class="p-1.5 text-emerald-600 hover:bg-emerald-100 rounded-lg transition-colors" title="Aktifkan">
                                                        <span class="material-symbols-outlined text-[20px]">power_settings_new</span>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
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
                                <td colspan="6" class="p-10 text-center text-sm text-slate-400">
                                    Belum ada booth terdaftar.
                                    <a href="<?= base_url('admin/booths/create') ?>" class="text-primary font-semibold ml-1">Tambah sekarang</a>.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-surface-container-low border-t border-outline-variant flex items-center justify-between">
                <span class="text-body-sm text-outline">
                    Menampilkan <?= count($booths ?? []) ?> dari <?= $totalBooths ?? 0 ?> mesin
                </span>
                <div class="flex space-x-1">
                    <a href="<?= base_url('admin/booths?page=' . max(1, ($currentPage ?? 1) - 1) . '&q=' . ($keyword ?? '') . '&status=' . ($filterStatus ?? '')) ?>"
                       class="p-2 border border-outline-variant rounded hover:bg-white transition-colors <?= ($currentPage ?? 1) <= 1 ? 'opacity-50 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-sm">chevron_left</span>
                    </a>
                    <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                        <a href="<?= base_url('admin/booths?page=' . $i . '&q=' . ($keyword ?? '') . '&status=' . ($filterStatus ?? '')) ?>"
                           class="px-3 py-1 border rounded font-label-bold text-label-bold <?= $i === ($currentPage ?? 1) ? 'bg-primary text-on-primary border-primary' : 'border-outline-variant hover:bg-white' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    <a href="<?= base_url('admin/booths?page=' . min(($totalPages ?? 1), ($currentPage ?? 1) + 1) . '&q=' . ($keyword ?? '') . '&status=' . ($filterStatus ?? '')) ?>"
                       class="p-2 border border-outline-variant rounded hover:bg-white transition-colors <?= ($currentPage ?? 1) >= ($totalPages ?? 1) ? 'opacity-50 pointer-events-none' : '' ?>">
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
                <div class="h-64 bg-slate-100 rounded-lg overflow-hidden relative">
                    <img alt="Map Indonesia"
                         class="w-full h-full object-cover grayscale opacity-50"
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuBpyfmSL3DtqqnK130WF11HLVY1VmAwt9uJEHyVNKjZP93sCMtqiUiMF2ob5weZGHt3C2vmyplnZul5FDZG7ANm6dN6eg6nuKEbVJNKYy9Jyxc1Nh6IxSR0Yu1dgcnRVKAWWV-VMv-NKFffEWCdcPxErpMWig18MXGCMrpQVyyhTnZAcC1XQJFVLPkAfDsJ86g6YgjGX-9iytkPzcOpZ8Aq3AHh9QLrj7t4KRHA870NX7BSXJbxGwwjcPoTVm3ZwNDXmaciGtoj2Rxr"/>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="bg-white/90 backdrop-blur-sm px-4 py-2 rounded-full shadow-lg border border-primary/20 flex items-center">
                            <span class="material-symbols-outlined text-primary mr-2">location_searching</span>
                            <span class="font-label-bold text-label-bold"><?= $statTotal ?? 0 ?> Mesin tersebar di berbagai kota</span>
                        </span>
                    </div>
                </div>
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

<?php $this->endSection(); ?>
