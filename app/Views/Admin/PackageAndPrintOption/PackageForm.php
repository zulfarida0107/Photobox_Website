<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<main class="pt-16 min-h-screen flex flex-col bg-slate-50">
    <div class="p-6 max-w-full mx-auto w-full flex-1">
        
        <!-- Breadcrumbs -->
        <nav aria-label="Breadcrumb" class="flex mb-6 text-xs font-medium text-slate-500">
            <ol class="flex items-center space-x-2">
                <li><a class="hover:text-blue-600 transition-colors" href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li><span class="material-symbols-outlined text-[10px]">chevron_right</span></li>
                <li><a class="hover:text-blue-600 transition-colors" href="<?= base_url('admin/packages') ?>">Paket &amp; Cetak</a></li>
                <li><span class="material-symbols-outlined text-[10px]">chevron_right</span></li>
                <li><span class="text-blue-700 font-bold"><?= isset($package) ? 'Edit Paket' : 'Tambah Paket Baru' ?></span></li>
            </ol>
        </nav>

        
        <div class="mb-6">
            <h2 class="font-h1 text-h1 text-on-surface"><?= isset($package) ? 'Edit Paket' : 'Tambah Paket Baru' ?></h2>
            <p class="font-body-sm text-body-sm text-on-surface-variant">Ubah pengaturan dan detail operasional paket layanan.</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">

            <div class="p-6 border-b border-slate-100 bg-white flex items-center gap-3">
                <span class="material-symbols-outlined text-[24px] text-primary">inventory_2</span>
                <h4 class="font-h2 text-h2 text-slate-800">Konfigurasi Paket Layanan</h4>
            </div>
            
            <div class="p-6 bg-white">
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                        <ul class="list-disc pl-5">
                            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                                <li><?= esc($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= isset($package) ? base_url('admin/packages/' . $package['id'] . '/update') : base_url('admin/packages') ?>" class="space-y-6">
                    <?= csrf_field() ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Paket</label>
                            <input type="text" name="name" value="<?= old('name', $package['name'] ?? '') ?>" required
                                   class="w-full px-4 py-2 border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm"/>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Jumlah Foto</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[18px]">photo_camera</span>
                                <input type="number" name="photo_count" value="<?= old('photo_count', $package['photo_count'] ?? '') ?>" required min="1"
                                       class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm"/>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Durasi Sesi (Menit)</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[18px]">timer</span>
                                <input type="number" name="duration" value="<?= old('duration', $package['duration'] ?? '') ?>" required min="1"
                                       class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm"/>
                            </div>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Harga Dasar (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-2.5 text-slate-400 font-bold text-sm">Rp</span>
                                <input type="number" name="base_price" value="<?= old('base_price', $package['base_price'] ?? '') ?>" required min="0"
                                       class="w-full pl-12 pr-4 py-2 border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm"/>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                        <a href="<?= base_url('admin/packages') ?>" class="px-6 py-2 bg-slate-100 text-slate-600 rounded-lg font-bold text-sm hover:bg-slate-200 transition-colors">Batal</a>
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg font-bold text-sm hover:bg-blue-800 transition-colors shadow-sm flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">save</span> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?= $this->include('Admin/layouts/footer') ?>
</main>
<?php $this->endSection(); ?>
