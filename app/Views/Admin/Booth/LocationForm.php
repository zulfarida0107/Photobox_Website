<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<main class="pt-16 min-h-screen flex flex-col">
    <div class="p-6 flex-1">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-4">
            <a class="hover:text-blue-700" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <a class="hover:text-blue-700" href="<?= base_url('admin/booths') ?>">Manajemen Mesin Booth</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-blue-700"><?= isset($location) ? 'Edit Lokasi Cabang' : 'Tambah Lokasi Cabang' ?></span>
        </nav>

        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="font-h1 text-h1 text-on-surface"><?= isset($location) ? 'Edit Lokasi Cabang' : 'Tambah Lokasi Cabang Baru' ?></h2>
            <p class="font-body-sm text-body-sm text-on-surface-variant"><?= isset($location) ? 'Ubah informasi titik lokasi, wilayah, dan koordinat geografis cabang.' : 'Daftarkan titik lokasi fisik cabang baru ke dalam sistem.' ?></p>
        </div>

        <!-- Form Card -->
        <div class="bg-white border border-outline-variant rounded-xl shadow-sm w-full overflow-hidden">
            <div class="p-6 border-b border-outline-variant bg-slate-50/50 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">storefront</span>
                <h4 class="font-bold text-on-surface">Konfigurasi Titik Lokasi Cabang</h4>
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

            <form method="post" action="<?= isset($location) ? base_url('admin/locations/' . $location['id'] . '/update') : base_url('admin/locations') ?>" class="p-6 space-y-5">
                <?= csrf_field() ?>

                <?php if (isset($location)): ?>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">ID Lokasi</label>
                        <input type="text" value="<?= esc($location['id']) ?>" readonly class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-500 outline-none" />
                    </div>
                <?php endif; ?>

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lokasi / Mall *</label>
                    <input type="text" name="name" id="name" required
                           value="<?= esc(old('name', $location['name'] ?? '')) ?>"
                           placeholder="Contoh: Margo City Mall"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
                </div>

                <div>
                    <label for="city" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kota / Wilayah (Opsional)</label>
                    <input type="text" name="city" id="city"
                           value="<?= esc(old('city', $location['city'] ?? '')) ?>"
                           placeholder="Contoh: Depok, Jawa Barat"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
                </div>

                <div>
                    <label for="address" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Lengkap (Opsional)</label>
                    <textarea name="address" id="address" rows="3"
                              placeholder="Contoh: Jl. Margonda Raya No. 358, Kemiri Muka, Kecamatan Beji, Kota Depok"
                              class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white"><?= esc(old('address', $location['address'] ?? '')) ?></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="latitude" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Latitude (Opsional)</label>
                        <input type="text" name="latitude" id="latitude"
                               value="<?= esc(old('latitude', $location['latitude'] ?? '')) ?>"
                               placeholder="Contoh: -6.372819"
                               class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
                    </div>
                    <div>
                        <label for="longitude" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Longitude (Opsional)</label>
                        <input type="text" name="longitude" id="longitude"
                               value="<?= esc(old('longitude', $location['longitude'] ?? '')) ?>"
                               placeholder="Contoh: 106.834190"
                               class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white" />
                    </div>
                </div>

                <?php if (isset($location)): ?>
                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status Operasional</label>
                        <select name="status" id="status" required
                                class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white">
                            <option value="active" <?= old('status', $location['status']) === 'active' ? 'selected' : '' ?>>Aktif (Operasional)</option>
                            <option value="inactive" <?= old('status', $location['status']) === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="<?= base_url('admin/booths') ?>" class="px-5 py-2.5 rounded-lg border border-slate-200 text-slate-700 font-semibold text-sm hover:bg-slate-50 transition-colors">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white font-semibold text-sm rounded-lg shadow-md hover:bg-blue-800 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Simpan Lokasi
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- Footer -->
    <?= $this->include('Admin/layouts/footer') ?>
</main>

<?php $this->endSection(); ?>
