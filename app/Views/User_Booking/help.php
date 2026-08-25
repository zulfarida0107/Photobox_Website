<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Pusat Bantuan - CuitBox Studio</title>
    
    <!-- Tailwind CSS v3 CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Google Fonts for Typography -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
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
                            blue: '#00B4D8',
                            darkBlue: '#0077B6',
                            lightGreen: '#C6FF00',
                            pastelPurple: '#E0AAFF',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #e0f2fe 0%, #fce7f3 100%);
            background-attachment: fixed;
        }
        .header-bg {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
        }
        .footer-bg {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .moment-highlight {
            background-color: #4A5568;
            color: white;
            padding: 4px 12px;
            border-radius: 6px;
            display: inline-block;
            line-height: 1.2;
        }
        .help-card {
            transition: transform 0.2s ease-in-out, border-color 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            border-radius: 20px;
        }
        .help-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 35px rgba(0, 0, 0, 0.06);
        }
        .btn-brand {
            transition: all 0.3s ease;
            border-radius: 9999px;
            cursor: pointer;
            background: #d9f99d;
            color: #1e293b;
            font-weight: 700;
            padding: 12px 36px;
            border: none;
            box-shadow: 0 10px 15px -3px rgba(190, 242, 100, 0.45);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-brand:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 25px -5px rgba(190, 242, 100, 0.55);
        }
        .btn-brand:active {
            transform: scale(0.95);
        }
        .contact-list {
            list-style: none;
            padding-left: 0;
        }
        .contact-list li {
            padding: 10px 0;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #f1f5f9;
        }
        .contact-list li:last-child {
            border-bottom: none;
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
            font-size: 11px;
            font-weight: 600;
            line-height: 1.35;
            pointer-events: auto;
            cursor: grab;
            user-select: none;
            touch-action: none;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }
        .toast:active {
            cursor: grabbing;
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
        @keyframes slideInDown {
            from {
                transform: translateY(-20px) scale(0.95);
                opacity: 0;
            }
            to {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }
    </style>
</head>
<body class="font-sans antialiased text-slate-800">
    <div id="toastContainer" class="toast-container"></div>
    <div class="page-wrapper">
        <div class="w-full gradient-bg flex flex-col min-h-screen">

            <!-- Header -->
            <header class="flex items-center justify-between px-6 sm:px-8 py-4 header-bg border-b border-gray-100">
                <div class="flex items-center">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">CuitBox Studio</h1>
                </div>
                <button onclick="handleGoBack()" class="flex items-center gap-2 text-slate-600 hover:text-slate-900 transition-colors font-semibold text-sm cursor-pointer">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                    <span>Kembali</span>
                </button>
            </header>

            <!-- Content -->
            <main class="flex-1 px-6 sm:px-8 py-8 bg-white/40 backdrop-blur-sm">
                <div class="max-w-4xl mx-auto">
                    <!-- Section Header -->
                    <div class="text-center mb-10 relative">
                        <div class="mb-4 inline-block">
                            <span class="text-[10px] font-extrabold px-3.5 py-1.5 rounded-full text-slate-800 uppercase tracking-wider" style="background:#d9f99d;">PUSAT BANTUAN</span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-800 tracking-tight leading-snug sm:leading-relaxed py-2 my-2">
                            Ada Pertanyaan? <span class="moment-highlight">Kami Siap</span> Membantu
                        </h2>
                        <p class="text-slate-500 text-sm sm:text-base mt-3">Temukan jawaban seputar pemesanan, sesi foto, dan layanan CuitBox Studio.</p>
                    </div>

                    <!-- FAQ Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10">
                        
                        <!-- Card 1: Cara Booking -->
                        <div class="help-card bg-white p-6 shadow-sm border border-slate-200/80 hover:border-slate-300" onclick="showAnswer(this)">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-slate-100 text-slate-700">
                                    <span class="material-symbols-outlined text-xl">calendar_today</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-slate-800 text-base">Bagaimana cara booking?</h4>
                                    <div class="text-sm text-slate-600 mt-2 hidden answer">
                                        <ol class="list-decimal list-inside space-y-1.5 font-medium text-xs sm:text-sm text-slate-600">
                                            <li>Klik tombol <span class="font-bold text-slate-800">"Start Booking"</span> di halaman utama</li>
                                            <li>Buat Akun atau Login jika sudah memiliki akun</li>
                                            <li>Pilih Tanggal dan Sesi Waktu booking (Schedule)</li>
                                            <li>Pilih Jumlah / Paket Cetak Foto (Print Option)</li>
                                            <li>Lakukan pembayaran melalui QRIS dan simpan kode booking Anda</li>
                                        </ol>
                                    </div> 
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Metode Pembayaran -->
                        <div class="help-card bg-white p-6 shadow-sm border border-slate-200/80 hover:border-slate-300" onclick="showAnswer(this)">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-slate-100 text-slate-700">
                                    <span class="material-symbols-outlined text-xl">qr_code_scanner</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-slate-800 text-base">Metode pembayaran apa saja?</h4>
                                    <div class="text-sm text-slate-600 mt-2 hidden answer">
                                        <p class="font-bold text-slate-700 mb-1">QRIS Terintegrasi</p>
                                        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                            Dapat dibayar secara instan menggunakan aplikasi Mobile Banking (BCA, Mandiri, BRI, BNI, dll.) atau E-Wallet (GoPay, OVO, DANA, ShopeePay).
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Reschedule & Pembatalan -->
                        <div class="help-card bg-white p-6 shadow-sm border border-slate-200/80 hover:border-slate-300" onclick="showAnswer(this)">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-slate-100 text-slate-700">
                                    <span class="material-symbols-outlined text-xl">update</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-slate-800 text-base">Bisa reschedule atau refund?</h4>
                                    <div class="text-sm text-slate-600 mt-2 hidden answer space-y-2">
                                        <p class="font-semibold text-slate-800">Bisa, dengan ketentuan waktu berikut:</p>
                                        <ul class="list-disc list-inside space-y-1 text-xs text-slate-600">
                                            <li><strong class="text-slate-800">Konfirmasi Wajib H-1:</strong> Permohonan reschedule atau pembatalan harus dikonfirmasi maksimal H-1 sebelum pukul 00:00 WIB hari jadwal booking Anda.</li>
                                            <li><strong class="text-slate-800">Ketentuan Pengembalian Dana (Refund):</strong>
                                                <div class="pl-4 mt-1 space-y-1 text-slate-500">
                                                    <p>Booking tanggal 26, jika pembatalan diajukan pada tanggal 25 sebelum pukul 00:00 WIB (23:59 WIB), dana <strong>dapat dikembalikan</strong>.</p>
                                                    <p>Jika telah melewati pukul 00:00 WIB (mulai pukul 00:01 WIB di hari-H tanggal 26), maka dana <strong>tidak dapat dikembalikan</strong>.</p>
                                                </div>
                                            </li>
                                        </ul>
                                        <p class="text-xs text-slate-500 pt-1">Silakan hubungi kami via DM Instagram <a href="https://instagram.com/cuitbox.studio" target="_blank" class="text-slate-800 font-bold underline">@cuitbox.studio</a> atau kirim pesan melalui formulir di bawah.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 4: Toleransi Keterlambatan (Lime Green Highlighted Theme) -->
                        <div class="help-card bg-white p-6 shadow-sm border-2 border-lime-300 hover:border-lime-500 relative overflow-hidden" onclick="showAnswer(this)">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-[#d9f99d] text-lime-950 font-bold">
                                    <span class="material-symbols-outlined text-xl">schedule</span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <h4 class="font-bold text-slate-800 text-base">Bagaimana jika terlambat datang?</h4>
                                        <span class="text-[10px] font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider border border-lime-400 shadow-sm text-lime-950" style="background:#d9f99d;">TOLERANSI 15 MENIT</span>
                                    </div>
                                    <div class="text-sm text-slate-600 mt-2 hidden answer space-y-1.5">
                                        <p class="text-xs sm:text-sm text-slate-700 leading-relaxed font-medium">
                                            Kami menyediakan <span class="bg-lime-100 text-lime-900 px-1.5 py-0.5 rounded font-bold">toleransi keterlambatan maksimal 15 menit</span> dari jam sesi booking Anda.
                                        </p>
                                        <div class="p-2.5 rounded-lg bg-lime-50 border border-lime-200 text-xs text-lime-950 space-y-1">
                                            <p>• Jika hadir dalam batas waktu toleransi, Anda tetap dapat berfoto menggunakan sisa durasi sesi Anda.</p>
                                            <p>• Keterlambatan lebih dari 15 menit dapat menyebabkan sesi dibatalkan agar tidak menggeser antrean pelanggan berikutnya.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 5: Durasi & Hasil Sesi Foto -->
                        <div class="help-card bg-white p-6 shadow-sm border border-slate-200/80 hover:border-slate-300" onclick="showAnswer(this)">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-slate-100 text-slate-700">
                                    <span class="material-symbols-outlined text-xl">photo_camera</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-slate-800 text-base">Bagaimana sesi & hasilnya?</h4>
                                    <div class="text-sm text-slate-600 mt-2 hidden answer space-y-2">
                                        <ul class="list-disc list-inside space-y-1 text-xs text-slate-600">
                                            <li><strong class="text-slate-800">Proses Sesi Foto:</strong> Dalam 1 sesi foto, terdapat 10 kali jepretan dengan jeda waktu hitung mundur 10 detik per jepretan untuk Anda bergaya.</li>
                                            <li><strong class="text-slate-800">Hasil yang Anda Dapatkan:</strong>
                                                <div class="pl-4 mt-1 space-y-1 text-slate-500">
                                                    <p>• 10 File foto original (softfile mentah) beresolusi tinggi.</p>
                                                    <p>• 1 File foto dengan tema frame yang Anda pilih di mesin.</p>
                                                    <p>• Lembar cetak fisik foto strip sesuai paket cetak.</p>
                                                </div>
                                            </li>
                                            <li><strong class="text-slate-800">Pengiriman Digital:</strong> Seluruh file foto digital otomatis dikirimkan langsung ke email terdaftar Anda setelah sesi selesai.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 6: Kontak Resmi -->
                        <div class="help-card bg-white p-6 shadow-sm border border-slate-200/80 hover:border-slate-300" onclick="showAnswer(this)">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-slate-100 text-slate-700">
                                    <span class="material-symbols-outlined text-xl">alternate_email</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-slate-800 text-base">Kontak Resmi CuitBox</h4>
                                    <div class="text-sm text-slate-600 mt-2 hidden answer">
                                        <p class="font-medium text-slate-700 mb-2 text-xs">Hubungi kami melalui kanal resmi berikut:</p>
                                        <ul class="contact-list text-xs sm:text-sm">
                                            <li>
                                                <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 flex-shrink-0">
                                                    <span class="material-symbols-outlined text-sm">photo_camera</span>
                                                </div>
                                                <div>
                                                    <span class="font-bold text-slate-800">Instagram Resmi</span>
                                                    <br>
                                                    <a href="https://instagram.com/cuitbox.studio" target="_blank" class="text-slate-600 hover:text-slate-900 font-semibold underline">@cuitbox.studio</a>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 flex-shrink-0">
                                                    <span class="material-symbols-outlined text-sm">mail</span>
                                                </div>
                                                <div>
                                                    <span class="font-bold text-slate-800">Email Resmi</span>
                                                    <br>
                                                    <a href="mailto:cuitboxstudio@gmail.com" class="text-slate-600 hover:text-slate-900 font-semibold underline">cuitboxstudio@gmail.com</a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Contact Form Card -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm max-w-2xl mx-auto border border-slate-200/80 relative overflow-hidden">
                        <div class="mb-6">
                            <span class="text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider text-slate-800" style="background:#d9f99d;">FORMULIR PESAN</span>
                            <h3 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight mt-2">Kirim Pertanyaan ke Admin</h3>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1">Isi formulir di bawah ini dan tim kami akan membalas melalui email Anda.</p>
                        </div>
                        
                        <form id="helpForm" action="<?= base_url('help/send') ?>" method="POST">
                            <?= csrf_field() ?>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-lime-400 focus:border-lime-400 outline-none transition-all" placeholder="Nama lengkap Anda" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Email Anda <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-lime-400 focus:border-lime-400 outline-none transition-all" placeholder="email@contoh.com" required>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Subjek Pertanyaan <span class="text-red-500">*</span></label>
                                <input type="text" name="subject" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-lime-400 focus:border-lime-400 outline-none transition-all" placeholder="Misal: Kendala Booking / Reschedule" required>
                            </div>
                            
                            <div class="mt-4">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Pesan Lengkap <span class="text-red-500">*</span></label>
                                <textarea name="message" rows="4" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-lime-400 focus:border-lime-400 outline-none transition-all" placeholder="Tuliskan pertanyaan atau kendala Anda di sini..." required></textarea>
                            </div>
                            
                            <div class="mt-6 text-center">
                                <button type="submit" id="btnSubmitHelp" class="btn-brand w-full sm:w-auto">
                                    <span id="btnSubmitText">Kirim Pesan</span>
                                    <span class="material-symbols-outlined text-lg" id="btnSubmitIcon">send</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>

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

    <script>
        // Toast notification system
        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerText = message;
            container.appendChild(toast);

            let autoTimer = setTimeout(() => dismissToast(), 3500);

            const dismissToast = () => {
                clearTimeout(autoTimer);
                toast.style.transition = 'transform 0.25s ease, opacity 0.25s ease';
                toast.style.transform = 'translateY(-30px)';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 250);
            };

            // Swipe to dismiss
            let startY = 0;
            let currentY = 0;
            let isDragging = false;

            const onStart = (clientY) => {
                isDragging = true;
                startY = clientY;
                currentY = clientY;
                clearTimeout(autoTimer);
                toast.style.transition = 'none';
            };

            const onMove = (clientY) => {
                if (!isDragging) return;
                currentY = clientY;
                const diffY = currentY - startY;
                if (diffY < 0) {
                    toast.style.transform = `translateY(${diffY}px)`;
                    toast.style.opacity = `${1 + diffY / 80}`;
                }
            };

            const onEnd = (clientY) => {
                if (!isDragging) return;
                isDragging = false;
                const diffY = clientY - startY;
                if (diffY < -15) {
                    dismissToast();
                } else {
                    toast.style.transition = 'transform 0.2s ease, opacity 0.2s ease';
                    toast.style.transform = 'translateY(0)';
                    toast.style.opacity = '1';
                    autoTimer = setTimeout(dismissToast, 3500);
                }
            };

            // Touch events
            toast.addEventListener('touchstart', (e) => onStart(e.touches[0].clientY), { passive: true });
            toast.addEventListener('touchmove', (e) => onMove(e.touches[0].clientY), { passive: true });
            toast.addEventListener('touchend', (e) => onEnd(e.changedTouches[0].clientY));

            // Mouse events
            toast.addEventListener('mousedown', (e) => {
                onStart(e.clientY);
                const moveHandler = (ev) => onMove(ev.clientY);
                const upHandler = (ev) => {
                    onEnd(ev.clientY);
                    document.removeEventListener('mousemove', moveHandler);
                    document.removeEventListener('mouseup', upHandler);
                };
                document.addEventListener('mousemove', moveHandler);
                document.addEventListener('mouseup', upHandler);
            });
        }

        // Show flash messages if loaded with flashdata
        <?php if (session()->getFlashdata('success')): ?>
            showToast('<?= session()->getFlashdata('success') ?>', 'success');
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            showToast('<?= session()->getFlashdata('error') ?>', 'error');
        <?php endif; ?>

        // AJAX Form Submit without page glitch / refresh
        const helpForm = document.getElementById('helpForm');
        if (helpForm) {
            helpForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const btn = document.getElementById('btnSubmitHelp');
                const btnText = document.getElementById('btnSubmitText');
                const btnIcon = document.getElementById('btnSubmitIcon');

                // Set loading state
                if (btn) {
                    btn.disabled = true;
                    btn.classList.add('opacity-75', 'cursor-not-allowed');
                }
                if (btnText) btnText.textContent = 'Mengirim...';
                if (btnIcon) btnIcon.textContent = 'hourglass_top';

                const formData = new FormData(helpForm);

                fetch(helpForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast(data.message || 'Pesan Anda berhasil dikirim! Tim kami akan segera merespon.', 'success');
                        helpForm.reset();
                    } else {
                        showToast(data.message || 'Gagal mengirim pesan. Silakan coba lagi.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error sending help message:', error);
                    showToast('Terjadi kesalahan jaringan. Silakan coba lagi.', 'error');
                })
                .finally(() => {
                    if (btn) {
                        btn.disabled = false;
                        btn.classList.remove('opacity-75', 'cursor-not-allowed');
                    }
                    if (btnText) btnText.textContent = 'Kirim Pesan';
                    if (btnIcon) btnIcon.textContent = 'send';
                });
            });
        }

        function closeAllAnswers() {
            document.querySelectorAll('.answer').forEach(a => {
                a.classList.add('hidden');
                a.style.display = 'none';
            });
        }

        function handleGoBack() {
            closeAllAnswers();
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = '<?= base_url() ?>';
            }
        }

        function showAnswer(element) {
            const answer = element.querySelector('.answer');
            if (answer) {
                if (answer.classList.contains('hidden') || answer.style.display === 'none') {
                    // Sembunyikan semua jawaban lain terlebih dahulu
                    closeAllAnswers();
                    answer.classList.remove('hidden');
                    answer.style.display = 'block';
                } else {
                    answer.classList.add('hidden');
                    answer.style.display = 'none';
                }
            }
        }

        // Pastikan saat halaman dimuat maupun diakses kembali dari cache (bfcache), semua FAQ tertutup
        document.addEventListener('DOMContentLoaded', closeAllAnswers);
        window.addEventListener('pageshow', closeAllAnswers);
    </script>
</body>
</html>