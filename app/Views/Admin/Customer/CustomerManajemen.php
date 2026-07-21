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
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Total Pelanggan -->
            <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
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
            <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
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
            <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
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
            <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
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
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden flex flex-col">

            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-slate-100 bg-white">
                <div class="flex items-center gap-3">
                    <div class="w-1 h-6 bg-primary rounded-full"></div>
                    <h4 class="font-h2 text-h2 text-on-surface">Daftar Pelanggan</h4>
                </div>
            </div>

            <!-- Table Toolbar -->
            <form method="get" action="<?= base_url('admin/customers') ?>" class="w-full">
                <div class="p-6 border-b border-slate-100 bg-white flex items-center justify-between gap-4">
                    <!-- Search Bar -->
                    <div class="relative flex-grow">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[20px]">search</span>
                        <input name="q" value="<?= esc($keyword ?? '') ?>"
                               class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"
                               placeholder="Cari nama, email, telepon..." type="text"
                               onkeydown="if(event.key==='Enter'){this.form.submit();}"/>
                    </div>
                    
                    <!-- Dropdown Status -->
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <div class="relative bg-white border border-slate-200 rounded-lg text-sm w-64">
                            <select name="status" class="w-full px-4 py-2 border-none bg-transparent focus:ring-0 text-sm font-bold text-slate-700 cursor-pointer outline-none animate-none pr-10" onchange="this.form.submit()">
                                <option value="all"      <?= ($filterStatus ?? 'all') === 'all'      ? 'selected' : '' ?>>Semua Status</option>
                                <option value="active"   <?= ($filterStatus ?? '') === 'active'   ? 'selected' : '' ?>>Aktif</option>
                                <option value="inactive" <?= ($filterStatus ?? '') === 'inactive' ? 'selected' : '' ?>>Non-Aktif</option>
                            </select>
                        </div>
                        <?php if (!empty($keyword) || ($filterStatus ?? 'all') !== 'all'): ?>
                            <a href="<?= base_url('admin/customers') ?>" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg flex items-center transition-colors">
                                Reset
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="border-y border-slate-100 bg-white">
                        <tr>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">ID Customer</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Nama Pelanggan</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Email</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Nomor Telepon</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Tanggal Registrasi</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Status</th>
                            <th class="px-6 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 bg-white">

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
                                        <div class="flex items-center justify-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-<?= $color ?>-100 flex items-center justify-center text-<?= $color ?>-700 font-bold text-xs"><?= esc($initials) ?></div>
                                            <span class="font-bold text-slate-900"><?= esc($cust['name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600 text-center"><?= esc($cust['email']) ?></td>
                                    <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600 text-center"><?= esc($cust['phone'] ?? '-') ?></td>
                                    <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-600 text-center"><?= date('d M Y', strtotime($cust['created_at'])) ?></td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2 py-1 rounded <?= $badgeClass ?> text-[10px] font-black uppercase"><?= $badgeText ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Detail Button -->
                                            <button type="button" onclick="showCustomerDetailModal('<?= esc($cust['id'], 'js') ?>', '<?= esc($cust['name'], 'js') ?>', '<?= esc($cust['email'], 'js') ?>', '<?= esc($cust['phone'] ?? '-', 'js') ?>', '<?= date('d M Y', strtotime($cust['created_at'])) ?>', '<?= $badgeText ?>', '<?= $badgeClass ?>', '<?= $initials ?>', '<?= $color ?>')"
                                                    class="p-2 text-green-600 hover:bg-green-50 rounded transition-all" title="Detail">
                                                <span class="material-symbols-outlined text-lg">visibility</span>
                                            </button>
                                            <a href="<?= base_url('admin/customers/' . $cust['id'] . '/edit') ?>" class="p-2 text-blue-600 hover:bg-blue-50 rounded transition-all" title="Edit">
                                                <span class="material-symbols-outlined text-lg">edit</span>
                                            </a>
                                            <!-- Delete Button -->
                                            <button type="button" onclick="showDeleteCustomerModal('<?= base_url('admin/customers/' . $cust['id'] . '/delete') ?>')"
                                                    class="p-2 text-red-500 hover:bg-red-50 rounded transition-all" title="Hapus">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>
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
            <div class="p-6 border-t border-slate-100 bg-white flex items-center justify-between">
                <span class="text-body-sm text-outline">Menampilkan <?= count($customers ?? []) ?> dari <?= $totalCustomers ?? 0 ?> pelanggan</span>
                <div class="flex items-center gap-2">
                    <a href="<?= base_url('admin/customers?page=' . max(1, ($currentPage ?? 1) - 1) . '&status=' . ($filterStatus ?? '') . '&q=' . ($keyword ?? '')) ?>"
                       class="w-8 h-8 rounded-lg flex items-center justify-center border border-slate-200 bg-white hover:bg-slate-50 text-slate-400 transition-colors <?= ($currentPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-sm">chevron_left</span>
                    </a>
                    <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                        <a href="<?= base_url('admin/customers?page=' . $i . '&status=' . ($filterStatus ?? '') . '&q=' . ($keyword ?? '')) ?>"
                           class="w-8 h-8 rounded-lg flex items-center justify-center border text-sm font-bold transition-colors <?= $i === ($currentPage ?? 1) ? 'border-primary bg-primary text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    <a href="<?= base_url('admin/customers?page=' . min(($totalPages ?? 1), ($currentPage ?? 1) + 1) . '&status=' . ($filterStatus ?? '') . '&q=' . ($keyword ?? '')) ?>"
                       class="w-8 h-8 rounded-lg flex items-center justify-center border border-slate-200 bg-white hover:bg-slate-50 text-slate-400 transition-colors <?= ($currentPage ?? 1) >= ($totalPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <?= $this->include('Admin/layouts/footer') ?>
</main>

<!-- Customer Detail Pop-Up Modal -->
<div id="customerDetailModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="customerDetailModalContent">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-white">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600">group</span>
                Detail Pelanggan
            </h4>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 flex flex-col items-center">
            <!-- Icon/Avatar Wrapper -->
            <div id="modalCustAvatar" class="w-20 h-20 rounded-full flex items-center justify-center font-bold text-2xl mb-5 shadow-inner">
            </div>

            <!-- Details List -->
            <div class="w-full space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-200 text-left text-sm mb-6">
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">ID Pelanggan</span>
                    <span class="font-mono text-primary font-bold" id="modalCustId"></span>
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
                    <span class="font-semibold text-slate-500">Nomor Telepon</span>
                    <span class="text-slate-800 font-semibold" id="modalCustPhone"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">Tanggal Registrasi</span>
                    <span class="text-slate-800 font-semibold" id="modalCustDate"></span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold text-slate-500">Status</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase" id="modalCustStatus"></span>
                </div>
            </div>
            
            <!-- Close Button -->
            <button type="button" onclick="closeCustomerDetailModal()" class="w-full py-2.5 bg-primary hover:bg-primary-container text-white font-semibold text-sm rounded-lg transition-colors shadow-md">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Delete Customer Confirmation Modal -->
<div id="deleteCustomerModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="deleteCustomerModalContent">
        <!-- Modal Body -->
        <div class="p-6 flex flex-col items-center text-center">
            <!-- Alert Trash Icon -->
            <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center p-4 mb-4">
                <span class="material-symbols-outlined text-[32px]">delete</span>
            </div>

            <h3 class="text-lg font-bold text-slate-800 mb-2">Konfirmasi Hapus</h3>
            <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                Apakah Anda yakin ingin menghapus pelanggan ini?<br>Tindakan ini tidak dapat dibatalkan.
            </p>

            <!-- Form & Actions -->
            <form id="deleteCustomerForm" method="POST" class="w-full flex gap-3">
                <?= csrf_field() ?>
                <button type="button" onclick="closeDeleteCustomerModal()" class="flex-grow py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-lg transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-grow py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-lg transition-colors shadow-md">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function showCustomerDetailModal(id, name, email, phone, regDate, statusText, statusClass, initials, avatarColor) {
        const modal = document.getElementById('customerDetailModal');
        const content = document.getElementById('customerDetailModalContent');
        const avatarEl = document.getElementById('modalCustAvatar');
        
        document.getElementById('modalCustId').textContent = '#' + id;
        document.getElementById('modalCustName').textContent = name;
        document.getElementById('modalCustEmail').textContent = email;
        document.getElementById('modalCustPhone').textContent = phone;
        document.getElementById('modalCustDate').textContent = regDate;
        
        avatarEl.textContent = initials;
        avatarEl.className = 'w-20 h-20 rounded-full flex items-center justify-center font-bold text-2xl mb-5 shadow-inner bg-' + avatarColor + '-100 text-' + avatarColor + '-700';

        const statusEl = document.getElementById('modalCustStatus');
        statusEl.textContent = statusText;
        statusEl.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase ' + statusClass;

        // Show Modal
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeCustomerDetailModal() {
        const modal = document.getElementById('customerDetailModal');
        const content = document.getElementById('customerDetailModalContent');

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function showDeleteCustomerModal(actionUrl) {
        const modal = document.getElementById('deleteCustomerModal');
        const content = document.getElementById('deleteCustomerModalContent');
        const form = document.getElementById('deleteCustomerForm');
        
        form.action = actionUrl;

        // Show Modal
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeDeleteCustomerModal() {
        const modal = document.getElementById('deleteCustomerModal');
        const content = document.getElementById('deleteCustomerModalContent');

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>

<?php $this->endSection(); ?>
