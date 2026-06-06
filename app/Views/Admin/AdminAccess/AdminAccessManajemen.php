<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<main class="pt-16 min-h-screen flex flex-col">
    <div class="p-6 flex-1">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 mb-6 text-slate-500 text-xs font-medium">
            <a class="hover:text-primary transition-colors" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-slate-900">Pengaturan Akses</span>
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
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="font-h1 text-h1 text-on-surface">Pengaturan Akses</h2>
                <p class="text-body-sm text-on-surface-variant">Kelola daftar administrator dan hak akses sistem Photobox v2.</p>
            </div>
            <a href="<?= base_url('admin/settings/create') ?>"
               class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-primary-container transition-colors shadow-sm">
                <span class="material-symbols-outlined text-lg">add_circle</span>
                Tambah Admin
            </a>
        </div>

        <!-- Stats Bento -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <!-- Total Admin -->
            <div class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined">manage_accounts</span>
                </div>
                <div>
                    <p class="text-label-bold text-slate-500">Total Admin</p>
                    <h3 class="text-h2 text-on-surface font-bold"><?= $statTotal ?? 0 ?></h3>
                </div>
            </div>
            <!-- Superadmin -->
            <div class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined">verified_user</span>
                </div>
                <div>
                    <p class="text-label-bold text-slate-500">Superadmin</p>
                    <h3 class="text-h2 text-on-surface font-bold"><?= $statSuperadmin ?? 0 ?></h3>
                </div>
            </div>
            <!-- Admin biasa -->
            <div class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined">badge</span>
                </div>
                <div>
                    <p class="text-label-bold text-slate-500">Admin</p>
                    <h3 class="text-h2 text-on-surface font-bold"><?= $statAdmin ?? 0 ?></h3>
                </div>
            </div>
            <!-- Status Keamanan -->
            <div class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-slate-50 text-slate-600 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined">security</span>
                </div>
                <div>
                    <p class="text-label-bold text-slate-500">Status Keamanan</p>
                    <h3 class="text-h2 text-on-surface font-bold">Optimal</h3>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
            <!-- Table Toolbar -->
            <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h4 class="font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">table_rows</span>
                    Daftar Administrator
                </h4>
                <div class="flex items-center gap-2">
                    <form method="get" action="<?= base_url('admin/settings') ?>" class="flex gap-2">
                        <select name="role" onchange="this.form.submit()"
                                class="text-xs bg-white border border-slate-300 rounded px-3 py-1.5 focus:ring-primary outline-none">
                            <option value="" <?= ($filterRole ?? '') === '' ? 'selected' : '' ?>>Semua Role</option>
                            <option value="superadmin" <?= ($filterRole ?? '') === 'superadmin' ? 'selected' : '' ?>>Superadmin</option>
                            <option value="admin"      <?= ($filterRole ?? '') === 'admin'      ? 'selected' : '' ?>>Admin</option>
                        </select>
                        <input name="q" value="<?= esc($keyword ?? '') ?>"
                               placeholder="Cari admin..."
                               class="text-xs bg-white border border-slate-300 rounded px-3 py-1.5 focus:ring-primary outline-none w-44"/>
                        <button type="submit" class="p-1.5 text-slate-400 hover:text-primary hover:bg-white border border-transparent hover:border-slate-200 rounded transition-all">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Data Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-white border-b border-slate-100">
                            <th class="px-5 py-3 text-label-bold text-slate-500 uppercase tracking-wider">ID Admin</th>
                            <th class="px-5 py-3 text-label-bold text-slate-500 uppercase tracking-wider">Nama / Username</th>
                            <th class="px-5 py-3 text-label-bold text-slate-500 uppercase tracking-wider">Email</th>
                            <th class="px-5 py-3 text-label-bold text-slate-500 uppercase tracking-wider">Role</th>
                            <th class="px-5 py-3 text-label-bold text-slate-500 uppercase tracking-wider">Terdaftar</th>
                            <th class="px-5 py-3 text-label-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (!empty($admins)): ?>
                            <?php foreach ($admins as $admin): ?>
                                <?php
                                    $initials = strtoupper(substr($admin['name'] ?? 'A', 0, 1) . (strpos($admin['name'] ?? '', ' ') !== false ? substr(strrchr($admin['name'], ' '), 1, 1) : substr($admin['name'] ?? 'D', 1, 1)));
                                    $isSuperadmin = strtolower($admin['role'] ?? '') === 'superadmin';
                                    $avatarClass  = $isSuperadmin ? 'bg-blue-100 text-blue-700' : 'bg-slate-200 text-slate-600';
                                    $badgeClass   = $isSuperadmin ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600';
                                ?>
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="px-5 py-4 text-data-tabular font-medium text-slate-900">#<?= esc($admin['id']) ?></td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full <?= $avatarClass ?> flex items-center justify-center font-bold text-xs flex-shrink-0">
                                                <?= esc($initials) ?>
                                            </div>
                                            <span class="text-body-base font-semibold text-on-surface"><?= esc($admin['name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-body-base text-on-surface-variant"><?= esc($admin['email']) ?></td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold <?= $badgeClass ?> uppercase tracking-wide">
                                            <?= esc(ucfirst($admin['role'] ?? 'admin')) ?>
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-body-sm text-slate-500">
                                        <?= date('d M Y, H:i', strtotime($admin['created_at'])) ?>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="<?= base_url('admin/settings/' . $admin['id'] . '/edit') ?>"
                                               class="p-2 text-slate-500 hover:text-primary hover:bg-white rounded border border-transparent hover:border-slate-200" title="Edit">
                                                <span class="material-symbols-outlined text-xl">edit_square</span>
                                            </a>
                                            <form action="<?= base_url('admin/settings/' . $admin['id'] . '/delete') ?>" method="post" class="inline">
                                                <?= csrf_field() ?>
                                                <button type="submit"
                                                        class="p-2 text-slate-500 hover:text-error hover:bg-white rounded border border-transparent hover:border-slate-200" title="Hapus"
                                                        onclick="return confirm('Hapus admin ini?')">
                                                    <span class="material-symbols-outlined text-xl">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-sm text-slate-400">
                                    Belum ada admin terdaftar.
                                    <a href="<?= base_url('admin/settings/create') ?>" class="text-primary font-semibold ml-1">Tambah sekarang</a>.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-slate-100 bg-slate-50/30 flex items-center justify-between">
                <p class="text-xs text-slate-500">
                    Menampilkan <?= count($admins ?? []) ?> dari <?= $totalAdmins ?? 0 ?> admin
                </p>
                <div class="flex items-center gap-1">
                    <a href="<?= base_url('admin/settings?page=' . max(1, ($currentPage ?? 1) - 1) . '&role=' . ($filterRole ?? '') . '&q=' . ($keyword ?? '')) ?>"
                       class="w-8 h-8 flex items-center justify-center rounded border border-slate-200 text-slate-400 hover:bg-white hover:text-primary transition-all <?= ($currentPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-lg">chevron_left</span>
                    </a>
                    <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                        <a href="<?= base_url('admin/settings?page=' . $i . '&role=' . ($filterRole ?? '') . '&q=' . ($keyword ?? '')) ?>"
                           class="w-8 h-8 flex items-center justify-center rounded text-xs font-bold
                                  <?= $i === ($currentPage ?? 1) ? 'bg-primary text-white' : 'border border-slate-200 text-slate-600 hover:bg-white hover:text-primary' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    <a href="<?= base_url('admin/settings?page=' . min(($totalPages ?? 1), ($currentPage ?? 1) + 1) . '&role=' . ($filterRole ?? '') . '&q=' . ($keyword ?? '')) ?>"
                       class="w-8 h-8 flex items-center justify-center rounded border border-slate-200 text-slate-400 hover:bg-white hover:text-primary transition-all <?= ($currentPage ?? 1) >= ($totalPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-lg">chevron_right</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

    <?= $this->include('Admin/layouts/footer') ?>
</main>

<?php $this->endSection(); ?>
