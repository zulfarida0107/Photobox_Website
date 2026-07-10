<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<main class="pt-16 min-h-screen flex flex-col">
    <div class="p-6 flex-1">

        <!-- Breadcrumbs -->
        <nav class="flex flex-wrap items-center gap-2 text-xs font-medium text-slate-500 mb-4">
            <a class="hover:text-blue-700" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <a class="hover:text-blue-700" href="<?= base_url('admin/packages') ?>">Paket & Cetak</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-blue-700"><?= isset($package) ? 'Edit Paket' : 'Buat Paket Baru' ?></span>
        </nav>

        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="font-h1 text-h1 text-on-surface"><?= isset($package) ? 'Edit Paket Layanan' : 'Buat Paket Baru' ?></h2>
            <p class="font-body-sm text-body-sm text-on-surface-variant"><?= isset($package) ? 'Perbarui informasi paket layanan.' : 'Tambahkan paket layanan photobox baru ke dalam basis data.' ?></p>
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

        <!-- Form Card -->
        <div class="bg-white border border-outline-variant rounded-xl shadow-sm w-full overflow-hidden">
            
            <div class="p-6 border-b border-outline-variant bg-slate-50/50 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">inventory_2</span>
                <h4 class="font-bold text-on-surface">Informasi Paket Layanan</h4>
            </div>

            <?php
            $actionUrl = isset($package) ? base_url('admin/packages/' . $package['id'] . '/update') : base_url('admin/packages');
            ?>
            <form action="<?= $actionUrl ?>" method="post" class="p-6 space-y-5">
                <?= csrf_field() ?>

                <?php if (isset($package)): ?>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">ID Paket</label>
                        <input type="text" value="<?= esc($package['id']) ?>" readonly class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-500 outline-none" />
                    </div>
                <?php endif; ?>

                <!-- Nama Paket -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Paket *</label>
                    <input type="text" name="name" required
                           value="<?= old('name') ?? ($package['name'] ?? '') ?>"
                           placeholder="Contoh: Basic Package, Premium, dll."
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Jumlah Foto -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jumlah Foto (Jepretan) *</label>
                        <input type="number" name="photo_count" required min="1"
                               value="<?= old('photo_count') ?? ($package['photo_count'] ?? '') ?>"
                               placeholder="Contoh: 4"
                               class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white">
                    </div>

                    <!-- Durasi -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Durasi Sesi (Menit) *</label>
                        <input type="number" name="duration" required min="1"
                               value="<?= old('duration') ?? ($package['duration'] ?? '') ?>"
                               placeholder="Contoh: 10"
                               class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white">
                    </div>
                </div>

                <!-- Harga Dasar -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Harga Dasar (Rp) *</label>
                    <input type="number" name="base_price" required min="0"
                           value="<?= old('base_price') ?? (isset($package['base_price']) ? (int)$package['base_price'] : '') ?>"
                           placeholder="Contoh: 25000"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white">
                </div>

                <!-- Footer Buttons -->
                <div class="flex flex-col-reverse sm:flex-row sm:items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="<?= base_url('admin/packages') ?>"
                       class="w-full sm:w-auto text-center px-5 py-2.5 rounded-lg border border-slate-200 text-slate-700 font-semibold text-sm hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto justify-center px-5 py-2.5 bg-primary text-white font-semibold text-sm rounded-lg shadow-md hover:bg-blue-800 transition-all flex items-center gap-2">
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
