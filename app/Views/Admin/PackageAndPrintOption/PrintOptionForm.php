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
                    <span class="text-primary font-bold"><?= isset($option) ? 'Edit Opsi Cetak' : 'Buat Opsi Cetak' ?></span>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="font-h1 text-h1 text-on-surface tracking-tight"><?= isset($option) ? 'Edit Opsi Cetak' : 'Buat Opsi Cetak Baru' ?></h1>
            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">
                <?= isset($option) ? 'Perbarui informasi opsi tambahan cetak foto.' : 'Tambahkan opsi tambahan jumlah cetakan foto (ekstra print).' ?>
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
                <span class="material-symbols-outlined text-primary">print</span>
                <h2 class="font-h3 text-h3 text-on-surface">Informasi Opsi Cetak</h2>
            </div>

            <?php
            $actionUrl = isset($option) ? base_url('admin/print-options/' . $option['id'] . '/update') : base_url('admin/print-options');
            ?>
            <form action="<?= $actionUrl ?>" method="post" class="p-8">
                <?= csrf_field() ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Copies -->
                    <div>
                        <label class="block font-label-bold text-label-bold text-on-surface uppercase mb-2">Jumlah Lembar (Copy) *</label>
                        <input type="number" name="copies" required min="1"
                               value="<?= old('copies') ?? ($option['copies'] ?? '') ?>"
                               placeholder="Contoh: 2"
                               class="w-full px-4 py-3 bg-white border border-outline-variant rounded-xl font-body text-body text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        <p class="text-[11px] text-slate-500 mt-2">Jumlah lembar foto fisik yang akan dicetak sebagai tambahan.</p>
                    </div>

                    <!-- Harga Tambahan -->
                    <div>
                        <label class="block font-label-bold text-label-bold text-on-surface uppercase mb-2">Harga Ekstra (Rp) *</label>
                        <input type="number" name="extra_price" required min="0"
                               value="<?= old('extra_price') ?? (isset($option['extra_price']) ? (int)$option['extra_price'] : '') ?>"
                               placeholder="Contoh: 15000"
                               class="w-full px-4 py-3 bg-white border border-outline-variant rounded-xl font-body text-body text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        <p class="text-[11px] text-slate-500 mt-2">Biaya tambahan yang dikenakan untuk opsi ini. Isi 0 jika gratis.</p>
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
                        <?= isset($option) ? 'Simpan Perubahan' : 'Simpan Opsi Cetak' ?>
                    </button>
                </div>
            </form>
        </div>

    </div>
</main>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php $this->endSection(); ?>
