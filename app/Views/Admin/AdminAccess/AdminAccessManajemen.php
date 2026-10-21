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
                    <span class="text-blue-700 font-bold">Manajemen Pengaturan Akses</span>
                </li>
            </ol>
        </nav>

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
        <?php if (!empty(session()->getFlashdata('errors'))): ?>
            <div id="flash-message-errors" class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc list-inside space-y-1">
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <script>
            setTimeout(() => {
                const ids = ['flash-message-success', 'flash-message-error', 'flash-message-errors'];
                ids.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.remove();
                });
            }, 1000);
        </script>

        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="font-h1 text-h1 text-on-surface">Manajemen Pengaturan Akses</h2>
            <p class="font-body-sm text-body-sm text-on-surface-variant">Kelola daftar administrator dan hak akses sistem CuitBox Studio.</p>
        </div>

        <!-- Stats Bento -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4 mb-6">
            <!-- Total Admin -->
            <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">manage_accounts</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Total Admin</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= $statTotal ?? 0 ?></h3>
            </div>
            <!-- Superadmin -->
            <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-emerald-600 bg-emerald-50 p-2 rounded-lg material-symbols-outlined">verified_user</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Superadmin</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= $statSuperadmin ?? 0 ?></h3>
            </div>
            <!-- Admin -->
            <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">admin_panel_settings</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Admin</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= $statAdmin ?? 0 ?></h3>
            </div>
            <!-- Staff -->
            <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-amber-600 bg-amber-50 p-2 rounded-lg material-symbols-outlined">badge</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Staff</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= $statStaff ?? 0 ?></h3>
            </div>
            <!-- Status Keamanan -->
            <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-slate-600 bg-slate-50 p-2 rounded-lg material-symbols-outlined">security</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Status Keamanan</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">Optimal</h3>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
            <!-- Table Header -->
            <div class="p-6 border-b border-slate-200 flex flex-col md:flex-row justify-between items-center bg-white gap-4">
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="w-1 h-6 bg-primary rounded-full"></div>
                    <h4 class="font-h2 text-h2 text-on-surface">Daftar Administrator</h4>
                </div>
                <a href="<?= base_url('admin/settings/create') ?>"
                   class="bg-primary text-white px-4 py-2 rounded-lg font-label-bold text-label-bold flex items-center gap-2 hover:bg-blue-800 transition-colors shadow-sm whitespace-nowrap w-full md:w-auto justify-center">
                    <span class="material-symbols-outlined text-[18px]">manage_accounts</span>
                    Tambah Admin
                </a>
            </div>

            <!-- Search & Filter Bar -->
            <div class="p-6 bg-white border-b border-slate-200 flex flex-col sm:flex-row gap-3">
                <form method="get" action="<?= base_url('admin/settings') ?>" class="flex flex-col sm:flex-row flex-1 gap-3 w-full">
                    <!-- Search Input -->
                    <div class="relative flex-1">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[20px]">search</span>
                        <input type="text" name="q" value="<?= esc($keyword ?? '') ?>" placeholder="Cari admin..."
                               class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white transition-all"/>
                    </div>
                    
                    <!-- Role Filter -->
                    <select name="role" onchange="this.form.submit()"
                            class="w-full sm:w-48 px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white transition-all text-slate-600">
                        <option value="" <?= ($filterRole ?? '') === '' ? 'selected' : '' ?>>Semua Role</option>
                        <option value="superadmin" <?= ($filterRole ?? '') === 'superadmin' ? 'selected' : '' ?>>Superadmin</option>
                        <option value="admin"      <?= ($filterRole ?? '') === 'admin'      ? 'selected' : '' ?>>Admin</option>
                        <option value="staff"      <?= ($filterRole ?? '') === 'staff'      ? 'selected' : '' ?>>Staff</option>
                    </select>

                    <!-- Reset button if search or filter is active -->
                    <?php if (!empty($keyword) || !empty($filterRole)): ?>
                        <a href="<?= base_url('admin/settings') ?>" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg flex items-center justify-center transition-colors">
                            Reset
                        </a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Data Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-center">
                    <thead>
                        <tr class="bg-white border-b border-slate-100">
                            <th class="px-6 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider text-center">ID Admin</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider text-center">Nama / Username</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider text-center">Email</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider text-center">Role</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider text-center">Terdaftar</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 bg-white">
                        <?php if (!empty($admins)): ?>
                            <?php foreach ($admins as $admin): ?>
                                <?php
                                    $initials = strtoupper(substr($admin['name'] ?? 'A', 0, 1) . (strpos($admin['name'] ?? '', ' ') !== false ? substr(strrchr($admin['name'], ' '), 1, 1) : substr($admin['name'] ?? 'D', 1, 1)));
                                    $role = strtolower($admin['role'] ?? '');
                                    $isLockedSuperadmin = $role === 'superadmin' && !($canManageSuperadmin ?? false);
                                    $roleLabels = ['superadmin' => 'Superadmin', 'admin' => 'Admin', 'staff' => 'Staff'];
                                    $avatarClass = match ($role) {
                                        'superadmin' => 'bg-emerald-100 text-emerald-700',
                                        'admin' => 'bg-blue-100 text-blue-700',
                                        default => 'bg-amber-100 text-amber-700',
                                    };
                                    $badgeClass = match ($role) {
                                        'superadmin' => 'bg-emerald-100 text-emerald-800',
                                        'admin' => 'bg-blue-100 text-blue-800',
                                        default => 'bg-amber-100 text-amber-800',
                                    };
                                ?>
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="px-6 py-4 text-sm font-medium text-slate-900 text-center">#<?= strtolower(esc($admin['id'])) ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-3">
                                            <div class="w-8 h-8 rounded-full <?= $avatarClass ?> flex items-center justify-center font-bold text-xs flex-shrink-0">
                                                <?= esc($initials) ?>
                                            </div>
                                            <span class="text-sm font-semibold text-on-surface"><?= esc($admin['name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 text-center"><?= esc($admin['email']) ?></td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold <?= $badgeClass ?> uppercase tracking-wide">
                                            <?= esc($roleLabels[$role] ?? ucfirst($role)) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-xs text-slate-500">
                                        <?= date('d M Y, H:i', strtotime($admin['created_at'])) ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <?php if ($isLockedSuperadmin): ?>
                                                <span class="p-2 text-slate-400 cursor-not-allowed" title="Record superadmin terkunci">
                                                    <span class="material-symbols-outlined text-[18px]">lock</span>
                                                </span>
                                            <?php else: ?>
                                                <a href="<?= base_url('admin/settings/' . $admin['id'] . '/edit') ?>"
                                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                                </a>
                                                <form action="<?= base_url('admin/settings/' . $admin['id'] . '/delete') ?>" method="post" class="inline" onsubmit="confirmDelete(event, this)">
                                                    <?= csrf_field() ?>
                                                    <button type="submit"
                                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-400">
                                    Belum ada admin terdaftar.
                                    <a href="<?= base_url('admin/settings/create') ?>" class="text-primary font-semibold ml-1">Tambah sekarang</a>.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-6 border-t border-slate-100 bg-white flex items-center justify-between">
                <p class="text-xs text-slate-500">
                    Menampilkan <?= count($admins ?? []) ?> dari <?= $totalAdmins ?? 0 ?> admin
                </p>
                <div class="flex items-center gap-2">
                    <a href="<?= base_url('admin/settings?page=' . max(1, ($currentPage ?? 1) - 1) . '&role=' . ($filterRole ?? '') . '&q=' . ($keyword ?? '')) ?>"
                       class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-400 hover:bg-slate-50 transition-colors <?= ($currentPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-sm">chevron_left</span>
                    </a>
                    <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                        <a href="<?= base_url('admin/settings?page=' . $i . '&role=' . ($filterRole ?? '') . '&q=' . ($keyword ?? '')) ?>"
                           class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-colors <?= $i === ($currentPage ?? 1) ? 'border border-primary bg-primary text-white' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    <a href="<?= base_url('admin/settings?page=' . min(($totalPages ?? 1), ($currentPage ?? 1) + 1) . '&role=' . ($filterRole ?? '') . '&q=' . ($keyword ?? '')) ?>"
                       class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-400 hover:bg-slate-50 transition-colors <?= ($currentPage ?? 1) >= ($totalPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal Konfirmasi Hapus Custom -->
    <div id="deleteConfirmModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/40 backdrop-blur-sm opacity-0 transition-opacity duration-300">
        <div id="deleteConfirmModalContent" class="bg-white rounded-3xl p-10 max-w-lg w-full mx-4 border border-slate-100 transform scale-95 opacity-0 transition-all duration-300 flex flex-col items-center text-center">
            
            <div class="w-20 h-20 rounded-2xl bg-red-50/70 flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-[32px] text-red-500">delete</span>
            </div>
            
            <h3 class="text-2xl font-black text-slate-900 mb-3">Konfirmasi Hapus</h3>
            <p class="text-[16px] text-slate-500 leading-relaxed mb-8">
                Apakah Anda yakin ingin menghapus data administrator ini?<br>Tindakan ini tidak dapat dibatalkan.
            </p>
            
            <div class="flex gap-4 w-full">
                <button onclick="closeDeleteModal()" class="flex-1 py-3.5 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold text-base rounded-2xl transition-all">Batal</button>
                <button id="btnConfirmDelete" class="flex-1 py-3.5 bg-red-600 hover:bg-red-700 text-white font-bold text-base rounded-2xl transition-all shadow-none">Hapus</button>
            </div>
        </div>
    </div>

    <script>
        let deleteFormToSubmit = null;

        function confirmDelete(event, form) {
            event.preventDefault();
            deleteFormToSubmit = form;
            
            const modal = document.getElementById('deleteConfirmModal');
            const content = document.getElementById('deleteConfirmModalContent');
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Trigger animation
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteConfirmModal');
            const content = document.getElementById('deleteConfirmModalContent');
            
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                deleteFormToSubmit = null;
            }, 300);
        }

        // Bind confirm button
        document.getElementById('btnConfirmDelete').addEventListener('click', function() {
            if (deleteFormToSubmit) {
                deleteFormToSubmit.submit();
            }
        });
    </script>

    <?= $this->include('Admin/layouts/footer') ?>
</main>

<?php $this->endSection(); ?>
