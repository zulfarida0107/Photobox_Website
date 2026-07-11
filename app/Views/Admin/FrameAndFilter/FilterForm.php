<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<main class="pt-16 min-h-screen flex flex-col">
    <div class="p-6 flex-1">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-4">
            <a class="hover:text-blue-700" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <a class="hover:text-blue-700" href="<?= base_url('admin/frames') ?>">Manajemen Aset Desain</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-blue-700"><?= isset($filter) ? 'Edit Filter' : 'Tambah Filter Baru' ?></span>
        </nav>

        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="font-h1 text-h1 text-on-surface"><?= isset($filter) ? 'Edit Filter' : 'Tambah Filter Baru' ?></h2>
            <p class="font-body-sm text-body-sm text-on-surface-variant"><?= isset($filter) ? 'Ubah efek visual preset CSS untuk filter foto.' : 'Daftarkan preset filter CSS baru ke dalam sistem.' ?></p>
        </div>

        <!-- Form Card -->
        <div class="bg-white border border-outline-variant rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-outline-variant bg-slate-50/50 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">filter_b_and_w</span>
                <h4 class="font-bold text-on-surface">Konfigurasi Filter</h4>
            </div>

            <!-- Error Alerts -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="m-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm font-medium">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= isset($filter) ? base_url('admin/filters/' . $filter['id'] . '/update') : base_url('admin/filters') ?>" class="p-6 space-y-5">
                <?= csrf_field() ?>

                <?php if (isset($filter)): ?>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">ID Filter</label>
                        <input type="text" value="<?= esc($filter['id']) ?>" readonly class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-500 outline-none" />
                    </div>
                <?php endif; ?>

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Filter *</label>
                    <input type="text" name="name" id="name" required
                           value="<?= esc(old('name', $filter['name'] ?? '')) ?>"
                           placeholder="Contoh: Warm Vintage, Black & White"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
                </div>

                <div>
                    <label for="css_value" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nilai Filter CSS *</label>
                    <input type="text" name="css_value" id="css_value" required
                           value="<?= esc(old('css_value', $filter['css_value'] ?? '')) ?>"
                           placeholder="Contoh: sepia(0.8) contrast(1.1) atau grayscale(1)"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
                    <p class="text-[11px] text-slate-400 mt-1">Masukkan string filter CSS standard (misalnya `grayscale(1)` atau `none`).</p>
                </div>

                <div>
                    <label for="is_active" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status Aktif</label>
                    <select name="is_active" id="is_active" required
                            class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white">
                        <option value="1" <?= old('is_active', $filter['is_active'] ?? '') == '1' ? 'selected' : '' ?>>Aktif (Tersedia untuk Customer)</option>
                        <option value="0" <?= old('is_active', $filter['is_active'] ?? '') == '0' ? 'selected' : '' ?>>Nonaktif (Disembunyikan)</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="<?= base_url('admin/frames') ?>" class="px-5 py-2.5 rounded-lg border border-slate-200 text-slate-700 font-semibold text-sm hover:bg-slate-50 transition-colors">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white font-semibold text-sm rounded-lg shadow-md hover:bg-blue-800 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        <?= isset($filter) ? 'Perbarui Filter' : 'Simpan Filter' ?>
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- Footer -->
    <?= $this->include('Admin/layouts/footer') ?>
</main>

<?php $this->endSection(); ?>
