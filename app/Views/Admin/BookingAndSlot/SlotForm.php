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
                    <span class="text-blue-700 font-bold">Buat Slot Baru</span>
                </li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="font-h1 text-h1 text-on-surface">Buat Slot Baru</h2>
            <p class="font-body-sm text-body-sm text-on-surface-variant">Tambahkan jadwal operasional booth baru ke dalam basis data.</p>
        </div>

        <style>
            /* Sembunyikan ikon kalender/jam bawaan browser pada input date & time */
            input[type="date"]::-webkit-calendar-picker-indicator,
            input[type="time"]::-webkit-calendar-picker-indicator {
                display: none;
                -webkit-appearance: none;
            }
        </style>

        <!-- Form Card -->
        <div class="bg-white border border-outline-variant rounded-xl shadow-sm w-full overflow-hidden">
            <div class="p-6 border-b border-outline-variant bg-slate-50/50 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">event_available</span>
                <h4 class="font-bold text-on-surface">Informasi Slot Operasional</h4>
            </div>

            <!-- Error Alerts -->
            <?php if (!empty(session()->getFlashdata('errors'))): ?>
                <div class="m-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('admin/slots') ?>" method="POST" class="p-6 space-y-5">
                <?= csrf_field() ?>

                <!-- Booth -->
                <div>
                    <label for="booth_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Booth *</label>
                    <select name="booth_id" id="booth_id" required class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                        <option value="">-- Pilih Booth --</option>
                        <?php foreach ($booths as $b): ?>
                            <option value="<?= $b['id'] ?>" <?= old('booth_id') == $b['id'] ? 'selected' : '' ?>><?= esc($b['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Date -->
                <div>
                    <label for="slot_date" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Slot *</label>
                    <input type="date" name="slot_date" id="slot_date" value="<?= old('slot_date') ?>" required class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors" />
                </div>
                
                <!-- Time -->
                <div>
                    <label for="start_time" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jam Mulai *</label>
                    <input type="time" name="start_time" id="start_time" value="<?= old('start_time') ?>" required class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors" />
                </div>

                <!-- Max Bookings -->
                <div>
                    <label for="max_bookings" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Batas Maksimal Booking *</label>
                    <input type="number" name="max_bookings" id="max_bookings" value="<?= old('max_bookings', 1) ?>" min="1" required class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors" placeholder="1" />
                </div>

                <!-- Footer Buttons -->
                <div class="pt-4 flex items-center justify-end gap-3 mt-8">
                    <a href="<?= base_url('admin/bookings') ?>" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-blue-700 rounded-lg hover:bg-blue-800 transition-colors">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Simpan Slot
                    </button>
                </div>
            </form>
        </div>

    </div>
</main>
<?php $this->endSection(); ?>
