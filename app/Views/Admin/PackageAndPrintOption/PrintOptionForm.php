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
            <span class="text-blue-700"><?= isset($option) ? 'Edit Opsi Cetak' : 'Buat Opsi Cetak' ?></span>
        </nav>

        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="font-h1 text-h1 text-on-surface"><?= isset($option) ? 'Edit Opsi Cetak' : 'Buat Opsi Cetak Baru' ?></h2>
            <p class="font-body-sm text-body-sm text-on-surface-variant"><?= isset($option) ? 'Perbarui informasi opsi tambahan cetak foto.' : 'Tambahkan opsi tambahan jumlah cetakan foto (ekstra print).' ?></p>
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
                <span class="material-symbols-outlined text-primary">print</span>
                <h4 class="font-bold text-on-surface">Informasi Opsi Cetak</h4>
            </div>

            <?php
            $actionUrl = isset($option) ? base_url('admin/print-options/' . $option['id'] . '/update') : base_url('admin/print-options');
            ?>
            <form action="<?= $actionUrl ?>" method="post" class="p-6 space-y-5">
                <?= csrf_field() ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    
                    <!-- Copies -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jumlah Lembar (Copy) *</label>
                        <input type="number" name="copies" required min="1"
                               value="<?= old('copies') ?? ($option['copies'] ?? '') ?>"
                               placeholder="Contoh: 2"
                               class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white">
                        <p class="text-[11px] text-slate-500 mt-2">Jumlah lembar foto fisik yang akan dicetak sebagai tambahan.</p>
                    </div>

                    <!-- Harga Tambahan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Harga Ekstra (Rp) *</label>
                        <input type="number" name="extra_price" required min="0"
                               value="<?= old('extra_price') ?? (isset($option['extra_price']) ? (int)$option['extra_price'] : '') ?>"
                               placeholder="Contoh: 15000"
                               class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-slate-700 transition-all bg-white">
                        <p class="text-[11px] text-slate-500 mt-2">Biaya tambahan yang dikenakan untuk opsi ini. Isi 0 jika gratis.</p>
                    </div>

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
