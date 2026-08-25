<?php 
    $isEdit = !empty($slot);
    $pageTitle = $isEdit ? 'Edit Slot Operasional' : 'Buat Slot Baru';
    $formAction = $isEdit ? base_url('admin/slots/' . esc($slot['id']) . '/update') : base_url('admin/slots');
?>

<main class="pt-16 min-h-screen flex flex-col bg-slate-50/50">
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
                    <span class="text-blue-700 font-bold"><?= $pageTitle ?></span>
                </li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="font-h1 text-h1 text-on-surface"><?= $pageTitle ?></h2>
            <p class="font-body-sm text-body-sm text-on-surface-variant">
                <?= $isEdit ? 'Perbarui jadwal dan kapasitas slot operasional booth.' : 'Tambahkan jadwal operasional booth baru ke dalam basis data.' ?>
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm w-full overflow-hidden max-w-2xl">
            <div class="p-4 border-b border-slate-200 bg-white flex items-center gap-2">
                <div class="w-1 h-5 bg-primary rounded-full"></div>
                <h4 class="font-bold text-slate-800 text-sm">Informasi Slot Operasional</h4>
            </div>

            <!-- Error Alerts -->
            <?php if (!empty(session()->getFlashdata('errors'))): ?>
                <div class="m-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= $formAction ?>" method="POST" class="p-6 space-y-4">
                <?= csrf_field() ?>

                <!-- Booth -->
                <div>
                    <label for="booth_id" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Pilih Booth *</label>
                    <select name="booth_id" id="booth_id" required class="w-full bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-xs text-slate-700 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                        <option value="">-- Pilih Booth --</option>
                        <?php foreach ($booths as $b): ?>
                            <option value="<?= $b['id'] ?>" <?= old('booth_id', $slot['booth_id'] ?? '') == $b['id'] ? 'selected' : '' ?>><?= esc($b['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Time -->
                <div>
                    <label for="start_time" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Jam Mulai Sesi *</label>
                    <input type="time" name="start_time" id="start_time" value="<?= old('start_time', $slot['start_time'] ?? '') ?>" required class="w-full bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-xs text-slate-700 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" />
                    <p class="text-[11px] text-slate-400 mt-1">Durasi sesi otomatis: Pagi/Siang/Sore 2 jam, Malam 3 jam.</p>
                </div>

                <!-- Max Bookings -->
                <div>
                    <label for="max_bookings" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Kapasitas Maksimal Booking *</label>
                    <input type="number" name="max_bookings" id="max_bookings" value="<?= old('max_bookings', $slot['max_bookings'] ?? 50) ?>" min="1" required class="w-full bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-xs text-slate-700 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" placeholder="50" />
                </div>

                <!-- Footer Buttons -->
                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 mt-6">
                    <a href="<?= base_url('admin/bookings') ?>" class="px-4 py-2 text-xs font-bold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-primary rounded-lg hover:bg-primary-container transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">save</span>
                        <?= $isEdit ? 'Perbarui Slot' : 'Simpan Slot' ?>
                    </button>
                </div>
            </form>
        </div>

    </div>
</main>
<?php $this->endSection(); ?>
