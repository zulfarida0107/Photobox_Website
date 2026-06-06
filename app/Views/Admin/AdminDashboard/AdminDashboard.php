<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<main class="pt-16 min-h-screen flex flex-col">
    <div class="p-6 flex-1">

        <!-- Breadcrumb -->
        <nav aria-label="Breadcrumb" class="flex mb-6 text-xs font-medium text-slate-500">
            <ol class="flex items-center space-x-2">
                <li><a class="hover:text-blue-600 flex items-center gap-1" href="<?= base_url('admin/dashboard') ?>">
                    <span class="material-symbols-outlined text-sm">home</span> Home</a></li>
                <li class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[10px]">chevron_right</span>
                    <span class="text-blue-700 font-bold">Dashboard</span>
                </li>
            </ol>
        </nav>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm font-medium">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <!-- Page Header -->
        <header class="mb-8">
            <h2 class="font-h1 text-h1 text-on-surface">Ringkasan Sistem</h2>
            <p class="text-slate-500 font-body-base mt-1">Pantau performa operasional Photobox Anda hari ini.</p>
        </header>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

            <!-- Total Pelanggan -->
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm relative overflow-hidden group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                        <span class="material-symbols-outlined">group</span>
                    </div>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Pelanggan</h3>
                    <p class="text-2xl font-bold text-on-surface">
                        <?= number_format($totalCustomers ?? 0, 0, ',', '.') ?>
                    </p>
                </div>
                <div class="mt-4 h-2 bg-blue-100 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-600" style="width: 70%"></div>
                </div>
            </div>

            <!-- Transaksi Berhasil -->
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm relative overflow-hidden group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                        <span class="material-symbols-outlined">check_circle</span>
                    </div>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Transaksi Berhasil</h3>
                    <p class="text-2xl font-bold text-on-surface">
                        <?= number_format($totalPaidTransactions ?? 0, 0, ',', '.') ?>
                    </p>
                </div>
                <div class="mt-4 h-2 bg-green-100 rounded-full overflow-hidden">
                    <div class="h-full bg-green-600" style="width: 85%"></div>
                </div>
            </div>

            <!-- Reservasi Menunggu -->
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm relative overflow-hidden group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                        <span class="material-symbols-outlined">pending_actions</span>
                    </div>
                    <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">
                        <?= esc($totalPendingBookings ?? 0) ?> Baru
                    </span>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Reservasi Menunggu</h3>
                    <p class="text-2xl font-bold text-on-surface">
                        <?= number_format($totalPendingBookings ?? 0, 0, ',', '.') ?>
                    </p>
                </div>
                <div class="mt-4 h-2 bg-amber-100 rounded-full overflow-hidden">
                    <div class="h-full bg-amber-600" style="width: 40%"></div>
                </div>
            </div>

            <!-- Mesin Booth Aktif -->
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm relative overflow-hidden group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 bg-purple-50 text-purple-600 rounded-lg">
                        <span class="material-symbols-outlined">nest_display</span>
                    </div>
                    <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">Online</span>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Mesin Booth Aktif</h3>
                    <p class="text-2xl font-bold text-on-surface"><?= esc($totalActiveBooths ?? 0) ?> Unit</p>
                </div>
                <div class="mt-4 h-2 bg-purple-100 rounded-full overflow-hidden">
                    <div class="h-full bg-purple-600" style="width: 80%"></div>
                </div>
            </div>
        </div>

        <!-- Revenue + Recent Bookings -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <!-- Tabel Pemesanan Terbaru -->
            <div class="xl:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-h2 text-on-surface">Pemesanan Terbaru</h3>
                        <p class="text-xs text-slate-500">Daftar 5 aktivitas reservasi terkini.</p>
                    </div>
                    <a href="<?= base_url('admin/bookings') ?>" class="text-xs font-bold text-blue-700 hover:underline">
                        Lihat Semua
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-3">Kode Booking</th>
                                <th class="px-6 py-3">Pelanggan</th>
                                <th class="px-6 py-3">Paket</th>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if (!empty($recentBookings)): ?>
                                <?php foreach ($recentBookings as $booking): ?>
                                    <?php
                                        $status = strtolower($booking['status'] ?? 'pending');
                                        $spanClass = match ($status) {
                                            'confirmed'  => 'bg-blue-50 text-blue-700 border-blue-100',
                                            'completed'  => 'bg-green-50 text-green-700 border-green-100',
                                            'pending'    => 'bg-amber-50 text-amber-700 border-amber-100',
                                            'cancelled'  => 'bg-red-50 text-red-700 border-red-100',
                                            default      => 'bg-slate-50 text-slate-600 border-slate-100',
                                        };
                                        $statusText = match ($status) {
                                            'confirmed'  => 'Dikonfirmasi',
                                            'completed'  => 'Selesai',
                                            'pending'    => 'Menunggu',
                                            'cancelled'  => 'Batal',
                                            default      => ucfirst($status),
                                        };
                                    ?>
                                    <tr class="hover:bg-blue-50/50 transition-colors">
                                        <td class="px-6 py-4 font-data-tabular font-bold text-blue-700">
                                            #<?= esc($booking['booking_code'] ?? 'N/A') ?>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600 text-sm">
                                            <?= esc($booking['customer_name'] ?? '-') ?>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600 text-sm">
                                            <?= esc($booking['package_name'] ?? '-') ?>
                                        </td>
                                        <td class="px-6 py-4 font-data-tabular text-slate-600">
                                            <?= date('d M Y, H:i', strtotime($booking['created_at'] ?? 'now')) ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold border <?= $spanClass ?>">
                                                <?= esc($statusText) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-500 font-body-base">
                                        Belum ada pemesanan terbaru.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Revenue Card -->
            <div class="bg-primary rounded-xl overflow-hidden relative min-h-[300px] flex flex-col justify-end p-8 text-white shadow-lg">
                <div class="relative z-10">
                    <div class="mb-4 inline-flex items-center gap-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-[10px] font-bold uppercase tracking-widest border border-white/20">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        Sistem Normal
                    </div>
                    <h3 class="text-2xl font-black mb-1 tracking-tight">Pendapatan Bulan Ini</h3>
                    <p class="text-3xl font-black text-white mb-4">
                        Rp <?= number_format($totalRevenueThisMonth ?? 0, 0, ',', '.') ?>
                    </p>
                    <p class="text-sm text-blue-100/80 mb-6 leading-relaxed">
                        <?= $totalActiveBooths ?? 0 ?> mesin booth aktif beroperasi optimal hari ini.
                    </p>
                    <div class="flex gap-4">
                        <a href="<?= base_url('admin/booths') ?>"
                           class="px-4 py-2 bg-white text-blue-700 font-bold rounded-lg text-xs hover:bg-blue-50 transition-colors">
                            Cek Status
                        </a>
                        <a href="<?= base_url('admin/payments') ?>"
                           class="px-4 py-2 bg-blue-600/40 backdrop-blur-sm text-white font-bold rounded-lg text-xs hover:bg-blue-600/60 transition-colors border border-white/20">
                            Lihat Transaksi
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?= $this->include('Admin/layouts/footer') ?>
</main>

<?php $this->endSection(); ?>