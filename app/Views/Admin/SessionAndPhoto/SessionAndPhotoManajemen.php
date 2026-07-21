<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<main class="pt-16 min-h-screen flex flex-col">
    <div class="p-6 space-y-6 flex-1">

        <!-- Breadcrumbs -->
        <nav aria-label="Breadcrumb" class="flex mb-6 text-xs font-medium text-slate-500">
            <ol class="flex items-center space-x-2">
                <li>
                    <a class="hover:text-blue-600 flex items-center gap-1 transition-colors" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
                </li>
                <li class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[10px]">chevron_right</span>
                    <span class="text-blue-700 font-bold">Manajemen Galeri Sesi</span>
                </li>
            </ol>
        </nav>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div id="flash-message-success" class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm font-medium flex items-center gap-2">
                <span class="material-symbols-outlined text-green-500">check_circle</span>
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div id="flash-message-error" class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm font-medium flex items-center gap-2">
                <span class="material-symbols-outlined text-red-500">error</span>
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <script>
            setTimeout(() => {
                const ids = ['flash-message-success', 'flash-message-error'];
                ids.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.remove();
                });
            }, 1000);
        </script>

        <!-- Page Title & Actions -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-h1 text-h1 text-on-surface">Manajemen Galeri Sesi</h2>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Kelola seluruh riwayat sesi foto dan pengiriman email.</p>
            </div>
            <div class="flex gap-2">
                <a href="<?= base_url('admin/sessions/export') ?>" onclick="showExportNotif()"
                   class="bg-white border-2 border-blue-600 text-blue-600 px-4 py-2 rounded-lg font-label-bold text-label-bold flex items-center gap-2 hover:bg-blue-50 hover:border-blue-700 hover:text-blue-700 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">cloud_download</span>
                    Ekspor Laporan
                </a>
            </div>
        </div>

        <!-- Bento Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Total Sesi -->
            <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">camera</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Total Sesi</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= $totalSessions ?? 0 ?></h3>
            </div>
            <!-- Sesi Hari Ini -->
            <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">today</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Sesi Hari Ini</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= $statToday ?? 0 ?></h3>
            </div>
            <!-- Sesi Bulan Ini -->
            <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">calendar_month</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Sesi Bulan Ini</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= $statThisMonth ?? 0 ?></h3>
            </div>
            <!-- Total Foto -->
            <div class="bg-white p-6 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">collections</span>
                </div>
                <p class="text-sm font-medium text-slate-500">Total Foto</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= number_format($statTotalPhotos ?? 0) ?></h3>
            </div>
        </div>

        <!-- Bento Grid -->
        <div class="grid grid-cols-12 gap-6">

            <!-- ============================================================
                 Tabel Sesi (8 kolom)
            ============================================================ -->
            <div class="col-span-12 lg:col-span-8 bg-white border border-slate-200 rounded-xl shadow-sm flex flex-col">
                <!-- Table Header -->
                <div class="p-6 border-b border-slate-200 flex flex-col md:flex-row justify-between items-center bg-white gap-4 rounded-t-xl">
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <div class="w-1 h-6 bg-primary rounded-full"></div>
                        <h4 class="font-h2 text-h2 text-on-surface">Daftar Riwayat Sesi Terkini</h4>
                        <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded-full text-[10px] font-bold border border-blue-100 ml-2">
                            Total: <?= $totalSessions ?? 0 ?> Sesi
                        </span>
                    </div>
                </div>

                <!-- Search & Filter Bar -->
                <div class="p-6 bg-white border-b border-slate-200 flex flex-col sm:flex-row gap-3">
                    <form method="get" action="<?= base_url('admin/sessions') ?>" class="flex flex-col sm:flex-row flex-1 gap-3 w-full">
                        <div class="relative flex-1">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[20px]">search</span>
                            <input type="text" name="q" value="<?= esc($keyword ?? '') ?>" placeholder="Cari ID sesi / booking / email..."
                                   class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white transition-all"/>
                        </div>
                        <?php if (!empty($keyword)): ?>
                            <a href="<?= base_url('admin/sessions') ?>" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg flex items-center justify-center transition-colors">
                                Reset
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-center">
                        <thead>
                            <tr class="bg-white border-b border-slate-100">
                                <th class="px-6 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider text-center">ID Sesi</th>
                                <th class="px-6 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider text-center">Kode Booking</th>
                                <th class="px-6 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider text-center">Frame / Filter</th>
                                <th class="px-6 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider text-center">Penerima Email</th>
                                <th class="px-6 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider text-center">Waktu</th>
                                <th class="px-6 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 bg-white">
                            <?php if (!empty($sessions)): ?>
                                <?php foreach ($sessions as $session): ?>
                                    <tr class="hover:bg-blue-50/30 transition-colors group">
                                        <td class="px-6 py-4 font-medium text-slate-900 text-sm">
                                            #<?= esc($session['id']) ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-600">
                                            <?= esc($session['booking_id'] ?? '-') ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <div class="flex flex-col items-center">
                                                <span class="font-semibold text-slate-900"><?= esc($session['frame_name'] ?? 'N/A') ?></span>
                                                <span class="text-[11px] text-slate-500 uppercase tracking-wide"><?= esc($session['filter_name'] ?? 'N/A') ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-600">
                                            <?= esc($session['email_recipient'] ?? '-') ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-500">
                                            <?= date('H:i', strtotime($session['created_at'])) ?> WIB
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="<?= base_url('admin/sessions/' . $session['id']) ?>"
                                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                                                    <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                                                </a>
                                                <?php if (!empty($session['email_recipient'])): ?>
                                                    <form action="<?= base_url('admin/sessions/' . $session['id'] . '/resend') ?>" method="post" class="inline">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Kirim Ulang Email">
                                                            <span class="material-symbols-outlined text-[18px]">send</span>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                <form action="<?= base_url('admin/sessions/' . $session['id'] . '/delete') ?>" method="post" class="inline">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Sesi"
                                                            onclick="return confirm('Hapus sesi ini beserta seluruh fotonya?')">
                                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-400">
                                        Belum ada sesi pemotretan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="p-6 border-t border-slate-100 bg-white flex items-center justify-between rounded-b-xl">
                    <span class="text-xs text-slate-500">
                        Menampilkan <?= count($sessions ?? []) ?> dari <?= $totalSessions ?? 0 ?> sesi
                    </span>
                    <div class="flex gap-2">
                        <a href="<?= base_url('admin/sessions?page=' . max(1, ($currentPage ?? 1) - 1) . '&q=' . ($keyword ?? '')) ?>"
                           class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                            <span class="material-symbols-outlined text-sm">chevron_left</span>
                        </a>
                        <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                            <a href="<?= base_url('admin/sessions?page=' . $i . '&q=' . ($keyword ?? '')) ?>"
                               class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-colors <?= $i === ($currentPage ?? 1) ? 'border border-primary bg-primary text-white' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                        <a href="<?= base_url('admin/sessions?page=' . min(($totalPages ?? 1), ($currentPage ?? 1) + 1) . '&q=' . ($keyword ?? '')) ?>"
                           class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentPage ?? 1) >= ($totalPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                            <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- ============================================================
                 Panel Kanan: Penyimpanan (4 kolom)
            ============================================================ -->
            <div class="col-span-12 lg:col-span-4 flex flex-col">
                <!-- Storage Card -->
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 flex-1 flex flex-col">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-1 h-6 bg-primary rounded-full"></div>
                        <h3 class="font-h2 text-h2 text-on-surface">Sesi Terbaru</h3>
                    </div>
                    <?php if (!empty($latestSession)): ?>
                        <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 flex-1">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-2">Terakhir Ditambahkan</label>
                            <div class="flex items-center justify-between gap-2 overflow-hidden">
                                <p class="text-sm truncate text-blue-700 font-medium">
                                    #<?= esc($latestSession['id']) ?> — <?= esc($latestSession['email_recipient'] ?? 'No email') ?>
                                </p>
                                <?php if (!empty($latestSession['drive_folder_url'])): ?>
                                    <a href="<?= esc($latestSession['drive_folder_url']) ?>" target="_blank"
                                       class="shrink-0 p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Buka Drive">
                                        <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 flex-1 flex items-center justify-center text-slate-400 text-sm">
                            Belum ada sesi terbaru
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ============================================================
                 Aksi Cepat (Full Width, di bawah)
            ============================================================ -->
            <div class="col-span-12">
                <!-- Quick Action Card -->
                <div class="bg-blue-700 text-white rounded-xl shadow-sm p-6 relative overflow-hidden flex flex-col items-start gap-4">
                    <span class="material-symbols-outlined absolute -right-8 top-1/2 -translate-y-1/2 text-9xl text-white opacity-10">mail</span>
                    <div class="z-10">
                        <h4 class="text-[10px] font-bold text-blue-100 uppercase tracking-wider mb-1">Aksi Cepat</h4>
                        <p class="font-h2 text-h2 leading-tight text-white mb-2">Gagal Mengirim Email?</p>
                    </div>
                    <a href="<?= base_url('admin/sessions/resend-all') ?>"
                       class="w-full bg-white text-blue-700 font-bold px-4 py-3 rounded-lg hover:bg-blue-50 transition-colors flex items-center justify-center gap-2 text-sm shadow-sm z-10 border border-blue-100">
                        <span class="material-symbols-outlined text-[18px]">send</span>
                        Kirim Manual Sekarang
                    </a>
                </div>
            </div>

            <!-- ============================================================
                 Galeri Foto (full width)
            ============================================================ -->
            <div class="col-span-12">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-h2 text-h2 text-on-surface">
                        Galeri Foto
                        <?php if (!empty($gallerySession)): ?>
                            Sesi #<?= esc($gallerySession['id']) ?>
                        <?php endif; ?>
                    </h3>
                    <?php if (!empty($gallerySession)): ?>
                        <div class="flex items-center gap-4">
                            <span class="text-xs text-slate-500"><?= count($photos ?? []) ?> foto</span>
                            <a href="<?= base_url('admin/sessions/' . $gallerySession['id']) ?>"
                               class="text-xs font-bold text-primary hover:underline">Lihat Detail →</a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    <?php if (!empty($photos)): ?>
                        <?php foreach ($photos as $index => $photo): ?>
                            <div class="group relative bg-white border border-slate-200 rounded overflow-hidden shadow-sm hover:shadow-md transition-all">
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
                                                        class="bg-white/20 backdrop-blur-md p-2 rounded-full text-white hover:bg-error transition-colors"
                                                        onclick="return confirm('Hapus foto ini?')">
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
                                        <span class="bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded text-[9px] font-black tracking-tight">CLOUD</span>
                                    <?php else: ?>
                                        <span class="bg-green-100 text-green-700 px-1.5 py-0.5 rounded text-[9px] font-black tracking-tight">SAVED</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-5 bg-slate-50 border border-dashed border-slate-300 rounded flex flex-col items-center justify-center gap-2 py-16">
                            <span class="material-symbols-outlined text-slate-300 text-5xl">add_a_photo</span>
                            <span class="text-sm font-bold text-slate-400">Belum ada foto tersimpan</span>
                        </div>
                    <?php endif; ?>
                    <!-- Empty state slot -->
                    <div class="bg-slate-50 border border-dashed border-slate-300 rounded flex flex-col items-center justify-center gap-2 aspect-square">
                        <span class="material-symbols-outlined text-slate-300 text-4xl">add_a_photo</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Menunggu Input</span>
                    </div>
                </div>
            </div>

        </div>
        <?= $this->include('Admin/layouts/footer') ?>
    </div>
</main>

<?php $this->endSection(); ?>
