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
                    <span class="text-blue-700 font-bold">Manajemen Pembayaran</span>
                </li>
            </ol>
        </nav>

        <!-- Header Section -->
        <!-- Header Section -->
        <div class="flex justify-between items-end mb-6">
            <div>
                <h2 class="font-h1 text-h1 text-on-surface mb-1">Manajemen Pembayaran</h2>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Pantau dan kelola semua transaksi masuk secara real-time.</p>
            </div>
            <div class="flex gap-3">
                <a href="<?= base_url('admin/payments/export') ?>"
                   class="flex items-center gap-2 px-4 py-2 border border-primary text-primary rounded font-semibold text-sm hover:bg-blue-50 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">cloud_download</span>
                    Ekspor Laporan
                </a>
                <form action="<?= base_url('admin/payments/verify-bulk') ?>" method="post" class="inline">
                    <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded font-semibold text-sm hover:bg-primary-container transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">verified_user</span>
                        Verifikasi Massal
                    </button>
                </form>
            </div>
        </div>

        <!-- Bento Stats Grid -->
        <!-- Bento Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <!-- Total Pendapatan -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">account_balance_wallet</span>
                    <div class="flex items-center gap-1 text-emerald-600 font-bold text-[12px]">
                        <span class="material-symbols-outlined text-[14px]">trending_up</span>
                        +12.5% bulan ini
                    </div>
                </div>
                <p class="text-sm font-medium text-slate-500">Total Pendapatan</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">
                    Rp <?= number_format($statTotalRevenue ?? 0, 0, ',', '.') ?>
                </h3>
            </div>
            <!-- Transaksi Sukses -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">receipt_long</span>
                    <div class="flex items-center gap-1 text-slate-500 font-bold text-[12px]">
                        <span class="material-symbols-outlined text-[14px]">analytics</span>
                        Konversi <?= $statConversion ?? '0' ?>%
                    </div>
                </div>
                <p class="text-sm font-medium text-slate-500">Transaksi Sukses</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= number_format($statPaid ?? 0) ?></h3>
            </div>
            <!-- Gagal/Pending -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-red-600 bg-red-50 p-2 rounded-lg material-symbols-outlined">error</span>
                    <div class="flex items-center gap-1 text-red-600 font-bold text-[12px]">
                        <span class="material-symbols-outlined text-[14px]">priority_high</span>
                        Perlu Verifikasi
                    </div>
                </div>
                <p class="text-sm font-medium text-slate-500">Gagal/Pending</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= number_format($statFailedPending ?? 0) ?></h3>
            </div>
            <!-- Add-on Cetak -->
            <div class="bg-white p-5 border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-blue-600 bg-blue-50 p-2 rounded-lg material-symbols-outlined">print</span>
                    <div class="flex items-center gap-1 text-blue-600 font-bold text-[12px]">
                        <span class="material-symbols-outlined text-[14px]">add</span>
                        Rp <?= number_format($statPrintProfit ?? 0, 0, ',', '.') ?> profit
                    </div>
                </div>
                <p class="text-sm font-medium text-slate-500">Add-on Cetak</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1"><?= number_format($statPrintAddon ?? 0) ?></h3>
            </div>
        </div>

        <!-- Table Container -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

            <!-- Filters Bar -->
            <form method="get" action="<?= base_url('admin/payments') ?>">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-wrap gap-4 items-center justify-between">
                    <div class="flex items-center gap-3">
                        <!-- Filter Status -->
                        <div class="flex items-center gap-2 bg-white border border-slate-200 px-3 py-1.5 rounded text-sm">
                            <span class="material-symbols-outlined text-slate-400 text-sm">filter_list</span>
                            <span class="text-slate-600 font-medium">Status:</span>
                            <select name="status" class="border-none bg-transparent p-0 focus:ring-0 text-sm font-bold text-primary" onchange="this.form.submit()">
                                <option value="" <?= ($filterStatus ?? '') === '' ? 'selected' : '' ?>>Semua Status</option>
                                <option value="paid"    <?= ($filterStatus ?? '') === 'paid'    ? 'selected' : '' ?>>Paid</option>
                                <option value="failed"  <?= ($filterStatus ?? '') === 'failed'  ? 'selected' : '' ?>>Failed</option>
                                <option value="pending" <?= ($filterStatus ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                            </select>
                        </div>
                        <!-- Filter Periode -->
                        <div class="flex items-center gap-2 bg-white border border-slate-200 px-3 py-1.5 rounded text-sm">
                            <span class="material-symbols-outlined text-slate-400 text-sm">calendar_month</span>
                            <span class="text-slate-600 font-medium">Waktu:</span>
                            <select name="period" class="border-none bg-transparent p-0 focus:ring-0 text-sm font-bold text-primary" onchange="this.form.submit()">
                                <option value="7"  <?= ($filterPeriod ?? '7')  === '7'  ? 'selected' : '' ?>>7 Hari Terakhir</option>
                                <option value="30" <?= ($filterPeriod ?? '')   === '30' ? 'selected' : '' ?>>30 Hari Terakhir</option>
                                <option value="month" <?= ($filterPeriod ?? '') === 'month' ? 'selected' : '' ?>>Bulan Ini</option>
                            </select>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500">
                        Menampilkan <?= count($payments ?? []) ?> dari <?= number_format($totalPayments ?? 0) ?> data
                    </p>
                </div>
            </form>

            <!-- Data Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-[11px] font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-4">ID Pembayaran</th>
                            <th class="px-6 py-4">Kode Booking</th>
                            <th class="px-6 py-4">Opsi Cetak</th>
                            <th class="px-6 py-4 text-right">Nominal</th>
                            <th class="px-6 py-4">Gateway Ref</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (!empty($payments)): ?>
                            <?php foreach ($payments as $pay): ?>
                                <?php
                                    $statusMap = [
                                        'paid'    => ['bg-emerald-100 text-emerald-700', 'bg-emerald-500', 'Paid'],
                                        'failed'  => ['bg-red-100 text-red-700',         'bg-red-500',     'Failed'],
                                        'pending' => ['bg-slate-100 text-slate-600',      'bg-slate-400',   'Pending'],
                                    ];
                                    $st = $statusMap[$pay['status']] ?? ['bg-slate-100 text-slate-600', 'bg-slate-400', ucfirst($pay['status'])];
                                    $isPaid = $pay['status'] === 'paid';
                                ?>
                                <tr class="hover:bg-blue-50/40 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-primary">#<?= esc($pay['id']) ?></td>
                                    <td class="px-6 py-4 text-slate-600 font-medium"><?= esc($pay['booking_code'] ?? $pay['booking_id']) ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-slate-100 rounded text-[11px] text-slate-600 font-bold">
                                            <?php if (!empty($pay['print_option_copies'])): ?>
                                                <?= esc($pay['print_option_copies']) ?> copy
                                                (+Rp <?= number_format($pay['print_option_extra'] ?? 0, 0, ',', '.') ?>)
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-slate-900">
                                        Rp <?= number_format($pay['amount'] ?? 0, 0, ',', '.') ?>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 text-xs font-mono"><?= esc($pay['gateway_ref'] ?? '-') ?></td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold <?= $st[0] ?>">
                                            <span class="w-1.5 h-1.5 rounded-full <?= $st[1] ?>"></span>
                                            <?= $st[2] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-500">
                                        <?= date('d M Y, H:i', strtotime($pay['created_at'])) ?>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <?php if ($isPaid): ?>
                                                <a href="<?= base_url('admin/payments/' . $pay['id'] . '/invoice') ?>"
                                                   class="p-1.5 text-blue-600 hover:bg-blue-100 rounded transition-colors" title="Cetak Invoice PDF">
                                                    <span class="material-symbols-outlined text-[20px]">picture_as_pdf</span>
                                                </a>
                                            <?php else: ?>
                                                <span class="p-1.5 text-slate-300 cursor-not-allowed" title="Invoice hanya tersedia untuk pembayaran sukses">
                                                    <span class="material-symbols-outlined text-[20px]">picture_as_pdf</span>
                                                </span>
                                            <?php endif; ?>
                                            <form action="<?= base_url('admin/payments/' . $pay['id'] . '/verify') ?>" method="post" class="inline">
                                                <?= csrf_field() ?>
                                                <button type="submit"
                                                        class="p-1.5 <?= $isPaid ? 'text-slate-400 hover:text-primary' : 'text-blue-600 hover:bg-blue-100' ?> rounded transition-colors"
                                                        title="Verifikasi Manual"
                                                        onclick="return confirm('Verifikasi pembayaran ini sebagai Paid?')">
                                                    <span class="material-symbols-outlined text-[20px]">verified</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center text-sm text-slate-400">
                                    Belum ada data pembayaran untuk filter ini.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-slate-50/50 flex items-center justify-between">
                <p class="text-xs text-slate-500 font-medium">
                    Halaman <?= $currentPage ?? 1 ?> dari <?= $totalPages ?? 1 ?>
                </p>
                <div class="flex gap-1">
                    <a href="<?= base_url('admin/payments?page=' . max(1, ($currentPage ?? 1) - 1) . '&status=' . ($filterStatus ?? '') . '&period=' . ($filterPeriod ?? '7')) ?>"
                       class="w-8 h-8 flex items-center justify-center rounded border border-slate-200 bg-white text-slate-400 hover:bg-slate-50 <?= ($currentPage ?? 1) <= 1 ? 'opacity-50 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                    </a>

                    <?php
                        $cp   = $currentPage ?? 1;
                        $tp   = $totalPages  ?? 1;
                        $qs   = '&status=' . ($filterStatus ?? '') . '&period=' . ($filterPeriod ?? '7');
                        $pages = [];
                        if ($tp <= 5) {
                            $pages = range(1, $tp);
                        } else {
                            $pages = array_unique(array_filter([1, $cp - 1, $cp, $cp + 1, $tp], fn($p) => $p >= 1 && $p <= $tp));
                            sort($pages);
                        }
                        $prev = 0;
                        foreach ($pages as $p):
                            if ($prev && $p - $prev > 1): ?>
                                <span class="px-1 py-1 text-slate-400 text-xs">...</span>
                            <?php endif; ?>
                            <a href="<?= base_url('admin/payments?page=' . $p . $qs) ?>"
                               class="w-8 h-8 flex items-center justify-center rounded border text-xs font-bold
                                      <?= $p === $cp ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>">
                                <?= $p ?>
                            </a>
                    <?php     $prev = $p;
                        endforeach; ?>

                    <a href="<?= base_url('admin/payments?page=' . min($tp, ($currentPage ?? 1) + 1) . $qs) ?>"
                       class="w-8 h-8 flex items-center justify-center rounded border border-slate-200 bg-white text-slate-400 hover:bg-slate-50 <?= ($currentPage ?? 1) >= $tp ? 'opacity-50 pointer-events-none' : '' ?>">
                        <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?= $this->include('Admin/layouts/footer') ?>
</main>

<?php $this->endSection(); ?>
