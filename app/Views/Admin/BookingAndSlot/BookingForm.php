<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<main class="pt-16 min-h-screen flex flex-col">
    <div class="p-6 flex-1 max-w-4xl mx-auto w-full">

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
            <h1 class="font-h1 text-h1 text-on-surface tracking-tight">Edit Reservasi</h1>
            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Perbarui status atau pindahkan slot reservasi pelanggan.</p>
        </div>

        <form action="<?= base_url('admin/bookings/' . $booking['id'] . '/update') ?>" method="POST" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <?= csrf_field() ?>

            <!-- Card Header -->
            <div class="p-5 border-b border-slate-200 flex items-center gap-3 bg-white">
                <span class="material-symbols-outlined text-primary text-xl">event_available</span>
                <h3 class="font-h2 text-h2 text-on-surface text-base">Informasi Reservasi</h3>
            </div>

            <div class="p-6 space-y-6">
                <!-- ID Booking -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">ID Booking</label>
                    <input type="text" value="<?= esc($booking['id']) ?>" disabled class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-400 font-semibold cursor-not-allowed text-sm">
                </div>

                <!-- Pindah Slot -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Pindah Slot Operasional</label>
                    <select name="slot_id" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-lg text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm font-medium cursor-pointer">
                        <?php foreach ($slots as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= (old('slot_id') ?? $booking['slot_id']) == $s['id'] ? 'selected' : '' ?>>
                                <?= esc($s['booth_name']) ?> - <?= date('d M Y', strtotime($s['slot_date'])) ?> <?= date('H:i', strtotime($s['start_time'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($validation->hasError('slot_id')): ?>
                        <p class="mt-1 text-xs text-red-500 font-medium"><?= $validation->getError('slot_id') ?></p>
                    <?php endif; ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nomor Antrean -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Nomor Antrean *</label>
                        <input type="text" name="queue_number" value="<?= old('queue_number', $booking['queue_number']) ?>" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-lg text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm font-medium">
                        <?php if ($validation->hasError('queue_number')): ?>
                            <p class="mt-1 text-xs text-red-500 font-medium"><?= $validation->getError('queue_number') ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Status Reservasi *</label>
                        <select name="status" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-lg text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm font-medium cursor-pointer">
                            <option value="pending" <?= (old('status') ?? $booking['status']) == 'pending' ? 'selected' : '' ?>>Menunggu (Pending)</option>
                            <option value="paid" <?= (old('status') ?? $booking['status']) == 'paid' ? 'selected' : '' ?>>Lunas / Terkonfirmasi (Paid)</option>
                            <option value="completed" <?= (old('status') ?? $booking['status']) == 'completed' ? 'selected' : '' ?>>Selesai (Completed)</option>
                            <option value="cancelled" <?= (old('status') ?? $booking['status']) == 'cancelled' ? 'selected' : '' ?>>Dibatalkan (Cancelled)</option>
                        </select>
                        <?php if ($validation->hasError('status')): ?>
                            <p class="mt-1 text-xs text-red-500 font-medium"><?= $validation->getError('status') ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="<?= base_url('admin/bookings') ?>" class="px-4 py-2 border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-lg text-sm font-semibold transition-colors">
                    Batal
                </a>
                <button type="submit" class="flex items-center gap-2 px-5 py-2.5 bg-primary hover:bg-primary-container text-white font-semibold text-sm rounded-lg transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</main>
<?php $this->endSection(); ?>
