<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<div class="flex-1 ml-0 flex flex-col">
    <main class="mt-16 p-6 flex-1 space-y-6">

        <!-- Breadcrumbs -->
        <nav class="flex text-sm text-slate-500 gap-2 items-center">
            <a class="hover:text-blue-600 transition-colors" href="<?= base_url('admin/dashboard') ?>">Admin Dashboard</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-slate-900 font-medium">Manajemen Sesi &amp; Galeri Foto</span>
        </nav>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm font-medium">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm font-medium">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <!-- Page Title & Actions -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-h1 text-h1 text-on-surface">Manajemen Sesi</h2>
                <p class="text-slate-500 text-body-sm">Kelola arsip sesi pemotretan dan kirim ulang galeri digital.</p>
            </div>
            <div class="flex gap-2">
                <a href="<?= base_url('admin/sessions/export') ?>"
                   class="bg-white border border-outline-variant text-blue-600 px-4 py-2 rounded font-label-bold text-label-bold flex items-center gap-2 hover:bg-slate-50 transition-colors">
                    <span class="material-symbols-outlined">download</span>
                    Ekspor CSV
                </a>
                <a href="<?= base_url('admin/sessions/resend-all') ?>"
                   class="bg-primary text-on-primary px-4 py-2 rounded font-label-bold text-label-bold flex items-center gap-2 hover:bg-blue-800 transition-colors">
                    <span class="material-symbols-outlined">mail</span>
                    Kirim Ulang Email
                </a>
            </div>
        </div>

        <!-- Bento Grid -->
        <div class="grid grid-cols-12 gap-6">

            <!-- ============================================================
                 Tabel Sesi (8 kolom)
            ============================================================ -->
            <div class="col-span-12 lg:col-span-8 bg-white border border-slate-200 rounded shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-white">
                    <h3 class="font-h2 text-h2 text-on-surface">Riwayat Sesi Terkini</h3>
                    <div class="flex gap-2 items-center">
                        <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded-full text-[10px] font-bold border border-blue-100">
                            Total: <?= $totalSessions ?? 0 ?> Sesi
                        </span>
                        <form method="get" action="<?= base_url('admin/sessions') ?>" class="flex gap-2">
                            <input name="q" value="<?= esc($keyword ?? '') ?>"
                                   class="pl-3 pr-4 py-1.5 border border-slate-200 rounded text-sm focus:ring-2 focus:ring-blue-500 bg-slate-50 w-44"
                                   placeholder="Cari ID sesi / booking..."/>
                        </form>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-slate-600 uppercase text-[11px] font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-3">ID Sesi</th>
                                <th class="px-6 py-3">Kode Booking</th>
                                <th class="px-6 py-3">Frame / Filter</th>
                                <th class="px-6 py-3">Penerima Email</th>
                                <th class="px-6 py-3">Waktu</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if (!empty($sessions)): ?>
                                <?php foreach ($sessions as $session): ?>
                                    <tr class="hover:bg-blue-50/50 transition-colors group">
                                        <td class="px-6 py-4 font-data-tabular text-data-tabular text-blue-700 font-bold">
                                            #<?= esc($session['id']) ?>
                                        </td>
                                        <td class="px-6 py-4 font-data-tabular text-data-tabular text-slate-900">
                                            <?= esc($session['booking_id'] ?? '-') ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-body-sm font-medium"><?= esc($session['frame_name'] ?? 'N/A') ?></span>
                                                <span class="text-[10px] text-slate-500 uppercase"><?= esc($session['filter_name'] ?? 'N/A') ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-body-sm text-slate-600">
                                            <?= esc($session['email_recipient'] ?? '-') ?>
                                        </td>
                                        <td class="px-6 py-4 text-body-sm text-slate-500">
                                            <?= date('H:i', strtotime($session['created_at'])) ?> WIB
                                        </td>
                                        <td class="px-6 py-4 text-right flex justify-end items-center gap-2">
                                            <a href="<?= base_url('admin/sessions/' . $session['id']) ?>"
                                               class="text-slate-400 hover:text-blue-600 p-1" title="Lihat Detail">
                                                <span class="material-symbols-outlined text-xl">open_in_new</span>
                                            </a>
                                            <?php if (!empty($session['email_recipient'])): ?>
                                                <form action="<?= base_url('admin/sessions/' . $session['id'] . '/resend') ?>" method="post" class="inline">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="text-slate-400 hover:text-green-600 p-1" title="Kirim Ulang Email">
                                                        <span class="material-symbols-outlined text-xl">send</span>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            <form action="<?= base_url('admin/sessions/' . $session['id'] . '/delete') ?>" method="post" class="inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="text-slate-400 hover:text-red-600 p-1" title="Hapus Sesi"
                                                        onclick="return confirm('Hapus sesi ini beserta seluruh fotonya?')">
                                                    <span class="material-symbols-outlined text-xl">delete</span>
                                                </button>
                                            </form>
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
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-500">
                        Menampilkan <?= count($sessions ?? []) ?> dari <?= $totalSessions ?? 0 ?> sesi
                    </span>
                    <div class="flex gap-1">
                        <a href="<?= base_url('admin/sessions?page=' . max(1, ($currentPage ?? 1) - 1) . '&q=' . ($keyword ?? '')) ?>"
                           class="p-1.5 border border-slate-200 rounded hover:bg-slate-50 <?= ($currentPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                            <span class="material-symbols-outlined text-sm">chevron_left</span>
                        </a>
                        <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                            <a href="<?= base_url('admin/sessions?page=' . $i . '&q=' . ($keyword ?? '')) ?>"
                               class="px-3 py-1 border rounded text-xs font-bold <?= $i === ($currentPage ?? 1) ? 'bg-primary text-white border-primary' : 'border-slate-200 hover:bg-white' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                        <a href="<?= base_url('admin/sessions?page=' . min(($totalPages ?? 1), ($currentPage ?? 1) + 1) . '&q=' . ($keyword ?? '')) ?>"
                           class="p-1.5 border border-slate-200 rounded hover:bg-slate-50 <?= ($currentPage ?? 1) >= ($totalPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                            <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- ============================================================
                 Panel Kanan: Penyimpanan + Aksi Cepat (4 kolom)
            ============================================================ -->
            <div class="col-span-12 lg:col-span-4 flex flex-col gap-6">

                <!-- Storage Card -->
                <div class="bg-white border border-slate-200 rounded shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-h2 text-h2 text-on-surface">Penyimpanan</h3>
                        <span class="material-symbols-outlined text-blue-600">cloud_done</span>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-body-sm">
                            <span class="text-slate-500">Total Foto Tersimpan</span>
                            <span class="font-bold text-slate-900"><?= number_format($statTotalPhotos ?? 0) ?></span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-blue-600 h-full" style="width: <?= min(100, ($statTotalPhotos ?? 0) > 0 ? min(100, round(($statTotalPhotos / 1000) * 100)) : 0) ?>%"></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 bg-slate-50 rounded border border-slate-200 text-center">
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Sesi Hari Ini</p>
                            <p class="text-lg font-bold text-blue-700"><?= $statToday ?? 0 ?></p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded border border-slate-200 text-center">
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Bulan Ini</p>
                            <p class="text-lg font-bold text-blue-700"><?= $statThisMonth ?? 0 ?></p>
                        </div>
                    </div>
                    <?php if (!empty($latestSession)): ?>
                        <div class="p-3 bg-slate-50 rounded border border-slate-200">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Sesi Terbaru</label>
                            <div class="flex items-center justify-between gap-2 overflow-hidden">
                                <p class="text-xs truncate text-blue-700 font-medium">
                                    #<?= esc($latestSession['id']) ?> — <?= esc($latestSession['email_recipient'] ?? 'No email') ?>
                                </p>
                                <?php if (!empty($latestSession['drive_folder_url'])): ?>
                                    <a href="<?= esc($latestSession['drive_folder_url']) ?>" target="_blank"
                                       class="shrink-0 text-slate-400 hover:text-blue-600" title="Buka Drive">
                                        <span class="material-symbols-outlined text-sm">open_in_new</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Quick Action Card -->
                <div class="bg-primary-container text-on-primary-container rounded shadow-sm p-6 relative overflow-hidden">
                    <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-9xl text-on-primary-container opacity-10">mail</span>
                    <h4 class="font-label-bold text-label-bold text-blue-100 uppercase mb-2">Aksi Cepat</h4>
                    <p class="text-white font-h2 text-h2 mb-4 leading-tight">Gagal Mengirim Email?</p>
                    <a href="<?= base_url('admin/sessions/resend-all') ?>"
                       class="w-full bg-white text-blue-800 font-bold py-2.5 rounded hover:bg-blue-50 transition-colors flex items-center justify-center gap-2 text-sm shadow-lg">
                        <span class="material-symbols-outlined">send</span>
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
    </main>

    <?= $this->include('Admin/layouts/footer') ?>
</div>

<?php $this->endSection(); ?>
