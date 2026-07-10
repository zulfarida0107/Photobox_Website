<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<main class="pt-16 min-h-screen flex flex-col">
    <div class="p-6 flex-1">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-4">
            <a class="hover:text-blue-700" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <a class="hover:text-blue-700" href="<?= base_url('admin/booths') ?>">Manajemen Booth</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-blue-700"><?= isset($booth) ? 'Edit Booth' : 'Tambah Booth' ?></span>
        </nav>

        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="font-h1 text-h1 text-on-surface"><?= isset($booth) ? 'Edit Booth' : 'Tambah Booth Baru' ?></h2>
            <p class="font-body-sm text-body-sm text-on-surface-variant"><?= isset($booth) ? 'Ubah pengaturan dan status operasional mesin booth.' : 'Daftarkan unit mesin booth baru ke dalam sistem.' ?></p>
        </div>

        <!-- Form Card -->
        <div class="bg-white border border-outline-variant rounded-xl shadow-sm w-full overflow-hidden">
            <div class="p-6 border-b border-outline-variant bg-slate-50/50 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">nest_display</span>
                <h4 class="font-bold text-on-surface">Konfigurasi Mesin Booth</h4>
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

            <form method="post" action="<?= isset($booth) ? base_url('admin/booths/' . $booth['id'] . '/update') : base_url('admin/booths') ?>" class="p-6 space-y-5">
                <?= csrf_field() ?>

                <?php if (isset($booth)): ?>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">ID Booth</label>
                        <input type="text" value="<?= esc($booth['id']) ?>" readonly class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-500 outline-none" />
                    </div>
                <?php endif; ?>

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Booth / Lokasi *</label>
                    <input type="text" name="name" id="name" required
                           value="<?= esc(old('name', $booth['name'] ?? '')) ?>"
                           placeholder="Contoh: Booth CuitBox - Grand Indonesia"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
                </div>

                <div>
                    <label for="ip_address" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat IP Booth (Opsional)</label>
                    <input type="text" name="ip_address" id="ip_address"
                           value="<?= esc(old('ip_address', $booth['ip_address'] ?? '')) ?>"
                           placeholder="Contoh: 192.168.1.50"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="latitude" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Latitude (Opsional)</label>
                        <input type="text" name="latitude" id="latitude"
                               value="<?= esc(old('latitude', $booth['latitude'] ?? '')) ?>"
                               placeholder="Contoh: -6.175392"
                               class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
                    </div>
                    <div>
                        <label for="longitude" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Longitude (Opsional)</label>
                        <input type="text" name="longitude" id="longitude"
                               value="<?= esc(old('longitude', $booth['longitude'] ?? '')) ?>"
                               placeholder="Contoh: 106.827153"
                               class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
                    </div>
                </div>

                <?php if (isset($booth)): ?>
                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status Operasional</label>
                        <select name="status" id="status" required
                                class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white">
                            <option value="active" <?= old('status', $booth['status']) === 'active' ? 'selected' : '' ?>>Aktif (Operasional)</option>
                            <option value="maintenance" <?= old('status', $booth['status']) === 'maintenance' ? 'selected' : '' ?>>Perbaikan (Maintenance)</option>
                            <option value="inactive" <?= old('status', $booth['status']) === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="<?= base_url('admin/booths') ?>" class="px-5 py-2.5 rounded-lg border border-slate-200 text-slate-700 font-semibold text-sm hover:bg-slate-50 transition-colors">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white font-semibold text-sm rounded-lg shadow-md hover:bg-blue-800 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Simpan Booth
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- Footer -->
    <?= $this->include('Admin/layouts/footer') ?>
</main>

<?php $this->endSection(); ?>
