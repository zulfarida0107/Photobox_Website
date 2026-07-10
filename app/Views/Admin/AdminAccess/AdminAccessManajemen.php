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
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm font-medium flex items-center gap-2">
                <span class="material-symbols-outlined text-green-500">check_circle</span>
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm font-medium flex items-center gap-2">
                <span class="material-symbols-outlined text-red-500">error</span>
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>
        <?php if (!empty(session()->getFlashdata('errors'))): ?>
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc list-inside space-y-1">
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="font-h1 text-h1 text-on-surface">Manajemen Pengaturan Akses</h2>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Kelola daftar administrator dan hak akses sistem CuitBox Studio v2.</p>
            </div>
        </div>

        <!-- Stats Bento -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <!-- Total Admin -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">manage_accounts</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Total Admin</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= $statTotal ?? 0 ?></h3>
            </div>
            <!-- Superadmin -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-green-600 bg-green-50 p-2 rounded-lg material-symbols-outlined">verified_user</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Superadmin</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= $statSuperadmin ?? 0 ?></h3>
            </div>
            <!-- Staff -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-amber-600 bg-amber-50 p-2 rounded-lg material-symbols-outlined">badge</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Staff</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= $statAdmin ?? 0 ?></h3>
            </div>
            <!-- Status Keamanan -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-slate-600 bg-slate-50 p-2 rounded-lg material-symbols-outlined">security</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Status Keamanan</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">Optimal</h3>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm flex flex-col mb-6">
            <div class="p-5 border-b border-slate-200 flex justify-between items-center bg-white">
                <div class="flex items-center gap-3">
                    <div class="w-1 h-6 bg-primary rounded-full"></div>
                    <h4 class="font-h2 text-h2 text-on-surface">Daftar Administrator</h4>
                </div>
                <a href="<?= base_url('admin/settings/create') ?>"
                   class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-primary-container transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-lg">manage_accounts</span>
                    Tambah Admin
                </a>
            </div>

            <div class="p-4 bg-slate-50/50 border-b border-slate-200 flex flex-col sm:flex-row gap-3">
                <form method="get" action="<?= base_url('admin/settings') ?>" class="flex flex-1 gap-4 justify-between">
                    <div class="relative flex-1">
                        <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[20px]">search</span>
                        <input type="text" name="q" value="<?= esc($keyword ?? '') ?>" placeholder="Cari admin..."
                               class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white transition-all"/>
                    </div>
                    <div class="flex items-center gap-2">
                        <select name="role" onchange="this.form.submit()"
                                class="w-56 border border-slate-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white outline-none transition-all cursor-pointer text-slate-700">
                            <option value="" <?= ($filterRole ?? '') === '' ? 'selected' : '' ?>>Semua Role</option>
                            <option value="superadmin" <?= ($filterRole ?? '') === 'superadmin' ? 'selected' : '' ?>>Superadmin</option>
                            <option value="staff"      <?= ($filterRole ?? '') === 'staff'      ? 'selected' : '' ?>>Staff</option>
                        </select>
                        <?php if (!empty($keyword) || !empty($filterRole)): ?>
                            <a href="<?= base_url('admin/settings') ?>" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm font-semibold rounded-lg flex items-center transition-colors">
                                Reset
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center whitespace-nowrap w-[10%]">ID Admin</th>
                            <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center whitespace-nowrap w-[30%]">Nama / Username</th>
                            <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center whitespace-nowrap w-[25%]">Email</th>
                            <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center whitespace-nowrap w-[15%]">Role</th>
                            <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center whitespace-nowrap w-[10%]">Terdaftar</th>
                            <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center whitespace-nowrap w-[10%]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (!empty($admins)): ?>
                            <?php foreach ($admins as $admin): ?>
                                <?php
                                    $initials = strtoupper(substr($admin['name'] ?? 'A', 0, 1) . (strpos($admin['name'] ?? '', ' ') !== false ? substr(strrchr($admin['name'], ' '), 1, 1) : substr($admin['name'] ?? 'D', 1, 1)));
                                    $isSuperadmin = strtolower($admin['role'] ?? '') === 'superadmin';
                                    $avatarClass  = $isSuperadmin ? 'bg-blue-100 text-blue-700' : 'bg-slate-200 text-slate-600';
                                    $badgeClass   = $isSuperadmin ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-amber-50 text-amber-700 border border-amber-200';
                                ?>
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="px-4 py-4 text-data-tabular font-medium text-slate-900 text-center whitespace-nowrap">#<?= esc($admin['id']) ?></td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <div class="w-8 h-8 rounded-full <?= $avatarClass ?> flex items-center justify-center font-bold text-xs flex-shrink-0">
                                                <?= esc($initials) ?>
                                            </div>
                                            <span class="text-body-base font-semibold text-on-surface"><?= esc($admin['name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-body-base text-slate-600 text-center whitespace-nowrap"><?= esc($admin['email']) ?></td>
                                    <td class="px-4 py-4 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold <?= $badgeClass ?> uppercase tracking-wide">
                                            <span class="w-1.5 h-1.5 <?= $isSuperadmin ? 'bg-green-500' : 'bg-amber-500' ?> rounded-full"></span>
                                            <?= $isSuperadmin ? 'SUPER ADMIN' : 'STAFF' ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-body-sm text-slate-500 text-center whitespace-nowrap">
                                        <?= date('d M Y, H:i', strtotime($admin['created_at'])) ?>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex flex-row items-center justify-center gap-1 whitespace-nowrap">
                                            <a href="<?= base_url('admin/settings/' . $admin['id'] . '/edit') ?>"
                                               class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                                                <span class="material-symbols-outlined text-[20px]">edit</span>
                                            </a>
                                            <?php if ($admin['id'] !== ($loggedInId ?? '')): ?>
                                                <button type="button"
                                                        class="p-1.5 text-red-500 hover:bg-red-50 rounded transition-colors" title="Hapus Admin"
                                                        onclick="openUniversalDeleteModal('<?= base_url('admin/settings/' . $admin['id'] . '/delete') ?>', '<?= esc($admin['name'], 'js') ?>', 'Hapus Akun Admin?')">
                                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                                </button>
                                            <?php else: ?>
                                                <span class="p-1.5 text-slate-300 rounded cursor-not-allowed" title="Tidak bisa menghapus akun aktif Anda">
                                                    <span class="material-symbols-outlined text-[20px]">lock</span>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-400">
                                    Belum ada admin terdaftar.
                                    <a href="<?= base_url('admin/settings/create') ?>" class="text-primary font-semibold ml-1">Tambah sekarang</a>.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 bg-slate-50/50 flex items-center justify-between border-t border-slate-200">
                <p class="text-xs text-slate-500">
                    Menampilkan <?= count($admins ?? []) ?> dari <?= $totalAdmins ?? 0 ?> admin
                </p>
                <div class="flex items-center gap-1">
                    <a href="<?= base_url('admin/settings?page=' . max(1, ($currentPage ?? 1) - 1) . '&role=' . ($filterRole ?? '') . '&q=' . ($keyword ?? '')) ?>"
                       class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-sm">chevron_left</span>
                    </a>
                    <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                        <a href="<?= base_url('admin/settings?page=' . $i . '&role=' . ($filterRole ?? '') . '&q=' . ($keyword ?? '')) ?>"
                           class="w-8 h-8 flex items-center justify-center border rounded text-sm font-bold transition-colors <?= $i === ($currentPage ?? 1) ? 'border-primary bg-primary text-white' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    <a href="<?= base_url('admin/settings?page=' . min(($totalPages ?? 1), ($currentPage ?? 1) + 1) . '&role=' . ($filterRole ?? '') . '&q=' . ($keyword ?? '')) ?>"
                       class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentPage ?? 1) >= ($totalPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

    <?= $this->include('Admin/layouts/footer') ?>
</main>

<?php $this->endSection(); ?>
