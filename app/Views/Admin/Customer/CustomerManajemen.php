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
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden flex-1 flex flex-col mb-6">
            <!-- Table Toolbar -->
            <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
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
                            <th class="px-6 py-4 text-center">ID Customer</th>
                            <th class="px-6 py-4">Nama Pelanggan</th>
                            <th class="px-6 py-4 text-center">Email</th>
                            <th class="px-6 py-4 text-center">Nomor Telepon</th>
                            <th class="px-6 py-4 text-center">Tanggal Registrasi</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
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
                                    <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-900 font-bold text-center">#<?= esc($cust['id']) ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-<?= $color ?>-100 flex items-center justify-center text-<?= $color ?>-700 font-bold text-xs"><?= esc($initials) ?></div>
                                            <span class="font-bold text-slate-900"><?= esc($cust['name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600 text-center"><?= esc($cust['email']) ?></td>
                                    <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600 text-center"><?= esc($cust['phone'] ?? '-') ?></td>
                                    <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600 text-center"><?= date('d M Y', strtotime($cust['created_at'])) ?></td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold <?= $badgeClass ?>"><?= $badgeText ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-center space-x-2">
                                        <button type="button" 
                                                onclick="showCustomerPreviewModal('<?= esc($cust['id'], 'js') ?>', '<?= esc($cust['name'], 'js') ?>', '<?= esc($cust['email'], 'js') ?>', '<?= esc($cust['phone'] ?? '-', 'js') ?>', '<?= date('d M Y', strtotime($cust['created_at'])) ?>', '<?= $badgeText ?>', '<?= $badgeClass ?>')" 
                                                class="inline p-1.5 text-emerald-600 hover:bg-emerald-50 rounded transition-colors" title="Detail">
                                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                                        </button>
                                        <a href="<?= base_url('admin/customers/' . $cust['id'] . '/edit') ?>" class="inline p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                        <form action="<?= base_url('admin/customers/' . $cust['id'] . '/delete') ?>" method="post" class="inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded transition-colors" title="Hapus">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </form>
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
                <div class="flex items-center gap-1">
                    <a href="<?= base_url('admin/customers?page=' . max(1, ($currentPage ?? 1) - 1) . '&status=' . ($filterStatus ?? '') . '&q=' . ($keyword ?? '')) ?>"
                       class="p-2 rounded hover:bg-slate-50 text-slate-400 <?= ($currentPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-sm">chevron_left</span>
                    </a>
                    <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                        <a href="<?= base_url('admin/customers?page=' . $i . '&status=' . ($filterStatus ?? '') . '&q=' . ($keyword ?? '')) ?>"
                           class="w-8 h-8 rounded <?= $i === ($currentPage ?? 1) ? 'bg-blue-600 text-white' : 'hover:bg-slate-50 text-slate-600' ?> text-xs font-bold flex items-center justify-center">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    <a href="<?= base_url('admin/customers?page=' . min(($totalPages ?? 1), ($currentPage ?? 1) + 1) . '&status=' . ($filterStatus ?? '') . '&q=' . ($keyword ?? '')) ?>"
                       class="p-2 rounded hover:bg-slate-50 text-slate-400 <?= ($currentPage ?? 1) >= ($totalPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <?= $this->include('Admin/layouts/footer') ?>
</main>

<!-- Customer Preview Modal -->
<div id="customerPreviewModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="customerPreviewModalContent">
        <!-- Modal Header -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">visibility</span>
                Detail Pelanggan
            </h4>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 flex flex-col items-center">
            <!-- Icon/Avatar Wrapper -->
            <div class="w-24 h-24 bg-blue-50 text-blue-600 border border-slate-200 rounded-full overflow-hidden flex items-center justify-center p-4 mb-5 shadow-inner">
                <span class="material-symbols-outlined text-[48px]">person</span>
            </div>

            <!-- Details List -->
            <div class="w-full space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-200 text-left text-sm mb-6">
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">ID Pelanggan</span>
                    <span class="font-mono text-slate-800 font-bold" id="modalCustId"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">Nama Pelanggan</span>
                    <span class="text-slate-800 font-semibold" id="modalCustName"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">Email</span>
                    <span class="text-slate-800 font-semibold" id="modalCustEmail"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">Telepon</span>
                    <span class="text-slate-800 font-semibold" id="modalCustPhone"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">Tanggal Registrasi</span>
                    <span class="text-slate-800" id="modalCustRegistered"></span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold text-slate-500">Status</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold" id="modalCustStatus"></span>
                </div>
            </div>
            
            <!-- Close Button -->
            <button type="button" onclick="closeCustomerPreviewModal()" class="w-full py-2.5 bg-primary hover:bg-primary-container text-white font-semibold text-sm rounded-lg transition-colors shadow-md">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function showCustomerPreviewModal(id, name, email, phone, registeredAt, badgeText, badgeClass) {
        const modal = document.getElementById('customerPreviewModal');
        const content = document.getElementById('customerPreviewModalContent');
        
        document.getElementById('modalCustId').textContent = '#' + id;
        document.getElementById('modalCustName').textContent = name;
        document.getElementById('modalCustEmail').textContent = email;
        document.getElementById('modalCustPhone').textContent = phone;
        document.getElementById('modalCustRegistered').textContent = registeredAt;
        
        const statusEl = document.getElementById('modalCustStatus');
        statusEl.textContent = badgeText;
        statusEl.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold ' + badgeClass;

        // Show Modal
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeCustomerPreviewModal() {
        const modal = document.getElementById('customerPreviewModal');
        const content = document.getElementById('customerPreviewModalContent');

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>

<?php $this->endSection(); ?>
