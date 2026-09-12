<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<main class="pt-16 min-h-screen flex flex-col">
    <div class="p-6 flex-1">

        <!-- Breadcrumbs -->
        <nav aria-label="Breadcrumb" class="flex mb-6 text-xs font-medium text-slate-500">
            <ol class="flex items-center space-x-2">
                <li><a class="hover:text-blue-600 flex items-center gap-1 transition-colors" href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[10px]">chevron_right</span>
                    <a class="hover:text-blue-600 transition-colors" href="<?= base_url('admin/bookings') ?>">Manajemen Reservasi</a>
                </li>
                <li class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[10px]">chevron_right</span>
                    <span class="text-blue-700 font-bold">Kalender Slot</span>
                </li>
            </ol>
        </nav>

        <div class="mb-6">
            <h1 class="font-h1 text-h1 text-on-surface tracking-tight">Kalender Slot Operasional</h1>
            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Daftar keseluruhan jadwal operasional seluruh booth.</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 uppercase">Slot ID</th>
                            <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 uppercase">Booth</th>
                            <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 uppercase">Tanggal</th>
                            <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 uppercase">Waktu</th>
                            <th class="px-4 py-3 font-label-bold text-label-bold text-slate-600 uppercase">Okupansi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (!empty($slots)): ?>
                            <?php foreach ($slots as $slot): ?>
                                <tr class="hover:bg-blue-50/50 transition-colors">
                                    <td class="px-4 py-3 text-sm font-bold text-primary">#<?= esc($slot['id']) ?></td>
                                    <td class="px-4 py-3 text-sm font-medium text-slate-700"><?= esc($slot['booth_name'] ?? 'Unknown Booth') ?></td>
                                    <td class="px-4 py-3 text-sm text-slate-600 font-medium"><?= date('d M Y', strtotime($slot['slot_date'])) ?></td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 text-xs font-bold border border-blue-100">
                                            <span class="material-symbols-outlined text-[14px]">schedule</span>
                                            <?= date('H:i', strtotime($slot['start_time'])) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-bold <?= $slot['booked_count'] >= $slot['max_bookings'] ? 'text-red-600' : 'text-slate-700' ?>">
                                                <?= $slot['booked_count'] ?> / <?= $slot['max_bookings'] ?>
                                            </span>
                                            <?php if ($slot['booked_count'] >= $slot['max_bookings']): ?>
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700">Penuh</span>
                                            <?php else: ?>
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700">Tersedia</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="material-symbols-outlined text-4xl mb-2 text-slate-300">event_busy</span>
                                        <p class="font-medium">Belum ada data slot yang tersedia.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="p-6 bg-white border-t border-slate-200 flex items-center justify-between">
                <span class="text-xs text-slate-500">
                    Menampilkan <?= count($slots ?? []) ?> dari <?= $totalSlots ?? 0 ?> slot
                </span>
                <div class="flex gap-2">
                    <a href="<?= base_url('admin/slots?page=' . max(1, ($currentPage ?? 1) - 1)) ?>"
                       class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentPage ?? 1) <= 1 ? 'opacity-40 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-sm">chevron_left</span>
                    </a>
                    <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                        <a href="<?= base_url('admin/slots?page=' . $i) ?>"
                           class="w-8 h-8 flex items-center justify-center border rounded-lg text-sm font-bold transition-colors <?= $i === ($currentPage ?? 1) ? 'border-primary bg-primary text-white' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    <a href="<?= base_url('admin/slots?page=' . min(($totalPages ?? 1), ($currentPage ?? 1) + 1)) ?>"
                       class="w-8 h-8 flex items-center justify-center border border-slate-200 bg-white rounded-lg hover:bg-slate-50 transition-colors text-slate-400 <?= ($currentPage ?? 1) >= ($totalPages ?? 1) ? 'opacity-40 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</main>
<?php $this->endSection(); ?>
