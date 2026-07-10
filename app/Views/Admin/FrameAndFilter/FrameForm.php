<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<main class="pt-16 min-h-screen flex flex-col">
    <div class="p-6 flex-1">

        <!-- Breadcrumbs -->
        <nav class="flex flex-wrap items-center gap-2 text-xs font-medium text-slate-500 mb-4">
            <a class="hover:text-blue-700" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <a class="hover:text-blue-700" href="<?= base_url('admin/frames') ?>">Manajemen Aset Desain</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-blue-700"><?= isset($frame) ? 'Edit Frame' : 'Unggah Frame Baru' ?></span>
        </nav>

        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="font-h1 text-h1 text-on-surface"><?= isset($frame) ? 'Edit Frame' : 'Unggah Frame Baru' ?></h2>
            <p class="font-body-sm text-body-sm text-on-surface-variant"><?= isset($frame) ? 'Ubah informasi atau ganti file desain frame.' : 'Daftarkan desain bingkai foto baru untuk sistem booth.' ?></p>
        </div>

        <!-- Form Card -->
        <div class="bg-white border border-outline-variant rounded-xl shadow-sm w-full overflow-hidden">
            <div class="p-6 border-b border-outline-variant bg-slate-50/50 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">add_photo_alternate</span>
                <h4 class="font-bold text-on-surface">Konfigurasi File Frame</h4>
            </div>

            <!-- Error Alerts -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="m-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm font-medium">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <?php $returnParam = service('request')->getGet('return'); ?>
            <form method="post" action="<?= isset($frame) ? base_url('admin/frames/' . $frame['id'] . '/update') . ($returnParam ? '?return=' . esc($returnParam, 'url') : '') : base_url('admin/frames') ?>" enctype="multipart/form-data" class="p-6 space-y-5">
                <?= csrf_field() ?>

                <?php if (isset($frame)): ?>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">ID Frame</label>
                        <input type="text" value="<?= esc($frame['id']) ?>" readonly class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-500 outline-none" />
                    </div>
                <?php endif; ?>

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Frame *</label>
                    <input type="text" name="name" id="name" required
                           value="<?= esc(old('name', $frame['name'] ?? '')) ?>"
                           placeholder="Contoh: Classic White Minimalist"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
                </div>

                <div>
                    <label for="category_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori Frame</label>
                    <select name="category_id" id="category_id"
                            class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white">
                        <option value="">-- Pilih Kategori (Opsional) --</option>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= esc($cat['id']) ?>" <?= old('category_id', $frame['category_id'] ?? '') === $cat['id'] ? 'selected' : '' ?>>
                                    <?= esc($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div>
                    <label for="photo_count" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jumlah Foto *</label>
                    <input type="number" name="photo_count" id="photo_count" required min="1" max="10"
                           value="<?= esc(old('photo_count', $frame['photo_count'] ?? 6)) ?>"
                           placeholder="Contoh: 1 atau 6"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
                    <p class="text-[11px] text-slate-400 mt-1">Jumlah slot foto yang harus dipilih/dimasukkan oleh pengguna untuk frame ini (contoh: 1 untuk layout koran, 6 untuk layout strip standar).</p>
                </div>

                <div>
                    <label for="is_active" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status Aktif *</label>
                    <select name="is_active" id="is_active" required
                            class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white">
                        <option value="1" <?= old('is_active', $frame['is_active'] ?? '') != '0' ? 'selected' : '' ?>>Aktif (Siap digunakan customer)</option>
                        <option value="0" <?= old('is_active', $frame['is_active'] ?? '') == '0' ? 'selected' : '' ?>>Nonaktif (Disembunyikan)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">File Desain Frame (PNG Transparan) *</label>
                    
                    <?php if (isset($frame) && !empty($frame['file_path'])): ?>
                        <div class="mb-3 p-3 bg-slate-50 border border-slate-200 rounded-lg flex items-center gap-4">
                            <img src="<?= base_url($frame['file_path']) ?>" alt="Current Frame" class="w-20 h-14 object-contain border border-slate-300 rounded bg-white" />
                            <div>
                                <p class="text-xs font-semibold text-slate-700">Desain Saat Ini</p>
                                <p class="text-[10px] text-slate-400">Biarkan kosong jika tidak ingin mengubah gambar.</p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <input type="file" name="frame_file" id="frame_file" <?= isset($frame) ? '' : 'required' ?>
                           accept="image/png,image/jpeg"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
                    <p class="text-[11px] text-slate-400 mt-1">Format PNG transparan sangat direkomendasikan dengan ukuran file maksimal 2MB.</p>
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="<?= $returnParam ? esc($returnParam) : base_url('admin/frames') ?>" class="w-full sm:w-auto text-center px-5 py-2.5 rounded-lg border border-slate-200 text-slate-700 font-semibold text-sm hover:bg-slate-50 transition-colors">Batal</a>
                    <button type="submit" class="w-full sm:w-auto justify-center px-5 py-2.5 bg-primary text-white font-semibold text-sm rounded-lg shadow-md hover:bg-blue-800 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        <?= isset($frame) ? 'Perbarui Frame' : 'Unggah Frame' ?>
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- Footer -->
    <?= $this->include('Admin/layouts/footer') ?>
</main>

<?php $this->endSection(); ?>
