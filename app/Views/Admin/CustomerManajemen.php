<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<!-- Main Content Canvas -->
<main class="ml-[260px] pt-16 min-h-screen flex flex-col">
    <div class="p-6 flex-1">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-4">
            <a class="hover:text-blue-700" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-blue-700">Manajemen Pelanggan</span>
        </nav>

        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-h2 text-h2 text-on-surface">Manajemen Pelanggan</h2>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Kelola basis data pelanggan dan informasi kontak mereka.</p>
            </div>
            <button class="flex items-center gap-2 bg-primary text-on-primary px-4 py-2 rounded font-label-bold text-label-bold hover:bg-primary-container transition-colors shadow-sm">
                <span class="material-symbols-outlined text-sm">person_add</span>
                Tambah Pelanggan
            </button>
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
                    <span class="text-xs font-bold text-slate-400">Terdaftar</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Pelanggan Terdaftar</p>
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

                        <!-- Row 1 -->
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-900 font-bold">CUST-2024-001</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-xs">AA</div>
                                    <span class="font-bold text-slate-900">Andi Ardiansyah</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600">andi.ardi@email.com</td>
                            <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600">+62 812-3456-7890</td>
                            <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600">12 Jan 2024</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded bg-green-100 text-green-700 text-[10px] font-black uppercase">Aktif</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-all" title="Detail">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </button>
                                    <button class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded transition-all" title="Edit">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </button>
                                    <button class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition-all" title="Hapus">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Row 2 -->
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-900 font-bold">CUST-2024-002</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 font-bold text-xs">BP</div>
                                    <span class="font-bold text-slate-900">Budi Pratama</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600">budi.pratama@email.com</td>
                            <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600">+62 856-9876-5432</td>
                            <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600">15 Jan 2024</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded bg-green-100 text-green-700 text-[10px] font-black uppercase">Aktif</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-all" title="Detail">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </button>
                                    <button class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded transition-all" title="Edit">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </button>
                                    <button class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition-all" title="Hapus">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Row 3 -->
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-900 font-bold">CUST-2024-003</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center text-pink-700 font-bold text-xs">CS</div>
                                    <span class="font-bold text-slate-900">Citra Sari</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600">citra.sari@email.com</td>
                            <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600">+62 821-1122-3344</td>
                            <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600">20 Jan 2024</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded bg-slate-100 text-slate-600 text-[10px] font-black uppercase">Keluar</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-all" title="Detail">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </button>
                                    <button class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded transition-all" title="Edit">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </button>
                                    <button class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition-all" title="Hapus">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Row 4 -->
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-900 font-bold">CUST-2024-004</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-700 font-bold text-xs">DW</div>
                                    <span class="font-bold text-slate-900">Dedi Wijaya</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600">dedi.wijaya@email.com</td>
                            <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600">+62 813-8877-6655</td>
                            <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600">25 Jan 2024</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded bg-green-100 text-green-700 text-[10px] font-black uppercase">Aktif</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-all" title="Detail">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </button>
                                    <button class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded transition-all" title="Edit">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </button>
                                    <button class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition-all" title="Hapus">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Row 5 -->
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-900 font-bold">CUST-2024-005</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-xs">EL</div>
                                    <span class="font-bold text-slate-900">Eka Lestari</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600">eka.lestari@email.com</td>
                            <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600">+62 878-4433-2211</td>
                            <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600">02 Feb 2024</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded bg-red-100 text-red-700 text-[10px] font-black uppercase">Gagal</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-all" title="Detail">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </button>
                                    <button class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded transition-all" title="Edit">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </button>
                                    <button class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition-all" title="Hapus">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-outline-variant bg-white flex items-center justify-between">
                <p class="text-xs font-medium text-slate-500">Menampilkan 1 - 5 dari 1,248 pelanggan</p>
                <div class="flex items-center gap-1">
                    <button class="p-2 rounded hover:bg-slate-50 text-slate-400">
                        <span class="material-symbols-outlined text-sm">first_page</span>
                    </button>
                    <button class="p-2 rounded hover:bg-slate-50 text-slate-400">
                        <span class="material-symbols-outlined text-sm">chevron_left</span>
                    </button>
                    <button class="w-8 h-8 rounded bg-blue-600 text-white text-xs font-bold">1</button>
                    <button class="w-8 h-8 rounded hover:bg-slate-50 text-slate-600 text-xs font-bold">2</button>
                    <button class="w-8 h-8 rounded hover:bg-slate-50 text-slate-600 text-xs font-bold">3</button>
                    <span class="px-2 text-slate-400">...</span>
                    <button class="w-8 h-8 rounded hover:bg-slate-50 text-slate-600 text-xs font-bold">250</button>
                    <button class="p-2 rounded hover:bg-slate-50 text-slate-400">
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </button>
                    <button class="p-2 rounded hover:bg-slate-50 text-slate-400">
                        <span class="material-symbols-outlined text-sm">last_page</span>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <?= $this->include('Admin/layouts/footer') ?>
</main>

<!-- FAB Quick Action -->
<button class="fixed bottom-8 right-8 w-14 h-14 bg-primary-container text-white rounded-full shadow-lg flex items-center justify-center hover:scale-105 transition-transform z-50">
    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">add</span>
</button>

<?php $this->endSection(); ?>
