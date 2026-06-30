<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<main class="pt-16 min-h-screen flex flex-col bg-slate-50">
    <div class="p-6 flex-1 max-w-4xl mx-auto w-full">

        <!-- Breadcrumbs -->
        <nav aria-label="Breadcrumb" class="flex mb-6 text-xs font-medium text-slate-500">
            <ol class="flex items-center space-x-2">
                <li><a class="hover:text-primary flex items-center gap-1 transition-colors" href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[10px]">chevron_right</span>
                    <a class="hover:text-primary transition-colors" href="<?= base_url('admin/packages') ?>">Paket & Cetak</a>
                </li>
                <li class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[10px]">chevron_right</span>
                    <span class="text-primary font-bold"><?= isset($package) ? 'Edit Paket' : 'Buat Paket Baru' ?></span>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="font-h1 text-h1 text-on-surface tracking-tight"><?= isset($package) ? 'Edit Paket Layanan' : 'Buat Paket Baru' ?></h1>
            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">
                <?= isset($package) ? 'Perbarui informasi paket layanan.' : 'Tambahkan paket layanan photobox baru ke dalam basis data.' ?>
            </p>
        </div>

        <?php if (session()->getFlashdata('errors')): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let errorMsg = '<ul style="text-align: left; margin-left: 20px;">';
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        errorMsg += '<li><?= esc($err) ?></li>';
                    <?php endforeach; ?>
                    errorMsg += '</ul>';

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops... Terjadi Kesalahan',
                        html: errorMsg,
                        confirmButtonColor: '#2563eb'
                    });
                });
            </script>
        <?php endif; ?>

        <!-- Form Container -->
        <div class="bg-white rounded-2xl shadow-sm border border-outline-variant overflow-hidden">
            
            <div class="border-b border-outline-variant px-8 py-5 bg-slate-50/50 flex items-center gap-3">
                <span class="material-symbols-outlined text-primary">inventory_2</span>
                <h2 class="font-h3 text-h3 text-on-surface">Informasi Paket Layanan</h2>
            </div>

            <?php
            $actionUrl = isset($package) ? base_url('admin/packages/' . $package['id'] . '/update') : base_url('admin/packages');
            ?>
            <form action="<?= $actionUrl ?>" method="post" class="p-8">
                <?= csrf_field() ?>

                <div class="space-y-6">
                    
                    <!-- Nama Paket -->
                    <div>
                        <label class="block font-label-bold text-label-bold text-on-surface uppercase mb-2">Nama Paket *</label>
                        <input type="text" name="name" required
                               value="<?= old('name') ?? ($package['name'] ?? '') ?>"
                               placeholder="Contoh: Basic Package, Premium, dll."
                               class="w-full px-4 py-3 bg-white border border-outline-variant rounded-xl font-body text-body text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Jumlah Foto -->
                        <div>
                            <label class="block font-label-bold text-label-bold text-on-surface uppercase mb-2">Jumlah Foto (Jepretan) *</label>
                            <input type="number" name="photo_count" required min="1"
                                   value="<?= old('photo_count') ?? ($package['photo_count'] ?? '') ?>"
                                   placeholder="Contoh: 4"
                                   class="w-full px-4 py-3 bg-white border border-outline-variant rounded-xl font-body text-body text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        </div>

                        <!-- Durasi -->
                        <div>
                            <label class="block font-label-bold text-label-bold text-on-surface uppercase mb-2">Durasi Sesi (Menit) *</label>
                            <input type="number" name="duration" required min="1"
                                   value="<?= old('duration') ?? ($package['duration'] ?? '') ?>"
                                   placeholder="Contoh: 10"
                                   class="w-full px-4 py-3 bg-white border border-outline-variant rounded-xl font-body text-body text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        </div>
                    </div>

                    <!-- Harga Dasar -->
                    <div>
                        <label class="block font-label-bold text-label-bold text-on-surface uppercase mb-2">Harga Dasar (Rp) *</label>
                        <input type="number" name="base_price" required min="0"
                               value="<?= old('base_price') ?? (isset($package['base_price']) ? (int)$package['base_price'] : '') ?>"
                               placeholder="Contoh: 25000"
                               class="w-full px-4 py-3 bg-white border border-outline-variant rounded-xl font-body text-body text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>

                </div>

                <!-- Footer Buttons -->
                <div class="mt-10 pt-6 border-t border-outline-variant flex items-center justify-end gap-3">
                    <a href="<?= base_url('admin/packages') ?>"
                       class="px-6 py-2.5 bg-white border border-outline-variant text-on-surface rounded-xl font-label-bold text-label-bold hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            class="flex items-center gap-2 px-6 py-2.5 bg-primary text-on-primary rounded-xl font-label-bold text-label-bold hover:opacity-90 transition-opacity shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        <?= isset($package) ? 'Simpan Perubahan' : 'Simpan Paket' ?>
                    </button>
                </div>
            </form>
        </div>

    </div>
</main>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php $this->endSection(); ?>
