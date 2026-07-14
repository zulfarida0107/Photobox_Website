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
                    <span class="text-blue-700 font-bold">Manajemen Reservasi</span>
                </li>
            </ol>
        </nav>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm font-medium flex items-center gap-2">
                <span class="material-symbols-outlined text-green-500">check_circle</span>
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm font-medium flex items-center gap-2">
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
<<<<<<< Updated upstream
        </div>

        <!-- Bento Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <!-- Total Reservasi -->
            <div class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Reservasi</span>
                    <div class="text-2xl font-black text-slate-900 mt-1"><?= $statTotalReservasi ?? 0 ?></div>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <span class="text-[10px] text-green-600 font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">trending_up</span> +12%
                    </span>
                    <svg class="w-16 h-8 stroke-green-500 fill-none stroke-2" viewBox="0 0 100 40">
                        <path d="M0,35 Q20,30 40,20 T80,15 T100,5"></path>
                    </svg>
                </div>
            </div>
            <!-- Tingkat Okupansi -->
            <div class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tingkat Okupansi</span>
                    <div class="text-2xl font-black text-slate-900 mt-1"><?= $statOkupansi ?? '0%' ?></div>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <span class="text-[10px] text-green-600 font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">trending_up</span> +3%
                    </span>
                    <svg class="w-16 h-8 stroke-blue-500 fill-none stroke-2" viewBox="0 0 100 40">
                        <path d="M0,30 Q30,35 50,15 T100,10"></path>
                    </svg>
                </div>
            </div>
            <!-- Batal/Reschedule -->
            <div class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Batal/Reschedule</span>
                    <div class="text-2xl font-black text-slate-900 mt-1"><?= $statCancelled ?? 0 ?></div>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <span class="text-[10px] text-red-600 font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">trending_down</span> -2%
                    </span>
                    <svg class="w-16 h-8 stroke-red-500 fill-none stroke-2" viewBox="0 0 100 40">
                        <path d="M0,10 Q20,15 40,30 T80,35 T100,38"></path>
                    </svg>
                </div>
            </div>
            <!-- Booth Teraktif -->
            <div class="bg-primary p-5 border border-primary rounded-xl shadow-sm flex flex-col justify-between text-white">
                <div>
                    <span class="text-[11px] font-bold text-blue-200 uppercase tracking-wider">Booth Teraktif</span>
                    <div class="text-lg font-black mt-1"><?= esc($statTopBooth ?? 'N/A') ?></div>
                </div>
                <div class="mt-4">
                    <div class="text-[10px] text-blue-100 mb-1">Target Harian: 90%</div>
                    <div class="w-full bg-blue-900/40 rounded-full h-1.5">
                        <div class="bg-blue-300 h-1.5 rounded-full" style="width: <?= $statTopBoothPct ?? 0 ?>%"></div>
                    </div>
                </div>
=======
            <div class="flex items-center gap-3">
                <a href="<?= base_url('admin/slots/create') ?>"
                   class="flex items-center gap-2 px-4 py-2.5 bg-primary text-on-primary rounded-lg font-label-bold text-label-bold hover:bg-primary-container transition-colors">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    Buat Slot Baru
                </a>
                <form method="get" action="<?= base_url('admin/bookings') ?>" class="inline-block">
                    <div class="flex items-center gap-2 bg-white border border-slate-200 px-3 py-2 rounded-lg text-sm shadow-sm">
                        <span class="material-symbols-outlined text-slate-400 text-sm">calendar_month</span>
                        <span class="text-slate-600 font-medium">Periode:</span>
                        <select name="period" class="border-none bg-transparent p-0 focus:ring-0 text-sm font-bold text-primary" onchange="this.form.submit()">
                            <option value="all"   <?= ($period ?? 'all') === 'all'   ? 'selected' : '' ?>>Semua Waktu</option>
                            <option value="today" <?= ($period ?? '')    === 'today' ? 'selected' : '' ?>>Hari Ini</option>
                            <option value="week"  <?= ($period ?? '')    === 'week'  ? 'selected' : '' ?>>Minggu Ini</option>
                            <option value="month" <?= ($period ?? '')    === 'month' ? 'selected' : '' ?>>Bulan Ini</option>
                        </select>
                    </div>
                </form>
