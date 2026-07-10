<?php $this->extend('Admin/layouts/main'); ?>
<?php $this->section('content'); ?>

<main class="pt-16 min-h-screen flex flex-col">
    <div class="p-6 flex-1 max-w-4xl mx-auto w-full">

        <!-- Breadcrumbs -->
        <nav aria-label="Breadcrumb" class="flex mb-6 text-xs font-medium text-slate-500 print:hidden">
            <ol class="flex items-center space-x-2">
                <li><a class="hover:text-blue-600 flex items-center gap-1 transition-colors" href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[10px]">chevron_right</span>
                    <a class="hover:text-blue-600 transition-colors" href="<?= base_url('admin/payments') ?>">Manajemen Pembayaran</a>
                </li>
                <li class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[10px]">chevron_right</span>
                    <span class="text-blue-700 font-bold">Invoice</span>
                </li>
            </ol>
        </nav>

        <div class="mb-6 flex items-center justify-between print:hidden">
            <div>
                <h1 class="font-h1 text-h1 text-on-surface tracking-tight">Invoice #<?= esc($payment['id']) ?></h1>
                <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Cetak atau simpan bukti pembayaran ini.</p>
            </div>
            
            <div class="flex gap-2">
                <a href="<?= base_url('admin/payments') ?>" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold text-sm rounded-lg hover:bg-slate-200 transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali
                </a>
                <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white font-bold text-sm rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">print</span> Cetak
                </button>
            </div>
        </div>

        <!-- Invoice Paper -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 md:p-12 print:border-none print:shadow-none print:p-0">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-slate-200 pb-6 mb-6">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">INVOICE</h2>
                    <p class="text-sm font-bold text-slate-500 mt-1">NO. <?= esc($payment['id']) ?></p>
                </div>
                <div class="mt-4 md:mt-0 text-left md:text-right">
                    <h3 class="text-lg font-bold text-blue-600">Photobox Studio</h3>
                    <p class="text-xs text-slate-500 mt-1">Jl. Contoh Alamat No. 123<br>Kota, Negara 12345</p>
                </div>
            </div>

            <!-- Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase mb-2">Informasi Pembayaran</h4>
                    <div class="space-y-1">
                        <p class="text-sm"><span class="text-slate-500 w-24 inline-block">Tanggal</span>: <span class="font-bold"><?= date('d F Y', strtotime($payment['created_at'])) ?></span></p>
                        <p class="text-sm"><span class="text-slate-500 w-24 inline-block">Metode</span>: <span class="font-bold uppercase"><?= esc($payment['payment_method'] ?? 'QRIS') ?></span></p>
                        <p class="text-sm"><span class="text-slate-500 w-24 inline-block">Status</span>: <span class="font-bold text-green-600 uppercase">LUNAS (PAID)</span></p>
                    </div>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase mb-2">Referensi Booking</h4>
                    <div class="space-y-1">
                        <p class="text-sm"><span class="text-slate-500 w-24 inline-block">Kode Booking</span>: <span class="font-bold text-blue-600"><?= esc($payment['booking_code'] ?? '-') ?></span></p>
                        <p class="text-sm"><span class="text-slate-500 w-24 inline-block">Ref. Gateway</span>: <span class="font-bold"><?= esc($payment['gateway_ref'] ?? '-') ?></span></p>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <table class="w-full text-left mb-8">
                <thead class="bg-slate-50 border-y border-slate-200">
                    <tr>
                        <th class="py-3 px-4 text-xs font-bold text-slate-600 uppercase">Deskripsi Item</th>
                        <th class="py-3 px-4 text-xs font-bold text-slate-600 uppercase text-right">Harga</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr>
                        <td class="py-4 px-4">
                            <p class="font-bold text-slate-900">Pembayaran Layanan Photobox</p>
                            <p class="text-xs text-slate-500 mt-1">Pembayaran penuh untuk kode booking <?= esc($payment['booking_code'] ?? '-') ?></p>
                        </td>
                        <td class="py-4 px-4 text-right font-bold">
                            Rp <?= number_format($payment['amount'] - ($payment['print_option_extra'] ?? 0), 0, ',', '.') ?>
                        </td>
                    </tr>
                    <?php if (!empty($payment['print_option_id'])): ?>
                    <tr>
                        <td class="py-4 px-4">
                            <p class="font-bold text-slate-900">Add-on: Ekstra Cetak Foto</p>
                            <p class="text-xs text-slate-500 mt-1"><?= esc($payment['print_option_copies']) ?> Copy Tambahan</p>
                        </td>
                        <td class="py-4 px-4 text-right font-bold">
                            Rp <?= number_format($payment['print_option_extra'], 0, ',', '.') ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot class="border-t-2 border-slate-200">
                    <tr>
                        <td class="py-4 px-4 text-right font-bold text-slate-600 uppercase">Total Pembayaran</td>
                        <td class="py-4 px-4 text-right text-xl font-black text-blue-700">
                            Rp <?= number_format($payment['amount'], 0, ',', '.') ?>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <!-- Footer -->
            <div class="text-center pt-8 border-t border-slate-200">
                <p class="text-sm font-bold text-slate-700">Terima Kasih atas Kunjungan Anda!</p>
                <p class="text-xs text-slate-500 mt-1">Bukti pembayaran ini sah dan diterbitkan secara otomatis oleh sistem.</p>
            </div>

        </div>
    </div>
</main>

<style>
@media print {
    /* Sembunyikan elemen layout admin */
    aside, header { 
        display: none !important; 
    }
    
    /* Reset margin sidebar dan padding header */
    .ml-\[260px\] { 
        margin-left: 0 !important; 
    }
    main.pt-16 { 
        padding-top: 0 !important; 
    }
    
    /* Reset background dan padding kontainer agar pas di kertas */
    body, html { 
        background-color: white !important; 
    }
    .p-6 { 
        padding: 0 !important; 
    }
    
    /* Pastikan lebar maksimal */
    .max-w-4xl {
        max-width: 100% !important;
    }
}
</style>
<?php $this->endSection(); ?>
