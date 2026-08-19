<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<main class="pt-16 min-h-screen flex flex-col">
    <div class="p-6 flex-1 w-full text-left">

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
                    <span class="text-blue-700 font-bold">Edit Booking</span>
                </li>
            </ol>
        </nav>

        <div class="mb-6">
            <h1 class="font-h1 text-h1 text-on-surface tracking-tight">Edit Booking</h1>
            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Perbarui status atau pindahkan slot reservasi pelanggan.</p>
        </div>

        <form action="<?= base_url('admin/bookings/' . $booking['id'] . '/update') ?>" method="POST" class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
            <?= csrf_field() ?>

            <!-- Card Header -->
            <div class="px-6 py-4 border-b border-slate-100 bg-white flex items-center gap-3">
                <span class="material-symbols-outlined text-blue-900 text-[20px]">calendar_today</span>
                <h4 class="font-bold text-slate-800 text-base">Konfigurasi Booking</h4>
            </div>

            <div class="p-6 space-y-6">
                
                <!-- Informasi Customer & Booking -->
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 text-left">
                    <h5 class="text-xs font-bold text-slate-800 mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-slate-600 text-sm">person</span>
                        Informasi Pelanggan &amp; Booking
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-xs">
                        <div>
                            <span class="text-slate-400 font-semibold uppercase tracking-wider block mb-1">Kode Booking</span>
                            <span class="text-primary font-bold text-sm font-mono"><?= esc($booking['booking_code']) ?></span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold uppercase tracking-wider block mb-1">Nama Pelanggan</span>
                            <span class="text-slate-900 font-bold text-sm"><?= esc($booking['customer_name'] ?? '-') ?></span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold uppercase tracking-wider block mb-1">Kontak Pelanggan</span>
                            <span class="text-slate-700 font-medium block"><?= esc($booking['customer_email'] ?? '-') ?></span>
                            <span class="text-slate-500 mt-0.5 block"><?= esc($booking['customer_phone'] ?? '-') ?></span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold uppercase tracking-wider block mb-1">Paket Terpilih</span>
                            <span class="text-slate-950 font-bold text-sm"><?= esc(!empty($booking['print_copies']) ? $booking['print_copies'] . ' Cetak' : '-') ?></span>
                        </div>
                    </div>
                </div>

                <!-- Info Banner -->
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-start gap-3">
                    <span class="material-symbols-outlined text-blue-500">info</span>
                    <div>
                        <h4 class="font-bold text-blue-900 text-sm">Informasi Penting</h4>
                        <p class="text-xs text-blue-700 mt-1">Mengubah slot akan menggeser antrean pelanggan. Pastikan pelanggan sudah dikonfirmasi sebelum mengubah slot atau status menjadi Completed/Cancelled.</p>
                    </div>
                </div>

                <!-- Pindah Slot (Full width, matching Nama Paket in layout) -->
                <div>
                    <label class="block font-label-bold text-label-bold text-slate-700 mb-1.5">Pindah Slot Operasional</label>
                    <div class="relative">
                        <select name="slot_id" required class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-label text-label text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all appearance-none cursor-pointer">
                            <?php foreach ($slots as $s): ?>
                                <option value="<?= $s['id'] ?>" <?= (old('slot_id') ?? $booking['slot_id']) == $s['id'] ? 'selected' : '' ?>>
                                    <?= esc($s['booth_name']) ?> - <?= date('d M Y', strtotime($s['slot_date'])) ?> <?= date('H:i', strtotime($s['start_time'])) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[18px] text-slate-400 pointer-events-none">event</span>
                    </div>
                    <?php if ($validation->hasError('slot_id')): ?>
                        <p class="mt-1 text-xs text-red-500 font-medium"><?= $validation->getError('slot_id') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Nomor Antrean & Status Reservasi (Half width each, matching Jumlah Foto & Durasi Sesi) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nomor Antrean -->
                    <div>
                        <label class="block font-label-bold text-label-bold text-slate-700 mb-1.5">Nomor Antrean <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="text" name="queue_number" value="<?= old('queue_number', $booking['queue_number']) ?>" required class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-label text-label text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[18px] text-slate-400">format_list_numbered</span>
                        </div>
                        <?php if ($validation->hasError('queue_number')): ?>
                            <p class="mt-1 text-xs text-red-500 font-medium"><?= $validation->getError('queue_number') ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Status -->
                    <div>
                        <label class="block font-label-bold text-label-bold text-slate-700 mb-1.5">Status Reservasi <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="status" required class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-label text-label text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all appearance-none cursor-pointer">
                                <option value="pending" <?= (old('status') ?? $booking['status']) == 'pending' ? 'selected' : '' ?>>Menunggu (Pending)</option>
                                <option value="paid" <?= (old('status') ?? $booking['status']) == 'paid' ? 'selected' : '' ?>>Lunas / Terkonfirmasi (Paid)</option>
                                <option value="completed" <?= (old('status') ?? $booking['status']) == 'completed' ? 'selected' : '' ?>>Selesai (Completed)</option>
                                <option value="cancelled" <?= (old('status') ?? $booking['status']) == 'cancelled' ? 'selected' : '' ?>>Dibatalkan (Cancelled)</option>
                            </select>
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[18px] text-slate-400 pointer-events-none">fact_check</span>
                        </div>
                        <?php if ($validation->hasError('status')): ?>
                            <p class="mt-1 text-xs text-red-500 font-medium"><?= $validation->getError('status') ?></p>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <!-- Footer (Matching Edit Paket buttons) -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="<?= base_url('admin/bookings') ?>" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-lg transition-colors">
                    Batal
                </a>
                <button type="submit" class="flex items-center gap-2 px-5 py-2.5 bg-[#003d9b] hover:bg-blue-800 text-white font-semibold text-sm rounded-lg transition-all shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan
                </button>
            </div>
        </form>

    </div>
</main>
<?php $this->endSection(); ?>