>>>>>>> Stashed changes
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

            <!-- KIRI: Tabel Slot Operasional -->
            <section class="lg:col-span-4 flex flex-col h-full">
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm flex-1 flex flex-col h-full">
                    <!-- Table Header -->
                    <div class="p-4 border-b border-slate-200 flex items-center justify-between bg-white">
                        <div class="flex items-center gap-2">
                            <div class="w-1 h-5 bg-primary rounded-full"></div>
                            <h4 class="font-h2 text-h2 text-on-surface text-base">Daftar Slot Operasional</h4>
                            <span class="px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[11px] font-bold whitespace-nowrap">
                                <?= $totalAvailableSlots ?? 0 ?> Slot Tersedia
                            </span>
                        </div>
                        <a href="<?= base_url('admin/slots/create') ?>"
                           class="bg-primary text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 hover:bg-primary-container transition-colors shadow-sm whitespace-nowrap">
                            <span class="material-symbols-outlined text-[16px]">add</span>
                            Tambah Slot Baru
                        </a>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-center">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 uppercase">Slot &amp; Booth</th>
                                    <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 uppercase">Waktu</th>
                                    <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if (!empty($slots)): ?>
                                    <?php foreach ($slots as $slot): ?>
                                        <?php
                                            $isFull = $slot['booked_count'] >= $slot['max_bookings'];
                                            $isClosed = isset($slot['status']) && $slot['status'] === 'closed';
                                            if ($isClosed) {
                                                $sc = 'bg-red-100 text-red-700'; $sl = 'Tutup';
                                            } elseif ($isFull) {
                                                $sc = 'bg-slate-100 text-slate-600'; $sl = 'Penuh';
                                            } else {
                                                $sc = 'bg-green-100 text-green-700'; $sl = 'Aktif';
                                            }
                                            $endTime = date('H:i', strtotime($slot['start_time']) + 3600);
                                        ?>
                                        <tr class="hover:bg-blue-50 transition-colors">
                                            <td class="px-4 py-3 text-center">
                                                <div class="text-sm font-semibold text-slate-900">#<?= esc($slot['id']) ?></div>
                                                <div class="text-xs text-slate-500"><?= esc($slot['booth_name'] ?? 'Booth') ?></div>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="text-xs font-medium"><?= date('d M Y', strtotime($slot['slot_date'])) ?></div>
                                                <div class="text-xs text-slate-500"><?= date('H:i', strtotime($slot['start_time'])) ?> - <?= $endTime ?></div>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase <?= $sc ?>">
                                                    <?= $sl ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="px-4 py-8 text-center text-sm text-slate-400">Belum ada slot hari ini.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-center">
                        <a href="<?= base_url('admin/slots') ?>" class="text-xs font-semibold text-blue-600 hover:underline">Lihat Kalender Slot</a>
                    </div>
                </div>
            </section>

            <!-- KANAN: Tabel Booking Masuk -->
            <section class="lg:col-span-8 flex flex-col h-full">
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm flex-1 flex flex-col h-full">
                    <!-- Table Header -->
                    <div class="p-4 border-b border-slate-200 flex items-center justify-between bg-white">
                        <div class="flex items-center gap-2">
                            <div class="w-1 h-5 bg-primary rounded-full"></div>
                            <h4 class="font-h2 text-h2 text-on-surface text-base">Daftar Booking Masuk</h4>
                        </div>
                    </div>
                    
                    <!-- Search & Filter Bar -->
                    <div class="p-3 bg-slate-50/50 border-b border-slate-200">
                        <form method="get" action="<?= base_url('admin/bookings') ?>" class="flex flex-col sm:flex-row gap-3 items-center justify-between w-full">
                            <input type="hidden" name="period" value="<?= esc($_GET['period'] ?? 'today') ?>" />
                            <!-- Search Input -->
                            <div class="relative w-full sm:flex-1">
                                <span class="material-symbols-outlined absolute left-3 top-2 text-slate-400 text-[18px]">search</span>
                                <input type="text" name="q" value="<?= esc($_GET['q'] ?? '') ?>" placeholder="Cari Kode atau Cust..."
                                       class="w-full pl-9 pr-3 py-1.5 border border-slate-200 rounded-lg text-xs outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white transition-all"/>
                            </div>
                            
                            <!-- Status Filter -->
                            <div class="relative w-full sm:w-60 flex items-center gap-2 bg-white border border-slate-200 px-3 py-1.5 rounded-lg text-xs shadow-sm">
                                <span class="material-symbols-outlined text-slate-400 text-sm">filter_list</span>
                                <select name="status" onchange="this.form.submit()"
                                        class="border-none bg-transparent p-0 focus:ring-0 text-xs font-semibold text-slate-700 outline-none flex-1">
                                    <option value="" <?= ($_GET['status'] ?? '') === '' ? 'selected' : '' ?>>Semua Status</option>
                                    <option value="confirmed" <?= ($_GET['status'] ?? '') === 'confirmed' ? 'selected' : '' ?>>TERKONFIRMASI</option>
                                    <option value="pending"   <?= ($_GET['status'] ?? '') === 'pending'   ? 'selected' : '' ?>>MENUNGGU</option>
                                    <option value="completed" <?= ($_GET['status'] ?? '') === 'completed' ? 'selected' : '' ?>>SELESAI</option>
                                    <option value="cancelled" <?= ($_GET['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>DIBATALKAN</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-center">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Kode &amp; Cust</th>
                                    <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Slot / Paket</th>
                                    <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Antrean</th>
                                    <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Status</th>
                                    <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 uppercase text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if (!empty($bookings)): ?>
                                    <?php foreach ($bookings as $bk): ?>
                                        <?php
                                            $statusMap = [
                                                'confirmed' => ['bg-blue-50 text-blue-700',    'TERKONFIRMASI'],
                                                'pending'   => ['bg-yellow-50 text-yellow-700', 'MENUNGGU'],
                                                'completed' => ['bg-green-50 text-green-700',   'SELESAI'],
                                                'cancelled' => ['bg-red-50 text-red-700',        'DIBATALKAN'],
                                            ];
                                            $st = $statusMap[$bk['status']] ?? ['bg-slate-50 text-slate-600', strtoupper($bk['status'])];
                                        ?>
                                        <tr class="hover:bg-blue-50/50 transition-colors">
                                            <td class="px-4 py-3 text-center">
                                                <div class="text-sm font-bold text-primary hover:underline cursor-pointer" onclick="showBookingPreviewModal('<?= esc($bk['id'], 'js') ?>', '<?= esc($bk['booking_code'], 'js') ?>', '<?= esc($bk['customer_name'] ?? '-', 'js') ?>', 'Slot #<?= esc($bk['slot_id'], 'js') ?> (<?= esc($bk['booth_name'] ?? 'Booth', 'js') ?>)', '<?= esc($bk['package_name'] ?? '-', 'js') ?>', '<?= esc($bk['queue_number'], 'js') ?>', '<?= $st[1] ?>', '<?= $st[0] ?>', '<?= $bk['status'] ?>', <?= $bk['edit_count'] ?? 0 ?>)">
                                                    <?= esc($bk['booking_code']) ?>
                                                </div>
                                                <div class="text-xs text-on-surface font-medium"><?= esc($bk['customer_name'] ?? '-') ?></div>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="text-xs text-slate-700 font-semibold">Slot #<?= esc($bk['slot_id']) ?> (<?= esc($bk['booth_name'] ?? 'Booth') ?>)</div>
                                                <div class="text-[11px] text-slate-500"><?= esc($bk['package_name'] ?? '-') ?></div>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="text-lg font-black text-slate-400"><?= esc($bk['queue_number']) ?></span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold <?= $st[0] ?>"><?= $st[1] ?></span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="flex justify-center gap-2">
                                                    <?php if (in_array($bk['status'], ['confirmed', 'pending'])): ?>
                                                        <a href="<?= base_url('admin/bookings/' . $bk['id'] . '/edit') ?>"
                                                           class="inline p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                                        </a>
                                                        <button type="button" onclick="showCancelBookingModal('<?= base_url('admin/bookings/' . $bk['id'] . '/cancel') ?>')"
                                                                class="inline p-1.5 text-red-500 hover:bg-red-50 rounded transition-colors" title="Batalkan">
                                                            <span class="material-symbols-outlined text-[20px]">cancel</span>
                                                        </button>
                                                    <?php elseif ($bk['status'] === 'completed'): ?>
                                                        <button type="button" onclick="showBookingPreviewModal('<?= esc($bk['id'], 'js') ?>', '<?= esc($bk['booking_code'], 'js') ?>', '<?= esc($bk['customer_name'] ?? '-', 'js') ?>', 'Slot #<?= esc($bk['slot_id'], 'js') ?> (<?= esc($bk['booth_name'] ?? 'Booth', 'js') ?>)', '<?= esc($bk['package_name'] ?? '-', 'js') ?>', '<?= esc($bk['queue_number'], 'js') ?>', '<?= $st[1] ?>', '<?= $st[0] ?>', '<?= $bk['status'] ?>', <?= $bk['edit_count'] ?? 0 ?>)"
                                                            class="inline p-1.5 text-emerald-600 hover:bg-emerald-50 rounded transition-colors" title="Detail">
                                                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="button" onclick="showBookingPreviewModal('<?= esc($bk['id'], 'js') ?>', '<?= esc($bk['booking_code'], 'js') ?>', '<?= esc($bk['customer_name'] ?? '-', 'js') ?>', 'Slot #<?= esc($bk['slot_id'], 'js') ?> (<?= esc($bk['booth_name'] ?? 'Booth', 'js') ?>)', '<?= esc($bk['package_name'] ?? '-', 'js') ?>', '<?= esc($bk['queue_number'], 'js') ?>', '<?= $st[1] ?>', '<?= $st[0] ?>', '<?= $bk['status'] ?>', <?= $bk['edit_count'] ?? 0 ?>)"
                                                            class="inline p-1.5 text-slate-500 hover:bg-slate-100 rounded transition-colors" title="Riwayat">
                                                            <span class="material-symbols-outlined text-[20px]">history</span>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-400">Belum ada booking untuk periode ini.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                        <span class="text-xs text-slate-500 font-medium">
                            Menampilkan <?= count($bookings ?? []) ?> dari <?= $totalBookings ?? 0 ?> booking
                        </span>
                        <?php 
                            $qs = '&period=' . ($period ?? 'today') . '&q=' . urlencode($_GET['q'] ?? '') . '&status=' . urlencode($_GET['status'] ?? '');
                        ?>
                        <div class="flex gap-1">
                            <a href="<?= base_url('admin/bookings?page=' . max(1, ($currentPage ?? 1) - 1) . '&period=' . ($period ?? 'today')) ?>"
                               class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                                <span class="material-symbols-outlined text-sm">chevron_left</span>
                            </a>
                            <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                                <a href="<?= base_url('admin/bookings?page=' . $i . '&period=' . ($period ?? 'today')) ?>"
                                   class="w-8 h-8 flex items-center justify-center border rounded text-sm font-bold transition-colors <?= $i === ($currentPage ?? 1) ? 'border-primary bg-primary text-white' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                            <a href="<?= base_url('admin/bookings?page=' . min(($totalPages ?? 1), ($currentPage ?? 1) + 1) . '&period=' . ($period ?? 'today')) ?>"
                               class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentPage ?? 1) >= ($totalPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                                <span class="material-symbols-outlined text-sm">chevron_right</span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- BAWAH: Quick Stats -->
            <div class="lg:col-span-12 grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Reservasi</span>
                        <div class="text-2xl font-black text-slate-900 mt-1"><?= $statTotalReservasi ?? 0 ?></div>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-[10px] text-green-600 font-bold flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">trending_up</span> +12%
                        </span>
                        <svg class="w-16 h-8 stroke-green-500 fill-none stroke-2" viewBox="0 0 100 40">
                            <path d="M0,35 Q20,30 40,20 T80,15 T100,5"></path>
                        </svg>
                    </div>
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
            
            <!-- Close Button -->
            <button type="button" onclick="closeBookingPreviewModal()" class="w-full py-2.5 bg-primary hover:bg-primary-container text-white font-semibold text-sm rounded-lg transition-colors shadow-md">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function showBookingPreviewModal(code, cust, slot, pkg, queue, badgeText, badgeClass) {
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
</script>

<!-- Booking Preview Modal -->
<div id="bookingPreviewModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="bookingPreviewModalContent">
        <!-- Modal Header -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">visibility</span>
                Detail &amp; Riwayat Reservasi
            </h4>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 flex flex-col items-center">
            <!-- Icon/Avatar Wrapper -->
            <div class="w-24 h-24 bg-blue-50 text-blue-600 border border-slate-200 rounded-full overflow-hidden flex items-center justify-center p-4 mb-5 shadow-inner">
                <span class="material-symbols-outlined text-[48px]">event_available</span>
            </div>

            <!-- Details List -->
            <div class="w-full space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-200 text-left text-sm mb-6">
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
                <span>ini perlu konfirmasi Pelanggan</span>
            </div>

            <!-- Edit Booking Button -->
            <a id="modalBkEditBtn" href="#" class="w-full py-2.5 bg-primary hover:bg-primary-container text-white font-bold text-center text-sm rounded-lg transition-colors shadow-md mb-2 flex items-center justify-center gap-1.5 hidden">
                <span class="material-symbols-outlined text-[18px]">edit</span>
                Ubah Booking
            </a>

            <!-- Close Button -->
            <button type="button" onclick="closeBookingPreviewModal()" class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-lg transition-colors shadow-sm">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Cancel Booking Confirmation Modal -->
<div id="cancelBookingModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-2xl w-full mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="cancelBookingModalContent">
        <!-- Modal Body -->
        <div class="p-6 flex flex-col items-center text-center">
            <!-- Warning Alert Icon -->
            <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center p-4 mb-4">
                <span class="material-symbols-outlined text-[32px]">warning</span>
            </div>

            <h3 class="text-lg font-bold text-slate-800 mb-2">Konfirmasi Pembatalan</h3>
            <p class="text-sm text-slate-500 mb-4 leading-relaxed">
                Apakah Anda yakin ingin membatalkan booking ini?<br>Tindakan ini tidak dapat dibatalkan.
            </p>
            
            <!-- Info Banner inside Modal -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 text-left mb-6 w-full flex items-start gap-2">
                <span class="material-symbols-outlined text-blue-500 text-sm mt-0.5">info</span>
                <div>
                    <h5 class="text-xs font-bold text-blue-900">Informasi Penting</h5>
                    <p class="text-[10px] text-blue-700 mt-0.5 leading-normal">
                        Mengubah slot atau membatalkan booking akan menggeser antrean pelanggan. Pastikan pelanggan sudah menyetujui sebelum melakukan pembatalan.
                    </p>
                </div>
            </div>

            <!-- Form & Actions -->
            <form id="cancelBookingForm" method="POST" class="w-full flex gap-3">
                <?= csrf_field() ?>
                <button type="button" onclick="closeCancelBookingModal()" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-lg transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-lg transition-colors shadow-md">
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
