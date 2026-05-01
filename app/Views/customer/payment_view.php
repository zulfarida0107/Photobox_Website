<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Photobox - Metode Pembayaran</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="bg-background text-on-background font-body-md min-h-screen flex flex-col">

<!-- HEADER PUTIH (RAPI) -->
<div class="header">
    <div class="logo">Photobox</div>
    <div class="header-right">
        <!-- Language Button -->
        <button class="header-btn" onclick="alert('Language selection')">
            <span>🌐</span>
            <span>ID</span>
        </button>
        
        <!-- Help Button -->
        <button class="header-btn" onclick="alert('Help - Contact support@photobox.com')">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="10" stroke="#008393" stroke-width="2"/>
                <path d="M9 9C9 7.89543 9.89543 7 11 7H13C14.1046 7 15 7.89543 15 9C15 10.1046 14.1046 11 13 11H12V13" stroke="#008393" stroke-width="2" stroke-linecap="round"/>
                <circle cx="12" cy="17" r="1" fill="#008393"/>
            </svg>
            <span>Help</span>
        </button>
    </div>
</div>

<style>
/* ============================================================ */
/* HEADER PUTIH
/* ============================================================ */
.header {
    background: white;
    padding: 16px 48px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    position: relative;
    z-index: 20;
}

.logo {
    font-size: 24px;
    font-weight: 600;
    color: #008393;
    letter-spacing: 1px;
}

.header-right {
    display: flex;
    gap: 16px;
}

.header-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    background: transparent;
    border: 1px solid #e2e8f0;
    padding: 8px 16px;
    border-radius: 40px;
    font-size: 14px;
    font-weight: 500;
    color: #008393;
    cursor: pointer;
    transition: all 0.2s;
}

.header-btn:hover {
    background: #f1f5f9;
    border-color: #008393;
}

/* Responsive Header */
@media (max-width: 640px) {
    .header {
        padding: 12px 20px;
    }
    .logo {
        font-size: 18px;
    }
    .header-btn {
        padding: 6px 12px;
        font-size: 12px;
    }
}
</style>

<!-- Main Content Area - Background Hijau -->
<main class="flex-grow pt-32 pb-16 px-gutter flex items-center justify-center bg-[#008393]">
    <div class="max-w-4xl w-full">
        <div class="mb-10 text-center">
          <h1 class="text-2xl md:text-4xl text-white mb-4 font-bold">Metode Pembayaran</h1>
<p class="text-xl md:text-1xl text-white/90 font-semibold">Scan pembayaran di bawah ini untuk memulai sesi foto anda</p>
        </div>

        <!-- Single Column Layout (Tanpa Left Section) -->
        <div class="flex justify-center">
            <!-- QR Focus Section - Full Width -->
            <div class="w-full max-w-2xl glass-panel p-8 rounded-xl border border-outline-variant bg-white shadow-xl flex flex-col items-center">
                <div class="w-full flex justify-between items-start mb-8">
                    <div>
                    <h2 class="font-h2 text-h2 text-[#008393] font-bold">Scan untuk Pembayaran</h2>
<p class="font-body-md text-body-md text-[#008393]">Total Pembayaran: <span class="font-bold text-[#008393]">Rp 50.000</span></p>
                    </div>
                </div>

                <!-- QR Code Container -->
                <div class="relative p-6 bg-white border-4 border-[#008393]/10 rounded-2xl mb-8 group">
                    <div class="absolute -top-3 -left-3 w-8 h-8 border-t-4 border-l-4 border-[#008393] rounded-tl-lg"></div>
                    <div class="absolute -top-3 -right-3 w-8 h-8 border-t-4 border-r-4 border-[#008393] rounded-tr-lg"></div>
                    <div class="absolute -bottom-3 -left-3 w-8 h-8 border-b-4 border-l-4 border-[#008393] rounded-bl-lg"></div>
                    <div class="absolute -bottom-3 -right-3 w-8 h-8 border-b-4 border-r-4 border-[#008393] rounded-br-lg"></div>
                    <div class="bg-white p-2">
                        <img alt="QR Code" id="qrImage" class="w-60 h-60" src="https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=PHOTOBOX-<?php echo uniqid(); ?>">
                    </div>
                </div>

                <div class="flex flex-col items-center gap-6 w-full max-w-sm">
                    <div class="flex items-center gap-3 text-[#008393]">
                        <span class="material-symbols-outlined">schedule</span>
                        <span class="font-label-md text-label-md" id="qrTimer">QR berlaku selama 04:59</span>
                    </div>
                    <form id="paymentForm" action="/customer/payment/process" method="POST" class="w-full">
                        <input type="hidden" name="booking_id" value="<?php echo isset($booking) ? $booking['id'] : 'DEMO001'; ?>">
                        <input type="hidden" name="payment_method" value="qris">
                        <button type="submit" class="w-full py-5 bg-[#008393] text-white rounded-xl font-h3 text-h3 shadow-lg shadow-[#008393]/30 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-3">
                            <span class="material-symbols-outlined">payments</span>
                            Bayar Sekarang
                        </button>
                    </form>
                   <p class="font-body-sm text-body-sm text-[#008393] text-center px-4">
    Dengan mengklik Bayar Sekarang, Anda menyetujui persyaratan layanan kami. Foto Anda akan siap segera setelah konfirmasi pembayaran.
</p>
                </div>
            </div>
        </div>
    </div>
</main>


<!-- Footer -->
<footer class="bg-slate-50 border-t border-slate-200 flex flex-col md:flex-row justify-between items-center w-full px-10 py-6">
    <div class="font-inter text-sm text-slate-500 mb-4 md:mb-0">
        © 2026 Photobox Systems. Hak Cipta Dilindungi.
    </div>
    <div class="flex gap-8">
        <a class="font-inter text-sm text-slate-400 hover:text-slate-900 transition-colors duration-200" href="#">Bantuan</a>
        <a class="font-inter text-sm text-slate-400 hover:text-slate-900 transition-colors duration-200" href="#">Kebijakan Privasi</a>
        <a class="font-inter text-sm text-slate-400 hover:text-slate-900 transition-colors duration-200" href="#">Ketentuan Layanan</a>
    </div>
</footer>

<script>
    // Countdown timer for QR expiry
    let timeLeft = 300; // 5 minutes
    const timerDisplay = document.getElementById('qrTimer');
    const qrImage = document.getElementById('qrImage');
    
    function formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }
    
    function updateTimer() {
        if (timeLeft <= 0) {
            timerDisplay.textContent = 'QR kadaluarsa. Silakan refresh halaman.';
            timerDisplay.style.color = '#dc2626';
        } else {
            timerDisplay.textContent = `QR berlaku selama ${formatTime(timeLeft)}`;
            timeLeft--;
            setTimeout(updateTimer, 1000);
        }
    }
    
    updateTimer();
    
    // Auto-refresh QR every 4 minutes
    setInterval(() => {
        if (timeLeft > 60) {
            const newData = 'PHOTOBOX-' + Date.now();
            qrImage.src = 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=' + newData;
        }
    }, 240000);
</script>

</body>
</html>