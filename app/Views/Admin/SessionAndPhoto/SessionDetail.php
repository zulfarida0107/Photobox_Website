<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<main class="pt-16 min-h-screen flex flex-col">
    <div class="p-6 flex-1">

        <!-- Breadcrumbs -->
        <nav aria-label="Breadcrumb" class="flex mb-6 text-xs font-medium text-slate-500">
            <ol class="flex items-center space-x-2">
                <li>
                    <a class="hover:text-blue-600 flex items-center gap-1 transition-colors" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
                </li>
                <li class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[10px]">chevron_right</span>
                    <a class="hover:text-blue-600 flex items-center gap-1 transition-colors" href="<?= base_url('admin/sessions') ?>">Manajemen Sesi</a>
                </li>
                <li class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[10px]">chevron_right</span>
                    <span class="text-blue-700 font-bold">Detail Sesi</span>
                </li>
            </ol>
        </nav>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm font-medium mb-6">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm font-medium mb-6">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="font-h1 text-h1 text-on-surface">Detail Sesi <span class="text-blue-600 font-mono">#<?= esc($session['id']) ?></span></h2>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Detail metadata sesi pemotretan dan daftar foto hasil jepretan.</p>
            </div>
            <div class="flex gap-2">
                <?php if (!empty($session['email_recipient'])): ?>
                    <form action="<?= base_url('admin/sessions/' . $session['id'] . '/resend') ?>" method="post" class="inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-lg font-semibold text-sm hover:bg-emerald-700 transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-sm">send</span>
                            Kirim Ulang Email
                        </button>
                    </form>
                <?php endif; ?>
                <form action="<?= base_url('admin/sessions/' . $session['id'] . '/delete') ?>" method="post" class="inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-lg font-semibold text-sm hover:bg-red-700 transition-colors shadow-sm"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus sesi ini beserta seluruh fotonya?')">
                        <span class="material-symbols-outlined text-sm">delete</span>
                        Hapus Sesi
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Left Info Panel (2 cols) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Session Metadata Card -->
                <div class="bg-white border border-outline-variant rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-outline-variant bg-slate-50/50 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">analytics</span>
                        <h4 class="font-bold text-on-surface">Metadata Sesi</h4>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-3 gap-4 border-b border-slate-100 pb-3">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">ID Sesi</span>
                            <span class="col-span-2 text-sm text-blue-600 font-bold font-mono">#<?= esc($session['id']) ?></span>
                        </div>
                        <div class="grid grid-cols-3 gap-4 border-b border-slate-100 pb-3">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kode Booking</span>
                            <span class="col-span-2 text-sm text-blue-700 font-bold font-mono">
                                <?= esc($session['booking_code'] ?? $session['booking_id'] ?? '-') ?>
                            </span>
                        </div>
                        <div class="grid grid-cols-3 gap-4 border-b border-slate-100 pb-3">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pilihan Frame</span>
                            <span class="col-span-2 text-sm text-slate-800"><?= esc($session['frame_name'] ?? 'Tidak Dipilih') ?></span>
                        </div>
                        <div class="grid grid-cols-3 gap-4 border-b border-slate-100 pb-3">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pilihan Filter</span>
                            <span class="col-span-2 text-sm text-slate-800 uppercase"><?= esc($session['filter_name'] ?? 'Tidak Dipilih') ?></span>
                        </div>
                        <div class="grid grid-cols-3 gap-4 border-b border-slate-100 pb-3">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Email Penerima</span>
                            <span class="col-span-2 text-sm text-slate-800 font-mono"><?= esc($session['email_recipient'] ?? '-') ?></span>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider font-mono">Waktu Mulai</span>
                            <span class="col-span-2 text-sm text-slate-800"><?= date('d F Y, H:i', strtotime($session['created_at'])) ?> WIB</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Drive Link Panel (1 col) -->
            <div class="lg:col-span-1">
                <div class="bg-white border border-outline-variant rounded-xl shadow-sm p-6 space-y-4 h-full flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-lg text-slate-900 flex items-center gap-2">
                                <span class="material-symbols-outlined text-blue-600">cloud_done</span>
                                Cloud Storage
                            </h3>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed mb-4">
                            Sesi foto ini disinkronkan dengan folder Cloud Storage untuk cadangan akses dan pengiriman tautan galeri digital ke email pelanggan.
                        </p>
                    </div>

                    <div>
                        <?php if (!empty($session['drive_folder_url'])): ?>
                            <a href="<?= esc($session['drive_folder_url']) ?>" target="_blank"
                               class="w-full bg-blue-50 border border-blue-200 text-blue-700 hover:bg-blue-100 font-bold py-2.5 rounded-lg transition-all flex items-center justify-center gap-2 text-sm shadow-sm">
                                <span class="material-symbols-outlined">open_in_new</span>
                                Buka Google Drive
                            </a>
                        <?php else: ?>
                            <div class="w-full bg-slate-50 border border-dashed border-slate-200 text-slate-400 py-3 rounded-lg text-center text-xs font-medium">
                                Link Drive belum dihasilkan
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gallery / Photos Section -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden p-6 mb-8">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                <h3 class="font-h2 text-h2 text-on-surface">Foto Hasil Jepretan</h3>
                <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full text-xs font-bold border border-blue-100">
                    Total: <?= count($photos) ?> Foto
                </span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <?php if (!empty($photos)): ?>
                    <?php foreach ($photos as $index => $photo): ?>
                        <div class="group relative bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all">
                            <div class="aspect-square bg-slate-200 relative overflow-hidden">
                                <?php if (!empty($photo['file_path'])): ?>
                                    <img alt="Shot #<?= $photo['shot_number'] ?? ($index + 1) ?>"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                         src="<?= base_url($photo['file_path']) ?>"/>
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center bg-slate-100">
                                        <span class="material-symbols-outlined text-slate-300 text-4xl">image</span>
                                    </div>
                                <?php endif; ?>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center p-4">
                                    <div class="flex gap-2">
                                        <?php if (!empty($photo['file_path'])): ?>
                                            <a href="<?= base_url($photo['file_path']) ?>" download
                                               class="bg-white/20 backdrop-blur-md p-2 rounded-full text-white hover:bg-white hover:text-blue-700 transition-colors">
                                                <span class="material-symbols-outlined text-sm">download</span>
                                            </a>
                                        <?php endif; ?>
                                        <form action="<?= base_url('admin/photos/' . $photo['id'] . '/delete') ?>" method="post" class="inline">
                                            <?= csrf_field() ?>
                                            <button type="submit"
                                                    class="bg-white/20 backdrop-blur-md p-2 rounded-full text-white hover:bg-red-600 transition-colors"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus foto ini secara permanen?')">
                                                <span class="material-symbols-outlined text-sm">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 flex justify-between items-center">
                                <div>
                                    <p class="text-xs font-bold text-slate-900">Shot #<?= str_pad($photo['shot_number'] ?? ($index + 1), 2, '0', STR_PAD_LEFT) ?></p>
                                    <p class="text-[10px] text-slate-500"><?= date('H:i:s', strtotime($photo['created_at'])) ?> WIB</p>
                                </div>
                                <?php if (!empty($photo['drive_file_id'])): ?>
                                    <span class="bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded text-[9px] font-black tracking-tight" title="Tersimpan di Cloud">CLOUD</span>
                                <?php else: ?>
                                    <span class="bg-green-100 text-green-700 px-1.5 py-0.5 rounded text-[9px] font-black tracking-tight" title="Tersimpan Lokal">SAVED</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full bg-slate-50 border border-dashed border-slate-300 rounded flex flex-col items-center justify-center gap-2 py-16">
                        <span class="material-symbols-outlined text-slate-300 text-5xl">add_a_photo</span>
                        <span class="text-sm font-bold text-slate-400">Belum ada foto yang diambil untuk sesi ini.</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="flex justify-end">
            <a href="<?= base_url('admin/sessions') ?>" class="px-5 py-2.5 bg-slate-100 border border-slate-200 text-slate-700 hover:bg-slate-200 rounded-lg text-sm font-bold transition-all flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                Kembali ke Manajemen Sesi
            </a>
        </div>

    </div>

    <!-- Footer -->
    <?= $this->include('Admin/layouts/footer') ?>
</main>

<?php $this->endSection(); ?>
