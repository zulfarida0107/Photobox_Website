<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?= esc($title ?? 'Pembayaran - CuitBox Studio') ?></title>
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
            text-decoration: none;
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
        .btn-secondary {
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
            text-decoration: none;
        }
        .btn-secondary:hover {
            background: #334155;
            transform: scale(1.02);
        }
        .btn-secondary:active {
            transform: scale(0.98);
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
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
            top: 28px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            max-width: 340px;
            width: calc(100% - 32px);
            pointer-events: none;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .toast {
            padding: 10px 16px;
            border-radius: 10px;
            margin-bottom: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            animation: slideInDown 0.35s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.35;
            pointer-events: auto;
        }
        .toast-success { background: #10b981; color: white; }
        .toast-error { background: #ef4444; color: white; }
        .toast-info { background: #3b82f6; color: white; }
        @keyframes slideInDown {
            from { transform: translateY(-20px) scale(0.95); opacity: 0; }
            to { transform: translateY(0) scale(1); opacity: 1; }
        }
        .payment-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }
        .badge-payment {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 6px 12px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
            color: #475569;
        }
        .qr-container {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        .qr-container img {
            width: 220px;
            height: 220px;
            margin: 0 auto;
            display: block;
        }
        @keyframes qrPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4); }
            50% { box-shadow: 0 0 0 12px rgba(99, 102, 241, 0); }
        }
        .qr-active {
            animation: qrPulse 2s ease-in-out infinite;
        }
        .status-checking {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #6366f1;
            font-weight: 600;
        }
        .status-checking .dot {
            width: 6px;
            height: 6px;
            background: #6366f1;
            border-radius: 50%;
            animation: dotPulse 1.4s ease-in-out infinite;
        }
        .status-checking .dot:nth-child(2) { animation-delay: 0.2s; }
        .status-checking .dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes dotPulse {
            0%, 100% { opacity: 0.3; transform: scale(0.8); }
            50% { opacity: 1; transform: scale(1.2); }
        }
    </style>
</head>
<body>
    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container"></div>

    <div class="page-wrapper">
        <div class="w-full main-container-rounded gradient-bg flex flex-col min-h-screen">

            <!-- BEGIN: Header -->
            <header class="flex items-center justify-between px-6 sm:px-8 py-4 header-bg border-b border-gray-100">
                <div class="flex items-center">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">CuitBox Studio</h1>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-4 text-slate-600">
                    <a href="<?= base_url('help') ?>" class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center rounded-full hover:bg-gray-100/50 transition-all relative group" aria-label="Help">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                        </svg>
                        <span class="absolute -bottom-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] px-2 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">Bantuan</span>
                    </a>
                </div>
            </header>
            <!-- END: Header -->

            <!-- BEGIN: Main Content -->
            <main class="flex-1 px-6 sm:px-8 py-8 overflow-hidden bg-white/40 backdrop-blur-sm">
                
                <!-- Flash Messages -->
                <?php if (session()->getFlashdata('success') || !empty($success)): ?>
                    <div class="mb-6 p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-2xl flex items-center gap-3">
                        <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                        <span class="font-medium text-sm"><?= session()->getFlashdata('success') ?? $success ?></span>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error') || !empty($error)): ?>
                    <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-800 rounded-2xl flex items-center gap-3">
                        <span class="material-symbols-outlined text-red-600">error</span>
                        <span class="font-medium text-sm"><?= session()->getFlashdata('error') ?? $error ?></span>
                    </div>
                <?php endif; ?>

                <?php 
                $bookingId = $booking['id'] ?? session()->get('booking_id') ?? '';
                $totalAmount = $booking['total_amount'] ?? ($payment['amount'] ?? 45000);
                $paymentId = $payment['id'] ?? '';
                ?>

                <div class="max-w-4xl mx-auto flex flex-col lg:flex-row gap-6">
                    <!-- Left Column - Detail Pesanan -->
                    <div class="flex-1">
                        <div class="payment-card mb-6">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-800">Ringkasan Pemesanan</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Periksa detail pesanan Anda sebelum membayar</p>
                                </div>
                                <span class="bg-indigo-50 text-indigo-600 text-xs font-bold px-3 py-1.5 rounded-lg border border-indigo-100">
                                    #<?= esc($booking['booking_code'] ?? 'BK-' . date('Ymd')) ?>
                                </span>
                            </div>

                            <div class="space-y-4">
                                <!-- Lokasi Booth -->
                                <div class="flex items-center space-x-4 p-3 bg-white/70 rounded-xl border border-slate-100">
                                    <div class="bg-indigo-50 p-3 rounded-xl text-indigo-600">
                                        <span class="material-symbols-outlined text-xl">store</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-[10px] font-extrabold text-indigo-500 uppercase tracking-widest">Booth Studio</p>
                                        <p class="text-slate-800 font-bold text-sm sm:text-base"><?= esc($booth['name'] ?? ($frame['name'] ?? 'Premium Booth Studio')) ?></p>
                                    </div>
                                </div>

                                <!-- Desain Frame & Opsi Cetak -->
                                <div class="flex items-center space-x-4 p-3 bg-white/70 rounded-xl border border-slate-100">
                                    <div class="bg-emerald-50 p-3 rounded-xl text-emerald-600">
                                        <span class="material-symbols-outlined text-xl">photo_library</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-[10px] font-extrabold text-emerald-500 uppercase tracking-widest">Pilihan Cetak & Frame</p>
                                        <p class="text-slate-800 font-bold text-sm sm:text-base">
                                            <?= esc(session()->get('selected_print_option_name') ?? '2 Lembar Cetak') ?> 
                                            <span class="text-slate-500 font-normal text-xs">• <?= esc($frame['name'] ?? 'Frame Pilihan') ?></span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Jadwal Sesi -->
                                <div class="flex items-center space-x-4 p-3 bg-white/70 rounded-xl border border-slate-100">
                                    <div class="bg-cyan-50 p-3 rounded-xl text-cyan-600">
                                        <span class="material-symbols-outlined text-xl">calendar_today</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-[10px] font-extrabold text-cyan-500 uppercase tracking-widest">Jadwal Sesi</p>
                                        <p class="text-slate-800 font-bold text-sm sm:text-base"><?= esc($booking['booking_date'] ?? date('d F Y')) ?> • <?= esc($booking['booking_time'] ?? date('H:i')) ?> WIB</p>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-6 border-dashed border-slate-200">

                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Total Tagihan</span>
                                    <span class="text-2xl sm:text-3xl font-black text-indigo-700">Rp <?= number_format($totalAmount, 0, ',', '.') ?></span>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block bg-emerald-100 text-emerald-700 text-[11px] font-bold px-3 py-1 rounded-full">
                                        Harga Terverifikasi
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: QRIS Payment via Bayar.gg -->
                    <div class="flex-1">
                        <div class="payment-card flex flex-col items-center">
                            <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mb-3 shadow-sm">
                                <span class="material-symbols-outlined text-3xl">qr_code_2</span>
                            </div>

                            <h4 class="text-base font-bold text-slate-800 text-center">Scan QRIS untuk Bayar</h4>
                            <p class="text-xs text-slate-500 text-center mt-1 mb-5">
                                Gunakan aplikasi m-Banking atau e-Wallet (GoPay, OVO, DANA, dll) untuk memindai kode QR
                            </p>

                            <!-- Supported Payment Badges -->
                            <div class="w-full flex flex-wrap justify-center gap-2 mb-5">
                                <span class="badge-payment">⚡ QRIS</span>
                                <span class="badge-payment">💚 GoPay</span>
                                <span class="badge-payment">💜 OVO</span>
                                <span class="badge-payment">💙 DANA</span>
                                <span class="badge-payment">🏦 m-Banking</span>
                            </div>

                            <!-- QR Code Display -->
                            <?php if (!empty($qrImageUrl)): ?>
                                <div class="qr-container qr-active mb-4" id="qrContainer">
                                    <img src="<?= esc($qrImageUrl) ?>" alt="QRIS CuitBox Studio" id="qrImage">
                                </div>

                                <div class="bg-indigo-50/80 w-full rounded-2xl p-4 text-center border border-indigo-100/50 mb-3">
                                    <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mb-1">Jumlah yang Harus Dibayar</p>
                                    <p class="text-3xl font-black text-slate-800">Rp <?= number_format($totalAmount, 0, ',', '.') ?></p>
                                    <p class="text-[11px] text-slate-500 mt-1">ID: <span class="font-mono font-bold text-slate-700"><?= esc($paymentId) ?></span></p>
                                </div>

                                <!-- Auto-check status indicator -->
                                <div class="w-full text-center mb-4" id="statusIndicator">
                                    <div class="status-checking">
                                        <span class="dot"></span>
                                        <span class="dot"></span>
                                        <span class="dot"></span>
                                        <span>Menunggu pembayaran...</span>
                                    </div>
                                </div>

                            <?php else: ?>
                                <!-- Fallback jika QR belum muncul (misal API Key belum diisi atau error) -->
                                <div class="w-full bg-amber-50 border border-amber-200 rounded-2xl p-6 text-center mb-4">
                                    <span class="material-symbols-outlined text-4xl text-amber-500 mb-2">info</span>
                                    <p class="text-sm font-bold text-amber-800">Menyiapkan QRIS</p>
                                    <p class="text-xs text-amber-600 mt-1">Silakan klik tombol di bawah untuk membuka halaman pembayaran atau muat ulang.</p>
                                </div>

                                <div class="bg-indigo-50/80 w-full rounded-2xl p-4 text-center border border-indigo-100/50 mb-3">
                                    <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mb-1">Jumlah yang Harus Dibayar</p>
                                    <p class="text-3xl font-black text-slate-800">Rp <?= number_format($totalAmount, 0, ',', '.') ?></p>
                                    <p class="text-[11px] text-slate-500 mt-1">ID: <span class="font-mono font-bold text-slate-700"><?= esc($paymentId) ?></span></p>
                                </div>
                            <?php endif; ?>

                            <!-- Timer Sesi -->
                            <div class="w-full bg-[#D9F99D] rounded-2xl py-3 flex items-center justify-center space-x-2 text-slate-800 font-bold mb-5" id="timer-container">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                </svg>
                                <span class="text-xs sm:text-sm" id="timer-display">Sesi berakhir dalam: 15:00</span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="w-full space-y-3">
                                <?php if (!empty($paymentPageUrl)): ?>
                                    <a href="<?= esc($paymentPageUrl) ?>" target="_blank" class="btn-primary" id="payButton">
                                        <span class="material-symbols-outlined text-xl">open_in_new</span>
                                        <span id="btnText">Buka Halaman Pembayaran</span>
                                    </a>
                                <?php else: ?>
                                    <button type="button" class="btn-primary" id="payButton" onclick="location.reload()">
                                        <span class="material-symbols-outlined text-xl">refresh</span>
                                        <span id="btnText">Muat Ulang Halaman</span>
                                    </button>
                                <?php endif; ?>

                                <a href="<?= base_url('print-option') ?>" class="btn-secondary">
                                    <span class="material-symbols-outlined">arrow_back</span>
                                    Ubah Opsi Cetak
                                </a>
                            </div>

                            <p class="text-[11px] text-slate-500 text-center leading-relaxed mt-4">
                                *Setelah pembayaran berhasil, halaman ini akan otomatis berpindah ke tiket booking Anda.
                            </p>
                        </div>
                    </div>
                </div>

            </main>
            <!-- END: Main Content -->

            <!-- BEGIN: Footer -->
            <footer class="flex flex-col sm:flex-row items-center justify-between px-6 sm:px-8 py-4 footer-bg border-t border-gray-100 text-slate-500 text-xs font-medium gap-2 sm:gap-0">
                <div class="flex items-center gap-2">
                    <svg class="h-3 w-3 sm:h-4 sm:w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    </svg>
                    <span class="font-semibold text-slate-700">Pembayaran QRIS Aman via Bayar.gg</span>
                </div>
                <div class="text-center">© 2026 CuitBox Studio. All rights reserved.</div>
            </footer>
            <!-- END: Footer -->

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ========== COUNTDOWN TIMER (15 menit) ==========
            let totalSeconds = 900;
            const timerDisplay = document.getElementById('timer-display');
            const timerContainer = document.getElementById('timer-container');
            const payBtn = document.getElementById('payButton');
            
            function formatTime(seconds) {
                const mins = Math.floor(seconds / 60);
                const secs = seconds % 60;
                return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
            }
            
            function updateTimer() {
                if (totalSeconds <= 0) {
                    timerDisplay.textContent = '⏰ Sesi QRIS telah berakhir!';
                    timerContainer.classList.add('timer-warning');
                    timerContainer.style.background = '#ef4444';
                    timerContainer.style.color = 'white';
                    clearInterval(timerInterval);
                    if (pollInterval) clearInterval(pollInterval);

                    if (payBtn) {
                        payBtn.style.pointerEvents = 'none';
                        payBtn.style.opacity = '0.5';
                    }
                    const btnText = document.getElementById('btnText');
                    if (btnText) btnText.textContent = 'QRIS Kedaluwarsa - Muat Ulang';
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
            
            const timerInterval = setInterval(updateTimer, 1000);
            updateTimer();

            // ========== TOAST NOTIFICATION ==========
            window.showToast = function(message, type = 'info') {
                const container = document.getElementById('toastContainer');
                const toast = document.createElement('div');
                toast.className = `toast toast-${type}`;
                toast.innerHTML = `<span>${message}</span>`;
                container.appendChild(toast);
                
                setTimeout(() => {
                    toast.style.transition = 'transform 0.25s ease, opacity 0.25s ease';
                    toast.style.transform = 'translateY(-40px)';
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 250);
                }, 4000);
            };

            // ========== AUTO-POLLING STATUS PEMBAYARAN ==========
            const paymentId = '<?= esc($paymentId ?? '', 'js') ?>';
            const bookingId = '<?= esc($bookingId ?? '', 'js') ?>';
            const redirectUrl = '<?= base_url('StatusBooking') ?>' + '?payment_id=' + encodeURIComponent(paymentId) + '&booking_id=' + encodeURIComponent(bookingId);

            let pollInterval = null;
            let pollCount = 0;
            const MAX_POLLS = 180; // 15 menit x 12 polls/menit = 180 polls

            function checkPaymentStatus() {
                if (!paymentId || pollCount >= MAX_POLLS) {
                    if (pollInterval) clearInterval(pollInterval);
                    return;
                }

                pollCount++;

                fetch('<?= base_url('api/payment') ?>' + '/' + encodeURIComponent(paymentId) + '/status', {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    const status = data?.payment?.status;

                    if (status === 'paid') {
                        if (pollInterval) clearInterval(pollInterval);
                        clearInterval(timerInterval);

                        // Update UI
                        const indicator = document.getElementById('statusIndicator');
                        if (indicator) {
                            indicator.innerHTML = `
                                <div class="flex items-center justify-center gap-2 text-emerald-600 font-bold text-sm">
                                    <span class="material-symbols-outlined">check_circle</span>
                                    <span>Pembayaran berhasil diterima!</span>
                                </div>
                            `;
                        }

                        const qrContainer = document.getElementById('qrContainer');
                        if (qrContainer) {
                            qrContainer.classList.remove('qr-active');
                            qrContainer.style.borderColor = '#10b981';
                            qrContainer.style.background = '#f0fdf4';
                        }

                        showToast('✅ Pembayaran berhasil! Mengalihkan ke tiket booking...', 'success');

                        setTimeout(function() {
                            window.location.href = redirectUrl;
                        }, 2000);
                    } else if (status === 'failed') {
                        if (pollInterval) clearInterval(pollInterval);
                        const indicator = document.getElementById('statusIndicator');
                        if (indicator) {
                            indicator.innerHTML = `
                                <div class="flex items-center justify-center gap-2 text-red-600 font-bold text-sm">
                                    <span class="material-symbols-outlined">cancel</span>
                                    <span>Pembayaran gagal / kedaluwarsa</span>
                                </div>
                            `;
                        }
                        showToast('Pembayaran gagal. Silakan muat ulang halaman untuk coba lagi.', 'error');
                    }
                })
                .catch(err => {
                    console.log('Poll retry:', err);
                });
            }

            // Mulai polling setiap 5 detik
            if (paymentId) {
                pollInterval = setInterval(checkPaymentStatus, 5000);
                setTimeout(checkPaymentStatus, 3000);
            }
        });
    </script>
</body>
</html>
