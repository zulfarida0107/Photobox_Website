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
                    <span class="text-blue-700 font-bold">Detail Booking</span>
                </li>
            </ol>
        </nav>

        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600">
                    <span class="material-symbols-outlined text-[24px]">receipt_long</span>
                </div>
                <div>
                    <h1 class="font-h1 text-h1 text-on-surface tracking-tight">Detail Booking #<?= esc($booking['id']) ?></h1>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Kode Booking: <strong><?= esc($booking['booking_code']) ?></strong></p>
                </div>
            </div>
            
            <a href="<?= base_url('admin/bookings') ?>" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold text-sm rounded-lg hover:bg-slate-200 transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Info -->
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase mb-3">Informasi Pelanggan</h4>
                    <div class="space-y-3">
                        <div>
                            <span class="block text-xs text-slate-500">Nama Pelanggan</span>
                            <span class="font-bold text-slate-900"><?= esc($booking['customer_name'] ?? 'Tidak diketahui') ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Booking Info -->
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase mb-3">Informasi Layanan</h4>
                    <div class="space-y-3">
                        <div>
                            <span class="block text-xs text-slate-500">Paket Terpilih</span>
                            <span class="font-bold text-slate-900"><?= esc($booking['package_name'] ?? '-') ?></span>
                        </div>
                        <div>
                            <span class="block text-xs text-slate-500">Booth / Studio</span>
                            <span class="font-bold text-slate-900"><?= esc($booking['booth_name'] ?? '-') ?></span>
                        </div>
                        <div>
                            <span class="block text-xs text-slate-500">Status Pembayaran</span>
                            <span class="font-bold <?= $booking['payment_status'] == 'paid' ? 'text-green-600' : 'text-amber-600' ?> uppercase">
                                <?= esc($booking['payment_status']) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100">
                <h4 class="text-xs font-bold text-slate-400 uppercase mb-3">Antrean & Status</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <span class="block text-xs text-slate-500">Nomor Antrean</span>
                        <span class="text-2xl font-black text-blue-600"><?= esc($booking['queue_number']) ?></span>
                    </div>
                    
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <span class="block text-xs text-slate-500 mb-1">Status Reservasi</span>
                        <?php
                            $statusMap = [
                                'paid'      => ['bg-blue-100 text-blue-700', 'Terkonfirmasi/Lunas'],
                                'pending'   => ['bg-yellow-100 text-yellow-700', 'Menunggu'],
                                'completed' => ['bg-green-100 text-green-700', 'Selesai'],
                                'cancelled' => ['bg-red-100 text-red-700', 'Dibatalkan'],
                            ];
                            $st = $statusMap[$booking['status']] ?? ['bg-slate-100 text-slate-600', $booking['status']];
                        ?>
                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-bold <?= $st[0] ?>"><?= $st[1] ?></span>
                    </div>
                </div>
            </div>
            
        </div>

    </div>
</main>
<?php $this->endSection(); ?>
