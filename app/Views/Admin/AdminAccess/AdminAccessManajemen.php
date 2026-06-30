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
            <a href="<?= base_url('admin/settings/create') ?>"
               class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-primary-container transition-colors shadow-sm">
                <span class="material-symbols-outlined text-lg">add_circle</span>
                Tambah Admin
            </a>
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
                                class="text-xs bg-white border border-slate-300 rounded px-3 py-1.5 focus:ring-primary outline-none w-32">
                            <option value="" <?= ($filterRole ?? '') === '' ? 'selected' : '' ?>>Semua Role</option>
                            <option value="superadmin" <?= ($filterRole ?? '') === 'superadmin' ? 'selected' : '' ?>>Superadmin</option>
                            <option value="staff"      <?= ($filterRole ?? '') === 'staff'      ? 'selected' : '' ?>>Staff</option>
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
                            <th class="px-5 py-3 text-label-bold text-slate-500 uppercase tracking-wider text-center">ID Admin</th>
                            <th class="px-5 py-3 text-label-bold text-slate-500 uppercase tracking-wider">Nama / Username</th>
                            <th class="px-5 py-3 text-label-bold text-slate-500 uppercase tracking-wider">Email</th>
                            <th class="px-5 py-3 text-label-bold text-slate-500 uppercase tracking-wider text-center">Role</th>
                            <th class="px-5 py-3 text-label-bold text-slate-500 uppercase tracking-wider text-center">Terdaftar</th>
                            <th class="px-5 py-3 text-label-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
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
                                    <td class="px-5 py-4 text-data-tabular font-medium text-slate-900 text-center">#<?= esc($admin['id']) ?></td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full <?= $avatarClass ?> flex items-center justify-center font-bold text-xs flex-shrink-0">
                                                <?= esc($initials) ?>
                                            </div>
                                            <span class="text-body-base font-semibold text-on-surface"><?= esc($admin['name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-body-base text-on-surface-variant"><?= esc($admin['email']) ?></td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold <?= $badgeClass ?> uppercase tracking-wide">
                                            <span class="w-1.5 h-1.5 <?= $isSuperadmin ? 'bg-blue-500' : 'bg-slate-500' ?> rounded-full"></span>
                                            <?= $isSuperadmin ? 'SUPER ADMIN' : 'STAFF' ?>
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-body-sm text-slate-500 text-center">
                                        <?= date('d M Y, H:i', strtotime($admin['created_at'])) ?>
                                    </td>
                                    <td class="px-5 py-4 text-center space-x-2">
                                        <a href="<?= base_url('admin/settings/' . $admin['id'] . '/edit') ?>"
                                           class="inline p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                        <?php if ($admin['id'] !== ($loggedInId ?? '')): ?>
                                            <button type="button"
                                                    class="inline p-1.5 text-red-500 hover:bg-red-50 rounded transition-colors" title="Hapus Admin"
                                                    onclick="showDeleteAdminModal('<?= esc($admin['id'], 'js') ?>', '<?= esc($admin['name'], 'js') ?>', '<?= esc(ucfirst($admin['role']), 'js') ?>')">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        <?php else: ?>
                                            <span class="inline p-1.5 text-slate-300 rounded cursor-not-allowed" title="Tidak bisa menghapus akun aktif Anda">
                                                <span class="material-symbols-outlined text-[20px]">lock</span>
                                            </span>
                                        <?php endif; ?>
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

<!-- ================================================================
     Modal Konfirmasi Hapus ADMIN
================================================================ -->
<div id="deleteAdminModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-sm w-full mx-4 transform transition-all scale-95 opacity-0 duration-300" id="deleteAdminModalContent">
        <!-- Header -->
        <div class="p-5 border-b border-slate-100 bg-slate-50/50">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-red-500">admin_panel_settings</span>
                Hapus Akun Admin?
            </h4>
        </div>
        <!-- Body -->
        <div class="p-6 text-center">
            <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-4xl text-red-500">person_remove</span>
            </div>
            <h3 class="text-lg font-black text-slate-800 mb-1">Yakin hapus admin ini?</h3>
            <p class="text-xs text-slate-400 mb-1" id="deleteAdminIdLabel"></p>
            <p class="text-sm text-slate-500 leading-relaxed mb-6">
                Akun <strong id="deleteAdminName"></strong>
                (<span id="deleteAdminRole" class="font-semibold text-red-600"></span>)
                akan dihapus permanen dan tidak dapat dipulihkan.
            </p>
            <form id="deleteAdminForm" method="post">
                <?= csrf_field() ?>
                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteAdminModal()"
                            class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-sm rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold text-sm rounded-lg shadow-md transition-colors">
                        Ya, Hapus Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const baseSettingsUrl = '<?= base_url("admin/settings") ?>';

    function showDeleteAdminModal(adminId, adminName, adminRole) {
        document.getElementById('deleteAdminIdLabel').textContent = '#' + adminId;
        document.getElementById('deleteAdminName').textContent    = adminName;
        document.getElementById('deleteAdminRole').textContent    = adminRole;
        document.getElementById('deleteAdminForm').action         = baseSettingsUrl + '/' + adminId + '/delete';

        const modal   = document.getElementById('deleteAdminModal');
        const content = document.getElementById('deleteAdminModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeDeleteAdminModal() {
        const modal   = document.getElementById('deleteAdminModal');
        const content = document.getElementById('deleteAdminModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }
</script>

<?php $this->endSection(); ?>
