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
                    <span class="text-blue-700 font-bold">Manajemen Pelanggan</span>
                </li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-h1 text-h1 text-on-surface">Manajemen Pelanggan</h2>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Kelola basis data pelanggan dan informasi kontak mereka.</p>
            </div>
            <a href="<?= base_url('admin/customers/create') ?>"
               class="flex items-center gap-2 bg-primary text-on-primary px-4 py-2 rounded font-label-bold text-label-bold hover:bg-primary-container transition-colors shadow-sm">
                <span class="material-symbols-outlined text-sm">person_add</span>
                Tambah Pelanggan
            </a>
        </div>

        <!-- Dashboard Stats Row (Bento Style) -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <!-- Total Pelanggan -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">groups</span>
                    <span class="text-xs font-bold text-green-600 flex items-center">
                        <span class="material-symbols-outlined text-xs">trending_up</span>
                    </span>
                </div>
                <p class="text-sm font-medium text-slate-500">Total Pelanggan</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">
                    <?= number_format($totalCustomers ?? 0, 0, ',', '.') ?>
                </h3>
            </div>
            <!-- Pelanggan Aktif -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-orange-600 bg-orange-50 p-2 rounded-lg material-symbols-outlined">person_pin</span>
                    <span class="text-xs font-bold text-slate-400">Status Aktif</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Pelanggan Aktif</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">
                    <?= number_format($totalActive ?? 0, 0, ',', '.') ?>
                </h3>
            </div>
            <!-- Filter Aktif -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-purple-600 bg-purple-50 p-2 rounded-lg material-symbols-outlined">search</span>
                    <span class="text-xs font-bold text-slate-400">Pencarian</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Hasil Filter/Cari</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">
                    <?= number_format($totalFiltered ?? 0, 0, ',', '.') ?>
                </h3>
            </div>
            <!-- Halaman -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-slate-600 bg-slate-50 p-2 rounded-lg material-symbols-outlined">pages</span>
                    <span class="text-xs font-bold text-slate-400">Hal <?= $currentPage ?? 1 ?>/<?= $totalPages ?? 1 ?></span>
                </div>
                <p class="text-sm font-medium text-slate-500">Per Halaman</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">
                    <?= $perPage ?? 10 ?> Data
                </h3>
            </div>
        </div>

        <!-- Main Data Table Section -->
        <div class="bg-white border border-outline-variant rounded-xl shadow-sm overflow-hidden">

            <!-- Table Toolbar -->
            <div class="p-4 border-b border-outline-variant flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <form method="get" action="<?= base_url('admin/customers') ?>" class="flex border border-slate-200 rounded-lg overflow-hidden text-xs font-bold">
                        <button type="submit" name="status" value="all" class="px-4 py-2 <?= ($filterStatus ?? 'all') === 'all' ? 'bg-blue-600 text-white' : 'bg-white text-slate-500 hover:bg-slate-50' ?> border-r border-slate-200 transition-colors">Semua</button>
                        <button type="submit" name="status" value="active" class="px-4 py-2 <?= ($filterStatus ?? '') === 'active' ? 'bg-blue-600 text-white' : 'text-slate-500 hover:bg-white' ?> border-r border-slate-200 transition-colors">Aktif</button>
                        <button type="submit" name="status" value="inactive" class="px-4 py-2 <?= ($filterStatus ?? '') === 'inactive' ? 'bg-blue-600 text-white' : 'text-slate-500 hover:bg-white' ?> transition-colors">Non-Aktif</button>
                        <?php if (!empty($keyword)): ?>
                            <input type="hidden" name="q" value="<?= esc($keyword) ?>">
                        <?php endif; ?>
                    </form>
                </div>
                <div class="flex items-center gap-2">
                    <!-- Search Box -->
                    <form method="get" action="<?= base_url('admin/customers') ?>" class="flex items-center gap-2">
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-2 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                            <input name="q" value="<?= esc($keyword ?? '') ?>"
                                   placeholder="Cari nama / email / telepon..."
                                   class="pl-8 pr-3 py-2 border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-blue-500 bg-white w-60"/>
                        </div>
                        <?php if (!empty($filterStatus) && $filterStatus !== 'all'): ?>
                            <input type="hidden" name="status" value="<?= esc($filterStatus) ?>">
                        <?php endif; ?>
                        <button type="submit" class="flex items-center gap-1 px-3 py-2 border border-slate-200 rounded-lg text-xs font-bold text-slate-600 hover:bg-white transition-colors">
                            <span class="material-symbols-outlined text-sm">filter_list</span>
                            Cari
                        </button>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-[11px] font-black uppercase tracking-wider text-slate-500 border-b border-outline-variant">
                            <th class="px-6 py-4">ID Customer</th>
                            <th class="px-6 py-4">Nama Pelanggan</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Nomor Telepon</th>
                            <th class="px-6 py-4">Tanggal Registrasi</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">

                        <?php if (!empty($customers)): ?>
                            <?php foreach ($customers as $cust): ?>
                                <?php
                                    $nameParts = explode(' ', $cust['name'] ?? 'U');
                                    $initials  = strtoupper(substr($nameParts[0], 0, 1) . (count($nameParts) > 1 ? substr(end($nameParts), 0, 1) : substr($nameParts[0], 1, 1)));
                                    $colors    = ['blue', 'purple', 'pink', 'amber', 'emerald', 'cyan', 'rose', 'indigo'];
                                    $color     = $colors[crc32($cust['name'] ?? '') % count($colors)];
                                    $isActive  = ($cust['status'] ?? 'active') === 'active';
                                    $badgeClass = $isActive ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
                                    $badgeText  = $isActive ? 'Aktif' : 'Non-Aktif';
                                ?>
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-900 font-bold">#<?= esc($cust['id']) ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-<?= $color ?>-100 flex items-center justify-center text-<?= $color ?>-700 font-bold text-xs"><?= esc($initials) ?></div>
                                            <span class="font-bold text-slate-900"><?= esc($cust['name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600"><?= esc($cust['email']) ?></td>
                                    <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600"><?= esc($cust['phone'] ?? '-') ?></td>
                                    <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600"><?= date('d M Y', strtotime($cust['created_at'])) ?></td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2 py-1 rounded <?= $badgeClass ?> text-[10px] font-black uppercase"><?= $badgeText ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="<?= base_url('admin/customers/' . $cust['id']) ?>" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-all" title="Detail">
                                                <span class="material-symbols-outlined text-lg">visibility</span>
                                            </a>
                                            <a href="<?= base_url('admin/customers/' . $cust['id'] . '/edit') ?>" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded transition-all" title="Edit">
                                                <span class="material-symbols-outlined text-lg">edit</span>
                                            </a>
                                            <form action="<?= base_url('admin/customers/' . $cust['id'] . '/delete') ?>" method="post" class="inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition-all" title="Hapus"
                                                        onclick="return confirm('Hapus pelanggan ini?')">
                                                    <span class="material-symbols-outlined text-lg">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-400">
                                    Belum ada pelanggan terdaftar.
                                    <a href="<?= base_url('admin/customers/create') ?>" class="text-primary font-semibold ml-1">Tambah sekarang</a>.
                                </td>
                            </tr>
                        <?php endif; ?>

                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-outline-variant bg-white flex items-center justify-between">
                <p class="text-xs font-medium text-slate-500">Menampilkan <?= count($customers ?? []) ?> dari <?= $totalCustomers ?? 0 ?> pelanggan</p>
                <div class="flex gap-1">
                    <a href="<?= base_url('admin/customers?page=' . max(1, ($currentPage ?? 1) - 1) . '&status=' . ($filterStatus ?? '') . '&q=' . ($keyword ?? '')) ?>"
                       class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded text-slate-400 hover:bg-slate-50 transition-colors <?= ($currentPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-sm">chevron_left</span>
                    </a>
                    <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                        <a href="<?= base_url('admin/customers?page=' . $i . '&status=' . ($filterStatus ?? '') . '&q=' . ($keyword ?? '')) ?>"
                           class="w-8 h-8 flex items-center justify-center border rounded text-sm font-bold transition-colors <?= $i === ($currentPage ?? 1) ? 'border-primary bg-primary text-white' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    <a href="<?= base_url('admin/customers?page=' . min(($totalPages ?? 1), ($currentPage ?? 1) + 1) . '&status=' . ($filterStatus ?? '') . '&q=' . ($keyword ?? '')) ?>"
                       class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded text-slate-400 hover:bg-slate-50 transition-colors <?= ($currentPage ?? 1) >= ($totalPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <?= $this->include('Admin/layouts/footer') ?>
</main>

<?php $this->endSection(); ?>
