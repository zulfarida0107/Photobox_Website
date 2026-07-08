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
        <!-- Header Actions -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="font-h1 text-h1 text-on-surface">Manajemen Reservasi</h2>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Atur jadwal operasional booth dan reservasi pelanggan.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="<?= base_url('admin/slots/create') ?>"
                   class="flex items-center gap-2 px-4 py-2.5 bg-primary text-on-primary rounded-lg font-label-bold text-label-bold hover:bg-primary-container transition-colors">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    Buat Slot Baru
                </a>
                <button class="flex items-center gap-2 px-4 py-2.5 border border-primary text-primary rounded-lg font-label-bold text-label-bold hover:bg-blue-50 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">filter_list</span>
                    Filter
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- KIRI: Tabel Slot Operasional -->
            <section class="lg:col-span-4 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-h2 text-h2">Slot Operasional</h3>
                    <span class="px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[11px] font-bold">
                        <?= $totalAvailableSlots ?? 0 ?> Slot Tersedia
                    </span>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
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
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-semibold text-slate-900">#<?= esc($slot['id']) ?></div>
                                                <div class="text-xs text-slate-500"><?= esc($slot['booth_name'] ?? 'Booth') ?></div>
                                            </td>
                                            <td class="px-4 py-3">
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
            <section class="lg:col-span-8 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-h2 text-h2">Daftar Booking Masuk</h3>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 uppercase">Kode &amp; Cust</th>
                                    <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 uppercase">Slot / Paket</th>
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
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-bold text-primary"><?= esc($bk['booking_code']) ?></div>
                                                <div class="text-xs text-on-surface font-medium"><?= esc($bk['customer_name'] ?? '-') ?></div>
                                            </td>
                                            <td class="px-4 py-3">
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
                                                        <form action="<?= base_url('admin/bookings/' . $bk['id'] . '/cancel') ?>" method="post" class="inline">
                                                            <?= csrf_field() ?>
                                                            <button type="submit" class="inline p-1.5 text-red-500 hover:bg-red-50 rounded transition-colors" title="Batalkan">
                                                                <span class="material-symbols-outlined text-[20px]">cancel</span>
                                                            </button>
                                                        </form>
                                                    <?php elseif ($bk['status'] === 'completed'): ?>
                                                        <button type="button" onclick="showBookingPreviewModal('<?= esc($bk['booking_code'], 'js') ?>', '<?= esc($bk['customer_name'] ?? '-', 'js') ?>', 'Slot #<?= esc($bk['slot_id'], 'js') ?> (<?= esc($bk['booth_name'] ?? 'Booth', 'js') ?>)', '<?= esc($bk['package_name'] ?? '-', 'js') ?>', '<?= esc($bk['queue_number'], 'js') ?>', '<?= $st[1] ?>', '<?= $st[0] ?>')"
                                                           class="inline p-1.5 text-emerald-600 hover:bg-emerald-50 rounded transition-colors" title="Detail">
                                                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="button" onclick="showBookingPreviewModal('<?= esc($bk['booking_code'], 'js') ?>', '<?= esc($bk['customer_name'] ?? '-', 'js') ?>', 'Slot #<?= esc($bk['slot_id'], 'js') ?> (<?= esc($bk['booth_name'] ?? 'Booth', 'js') ?>)', '<?= esc($bk['package_name'] ?? '-', 'js') ?>', '<?= esc($bk['queue_number'], 'js') ?>', '<?= $st[1] ?>', '<?= $st[0] ?>')"
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
                        <span class="text-xs text-slate-500">
                            Menampilkan <?= count($bookings ?? []) ?> dari <?= $totalBookings ?? 0 ?> booking
                        </span>
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
                </div>
            </div>

        </div>
    </div>

    <?= $this->include('Admin/layouts/footer') ?>
</main>

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

<?php $this->endSection(); ?>
