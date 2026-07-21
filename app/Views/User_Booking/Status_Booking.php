<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?= $title ?? 'Status Booking' ?> | CuitBox Studio</title>
    <!-- Tailwind CSS v3 CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            lime: '#D9F99D',
                            active: '#bef264',
                        },
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #B8E4FF 0%, #FCD2E8 100%);
            background-attachment: fixed;
        }
        .main-container-rounded {
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .page-wrapper {
            background-color: #e5e7eb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        @media (min-width: 640px) {
            .page-wrapper {
                padding: 40px;
            }
        }
        .success-circle-animation {
            animation: scale-up 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        @keyframes scale-up {
            from { transform: scale(0.5); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .btn-primary {
            background: #bef264;
            box-shadow: 0 10px 15px -3px rgba(190, 242, 100, 0.4);
            transition: all 0.3s ease;
            border-radius: 16px;
            padding: 16px 24px;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            border: none;
            cursor: pointer !important;
            position: relative;
            z-index: 10;
            pointer-events: auto !important;
            text-decoration: none;
            font-size: 1rem;
        }
        .btn-primary:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 25px -5px rgba(190, 242, 100, 0.5);
        }
        .btn-primary:active {
            transform: scale(0.98);
        }
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed !important;
            transform: none !important;
        }
        .header-bg {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
        }
        .footer-bg {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .booking-info {
            background: rgba(255,255,255,0.6);
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 20px;
            width: 100%;
            backdrop-filter: blur(10px);
        }
        .booking-code-display {
            font-family: monospace;
            background: white;
            padding: 8px 16px;
            border-radius: 8px;
            display: inline-block;
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: 1px;
        }
        .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(0,0,0,0.1);
            border-radius: 50%;
            border-top-color: #1e293b;
            animation: spin 0.6s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .btn-loading .spinner {
            display: inline-block;
        }
        .btn-loading .btn-text {
            display: none;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="max-w-[1200px] w-full main-container-rounded gradient-bg">

            <!-- Header -->
            <header class="flex items-center justify-between px-6 sm:px-8 py-4 header-bg border-b border-gray-100">
                <div class="flex items-center">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">CuitBox Studio</h1>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-4 text-slate-600">
                    <a href="/StatusBooking" class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center rounded-full hover:bg-gray-100/50 transition-all" title="Cek Status">
                        <span class="material-symbols-outlined">receipt_long</span>
                    </a>
                    <a href="/barcode" class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center rounded-full hover:bg-gray-100/50 transition-all" title="Lihat Barcode">
                        <span class="material-symbols-outlined">qr_code</span>
                    </a>
                </div>
            </header>

            <!-- Main Content -->
            <main class="relative px-6 sm:px-8 pt-12 pb-12 overflow-hidden bg-white/40 backdrop-blur-sm flex items-center justify-center">
                <div class="w-full max-w-[600px] mx-auto flex flex-col items-center text-center success-circle-animation">
                    
                    <!-- Flash Messages -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="w-full bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Icon -->
                    <div class="relative w-32 h-32 mb-6">
                        <div class="absolute inset-0 border-[6px] border-[#d9f99d]/40 rounded-full scale-[1.4]"></div>
                        <div class="absolute inset-0 border-[6px] border-[#d9f99d]/60 rounded-full scale-[1.2]"></div>
                        <div class="w-full h-full rounded-full bg-[#bef264] flex items-center justify-center shadow-lg">
                            <span class="material-symbols-outlined text-[#365314] text-[64px] font-bold">check</span>
                        </div>
                    </div>
                    
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-4">
                        Booking Berhasil!
                    </h2>
                    <p class="text-slate-500 text-base leading-relaxed max-w-sm mb-2">
                        Kode booking Anda telah dikirim ke email Anda.
                    </p>
                    <p class="text-slate-700 text-base font-semibold max-w-sm mb-6">
                        CuitBox Team sudah menyimpan Jadwal Booking Photo untuk momen spesial Anda!
                    </p>
                    
                    <!-- Informasi Booking -->
                    <?php if (isset($booking) && $booking && !empty($booking['booking_code'])): ?>
                    <div class="booking-info">
                        <p class="text-sm text-slate-600 mb-1">
                            <span class="font-semibold">Kode Booking:</span>
                        </p>
                        <div class="booking-code-display text-blue-600">
                            <?= $booking['booking_code'] ?>
                        </div>
                        <p class="text-sm text-slate-600 mt-2">
                            <span class="font-semibold">Status:</span> 
                            <span class="inline-flex items-center px-3 py-1 rounded-full <?= $booking['status_badge'] ?? 'bg-gray-100 text-gray-800' ?> text-xs font-medium">
                                <span class="material-symbols-outlined text-sm mr-1"><?= $booking['status_icon'] ?? 'info' ?></span>
                                <?= $booking['status_label'] ?? ucfirst($booking['status'] ?? 'Pending') ?>
                            </span>
                        </p>
                        <p class="text-sm text-slate-600 mt-1">
                            <span class="font-semibold">Tanggal:</span> <?= $booking['booking_date'] ?? 'N/A' ?>
                        </p>
                        <p class="text-sm text-slate-600 mt-1">
                            <span class="font-semibold">Jam:</span> <?= $booking['booking_time'] ?? 'N/A' ?>
                        </p>
                    </div>
                    <?php endif; ?>
                    
                    <!-- BUTTON NEXT PAGE - POST ke StatusBooking/store -->
                    <div class="w-full max-w-sm">
                        <form action="/StatusBooking/store" method="post" class="w-full">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn-primary">
                                <span class="btn-text">
                                    <?php if (isset($booking) && $booking && !empty($booking['booking_code'])): ?>
                                        Simpan dan Lihat Kode Booking Anda
                                    <?php else: ?>
                                        Cek Status Booking
                                    <?php endif; ?>
                                </span>
                                <span class="material-symbols-outlined text-xl">arrow_forward</span>
                            </button>
                        </form>
                    </div>
                    
                </div>
            </main>
            <!-- END: Main Content -->

            <!-- Footer -->
            <footer class="flex flex-col sm:flex-row items-center justify-between px-6 sm:px-8 py-4 footer-bg border-t border-gray-100 text-slate-500 text-xs font-medium gap-2 sm:gap-0">
                <div class="flex items-center gap-2">
                    <svg class="h-3 w-3 sm:h-4 sm:w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    </svg>
                    <span class="font-semibold text-slate-600">Connected</span>
                </div>
                <div class="text-center">© 2026 CuitBox Studio. All rights reserved.</div>
            </footer>

        </div>
    </div>
</body>
</html>