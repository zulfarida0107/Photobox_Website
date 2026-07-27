<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Pusat Bantuan - CuitBox Studio</title>
    
    <!-- Tailwind CSS v3 CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
    
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    
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
        .help-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .help-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }
        .btn-back {
            background: #1e293b;
            color: white;
            transition: all 0.3s ease;
            border-radius: 9999px;
            padding: 12px 24px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-back:hover {
            background: #334155;
            transform: scale(1.02);
        }
        .payment-method-list {
            list-style: none;
            padding-left: 0;
        }
        .payment-method-list li {
            padding: 6px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .payment-method-list li::before {
            content: "✓";
            color: #22c55e;
            font-weight: bold;
            font-size: 16px;
        }
        .contact-list {
            list-style: none;
            padding-left: 0;
        }
        .contact-list li {
            padding: 8px 0;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #f1f5f9;
        }
        .contact-list li:last-child {
            border-bottom: none;
        }
        .contact-list .icon {
            font-size: 20px;
            width: 32px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="w-full gradient-bg flex flex-col min-h-screen">

            <!-- Header -->
            <header class="flex items-center justify-between px-6 sm:px-8 py-4 header-bg border-b border-gray-100">
                <div class="flex items-center">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">CuitBox Studio</h1>
                </div>
                <button onclick="window.history.back()" class="flex items-center gap-2 text-slate-600 hover:text-slate-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span class="text-sm font-medium">Kembali</span>
                </button>
            </header>

            <!-- Content -->
            <main class="flex-1 px-6 sm:px-8 py-8 bg-white/40 backdrop-blur-sm">
                <div class="max-w-4xl mx-auto">
                    <div class="text-center mb-10">
                        <span class="text-4xl">💬</span>
                        <h2 class="text-3xl font-extrabold text-slate-800 mt-2">Pusat Bantuan</h2>
                        <p class="text-slate-500 mt-2">Ada pertanyaan? Kami siap membantu Anda!</p>
                    </div>

                    <!-- FAQ Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10">
                        <!-- Card 1: Cara Booking -->
                        <div class="help-card bg-white rounded-xl p-6 shadow-sm hover:shadow-md cursor-pointer border border-gray-100" onclick="showAnswer(this)">
                            <div class="flex items-start gap-3">
                                <span class="text-2xl">📅</span>
                                <div>
                                   <h4 class="font-bold text-slate-800">Bagaimana cara booking?</h4>
<div class="text-sm text-slate-500 mt-1 hidden answer">
    <ol class="list-decimal list-inside space-y-1 pl-2 font-semibold">
        <li class="font-bold text-slate-700">Klik tombol <span class="text-blue-600">"Start Booking"</span></li>
        <li class="font-bold text-slate-700">Buat Akun atau Login jika sudah punya akun</li>
        <li class="font-bold text-slate-700">Pilih Tanggal dan Waktu yang akan Anda Booking (Schedule)</li>
        <li class="font-bold text-slate-700">Pilih Tema Frame untuk Photobox Anda, Sesuaikan dengan Momen Seru Anda</li>
        <li class="font-bold text-slate-700">Ikuti langkah-langkah hingga selesai</li>
    </ol>
</div> 
                                </div>
                            </div>
                        </div>

                      <!-- Card 2: Metode Pembayaran (DIPERBAIKI) -->
<div class="help-card bg-white rounded-xl p-6 shadow-sm hover:shadow-md cursor-pointer border border-gray-100" onclick="showAnswer(this)">
    <div class="flex items-start gap-3">
        <span class="text-2xl">💳</span>
        <div>
            <h4 class="font-bold text-slate-800">Metode pembayaran apa saja?</h4>
            <div class="text-sm text-slate-500 mt-1 hidden answer">
                <p class="font-bold text-slate-700 mb-2">QRIS Barcode.</p>
                <ul class="payment-method-list space-y-1">
                    <li class="font-bold text-slate-700">Gunakan : Mobile Banking, E-Wallet, atau Pembayaran Online Apa Saja yg Mendukung Qris.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

                        <!-- Card 3: Reschedule -->
                        <div class="help-card bg-white rounded-xl p-6 shadow-sm hover:shadow-md cursor-pointer border border-gray-100" onclick="showAnswer(this)">
                            <div class="flex items-start gap-3">
                                <span class="text-2xl">🔄</span>
                                <div>
                                    <h4 class="font-bold text-slate-800">Bisa reschedule?</h4>
                                    <p class="text-sm text-slate-500 mt-1 hidden answer">Hubungi Admin Kami Terlebih Dahulu, Melalui Contact : (no.contact admin)</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 4: Diskon Pelajar -->
                        <div class="help-card bg-white rounded-xl p-6 shadow-sm hover:shadow-md cursor-pointer border border-gray-100" onclick="showAnswer(this)">
                            <div class="flex items-start gap-3">
                                <span class="text-2xl">💰</span>
                                <div>
                                    <h4 class="font-bold text-slate-800">Ada diskon untuk pelajar?</h4>
                                    <p class="text-sm text-slate-500 mt-1 hidden answer">Ya! Tersedia diskon khusus pelajar/mahasiswa hingga 30%. Tunjukkan kartu pelajar/mahasiswa Anda saat melakukan booking atau saat datang ke studio.</p>
                                    <ul>Diskon 30% Hanya Tersedia pada 1 Hari Pertama Opening Photobox.</ul>
                                </div>
                            </div>
                        </div>

                        <!-- Card 5: Durasi Sesi -->
                        <div class="help-card bg-white rounded-xl p-6 shadow-sm hover:shadow-md cursor-pointer border border-gray-100" onclick="showAnswer(this)">
                            <div class="flex items-start gap-3">
                                <span class="text-2xl">📸</span>
                                <div>
                                    <h4 class="font-bold text-slate-800">Berapa lama sesi foto?</h4>
                                    <p class="text-sm text-slate-500 mt-1 hidden answer">Sesi foto Booking Tersedia 2 Jam, Bisa termasuk waktu persiapan, pengambilan foto, dan pemilihan hasil foto. Untuk sesi tambahan bisa diatur dengan admin.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 6: Butuh Bantuan Lain (DIPERBAIKI) -->
                        <div class="help-card bg-white rounded-xl p-6 shadow-sm hover:shadow-md cursor-pointer border border-gray-100" onclick="showAnswer(this)">
                            <div class="flex items-start gap-3">
                                <span class="text-2xl">📱</span>
                                <div>
                                    <h4 class="font-bold text-slate-800">Butuh bantuan lain?</h4>
                                    <div class="text-sm text-slate-500 mt-1 hidden answer">
                                        <p class="font-medium text-slate-700 mb-2">Hubungi kami melalui:</p>
                                        <ul class="contact-list">
                                            <li>
                                                <span class="icon">📞</span>
                                                <div>
                                                    <span class="font-medium">WhatsApp</span>
                                                    <br>
                                                    <span class="text-slate-600">0812-3456-7890 (Respons cepat)</span>
                                                </div>
                                            </li>
                                            <li>
                                                <span class="icon">🅾</span>
                                                <div>
                                                    <span class="font-medium">Instagram</span>
                                                    <br>
                                                    <span class="text-slate-600">@cuitbox.studio</span>
                                                </div>
                                            </li>
                                            <li>
                                                <span class="icon">📍</span>
                                                <div>
                                                    <span class="font-medium">Alamat : </span>
                                                    <br>
                                                    <span class="text-slate-600">Gedung Engineering Center (EC), Fakultas Teknik Universitas Indonesia</span>
                                                </div>
                                            </li>
                                            <li>
                                                <span class="icon">🕐</span>
                                                <div>
                                                    <span class="font-medium">Jam Operasional</span>
                                                    <br>
                                                    <span class="text-slate-600">Senin - Minggu, 08:00 - 22:00 WIB</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="bg-white rounded-2xl p-8 shadow-sm max-w-2xl mx-auto">
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Kirim Pesan</h3>
                        <p class="text-sm text-slate-500 mb-6">Isi form di bawah untuk menghubungi tim kami.</p>
                        
                        <form action="<?= base_url('help/send') ?>" method="POST">
                            <?= csrf_field() ?>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none" placeholder="Nama lengkap" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none" placeholder="email@example.com" required>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Subjek <span class="text-red-500">*</span></label>
                                <input type="text" name="subject" class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none" placeholder="Subjek pertanyaan" required>
                            </div>
                            
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Pesan <span class="text-red-500">*</span></label>
                                <textarea name="message" rows="4" class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none" placeholder="Tulis pesan Anda..." required></textarea>
                            </div>
                            
                            <!-- Alert Messages -->
                            <?php if (session()->getFlashdata('success')): ?>
                                <div class="mt-4 p-3 bg-green-100 text-green-700 rounded-xl text-sm">
                                    ✅ <?= session()->getFlashdata('success') ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-xl text-sm">
                                    ❌ <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (session()->getFlashdata('errors')): ?>
                                <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-xl text-sm">
                                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                        ❌ <?= $error ?><br>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <button type="submit" class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-full transition-all">
                                Kirim Pesan
                            </button>
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
        function showAnswer(element) {
            const answer = element.querySelector('.answer');
            if (answer) {
                if (answer.classList.contains('hidden')) {
                    answer.classList.remove('hidden');
                    answer.style.display = 'block';
                    // Sembunyikan jawaban lain
                    document.querySelectorAll('.answer').forEach(a => {
                        if (a !== answer && !a.classList.contains('hidden')) {
                            a.classList.add('hidden');
                            a.style.display = 'none';
                        }
                    });
                } else {
                    answer.classList.add('hidden');
                    answer.style.display = 'none';
                }
            }
        }

        // Tampilkan jawaban pertama secara default
        document.querySelector('.answer')?.classList.remove('hidden');
        document.querySelector('.answer')?.style.removeProperty('display');
    </script>
</body>
</html>