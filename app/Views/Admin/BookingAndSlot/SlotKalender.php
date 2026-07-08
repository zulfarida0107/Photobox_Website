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

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="font-h1 text-h1 text-on-surface tracking-tight">Kalender Slot Operasional</h1>
                <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Daftar keseluruhan jadwal operasional seluruh booth.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="<?= base_url('admin/slots/create') ?>"
                   class="flex items-center gap-2 px-4 py-2.5 bg-primary text-on-primary rounded-lg font-label-bold text-label-bold hover:bg-primary-container transition-colors">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    Buat Slot Baru
                </a>
            </div>
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
        </div>

    </div>
</main>
<?php $this->endSection(); ?>
