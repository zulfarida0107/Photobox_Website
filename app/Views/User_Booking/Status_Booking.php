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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #B8E4FF 0%, #FCD2E8 100%);
            background-attachment: fixed;
        }
        .page-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: stretch;
            justify-content: center;
            padding: 0;
            background-color: #e5e7eb;
        }
        .main-container {
            width: 100%;
            border-radius: 0;
            overflow: hidden;
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
            background: rgba(255,255,255,0.7);
            border-radius: 16px;
            padding: 24px;
            width: 100%;
            max-width: 500px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.5);
        }
        .booking-code-display {
            font-family: monospace;
            background: white;
            padding: 10px 20px;
            border-radius: 10px;
            display: inline-block;
            font-weight: 700;
            font-size: 1.2rem;
            letter-spacing: 2px;
            color: #2563eb;
            border: 1px solid #e2e8f0;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 14px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .icon-wrapper {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #bef264;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(190, 242, 100, 0.4);
            position: relative;
            margin: 0 auto 24px;
        }
        .icon-wrapper .ring-1 {
            position: absolute;
            inset: -12px;
            border: 6px solid rgba(190, 242, 100, 0.3);
            border-radius: 50%;
        }
        .icon-wrapper .ring-2 {
            position: absolute;
            inset: -24px;
            border: 6px solid rgba(190, 242, 100, 0.15);
            border-radius: 50%;
        }
        .icon-wrapper .material-symbols-outlined {
            font-size: 64px;
            color: #365314;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            width: 100%;
            margin-top: 12px;
        }
        .info-item {
            background: rgba(255,255,255,0.5);
            border-radius: 12px;
            padding: 12px;
            text-align: center;
        }
        .info-item .label {
            font-size: 0.6rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .info-item .value {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1e293b;
            margin-top: 2px;
        }
        .content-wrapper {
            max-width: 500px;
            width: 100%;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
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
        @media (max-width: 480px) {
            .info-grid {
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }
            .icon-wrapper {
                width: 96px;
                height: 96px;
            }
            .icon-wrapper .material-symbols-outlined {
                font-size: 48px;
            }
            .icon-wrapper .ring-1 {
                inset: -8px;
                border-width: 4px;
            }
            .icon-wrapper .ring-2 {
                inset: -16px;
                border-width: 4px;
            }
            .booking-code-display {
                font-size: 1rem;
                padding: 8px 16px;
            }
            .booking-info {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="main-container gradient-bg flex flex-col min-h-screen">

          <!-- BEGIN: MainHeader -->
<header class="flex items-center justify-between px-6 sm:px-8 py-4 header-bg border-b border-gray-100" data-purpose="site-header">
    <div class="flex items-center">
        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">CuitBox Studio</h1>
    </div>
    <div class="flex items-center space-x-2 sm:space-x-4 text-slate-600">
        <!-- Help Button -->
        <a href="<?= base_url('help') ?>" class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center rounded-full hover:bg-gray-100/50 transition-all relative group" aria-label="Help">
            <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
</svg>
        </a>
    </div>
</header>
<!-- END: MainHeader -->

            <!-- Main Content -->
            <main class="flex-1 px-6 sm:px-8 py-10 md:py-16 bg-white/40 backdrop-blur-sm flex items-center justify-center">
                <div class="content-wrapper success-circle-animation">
                    
                    <!-- Flash Messages -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="w-full bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="w-full bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Icon -->
                    <div class="icon-wrapper">
                        <div class="ring-1"></div>
                        <div class="ring-2"></div>
                        <span class="material-symbols-outlined">check</span>
                    </div>
                    
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-2">
                        Booking Berhasil!
                    </h2>
                    <p class="text-slate-500 text-sm md:text-base leading-relaxed mb-1">
                        Kode booking Anda telah dikirim ke email Anda.
                    </p>
                    <p class="text-slate-600 text-sm md:text-base font-semibold mb-6">
                        Tim CuitBox sudah menyimpan jadwal booking photo Anda!
                    </p>
                    
                    <!-- Informasi Booking -->
                    <?php if (isset($booking) && $booking && !empty($booking['booking_code'])): ?>
                    <div class="booking-info">
                        <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">Kode Booking</p>
                        <div class="booking-code-display">
                            <?= $booking['booking_code'] ?>
                        </div>
                        
                        <div class="info-grid">
                            <div class="info-item">
                                <p class="label">Status</p>
                                <p class="value">
                                    <span class="status-badge <?= $booking['status_badge'] ?? 'bg-gray-100 text-gray-800' ?>">
                                        <span class="material-symbols-outlined text-sm"><?= $booking['status_icon'] ?? 'info' ?></span>
                                        <?= $booking['status_label'] ?? ucfirst($booking['status'] ?? 'Pending') ?>
                                    </span>
                                </p>
                            </div>
                            <div class="info-item">
                                <p class="label">Tanggal</p>
                                <p class="value"><?= $booking['booking_date'] ?? 'N/A' ?></p>
                            </div>
                            <div class="info-item">
                                <p class="label">Jam</p>
                                <p class="value"><?= $booking['booking_time'] ?? 'N/A' ?></p>
                            </div>
                            <div class="info-item">
                                <p class="label">Durasi</p>
                                <p class="value"><?= $booking['duration'] ?? 1 ?> Jam</p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Button Next Page -->
                    <div class="w-full mt-6">
                        <form action="/StatusBooking/store" method="post" class="w-full">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn-primary" id="btnBarcode">
                                <span class="spinner"></span>
                                <span class="btn-text">
                                    <?php if (isset($booking) && $booking && !empty($booking['booking_code'])): ?>
                                        Simpan & Lihat Kode Booking
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
            <footer class="flex flex-col sm:flex-row items-center justify-between px-6 sm:px-8 py-4 footer-bg border-t border-gray-100 text-slate-500 text-xs font-medium gap-2">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[action="/StatusBooking/store"]');
            const button = document.getElementById('btnBarcode');
            
            if (form && button) {
                form.addEventListener('submit', function(e) {
                    button.classList.add('btn-loading');
                    button.disabled = true;
                    console.log('📤 Submitting to StatusBooking/store');
                    return true;
                });
            }
        });
    </script>
</body>
</html>

