<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Selesaikan Pembayaran - CuitBox Studio</title>
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
            border-radius: 0;
            overflow: hidden;
        }
        .page-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: stretch;
            justify-content: center;
            padding: 0;
        }
        @media (min-width: 640px) {
            .page-wrapper {
                padding: 0;
            }
        }
        .header-bg {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
        }
        .footer-bg {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
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
            border: none;
            cursor: pointer;
            width: 100%;
        }
        .btn-primary:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 25px -5px rgba(190, 242, 100, 0.5);
        }
        .btn-primary:active {
            transform: scale(0.98);
        }
        .btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
        }
        .btn-back {
            background: #1e293b;
            transition: all 0.3s ease;
            border-radius: 16px;
            padding: 16px 24px;
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
        }
        .btn-back:hover {
            background: #334155;
            transform: scale(1.02);
        }
        .btn-back:active {
            transform: scale(0.98);
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .barcode-container {
            background: white;
            padding: 16px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .barcode-wrapper {
            background: #1e293b;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        .barcode-wrapper img {
            display: block;
            width: 160px;
            height: 160px;
            object-fit: contain;
        }
        .timer-warning {
            animation: pulse 1s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
            width: 100%;
        }
        .toast {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            animation: slideInRight 0.5s ease;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .toast-success {
            background: #10b981;
            color: white;
        }
        .toast-error {
            background: #ef4444;
            color: white;
        }
        .toast-info {
            background: #3b82f6;
            color: white;
        }
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        .toast-close {
            margin-left: auto;
            cursor: pointer;
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: background 0.3s;
        }
        .toast-close:hover {
            background: rgba(255,255,255,0.3);
        }
        .spinner {
            display: inline-block;
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
        /* Main content agar footer menempel di bawah */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>
    <!-- BEGIN: Page Wrapper -->
    <div class="page-wrapper">
        <!-- BEGIN: MainContainer with Gradient -->
        <div class="w-full main-container-rounded gradient-bg flex flex-col min-h-screen">

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
                        <span class="absolute -bottom-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] px-2 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">Bantuan</span>
                    </a>
                </div>
            </header>
            <!-- END: MainHeader -->

            <!-- BEGIN: Main Content -->
            <main class="main-content relative px-6 sm:px-8 pt-8 pb-8 overflow-hidden bg-white/40 backdrop-blur-sm">
                
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <div class="text-center mb-8">
                    <h2 class="text-2xl font-extrabold mb-1 text-slate-800">Pembayaran Booking CuitBox Studio</h2>
                    <p class="text-[10px] text-slate-600 font-medium">Segera lakukan pembayaran untuk Booking Photo Anda.</p>
                </div>

                <!-- FORM PEMBAYARAN -->
                <form action="/payment/store" method="post" id="paymentForm">
                    <?= csrf_field() ?>
                    
                    <!-- HIDDEN INPUTS -->
                    <?php 
                    $bookingId = $booking['id'] ?? session()->get('booking_id') ?? 1;
                    $amount = $booking['total_amount'] ?? 45000;
                    ?>
                    <input type="hidden" name="payment_id" value="<?= 'PAY' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) ?>">
                    <input type="hidden" name="booking_id" value="<?= $bookingId ?>">
                    <input type="hidden" name="amount" value="<?= $amount ?>">
                    <input type="hidden" name="status" value="success">
                    <input type="hidden" name="payment_method" value="QRIS">

                    <div class="flex flex-col lg:flex-row gap-6">
                        <!-- Left Column -->
                        <div class="flex-1">
                            <div class="bg-white/90 rounded-2xl p-6 shadow-sm">
                                <div class="flex justify-between items-start mb-6">
                                    <h3 class="text-lg font-bold text-slate-800">Detail Booking</h3>
                                    <span class="bg-indigo-50 text-indigo-600 text-[10px] font-bold px-3 py-1 rounded-md border border-indigo-100">
                                        ID #<?= $booking['booking_code'] ?? 'GPB-8829' ?>
                                    </span>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="bg-indigo-600 p-3 rounded-xl shadow-md shadow-indigo-100">
                                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-extrabold text-indigo-500 uppercase tracking-widest">Booth Terpilih</p>
                                            <p class="text-slate-800 font-bold text-base"><?= $booth['name'] ?? 'Retro Vibes' ?></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <div class="bg-cyan-600 p-3 rounded-xl shadow-md shadow-cyan-100">
                                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-extrabold text-cyan-500 uppercase tracking-widest">Jadwal Sesi</p>
                                            <p class="text-slate-800 font-bold text-base"><?= $booking['booking_date'] ?? 'Sabtu, 24 Mei 2024' ?> • <?= $booking['booking_time'] ?? '19:00 WIB' ?></p>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-6 border-dashed border-slate-200">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-slate-800">Total Tagihan</span>
                                    <span class="text-2xl font-black text-indigo-700">Rp <?= number_format($amount, 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: QR Code -->
                        <div class="flex-1">
                            <div class="bg-white/90 rounded-2xl p-6 shadow-sm flex flex-col items-center">
                                <div class="barcode-wrapper">
                                    <div class="barcode-container">
                                        <img alt="QR Code Barcode" src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=PAYMENT-<?= date('Ymd') ?>-CUITBOX-<?= $bookingId ?>" class="w-40 h-40">
                                    </div>
                                </div>
                                <div class="bg-indigo-50/50 w-full rounded-2xl p-4 mt-4 mb-4 text-center border border-indigo-100/50">
                                    <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mb-1">Scan Qris dan Lakukan Pembayaran</p>
                                    <p class="text-2xl font-black text-slate-800">Rp <?= number_format($amount, 0, ',', '.') ?></p>
                                </div>
                                <div class="w-full bg-[#D9F99D] rounded-2xl py-3 flex items-center justify-center space-x-2 text-slate-800 font-bold mb-3" id="timer-container">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                    </svg>
                                    <span class="text-sm" id="timer-display">Sesi berakhir dalam: 05:00</span>
                                </div>
                                <p class="text-[10px] text-slate-700 text-center leading-relaxed font-medium">
                                    *Dapat dibayar melalui semua aplikasi m-banking dan e-wallet.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Action Buttons -->
                    <div class="mt-6 flex flex-col sm:flex-row gap-4">
                        <a href="/booth" class="btn-back flex-1 text-center no-underline">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali
                        </a>
                        <button type="submit" class="btn-primary flex-1" id="confirmPaymentBtn">
                            <span id="btnText">Konfirmasi Pembayaran</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" id="btnIcon">
                                <path clip-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.64.304 1.24.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" fill-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </main>
            <!-- END: Main Content -->

            <!-- BEGIN: Footer -->
            <footer class="flex flex-col sm:flex-row items-center justify-between px-6 sm:px-8 py-4 footer-bg border-t border-gray-100 text-slate-500 text-xs font-medium gap-2 sm:gap-0">
                <div class="flex items-center gap-2">
                    <svg class="h-3 w-3 sm:h-4 sm:w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    </svg>
                    <span class="font-semibold text-slate-600">Connected</span>
                </div>
                <div class="text-center">© 2026 CuitBox Studio. All rights reserved.</div>
            </footer>
            <!-- END: Footer -->

        </div>
        <!-- END: MainContainer -->
    </div>
    <!-- END: Page Wrapper -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // COUNTDOWN TIMER
            let totalSeconds = 300;
            const timerDisplay = document.getElementById('timer-display');
            const timerContainer = document.getElementById('timer-container');
            let timerInterval;
            
            function formatTime(seconds) {
                const mins = Math.floor(seconds / 60);
                const secs = seconds % 60;
                return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
            }
            
            function updateTimer() {
                if (totalSeconds <= 0) {
                    timerDisplay.textContent = '⏰ Sesi telah berakhir!';
                    timerContainer.classList.add('timer-warning');
                    timerContainer.style.background = '#ef4444';
                    timerContainer.style.color = 'white';
                    clearInterval(timerInterval);
                    
                    const btn = document.getElementById('confirmPaymentBtn');
                    btn.disabled = true;
                    btn.style.opacity = '0.5';
                    btn.style.cursor = 'not-allowed';
                    document.getElementById('btnText').textContent = 'Sesi Berakhir';
                    document.getElementById('btnIcon').style.display = 'none';
                    return;
                }
                
                timerDisplay.textContent = `Sesi berakhir dalam: ${formatTime(totalSeconds)}`;
                
                if (totalSeconds <= 60) {
                    timerContainer.classList.add('timer-warning');
                    timerContainer.style.background = '#f59e0b';
                } else {
                    timerContainer.classList.remove('timer-warning');
                    timerContainer.style.background = '#D9F99D';
                }
                
                totalSeconds--;
            }
            
            timerInterval = setInterval(updateTimer, 1000);
            updateTimer();

            // TOAST NOTIFICATION
            window.showToast = function(message, type = 'success') {
                const container = document.getElementById('toastContainer');
                const toast = document.createElement('div');
                const icons = { success: '✅', error: '❌', info: 'ℹ️' };
                toast.className = `toast toast-${type}`;
                toast.innerHTML = `
                    <span class="text-xl">${icons[type] || 'ℹ️'}</span>
                    <span class="flex-1">${message}</span>
                    <button class="toast-close" onclick="this.parentElement.remove()">×</button>
                `;
                container.appendChild(toast);
                
                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.style.opacity = '0';
                        toast.style.transition = 'opacity 0.5s ease';
                        setTimeout(() => toast.remove(), 500);
                    }
                }, 5000);
            };

            // FLASH MESSAGES
            <?php if (session()->getFlashdata('success')): ?>
                showToast('<?= session()->getFlashdata('success') ?>', 'success');
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('error')): ?>
                showToast('<?= session()->getFlashdata('error') ?>', 'error');
            <?php endif; ?>
        });
    </script>
</body>
</html>