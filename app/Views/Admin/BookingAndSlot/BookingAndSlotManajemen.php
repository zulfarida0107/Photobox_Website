<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<main class="pt-16 min-h-screen flex flex-col bg-slate-50/50">
    <div class="p-6 flex-1">

        <!-- Breadcrumbs -->
        <nav aria-label="Breadcrumb" class="flex mb-6 text-xs font-medium text-slate-500">
            <ol class="flex items-center space-x-2">
                <li>
                    <a class="hover:text-blue-600 flex items-center gap-1 transition-colors" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
                </li>
                <li class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[10px]">chevron_right</span>
                    <span class="text-blue-700 font-bold">Manajemen Reservasi</span>
                </li>
            </ol>
        </nav>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-green-500">check_circle</span>
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-red-500">error</span>
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <!-- Header Actions -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="font-h1 text-h1 text-on-surface">Manajemen Reservasi</h2>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Atur jadwal operasional booth dan reservasi pelanggan.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <form method="get" action="<?= base_url('admin/bookings') ?>" class="inline-block">
                    <div class="flex items-center gap-2 bg-white border border-slate-200 px-3 py-2 rounded-lg text-xs shadow-sm">
                        <span class="material-symbols-outlined text-slate-400 text-sm">calendar_month</span>
                        <span class="text-slate-600 font-medium">Periode:</span>
                        <select name="period" class="border-none bg-transparent py-0 pl-1 pr-7 focus:ring-0 text-xs font-bold text-primary cursor-pointer outline-none min-w-[130px]" onchange="this.form.submit()">
                            <option value="all"   <?= ($period ?? 'all') === 'all'   ? 'selected' : '' ?>>Semua Waktu</option>
                            <option value="today" <?= ($period ?? '')    === 'today' ? 'selected' : '' ?>>Hari Ini</option>
                            <option value="week"  <?= ($period ?? '')    === 'week'  ? 'selected' : '' ?>>Minggu Ini</option>
                            <option value="month" <?= ($period ?? '')    === 'month' ? 'selected' : '' ?>>Bulan Ini</option>
                        </select>
                    </div>
                </form>

                <a href="<?= base_url('admin/bookings/export?period=' . esc($period ?? 'all') . '&q=' . urlencode($_GET['q'] ?? '')) ?>"
                   onclick="showExportNotif()"
                   class="flex items-center gap-2 px-4 py-2 bg-white border border-blue-600 text-blue-600 rounded-lg font-bold text-xs hover:bg-blue-50 transition-colors shadow-sm whitespace-nowrap">
                    <span class="material-symbols-outlined text-[18px]">cloud_download</span>
                    Ekspor Laporan
                </a>
            </div>
        </div>

        <!-- Bento Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Reservasi -->
            <div class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Reservasi</span>
                    <span class="p-2 bg-blue-50 text-blue-600 rounded-lg material-symbols-outlined text-lg">event_note</span>
                </div>
                <div class="text-2xl font-black text-slate-900 mt-1"><?= number_format($statTotalReservasi ?? 0, 0, ',', '.') ?></div>
                <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-2">
                    <span class="text-[10px] text-slate-400">Periode Terpilih</span>
                    <span class="text-[10px] text-blue-600 font-bold uppercase"><?= esc($period ?? 'all') ?></span>
                </div>
            </div>

            <!-- Tingkat Okupansi -->
            <div class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tingkat Okupansi</span>
                    <span class="p-2 bg-green-50 text-green-600 rounded-lg material-symbols-outlined text-lg">percent</span>
                </div>
                <div class="text-2xl font-black text-slate-900 mt-1"><?= esc($statOkupansi ?? '0%') ?></div>
                <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-2">
                    <span class="text-[10px] text-slate-400">Slot Terisi vs Kapasitas</span>
                    <span class="text-[10px] text-green-600 font-bold">Terjadwal</span>
                </div>
            </div>

            <!-- Batal/Reschedule -->
            <div class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Batal / Cancelled</span>
                    <span class="p-2 bg-red-50 text-red-600 rounded-lg material-symbols-outlined text-lg">cancel</span>
                </div>
                <div class="text-2xl font-black text-slate-900 mt-1"><?= number_format($statCancelled ?? 0, 0, ',', '.') ?></div>
                <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-2">
                    <span class="text-[10px] text-slate-400">Total Dibayangkan</span>
                    <span class="text-[10px] text-red-600 font-bold">Status Batal</span>
                </div>
            </div>

            <!-- Booth Teraktif -->
            <div class="bg-gradient-to-br from-primary to-blue-800 p-5 border border-primary rounded-xl shadow-sm flex flex-col justify-between text-white">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-bold text-blue-200 uppercase tracking-wider">Booth Teraktif</span>
                    <span class="p-1.5 bg-blue-700/50 text-blue-200 rounded-lg material-symbols-outlined text-lg">storefront</span>
                </div>
                <div class="text-lg font-black text-white truncate mt-1"><?= esc($statTopBooth ?? 'N/A') ?></div>
                <div class="mt-3">
                    <div class="flex justify-between text-[10px] text-blue-200 mb-1">
                        <span>Aktivitas Reservasi</span>
                        <span class="font-bold"><?= $statTopBoothPct ?? 0 ?>%</span>
                    </div>
                    <div class="w-full bg-blue-950/50 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-blue-300 h-1.5 rounded-full transition-all duration-500" style="width: <?= min(100, max(5, $statTopBoothPct ?? 0)) ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- KIRI: Tabel Slot Operasional (5 Cols) -->
            <section class="lg:col-span-5 flex flex-col h-full">
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm flex-1 flex flex-col">
                    <!-- Table Header -->
                    <div class="p-4 border-b border-slate-200 flex items-center justify-between bg-white">
                        <div class="flex items-center gap-2">
                            <div class="w-1 h-5 bg-primary rounded-full"></div>
                            <h4 class="font-bold text-slate-800 text-sm">Slot Operasional</h4>
                            <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 text-[11px] font-bold border border-blue-100">
                                <?= $totalAvailableSlots ?? 0 ?> Tersedia
                            </span>
                        </div>
                        <a href="<?= base_url('admin/slots/create') ?>"
                           class="bg-primary text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 hover:bg-primary-container transition-colors shadow-sm whitespace-nowrap">
                            <span class="material-symbols-outlined text-[16px]">event_available</span>
                            Tambah Slot
                        </a>
                    </div>

                    <!-- Search Slot -->
                    <div class="p-3 bg-slate-50/50 border-b border-slate-200">
                        <form method="get" action="<?= base_url('admin/bookings') ?>" class="flex gap-2">
                            <input type="hidden" name="period" value="<?= esc($_GET['period'] ?? 'all') ?>" />
                            <div class="relative w-full">
                                <span class="material-symbols-outlined absolute left-2.5 top-2 text-slate-400 text-[16px]">search</span>
                                <input type="text" name="slot_q" value="<?= esc($_GET['slot_q'] ?? '') ?>" placeholder="Cari Slot ID / Booth..."
                                       class="w-full pl-8 pr-3 py-1.5 border border-slate-200 rounded-lg text-xs outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white transition-all"/>
                            </div>
                        </form>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto flex-1 max-h-[500px]">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 text-[11px] font-bold uppercase tracking-wider">
                                <tr>
                                    <th class="px-3.5 py-2.5">SESI</th>
                                    <th class="px-3.5 py-2.5 text-center">WAKTU</th>
                                    <th class="px-3.5 py-2.5 text-center">TERISI</th>
                                    <th class="px-3.5 py-2.5 text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                <?php if (!empty($slots)): ?>
                                    <?php foreach ($slots as $slot): ?>
                                        <?php
                                            $isFull   = $slot['booked_count'] >= $slot['max_bookings'];
                                            $isClosed = isset($slot['status']) && $slot['status'] === 'closed';
                                            if ($isClosed) {
                                                $sc = 'bg-red-100 text-red-700 border-red-200'; $sl = 'Tutup';
                                            } elseif ($isFull) {
                                                $sc = 'bg-slate-100 text-slate-600 border-slate-200'; $sl = 'Penuh';
                                            } else {
                                                $sc = 'bg-emerald-50 text-emerald-700 border-emerald-200'; $sl = 'Tersedia';
                                            }
                                            $startHour = (int) date('H', strtotime($slot['start_time']));
                                            $startFmt  = date('H:i', strtotime($slot['start_time']));
                                            // Durasi: PAGI=2jam, SIANG=2jam, SORE=2jam, MALAM=3jam
                                            if ($startHour < 10) { $sesLabel = 'Pagi'; $dur = 7200; }
                                            elseif ($startHour < 13) { $sesLabel = 'Pagi'; $dur = 7200; }
                                            elseif ($startHour < 16) { $sesLabel = 'Siang'; $dur = 7200; }
                                            elseif ($startHour < 19) { $sesLabel = 'Sore'; $dur = 7200; }
                                            else { $sesLabel = 'Malam'; $dur = 10800; }
                                            $endFmt = date('H:i', strtotime($slot['start_time']) + $dur);
                                            $sesColors = [
                                                'Pagi'  => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'Siang' => 'bg-orange-50 text-orange-700 border-orange-200',
                                                'Sore'  => 'bg-purple-50 text-purple-700 border-purple-200',
                                                'Malam' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                            ];
                                        ?>
                                        <tr class="hover:bg-blue-50/50 transition-colors">
                                            <td class="px-3.5 py-2.5">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold border <?= $sesColors[$sesLabel] ?>">
                                                    <span class="material-symbols-outlined text-[12px]">schedule</span>
                                                    Sesi <?= $sesLabel ?>
                                                </span>
                                            </td>
                                            <td class="px-3.5 py-2.5 text-center">
                                                <div class="font-semibold text-slate-800 font-mono text-[11px]"><?= $startFmt ?> - <?= $endFmt ?></div>
                                            </td>
                                            <td class="px-3.5 py-2.5 text-center">
                                                <div class="font-bold text-slate-800 text-[11px] mb-0.5"><?= $slot['booked_count'] ?> / <?= $slot['max_bookings'] ?></div>
                                                <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-bold border <?= $sc ?>"><?= $sl ?></span>
                                            </td>
                                            <td class="px-3.5 py-2.5 text-center">
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <a href="<?= base_url('admin/slots/' . esc($slot['id']) . '/edit') ?>"
                                                       class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-blue-50 text-blue-600 border border-blue-200 text-[10px] font-semibold hover:bg-blue-100 transition-colors">
                                                        <span class="material-symbols-outlined text-[13px]">edit</span>
                                                        Edit
                                                    </a>
                                                    <form method="post" action="<?= base_url('admin/slots/' . esc($slot['id']) . '/delete') ?>"
                                                          onsubmit="return confirm('Hapus sesi <?= esc($sesLabel . ' ' . $startFmt) ?>? Tindakan ini tidak dapat dibatalkan.')">
                                                        <?= csrf_field() ?>
                                                        <button type="submit"
                                                                class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-red-50 text-red-600 border border-red-200 text-[10px] font-semibold hover:bg-red-100 transition-colors">
                                                            <span class="material-symbols-outlined text-[13px]">delete</span>
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-xs text-slate-400">Belum ada slot operasional. <a href="<?= base_url('admin/slots/create') ?>" class="text-primary font-semibold hover:underline">Tambah sekarang</a></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer Link -->
                    <div class="p-3 bg-slate-50 border-t border-slate-200 text-center">
                        <a href="<?= base_url('admin/slots') ?>" class="text-xs font-bold text-primary hover:underline flex items-center justify-center gap-1">
                            <span class="material-symbols-outlined text-sm">calendar_month</span>
                            Kelola Semua Kalender Slot
                        </a>
                    </div>
                </div>
            </section>

            <!-- KANAN: Tabel Booking Masuk (7 Cols) -->
            <section class="lg:col-span-7 flex flex-col h-full">
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm flex-1 flex flex-col">
                    <!-- Table Header -->
                    <div class="p-4 border-b border-slate-200 flex items-center justify-between bg-white">
                        <div class="flex items-center gap-2">
                            <div class="w-1 h-5 bg-primary rounded-full"></div>
                            <h4 class="font-bold text-slate-800 text-sm">Daftar Booking Masuk</h4>
                            <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[11px] font-bold">
                                Total: <?= $totalBookings ?? 0 ?>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Search & Filter Bar -->
                    <div class="p-3 bg-slate-50/50 border-b border-slate-200">
                        <form method="get" action="<?= base_url('admin/bookings') ?>" class="flex flex-col sm:flex-row gap-2.5 items-center justify-between w-full">
                            <input type="hidden" name="period" value="<?= esc($_GET['period'] ?? 'all') ?>" />
                            <!-- Search Input -->
                            <div class="relative w-full sm:flex-1">
                                <span class="material-symbols-outlined absolute left-3 top-2 text-slate-400 text-[18px]">search</span>
                                <input type="text" name="q" value="<?= esc($_GET['q'] ?? '') ?>" placeholder="Cari Kode Booking / Nama Pelanggan / Paket..."
                                       class="w-full pl-9 pr-3 py-1.5 border border-slate-200 rounded-lg text-xs outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white transition-all"/>
                            </div>
                            
                            <!-- Status Filter -->
                            <div class="relative w-full sm:w-48 flex items-center gap-1.5 bg-white border border-slate-200 px-2.5 py-1.5 rounded-lg text-xs shadow-sm">
                                <span class="material-symbols-outlined text-slate-400 text-sm">filter_list</span>
                                <select name="status" onchange="this.form.submit()"
                                        class="border-none bg-transparent p-0 focus:ring-0 text-xs font-semibold text-slate-700 outline-none flex-1 cursor-pointer">
                                    <option value="" <?= ($_GET['status'] ?? '') === '' ? 'selected' : '' ?>>Semua Status</option>
                                    <option value="confirmed" <?= ($_GET['status'] ?? '') === 'confirmed' ? 'selected' : '' ?>>TERKONFIRMASI</option>
                                    <option value="pending"   <?= ($_GET['status'] ?? '') === 'pending'   ? 'selected' : '' ?>>MENUNGGU</option>
                                    <option value="completed" <?= ($_GET['status'] ?? '') === 'completed' ? 'selected' : '' ?>>SELESAI</option>
                                    <option value="cancelled" <?= ($_GET['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>DIBATALKAN</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 text-[11px] font-bold uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-3">KODE &amp; PELANGGAN</th>
                                    <th class="px-4 py-3">SLOT / PAKET</th>
                                    <th class="px-4 py-3 text-center">ANTREAN</th>
                                    <th class="px-4 py-3 text-center">STATUS</th>
                                    <th class="px-4 py-3 text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                <?php if (!empty($bookings)): ?>
                                    <?php foreach ($bookings as $bk): ?>
                                        <?php
                                            $statusMap = [
                                                'confirmed' => ['bg-blue-50 text-blue-700 border-blue-200',    'TERKONFIRMASI'],
                                                'pending'   => ['bg-amber-50 text-amber-700 border-amber-200', 'MENUNGGU'],
                                                'completed' => ['bg-emerald-50 text-emerald-700 border-emerald-200', 'SELESAI'],
                                                'cancelled' => ['bg-red-50 text-red-700 border-red-200',        'DIBATALKAN'],
                                            ];
                                            $st = $statusMap[$bk['status']] ?? ['bg-slate-50 text-slate-600 border-slate-200', strtoupper($bk['status'])];
                                        ?>
                                        <tr class="hover:bg-blue-50/50 transition-colors">
                                            <td class="px-4 py-3">
                                                <div class="font-bold text-primary hover:underline cursor-pointer flex items-center gap-1"
                                                     onclick="showBookingPreviewModal('<?= esc($bk['id'], 'js') ?>', '<?= esc($bk['booking_code'], 'js') ?>', '<?= esc($bk['customer_name'] ?? '-', 'js') ?>', 'Slot #<?= esc($bk['slot_id'], 'js') ?> (<?= esc($bk['booth_name'] ?? 'Booth', 'js') ?>)', '<?= esc(!empty($bk['print_copies']) ? $bk['print_copies'] . ' Cetak' : '-', 'js') ?>', '<?= esc($bk['queue_number'], 'js') ?>', '<?= $st[1] ?>', '<?= $st[0] ?>', '<?= $bk['status'] ?>', <?= $bk['edit_count'] ?? 0 ?>)">
                                                    <span><?= esc($bk['booking_code']) ?></span>
                                                    <span class="material-symbols-outlined text-[14px] text-blue-400">info</span>
                                                </div>
                                                <div class="text-slate-700 font-medium mt-0.5"><?= esc($bk['customer_name'] ?? '-') ?></div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="font-semibold text-slate-800">Slot #<?= esc($bk['slot_id']) ?></div>
                                                <div class="text-[11px] text-slate-500 font-medium"><?= esc($bk['booth_name'] ?? 'Booth') ?> &bull; <?= esc(!empty($bk['print_copies']) ? $bk['print_copies'] . ' Cetak' : '-') ?></div>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="text-base font-black text-slate-700 bg-slate-100 px-2 py-0.5 rounded-lg border border-slate-200 font-mono">
                                                    <?= esc($bk['queue_number']) ?>
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase border <?= $st[0] ?>">
                                                    <?= $st[1] ?>
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="flex justify-center items-center gap-1">
                                                    <?php if (in_array($bk['status'], ['confirmed', 'pending'])): ?>
                                                        <a href="<?= base_url('admin/bookings/' . $bk['id'] . '/edit') ?>"
                                                           class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="Edit Booking">
                                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                                        </a>
                                                        <button type="button" onclick="showCancelBookingModal('<?= base_url('admin/bookings/' . $bk['id'] . '/cancel') ?>')"
                                                                class="p-1.5 text-red-500 hover:bg-red-100 rounded-lg transition-colors" title="Batalkan Booking">
                                                            <span class="material-symbols-outlined text-[18px]">cancel</span>
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="button" onclick="showBookingPreviewModal('<?= esc($bk['id'], 'js') ?>', '<?= esc($bk['booking_code'], 'js') ?>', '<?= esc($bk['customer_name'] ?? '-', 'js') ?>', 'Slot #<?= esc($bk['slot_id'], 'js') ?> (<?= esc($bk['booth_name'] ?? 'Booth', 'js') ?>)', '<?= esc(!empty($bk['print_copies']) ? $bk['print_copies'] . ' Cetak' : '-', 'js') ?>', '<?= esc($bk['queue_number'], 'js') ?>', '<?= $st[1] ?>', '<?= $st[0] ?>', '<?= $bk['status'] ?>', <?= $bk['edit_count'] ?? 0 ?>)"
                                                                class="p-1.5 text-slate-500 hover:bg-slate-100 rounded-lg transition-colors" title="Detail & Riwayat">
                                                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-4 py-10 text-center text-xs text-slate-400">
                                            <span class="material-symbols-outlined text-3xl mb-1 text-slate-300 block">event_busy</span>
                                            Belum ada booking untuk periode ini.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="p-4 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <span class="text-xs text-slate-500 font-medium">
                            Menampilkan <?= count($bookings ?? []) ?> dari <?= $totalBookings ?? 0 ?> booking
                        </span>
                        <div class="flex items-center gap-1">
                            <a href="<?= base_url('admin/bookings?page=' . max(1, ($currentPage ?? 1) - 1) . '&period=' . esc($period ?? 'all') . '&q=' . urlencode($_GET['q'] ?? '') . '&status=' . urlencode($_GET['status'] ?? '')) ?>"
                               class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-500 <?= ($currentPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                                <span class="material-symbols-outlined text-sm">chevron_left</span>
                            </a>
                            <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                                <a href="<?= base_url('admin/bookings?page=' . $i . '&period=' . esc($period ?? 'all') . '&q=' . urlencode($_GET['q'] ?? '') . '&status=' . urlencode($_GET['status'] ?? '')) ?>"
                                   class="w-8 h-8 flex items-center justify-center border rounded-lg text-xs font-bold transition-colors <?= $i === ($currentPage ?? 1) ? 'border-primary bg-primary text-white shadow-sm' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                            <a href="<?= base_url('admin/bookings?page=' . min(($totalPages ?? 1), ($currentPage ?? 1) + 1) . '&period=' . esc($period ?? 'all') . '&q=' . urlencode($_GET['q'] ?? '') . '&status=' . urlencode($_GET['status'] ?? '')) ?>"
                               class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-500 <?= ($currentPage ?? 1) >= ($totalPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                                <span class="material-symbols-outlined text-sm">chevron_right</span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>

<!-- Booking Preview Modal -->
<div id="bookingPreviewModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="bookingPreviewModalContent">
        <!-- Modal Header -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h4 class="font-bold text-slate-800 flex items-center gap-2 text-sm">
                <span class="material-symbols-outlined text-emerald-600">visibility</span>
                Detail &amp; Riwayat Reservasi
            </h4>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 flex flex-col items-center">
            <!-- Icon/Avatar Wrapper -->
            <div class="w-20 h-20 bg-blue-50 text-blue-600 border border-slate-200 rounded-full overflow-hidden flex items-center justify-center p-4 mb-4 shadow-inner">
                <span class="material-symbols-outlined text-[40px]">event_available</span>
            </div>

            <!-- Details List -->
            <div class="w-full space-y-2.5 bg-slate-50 p-4 rounded-xl border border-slate-200 text-left text-xs mb-5">
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">Kode Booking</span>
                    <span class="font-mono text-primary font-bold" id="modalBkCode"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">Nama Pelanggan</span>
                    <span class="text-slate-800 font-semibold" id="modalBkCust"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">Slot &amp; Booth</span>
                    <span class="text-slate-800 font-semibold" id="modalBkSlot"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">Paket Terpilih</span>
                    <span class="text-slate-800 font-semibold" id="modalBkPackage"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="font-semibold text-slate-500">Nomor Antrean</span>
                    <span class="text-slate-800 font-black" id="modalBkQueue"></span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold text-slate-500">Status</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold" id="modalBkStatus"></span>
                </div>
            </div>
            
            <!-- Warning/Note Text -->
            <div id="modalBkEditWarning" class="flex items-center gap-1.5 text-xs text-primary font-bold mb-3 text-center justify-center hidden">
                <span class="material-symbols-outlined text-sm">info</span>
                <span>Perubahan booking memerlukan konfirmasi pelanggan.</span>
            </div>

            <!-- Edit Booking Button -->
            <a id="modalBkEditBtn" href="#" class="w-full py-2.5 bg-primary hover:bg-primary-container text-white font-bold text-center text-xs rounded-lg transition-colors shadow-md mb-2 flex items-center justify-center gap-1.5 hidden">
                <span class="material-symbols-outlined text-[18px]">edit</span>
                Ubah Booking
            </a>

            <!-- Close Button -->
            <button type="button" onclick="closeBookingPreviewModal()" class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-lg transition-colors shadow-sm">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Cancel Booking Confirmation Modal -->
<div id="cancelBookingModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="cancelBookingModalContent">
        <!-- Modal Body -->
        <div class="p-6 flex flex-col items-center text-center">
            <!-- Warning Alert Icon -->
            <div class="w-14 h-14 bg-red-50 text-red-500 rounded-full flex items-center justify-center p-3 mb-3">
                <span class="material-symbols-outlined text-[28px]">warning</span>
            </div>

            <h3 class="text-base font-bold text-slate-800 mb-1">Konfirmasi Pembatalan</h3>
            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                Apakah Anda yakin ingin membatalkan booking ini?<br>Tindakan ini tidak dapat dibatalkan.
            </p>
            
            <!-- Info Banner inside Modal -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 text-left mb-5 w-full flex items-start gap-2">
                <span class="material-symbols-outlined text-blue-500 text-sm mt-0.5">info</span>
                <div>
                    <h5 class="text-xs font-bold text-blue-900">Informasi Penting</h5>
                    <p class="text-[10px] text-blue-700 mt-0.5 leading-normal">
                        Membatalkan booking akan melepaskan antrean pelanggan dan memperbarui jumlah slot yang terisi.
                    </p>
                </div>
            </div>

            <!-- Form & Actions -->
            <form id="cancelBookingForm" method="POST" class="w-full flex gap-3">
                <?= csrf_field() ?>
                <button type="button" onclick="closeCancelBookingModal()" class="flex-1 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-lg transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold text-xs rounded-lg transition-colors shadow-md">
                    Batalkan Booking
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function showBookingPreviewModal(id, code, cust, slot, pkg, queue, badgeText, badgeClass, rawStatus, editCount) {
        const modal = document.getElementById('bookingPreviewModal');
        const content = document.getElementById('bookingPreviewModalContent');
        
        document.getElementById('modalBkCode').textContent = code;
        document.getElementById('modalBkCust').textContent = cust;
        document.getElementById('modalBkSlot').textContent = slot;
        document.getElementById('modalBkPackage').textContent = pkg;
        document.getElementById('modalBkQueue').textContent = queue;
        
        const statusEl = document.getElementById('modalBkStatus');
        statusEl.textContent = badgeText;
        statusEl.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase ' + badgeClass;

        const editBtn = document.getElementById('modalBkEditBtn');
        const warningEl = document.getElementById('modalBkEditWarning');

        // Allow Ubah Booking only once
        if (parseInt(editCount || 0) >= 1) {
            editBtn.classList.add('hidden');
            if (warningEl) warningEl.classList.add('hidden');
        } else {
            editBtn.href = '<?= base_url('admin/bookings/') ?>/' + id + '/edit';
            editBtn.classList.remove('hidden');
            if (warningEl) warningEl.classList.remove('hidden');
        }

        // Show Modal
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeBookingPreviewModal() {
        const modal = document.getElementById('bookingPreviewModal');
        const content = document.getElementById('bookingPreviewModalContent');

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function showCancelBookingModal(actionUrl) {
        const modal = document.getElementById('cancelBookingModal');
        const content = document.getElementById('cancelBookingModalContent');
        const form = document.getElementById('cancelBookingForm');
        
        form.action = actionUrl;

        // Show Modal
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeCancelBookingModal() {
        const modal = document.getElementById('cancelBookingModal');
        const content = document.getElementById('cancelBookingModalContent');

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>

<?php $this->endSection(); ?>
