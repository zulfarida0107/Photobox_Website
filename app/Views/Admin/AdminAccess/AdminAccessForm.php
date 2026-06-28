<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<main class="pt-16 min-h-screen flex flex-col">
    <div class="p-6 flex-1">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-4">
            <a class="hover:text-blue-700" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <a class="hover:text-blue-700" href="<?= base_url('admin/settings') ?>">Pengaturan Akses</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-blue-700"><?= isset($admin) ? 'Edit Admin' : 'Tambah Admin' ?></span>
        </nav>

        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="font-h1 text-h1 text-on-surface"><?= isset($admin) ? 'Edit Administrator' : 'Tambah Administrator Baru' ?></h2>
            <p class="font-body-sm text-body-sm text-on-surface-variant"><?= isset($admin) ? 'Ubah informasi detail administrator atau atur ulang password.' : 'Daftarkan administrator baru untuk hak akses sistem.' ?></p>
        </div>

        <!-- Form Card -->
        <div class="bg-white border border-outline-variant rounded-xl shadow-sm max-w-2xl overflow-hidden">
            <div class="p-6 border-b border-outline-variant bg-slate-50/50 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">manage_accounts</span>
                <h4 class="font-bold text-on-surface">Data Kredensial Administrator</h4>
            </div>

            <!-- Error Alerts -->
            <?php if (!empty(session()->getFlashdata('errors'))): ?>
                <div class="m-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= isset($admin) ? base_url('admin/settings/' . $admin['id'] . '/update') : base_url('admin/settings') ?>" class="p-6 space-y-5">
                <?= csrf_field() ?>

                <?php if (isset($admin)): ?>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">ID Administrator</label>
                        <input type="text" value="#<?= esc($admin['id']) ?>" readonly class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-500 outline-none" />
                    </div>
                <?php endif; ?>

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap *</label>
                    <input type="text" name="name" id="name" required
                           value="<?= esc(old('name', $admin['name'] ?? '')) ?>"
                           placeholder="Masukkan nama lengkap admin"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Email *</label>
                    <input type="email" name="email" id="email" required
                           value="<?= esc(old('email', $admin['email'] ?? '')) ?>"
                           placeholder="contoh@cuitboxstudio.com"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password <?= isset($admin) ? '(Kosongkan jika tidak ingin diubah)' : '*' ?></label>
                    <input type="password" name="password" id="password" <?= isset($admin) ? '' : 'required' ?>
                           placeholder="<?= isset($admin) ? 'Masukkan password baru jika ingin mengubah' : 'Masukkan password minimal 8 karakter' ?>"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
                </div>

                <div>
                    <label for="role" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Hak Akses / Role *</label>
                    <select name="role" id="role" required
                            class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white">
                        <option value="superadmin" <?= old('role', $admin['role'] ?? '') === 'superadmin' ? 'selected' : '' ?>>Superadmin</option>
                        <option value="admin" <?= old('role', $admin['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="<?= base_url('admin/settings') ?>" class="px-5 py-2.5 rounded-lg border border-slate-200 text-slate-700 font-semibold text-sm hover:bg-slate-50 transition-colors">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white font-semibold text-sm rounded-lg shadow-md hover:bg-blue-800 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Simpan Admin
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- Footer -->
    <?= $this->include('Admin/layouts/footer') ?>
</main>

<?php $this->endSection(); ?>
