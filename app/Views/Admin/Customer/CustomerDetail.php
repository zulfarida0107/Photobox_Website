<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<main class="pt-16 min-h-screen flex flex-col">
    <div class="p-6 flex-1">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-4">
            <a class="hover:text-blue-700" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <a class="hover:text-blue-700" href="<?= base_url('admin/customers') ?>">Manajemen Pelanggan</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-blue-700">Detail Pelanggan</span>
        </nav>

        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="font-h1 text-h1 text-on-surface">Detail Pelanggan #<?= esc($customer['id']) ?></h2>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Informasi lengkap profil dan registrasi pelanggan.</p>
            </div>
            <a href="<?= base_url('admin/customers/' . $customer['id'] . '/edit') ?>"
               class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg font-semibold text-sm hover:bg-blue-800 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-sm">edit</span>
                Edit Pelanggan
            </a>
        </div>

        <!-- Detail Card -->
        <div class="bg-white border border-outline-variant rounded-xl shadow-sm max-w-2xl overflow-hidden">
            <div class="p-6 border-b border-outline-variant bg-slate-50/50 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">contact_page</span>
                <h4 class="font-bold text-on-surface">Data Registrasi Kontak</h4>
            </div>

            <div class="p-6 space-y-6">
                <!-- Info Row 1 -->
                <div class="grid grid-cols-3 gap-4 border-b border-slate-100 pb-4">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Lengkap</span>
                    <span class="col-span-2 text-sm text-slate-800 font-semibold"><?= esc($customer['name']) ?></span>
                </div>

                <!-- Info Row 2 -->
                <div class="grid grid-cols-3 gap-4 border-b border-slate-100 pb-4">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Alamat Email</span>
                    <span class="col-span-2 text-sm text-slate-800 font-mono"><?= esc($customer['email']) ?></span>
                </div>

                <!-- Info Row 3 -->
                <div class="grid grid-cols-3 gap-4 border-b border-slate-100 pb-4">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nomor Telepon</span>
                    <span class="col-span-2 text-sm text-slate-800 font-mono"><?= esc($customer['phone'] ?? '-') ?></span>
                </div>

                <!-- Info Row 4 -->
                <div class="grid grid-cols-3 gap-4 border-b border-slate-100 pb-4">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Terdaftar</span>
                    <span class="col-span-2 text-sm text-slate-800"><?= date('d M Y, H:i', strtotime($customer['created_at'])) ?></span>
                </div>

                <!-- Info Row 5 -->
                <div class="grid grid-cols-3 gap-4">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Terakhir Diperbarui</span>
                    <span class="col-span-2 text-sm text-slate-800"><?= date('d M Y, H:i', strtotime($customer['updated_at'])) ?></span>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                <form action="<?= base_url('admin/customers/' . $customer['id'] . '/delete') ?>" method="post" class="inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="px-4 py-2 border border-red-200 text-red-600 rounded-lg text-xs font-bold hover:bg-red-50 transition-colors"
                            onclick="return confirm('Hapus pelanggan ini?')">
                        Hapus Akun Pelanggan
                    </button>
                </form>
                <a href="<?= base_url('admin/customers') ?>" class="text-xs font-bold text-primary hover:underline">Kembali ke Daftar</a>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <?= $this->include('Admin/layouts/footer') ?>
</main>

<?php $this->endSection(); ?>
