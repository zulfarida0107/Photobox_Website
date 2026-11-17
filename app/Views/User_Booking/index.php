<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?= esc($title ?? 'CuitBox Studio - Premium Photobox & Self-Photo Studio') ?></title>
    <meta name="description" content="CuitBox Studio - Pengalaman Photobox & Self-Photo Studio Premium. Cetak instan anti-luntur, kamera DSLR profesional, dan semua softfile digital gratis langsung ke HP Anda.">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Google Fonts & Material Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            blue: '#0284c7',
                            darkBlue: '#0369a1',
                            lightBlue: '#e0f2fe',
                            lime: '#a3e635',
                            limeDark: '#4d7c0f',
                            purple: '#c084fc',
                            pink: '#f472b6',
                            coral: '#fb7185',
                        }
                    },
                    keyframes: {
                        marquee: {
                            '0%': { transform: 'translateX(0%)' },
                            '100%': { transform: 'translateX(-50%)' },
                        },
                        floatSlow: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        pulseGlow: {
                            '0%, 100%': { opacity: '0.6', transform: 'scale(1)' },
                            '50%': { opacity: '0.9', transform: 'scale(1.05)' },
                        }
                    },
                    animation: {
                        marquee: 'marquee 25s linear infinite',
                        floatSlow: 'floatSlow 4s ease-in-out infinite',
                        pulseGlow: 'pulseGlow 3s ease-in-out infinite',
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-mesh {
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 10% 10%, #dbeafe 0px, transparent 50%),
                radial-gradient(at 90% 10%, #fce7f3 0px, transparent 50%),
                radial-gradient(at 50% 50%, #f0fdf4 0px, transparent 50%),
                radial-gradient(at 90% 90%, #e0e7ff 0px, transparent 50%),
                radial-gradient(at 10% 90%, #fef3c7 0px, transparent 50%);
            background-attachment: fixed;
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.9);
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
            display: inline-block;
        }
        .text-gradient {
            background: linear-gradient(135deg, #0284c7 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .text-gradient-warm {
            background: linear-gradient(135deg, #ea580c 0%, #db2777 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="gradient-mesh text-slate-800 font-sans antialiased min-h-screen selection:bg-brand-lightBlue selection:text-brand-darkBlue">

    <!-- ============================================================ -->
    <!-- 1. STICKY HEADER & NAVBAR -->
    <!-- ============================================================ -->
    <header class="sticky top-0 z-50 glass-nav border-b border-slate-200/70 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 sm:h-20 flex items-center justify-between">
            
            <!-- Brand Logo -->
            <a href="<?= base_url('/') ?>" class="flex items-center gap-3 group">
                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-gradient-to-tr from-brand-blue to-indigo-600 flex items-center justify-center text-white shadow-md shadow-brand-blue/30 group-hover:scale-105 transition-transform duration-200">
                    <span class="material-symbols-outlined text-2xl">photo_camera</span>
                </div>
                <div>
                    <span class="text-xl sm:text-2xl font-black tracking-tight text-slate-900 block leading-tight">CuitBox<span class="text-brand-blue">.</span></span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block -mt-0.5">Studio Photobox</span>
                </div>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden md:flex items-center gap-1 lg:gap-2">
                <a href="#how-it-works" class="px-3 py-2 rounded-xl text-sm font-semibold text-slate-600 hover:text-brand-blue hover:bg-slate-100/70 transition-all">Cara Booking</a>
                <a href="#pricing" class="px-3 py-2 rounded-xl text-sm font-semibold text-slate-600 hover:text-brand-blue hover:bg-slate-100/70 transition-all">Paket & Harga</a>
                <a href="#frames" class="px-3 py-2 rounded-xl text-sm font-semibold text-slate-600 hover:text-brand-blue hover:bg-slate-100/70 transition-all">Desain Frame</a>
                <a href="#booths" class="px-3 py-2 rounded-xl text-sm font-semibold text-slate-600 hover:text-brand-blue hover:bg-slate-100/70 transition-all">Lokasi Booth</a>
                <a href="#faq" class="px-3 py-2 rounded-xl text-sm font-semibold text-slate-600 hover:text-brand-blue hover:bg-slate-100/70 transition-all">FAQ</a>
            </nav>

            <!-- Action Buttons (Right) -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Cek Status Tiket / Barcode -->
                <a href="<?= base_url('statusbooking') ?>" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 hover:text-brand-blue bg-slate-100/80 hover:bg-slate-200/80 border border-slate-200 transition-all" title="Cek Status Tiket">
                    <span class="material-symbols-outlined text-sm">qr_code_scanner</span>
                    <span>Cek Tiket</span>
                </a>

                <?php if (session()->get('isUserLoggedIn') || session()->get('isLoggedIn')): ?>
                    <a href="<?= base_url('statusbooking') ?>" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-white border border-slate-300 shadow-sm hover:bg-slate-50 transition-all">
                        <span class="material-symbols-outlined text-sm text-green-600">account_circle</span>
                        <span class="max-w-[100px] truncate"><?= esc(session()->get('customer_name') ?? 'Akun Saya') ?></span>
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>" class="hidden sm:inline-flex items-center px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:text-brand-blue hover:bg-slate-100 transition-all">
                        Masuk
                    </a>
                <?php endif; ?>

                <!-- Primary CTA Button -->
                <a href="<?= base_url('booking') ?>" class="inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl text-xs sm:text-sm font-extrabold text-white bg-gradient-to-r from-brand-blue via-indigo-600 to-purple-600 hover:shadow-lg hover:shadow-brand-blue/30 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                    <span>Mulai Booking</span>
                    <span class="material-symbols-outlined text-sm sm:text-base">arrow_forward</span>
                </a>

                <!-- Mobile Menu Button -->
                <button type="button" onclick="toggleMobileNav()" class="md:hidden p-2 rounded-xl text-slate-700 hover:bg-slate-100 transition-colors" aria-label="Buka Menu">
                    <span id="nav-icon-menu" class="material-symbols-outlined text-2xl">menu</span>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Menu Dropdown -->
        <div id="mobile-nav-menu" class="hidden md:hidden border-t border-slate-200 bg-white/95 backdrop-blur-lg px-4 pt-3 pb-6 space-y-2 shadow-xl">
            <a href="#how-it-works" onclick="toggleMobileNav()" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">
                <span class="material-symbols-outlined text-brand-blue">timeline</span>
                <span>Cara Booking</span>
            </a>
            <a href="#pricing" onclick="toggleMobileNav()" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">
                <span class="material-symbols-outlined text-brand-blue">payments</span>
                <span>Paket & Harga</span>
            </a>
            <a href="#frames" onclick="toggleMobileNav()" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">
                <span class="material-symbols-outlined text-brand-blue">palette</span>
                <span>Desain Frame</span>
            </a>
            <a href="#booths" onclick="toggleMobileNav()" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">
                <span class="material-symbols-outlined text-brand-blue">storefront</span>
                <span>Lokasi Booth</span>
            </a>
            <a href="#faq" onclick="toggleMobileNav()" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-100">
                <span class="material-symbols-outlined text-brand-blue">help_outline</span>
                <span>FAQ & Bantuan</span>
            </a>
            <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                <a href="<?= base_url('statusbooking') ?>" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 border border-slate-200">
                    <span class="material-symbols-outlined text-sm">qr_code_scanner</span>
                    <span>Cek Tiket / Riwayat Booking</span>
                </a>
                <?php if (!session()->get('isUserLoggedIn') && !session()->get('isLoggedIn')): ?>
                    <a href="<?= base_url('login') ?>" class="flex items-center justify-center w-full py-2.5 rounded-xl text-xs font-bold text-brand-blue bg-blue-50 border border-blue-100">
                        Masuk ke Akun
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- ============================================================ -->
    <!-- 2. HERO SECTION -->
    <!-- ============================================================ -->
    <section class="relative pt-8 pb-16 sm:pt-14 sm:pb-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto overflow-hidden">
        
        <!-- Flash Info / Message -->
        <?php if (session()->getFlashdata('info')): ?>
            <div class="mb-8 max-w-2xl mx-auto bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-2xl text-xs sm:text-sm font-medium flex items-center gap-3 shadow-sm">
                <span class="material-symbols-outlined text-blue-600 text-lg">info</span>
                <span><?= esc(session()->getFlashdata('info')) ?></span>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
            
            <!-- Left Column: Value Proposition & CTAs -->
            <div class="lg:col-span-7 space-y-6 sm:space-y-8 text-center lg:text-left">
                
                <!-- Pill Badges -->
                <div class="inline-flex flex-wrap items-center justify-center lg:justify-start gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Studio Grade DSLR & Lighting
                    </span>
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase bg-purple-100 text-purple-800 border border-purple-200 shadow-sm">
                        ✨ Free Softfile & GIF
                    </span>
                </div>

                <!-- Main Headline -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-slate-900 leading-[1.12]">
                    Abadikan Setiap Momen <span class="text-gradient block sm:inline">Penuh Makna.</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-base sm:text-lg text-slate-600 max-w-xl mx-auto lg:mx-0 leading-relaxed font-normal">
                    Pengalaman photobox otomatis paling estetik di Indonesia. Cetak instan kualitas studio tahan air + unduh seluruh softfile HD & video animasi langsung dari HP Anda.
                </p>

                <!-- Action Button Group -->
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3 sm:gap-4 pt-2">
                    <a href="<?= base_url('booking') ?>" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl text-base font-extrabold text-white bg-gradient-to-r from-brand-blue to-indigo-600 hover:shadow-xl hover:shadow-brand-blue/30 hover:scale-105 active:scale-95 transition-all duration-200 shadow-md">
                        <span class="material-symbols-outlined text-xl">event_available</span>
                        <span>Mulai Booking Sekarang</span>
                    </a>
                    
                    <a href="#pricing" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-4 rounded-2xl text-sm font-bold text-slate-700 bg-white/80 hover:bg-white border border-slate-200 shadow-sm hover:shadow transition-all duration-200">
                        <span class="material-symbols-outlined text-lg text-slate-500">sell</span>
                        <span>Lihat Paket & Harga</span>
                    </a>
                </div>

                <!-- Social Proof & Trust Metric Stats -->
                <div class="pt-6 border-t border-slate-200/80 grid grid-cols-3 gap-4 max-w-md mx-auto lg:mx-0 text-center lg:text-left">
                    <div>
                        <div class="text-2xl sm:text-3xl font-black text-slate-900">15.000+</div>
                        <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mt-0.5">Foto Terabadikan</div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-black text-slate-900 flex items-center justify-center lg:justify-start gap-1">
                            <span>4.9</span>
                            <span class="text-amber-500 text-lg sm:text-xl">★</span>
                        </div>
                        <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mt-0.5">Rating Kepuasan</div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-black text-slate-900">&lt; 1 Menit</div>
                        <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mt-0.5">Cetak Instan</div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Interactive Visual Showcase -->
            <div class="lg:col-span-5 relative flex justify-center items-center">
                <!-- Glowing Backdrop Circles -->
                <div class="absolute -top-12 -left-12 w-64 h-64 bg-blue-400/20 rounded-full blur-3xl animate-pulseGlow pointer-events-none"></div>
                <div class="absolute -bottom-12 -right-12 w-64 h-64 bg-purple-400/20 rounded-full blur-3xl animate-pulseGlow pointer-events-none"></div>

                <!-- Stacked Visual Photostrips Card -->
                <div class="relative w-full max-w-sm sm:max-w-md group">
                    <div class="glass-card p-4 sm:p-5 rounded-3xl shadow-2xl shadow-slate-300/60 transition-transform duration-500 hover:scale-[1.02] relative z-10">
                        <div class="relative aspect-[4/5] rounded-2xl overflow-hidden bg-slate-100 shadow-inner">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAGgnPUCclbfhn6AtbXFjK1fX_cKPqcVbzY2Rgi9oszZK10a85K99JzNHeU2tdxEVIefMPuzyGzzcQ1yqg_3hKZovYWn8B7QJdwp0_WxhFC8kKmCiSMNOpleKKs5qoOHhv7xo19U-jVO8dZ-gN3KhXWQ-vUexzqSUejqE_mGimGmOU0veDZCh-Lv7_1ulBsfiqwHdT2zsKd_fnB1IWqAH0KrsjOaQuZPqX-foOAXszZ0MtcJcgkQwG5sw" 
                                 alt="CuitBox Photostrip Preview" 
                                 class="w-full h-full object-cover">
                            
                            <!-- Floating Tag on Image -->
                            <div class="absolute bottom-4 left-4 right-4 bg-slate-900/80 backdrop-blur-md rounded-xl p-3 text-white flex items-center justify-between border border-white/10">
                                <div>
                                    <p class="text-xs font-bold leading-tight">Quad Cut Frame Special</p>
                                    <p class="text-[10px] text-slate-300">Waterproof • Ultra HD</p>
                                </div>
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black bg-brand-lime text-slate-900">HOT VIBES</span>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Badge 1: Top Right -->
                    <div class="absolute -top-4 -right-4 sm:-right-6 bg-white/90 backdrop-blur-md border border-slate-200 px-4 py-2.5 rounded-2xl shadow-lg flex items-center gap-2 animate-floatSlow z-20">
                        <span class="material-symbols-outlined text-amber-500 text-xl">auto_awesome</span>
                        <span class="text-xs font-extrabold text-slate-800">100+ Desain Frame</span>
                    </div>

                    <!-- Floating Badge 2: Bottom Left -->
                    <div class="absolute -bottom-4 -left-4 sm:-left-6 bg-white/90 backdrop-blur-md border border-slate-200 px-4 py-2.5 rounded-2xl shadow-lg flex items-center gap-2 z-20">
                        <span class="material-symbols-outlined text-emerald-600 text-xl">check_circle</span>
                        <span class="text-xs font-extrabold text-slate-800">Bayar Praktis via QRIS</span>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ============================================================ -->
    <!-- 3. MARQUEE BANNER -->
    <!-- ============================================================ -->
    <div class="bg-gradient-to-r from-brand-blue via-indigo-600 to-purple-600 text-white py-3.5 overflow-hidden shadow-sm">
        <div class="flex animate-marquee whitespace-nowrap text-xs sm:text-sm font-extrabold tracking-widest uppercase">
            <span class="mx-6">📸 INSTA-READY MEMORIES</span>
            <span class="mx-6">•</span>
            <span class="mx-6">⚡ DSLR STUDIO LIGHTING</span>
            <span class="mx-6">•</span>
            <span class="mx-6">💖 ALL DIGITAL SOFTFILES INCLUDED</span>
            <span class="mx-6">•</span>
            <span class="mx-6">🎨 100+ UNIQUE FRAMES</span>
            <span class="mx-6">•</span>
            <span class="mx-6">💳 INSTANT QRIS PAYMENT</span>
            <span class="mx-6">•</span>
            <span class="mx-6">✨ WATERPROOF THERMAL PRINTS</span>
            <span class="mx-6">•</span>
            <span class="mx-6">📸 INSTA-READY MEMORIES</span>
            <span class="mx-6">•</span>
            <span class="mx-6">⚡ DSLR STUDIO LIGHTING</span>
            <span class="mx-6">•</span>
            <span class="mx-6">💖 ALL DIGITAL SOFTFILES INCLUDED</span>
            <span class="mx-6">•</span>
            <span class="mx-6">🎨 100+ UNIQUE FRAMES</span>
            <span class="mx-6">•</span>
            <span class="mx-6">💳 INSTANT QRIS PAYMENT</span>
            <span class="mx-6">•</span>
            <span class="mx-6">✨ WATERPROOF THERMAL PRINTS</span>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- 4. HOW IT WORKS (4 LANGKAH MUDAH) -->
    <!-- ============================================================ -->
    <section id="how-it-works" class="py-16 sm:py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-3 py-1 bg-blue-100 text-brand-blue text-xs font-bold rounded-full uppercase tracking-wider mb-3">Sangat Mudah & Praktis</span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">4 Langkah Pengalaman Photobox Seru</h2>
            <p class="text-slate-600 mt-3 text-sm sm:text-base">Mulai dari reservasi hingga foto tercetak rapi di tangan Anda dalam hitungan menit.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative">
            
            <!-- Step 1 -->
            <div class="glass-card p-6 sm:p-7 rounded-3xl relative group hover:border-brand-blue hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-brand-blue flex items-center justify-center font-black text-xl mb-6 group-hover:scale-110 transition-transform">
                        1
                    </div>
                    <h3 class="text-lg font-black text-slate-900 mb-2">Pilih Booth & Jadwal</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Pesan slot waktu favorit Anda secara online atau langsung di kiosk tanpa perlu antre panjang.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-2 text-xs font-bold text-brand-blue">
                    <span class="material-symbols-outlined text-base">calendar_month</span>
                    <span>Reservasi Fleksibel</span>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="glass-card p-6 sm:p-7 rounded-3xl relative group hover:border-purple-500 hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-black text-xl mb-6 group-hover:scale-110 transition-transform">
                        2
                    </div>
                    <h3 class="text-lg font-black text-slate-900 mb-2">Pilih Paket & Frame</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Tentukan jumlah cetak dan pilih desain bingkai estetik (Retro, Y2K, Minimalist, Cute, dll).
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-2 text-xs font-bold text-purple-600">
                    <span class="material-symbols-outlined text-base">palette</span>
                    <span>100+ Pilihan Desain</span>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="glass-card p-6 sm:p-7 rounded-3xl relative group hover:border-pink-500 hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center font-black text-xl mb-6 group-hover:scale-110 transition-transform">
                        3
                    </div>
                    <h3 class="text-lg font-black text-slate-900 mb-2">Sesi Foto Bebas & Asik</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Masuk ke dalam booth, pandu gaya di monitor cermin, dan jepret dengan remote shutter nirkabel.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-2 text-xs font-bold text-pink-600">
                    <span class="material-symbols-outlined text-base">photo_camera</span>
                    <span>Live Screen Preview</span>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="glass-card p-6 sm:p-7 rounded-3xl relative group hover:border-emerald-500 hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xl mb-6 group-hover:scale-110 transition-transform">
                        4
                    </div>
                    <h3 class="text-lg font-black text-slate-900 mb-2">Cetak & Unduh Softfile</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Hasil cetak keluar seketika + scan QR code untuk unduh seluruh softfile foto HD dan video animasi GIF.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-2 text-xs font-bold text-emerald-600">
                    <span class="material-symbols-outlined text-base">cloud_download</span>
                    <span>Instan Print & Digital</span>
                </div>
            </div>

        </div>
    </section>

    <!-- ============================================================ -->
    <!-- 5. PRICING & PACKAGES -->
    <!-- ============================================================ -->
    <section id="pricing" class="py-16 sm:py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full uppercase tracking-wider mb-3">Harga Transparan</span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Pilihan Paket Foto Sesuai Kebutuhan</h2>
            <p class="text-slate-600 mt-3 text-sm sm:text-base">Semua paket sudah termasuk seluruh file digital (softfile) dan hasil cetak thermal tahan air.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto items-stretch">
            
            <!-- Package 1: Duo Strip -->
            <div class="glass-card p-8 rounded-3xl border border-slate-200 flex flex-col justify-between hover:shadow-xl transition-all duration-300">
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 bg-slate-100 px-3 py-1 rounded-full">Basic Duo</span>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900">2 Lembar Cetak</h3>
                    <p class="text-xs text-slate-500 mt-1">Sempurna untuk foto bersama pasangan atau sahabat karib.</p>
                    
                    <div class="my-6">
                        <span class="text-4xl font-black text-slate-900">Rp 25.000</span>
                        <span class="text-xs text-slate-500">/ sesi</span>
                    </div>

                    <ul class="space-y-3 text-xs sm:text-sm text-slate-600 border-t border-slate-100 pt-6">
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-600 text-base">check_circle</span>
                            <span><strong>2 Strip</strong> Foto Fisik Kualitas Tinggi</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-600 text-base">check_circle</span>
                            <span>Sesi Foto Bebas 10 Menit</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-600 text-base">check_circle</span>
                            <span>Gratis Seluruh Softfile HD</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-600 text-base">check_circle</span>
                            <span>Pilihan Frame Standar Lengkap</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-8">
                    <a href="<?= base_url('booking') ?>" class="w-full inline-flex items-center justify-center gap-2 py-3.5 rounded-xl text-xs sm:text-sm font-bold text-slate-800 bg-slate-100 hover:bg-slate-200 transition-colors">
                        <span>Pilih Paket Ini</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Package 2: Quad Squad (RECOMMENDED) -->
            <div class="bg-gradient-to-b from-white via-white to-blue-50/50 p-8 rounded-3xl border-2 border-brand-blue shadow-2xl shadow-brand-blue/20 flex flex-col justify-between relative scale-105 z-10">
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-brand-blue to-indigo-600 text-white text-[11px] font-black uppercase tracking-widest px-4 py-1 rounded-full shadow-md">
                    👑 PALING POPULER
                </div>
                <div>
                    <div class="flex justify-between items-center mb-4 mt-1">
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-blue bg-blue-50 px-3 py-1 rounded-full">Squad Combo</span>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900">4 Lembar Cetak</h3>
                    <p class="text-xs text-slate-500 mt-1">Pilihan favorit untuk geng sahabat, keluarga kecil, atau grup 3-4 orang.</p>
                    
                    <div class="my-6">
                        <span class="text-4xl font-black text-slate-900">Rp 50.000</span>
                        <span class="text-xs text-slate-500">/ sesi</span>
                    </div>

                    <ul class="space-y-3 text-xs sm:text-sm text-slate-600 border-t border-slate-100 pt-6">
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-brand-blue text-base">check_circle</span>
                            <span><strong>4 Strip</strong> Cetak Fisik Tahan Air</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-brand-blue text-base">check_circle</span>
                            <span>Sesi Foto 15 Menit Lengkap</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-brand-blue text-base">check_circle</span>
                            <span>Gratis Semua Softfile HD + <strong>Video GIF</strong></span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-brand-blue text-base">check_circle</span>
                            <span>Akses Seluruh Frame Premium & Seasonal</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-8">
                    <a href="<?= base_url('booking') ?>" class="w-full inline-flex items-center justify-center gap-2 py-3.5 rounded-xl text-xs sm:text-sm font-extrabold text-white bg-gradient-to-r from-brand-blue to-indigo-600 hover:shadow-lg hover:shadow-brand-blue/30 transition-all">
                        <span>Pilih Paket Terpopuler</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Package 3: Party Group -->
            <div class="glass-card p-8 rounded-3xl border border-slate-200 flex flex-col justify-between hover:shadow-xl transition-all duration-300">
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-xs font-bold uppercase tracking-wider text-purple-600 bg-purple-50 px-3 py-1 rounded-full">Group Party</span>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900">6+ Lembar Cetak</h3>
                    <p class="text-xs text-slate-500 mt-1">Kapasitas cetak ekstra untuk kelompok besar atau perayaan wisuda & ulang tahun.</p>
                    
                    <div class="my-6">
                        <span class="text-4xl font-black text-slate-900">Rp 75.000</span>
                        <span class="text-xs text-slate-500">/ sesi</span>
                    </div>

                    <ul class="space-y-3 text-xs sm:text-sm text-slate-600 border-t border-slate-100 pt-6">
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-600 text-base">check_circle</span>
                            <span><strong>6 Lembar</strong> Cetak Foto Fisik</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-600 text-base">check_circle</span>
                            <span>Sesi Foto Lebih Panjang (15-20 Menit)</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-600 text-base">check_circle</span>
                            <span>Gratis Semua Softfile HD & Live GIF</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-600 text-base">check_circle</span>
                            <span>Bisa Tambah Copy Tambahan Rp 15rb/lbr</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-8">
                    <a href="<?= base_url('booking') ?>" class="w-full inline-flex items-center justify-center gap-2 py-3.5 rounded-xl text-xs sm:text-sm font-bold text-slate-800 bg-slate-100 hover:bg-slate-200 transition-colors">
                        <span>Pilih Paket Ini</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- ============================================================ -->
    <!-- 6. DESAIN FRAME ESTETIK & TEMPLATE SHOWCASE -->
    <!-- ============================================================ -->
    <section id="frames" class="py-16 sm:py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 text-xs font-bold rounded-full uppercase tracking-wider mb-3">Koleksi Desain Estetik</span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Katalog Desain Frame Terpopuler</h2>
            <p class="text-slate-600 mt-3 text-sm sm:text-base">Pilih berbagai tema bingkai eksklusif mulai dari Retro, Minimalist, Y2K, hingga edisi spesial kampus.</p>
        </div>

        <!-- Frame Filter Tabs -->
        <div class="flex flex-wrap items-center justify-center gap-2 mb-10">
            <button type="button" onclick="filterFrames('all')" class="frame-filter-btn px-4 py-2 rounded-xl text-xs font-bold bg-slate-900 text-white shadow-sm transition-all" data-category="all">Semua Desain</button>
            <button type="button" onclick="filterFrames('retro')" class="frame-filter-btn px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 transition-all" data-category="retro">Retro & 80s</button>
            <button type="button" onclick="filterFrames('pastel')" class="frame-filter-btn px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 transition-all" data-category="pastel">Pastel & Cute</button>
            <button type="button" onclick="filterFrames('minimalist')" class="frame-filter-btn px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 transition-all" data-category="minimalist">Minimalist</button>
            <button type="button" onclick="filterFrames('magazine')" class="frame-filter-btn px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 transition-all" data-category="magazine">Magazine</button>
        </div>

        <!-- Frame Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            
            <!-- Frame 1 -->
            <div class="frame-card glass-card rounded-2xl overflow-hidden group hover:shadow-xl transition-all duration-300 border border-slate-200/80" data-cat="magazine">
                <div class="aspect-[3/4] overflow-hidden bg-slate-100 relative">
                    <img src="https://cdn.ayobandung.id/crop/original/medias/2026/Mar/25/fotoboxkoran.jpg" alt="Magazine News Theme" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute top-2.5 right-2.5 bg-slate-900/80 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-0.5 rounded-md">Trending</div>
                </div>
                <div class="p-3.5 text-center">
                    <h4 class="font-bold text-xs sm:text-sm text-slate-800">Magazine News</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">Editorial newspaper style</p>
                </div>
            </div>

            <!-- Frame 2 -->
            <div class="frame-card glass-card rounded-2xl overflow-hidden group hover:shadow-xl transition-all duration-300 border border-slate-200/80" data-cat="retro">
                <div class="aspect-[3/4] overflow-hidden bg-slate-100 relative">
                    <img src="https://i.pinimg.com/originals/02/7b/8d/027b8dc899968a6fb495267e7cdb533d.jpg" alt="Retro 80s Theme" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute top-2.5 right-2.5 bg-amber-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-md">Classic</div>
                </div>
                <div class="p-3.5 text-center">
                    <h4 class="font-bold text-xs sm:text-sm text-slate-800">Retro 80s Vintage</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">Warm nostalgic vibes</p>
                </div>
            </div>

            <!-- Frame 3 -->
            <div class="frame-card glass-card rounded-2xl overflow-hidden group hover:shadow-xl transition-all duration-300 border border-slate-200/80" data-cat="pastel">
                <div class="aspect-[3/4] overflow-hidden bg-slate-100 relative">
                    <img src="https://img.magnific.com/premium-photo/beautiful-pastel-color-schemes-homes-interior-design_758367-62769.jpg" alt="Pastel Color Theme" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <div class="p-3.5 text-center">
                    <h4 class="font-bold text-xs sm:text-sm text-slate-800">Pastel Aesthetic</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">Soft & colorful tones</p>
                </div>
            </div>

            <!-- Frame 4 -->
            <div class="frame-card glass-card rounded-2xl overflow-hidden group hover:shadow-xl transition-all duration-300 border border-slate-200/80" data-cat="minimalist">
                <div class="aspect-[3/4] overflow-hidden bg-slate-100 relative">
                    <img src="https://images.squarespace-cdn.com/content/v1/63dde481bbabc6724d988548/4f246e68-c4c6-4bf7-ab17-36792e1beb8b/_501b5536-1cc2-4675-b813-9d68911eee81.jfif" alt="Minimalist Clean Theme" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute top-2.5 right-2.5 bg-brand-blue text-white text-[10px] font-bold px-2 py-0.5 rounded-md">Popular</div>
                </div>
                <div class="p-3.5 text-center">
                    <h4 class="font-bold text-xs sm:text-sm text-slate-800">Minimalist Monochrome</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">Clean & modern layout</p>
                </div>
            </div>

            <!-- Frame 5 -->
            <div class="frame-card glass-card rounded-2xl overflow-hidden group hover:shadow-xl transition-all duration-300 border border-slate-200/80" data-cat="pastel">
                <div class="aspect-[3/4] overflow-hidden bg-slate-100 relative">
                    <img src="https://wallpapers.com/images/hd/kawaii-room-1500-x-1500-wallpaper-qh7zrfbr4o71dpae.jpg" alt="Cute & Kawaii Theme" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <div class="p-3.5 text-center">
                    <h4 class="font-bold text-xs sm:text-sm text-slate-800">Cute & Kawaii</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">Adorable playful graphics</p>
                </div>
            </div>

            <!-- Frame 6 -->
            <div class="frame-card glass-card rounded-2xl overflow-hidden group hover:shadow-xl transition-all duration-300 border border-slate-200/80" data-cat="minimalist">
                <div class="aspect-[3/4] overflow-hidden bg-slate-100 relative">
                    <img src="https://cdn.antaranews.com/cache/1200x800/2023/04/03/Rektorat-Kampus-UI-Depok.jpg" alt="All Faculty UI Theme" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute top-2.5 right-2.5 bg-yellow-500 text-slate-900 text-[10px] font-bold px-2 py-0.5 rounded-md">Campus</div>
                </div>
                <div class="p-3.5 text-center">
                    <h4 class="font-bold text-xs sm:text-sm text-slate-800">Special UI Edition</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">All Faculty Special Frame</p>
                </div>
            </div>

            <!-- Frame 7 -->
            <div class="frame-card glass-card rounded-2xl overflow-hidden group hover:shadow-xl transition-all duration-300 border border-slate-200/80" data-cat="retro">
                <div class="aspect-[3/4] overflow-hidden bg-gradient-to-tr from-purple-800 via-pink-600 to-amber-500 relative flex items-center justify-center text-white p-4 text-center">
                    <div>
                        <span class="material-symbols-outlined text-4xl mb-2">flare</span>
                        <p class="font-extrabold text-sm">Y2K Cyber Glitch</p>
                        <p class="text-[10px] opacity-80 mt-1">Futuristic neon vibes</p>
                    </div>
                </div>
                <div class="p-3.5 text-center">
                    <h4 class="font-bold text-xs sm:text-sm text-slate-800">Y2K Cyber Glitch</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">Vibrant retro future</p>
                </div>
            </div>

            <!-- Frame 8 -->
            <div class="frame-card glass-card rounded-2xl overflow-hidden group hover:shadow-xl transition-all duration-300 border border-slate-200/80" data-cat="magazine">
                <div class="aspect-[3/4] overflow-hidden bg-slate-900 relative flex items-center justify-center text-white p-4 text-center">
                    <div>
                        <span class="material-symbols-outlined text-4xl mb-2 text-brand-lime">auto_awesome</span>
                        <p class="font-extrabold text-sm">Vogue High Fashion</p>
                        <p class="text-[10px] opacity-80 mt-1">Luxury black & gold</p>
                    </div>
                </div>
                <div class="p-3.5 text-center">
                    <h4 class="font-bold text-xs sm:text-sm text-slate-800">Vogue High Fashion</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">Black & gold elegance</p>
                </div>
            </div>

        </div>
    </section>

    <!-- ============================================================ -->
    <!-- 7. WHY CHOOSE US (KEY FEATURES) -->
    <!-- ============================================================ -->
    <section class="py-16 sm:py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="bg-gradient-to-b from-slate-900 to-indigo-950 text-white rounded-3xl p-8 sm:p-14 shadow-2xl relative overflow-hidden">
            <!-- Decorative Glow -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-brand-blue/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="inline-block px-3 py-1 bg-white/10 text-brand-lime text-xs font-bold rounded-full uppercase tracking-wider mb-3">Keunggulan Utama</span>
                <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight">Kenapa Harus CuitBox Studio?</h2>
                <p class="text-slate-300 mt-3 text-sm sm:text-base">Kami merancang setiap detail studio untuk memberikan hasil foto terbaik dengan kepraktisan maksimal.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <div class="bg-white/5 border border-white/10 p-6 rounded-2xl backdrop-blur-sm">
                    <div class="w-12 h-12 rounded-xl bg-blue-500/20 text-brand-blue flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-2xl">photo_camera</span>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">DSLR & Studio Lighting</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">Kamera profesional dengan pencahayaan softbox studio yang membuat warna kulit bersinar natural.</p>
                </div>

                <div class="bg-white/5 border border-white/10 p-6 rounded-2xl backdrop-blur-sm">
                    <div class="w-12 h-12 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-2xl">print</span>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Waterproof Thermal Print</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">Kertas foto premium tahan air, anti gores, dan tidak pudar disimpan bertahun-tahun.</p>
                </div>

                <div class="bg-white/5 border border-white/10 p-6 rounded-2xl backdrop-blur-sm">
                    <div class="w-12 h-12 rounded-xl bg-pink-500/20 text-pink-400 flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-2xl">cloud_sync</span>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Free HD Softfiles & GIF</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">Semua file foto resolusi tinggi dan animasi video time-lapse bisa langsung diunduh ke HP.</p>
                </div>

                <div class="bg-white/5 border border-white/10 p-6 rounded-2xl backdrop-blur-sm">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-brand-lime flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-2xl">qr_code</span>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">QRIS Otomatis Midtrans</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">Bayar praktis tanpa uang tunai via GoPay, OVO, ShopeePay, DANA, dan semua m-Banking.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- ============================================================ -->
    <!-- 8. LOKASI BOOTH & JAM OPERASIONAL -->
    <!-- ============================================================ -->
    <section id="booths" class="py-16 sm:py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="inline-block px-3 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-full uppercase tracking-wider mb-3">Temukan Kami</span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Lokasi Mesin Booth CuitBox</h2>
            <p class="text-slate-600 mt-3 text-sm sm:text-base">Kunjungi booth terdekat dan nikmati pengalaman photobox seru bersama orang terkasih.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
            
            <?php if (!empty($booths)): ?>
                <?php foreach ($booths as $b): ?>
                    <div class="glass-card p-6 rounded-3xl border border-slate-200 hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full <?= ($b['status'] ?? 'ready') === 'ready' ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500' ?>"></span>
                                    <span class="text-xs font-bold <?= ($b['status'] ?? 'ready') === 'ready' ? 'text-emerald-700' : 'text-amber-700' ?>">
                                        <?= ($b['status'] ?? 'ready') === 'ready' ? 'Booth Online & Siap Foto' : 'Sedang Ada Sesi' ?>
                                    </span>
                                </div>
                                <span class="text-xs font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg">Kapasitas <?= esc($b['capacity'] ?? 4) ?> Orang</span>
                            </div>

                            <h3 class="text-xl font-black text-slate-900 mb-2"><?= esc($b['name'] ?? 'Booth Studio') ?></h3>
                            <p class="text-xs sm:text-sm text-slate-600 flex items-start gap-1.5 mb-4">
                                <span class="material-symbols-outlined text-slate-400 text-base flex-shrink-0 mt-0.5">location_on</span>
                                <span><?= esc($b['location_id'] ?? 'Lokasi Booth Utama CuitBox Studio') ?></span>
                            </p>

                            <div class="bg-slate-50 rounded-2xl p-3.5 space-y-1.5 text-xs text-slate-600">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500">Jam Operasional:</span>
                                    <span class="font-bold text-slate-800">10:00 - 22:00 WIB</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500">Antrean Saat Ini:</span>
                                    <span class="font-bold text-slate-800"><?= esc($b['current_queue'] ?? 0) ?> Sesi</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-100">
                            <a href="<?= base_url('booking') ?>" class="w-full inline-flex items-center justify-center gap-2 py-3 rounded-xl text-xs font-bold text-brand-blue bg-blue-50 hover:bg-blue-100 transition-colors">
                                <span>Pesan Slot di Booth Ini</span>
                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback Mock Booth 1 -->
                <div class="glass-card p-6 rounded-3xl border border-slate-200 hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-xs font-bold text-emerald-700">Booth Online & Siap Foto</span>
                            </div>
                            <span class="text-xs font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg">Kapasitas 4-6 Orang</span>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 mb-2">CuitBox Central Booth #1</h3>
                        <p class="text-xs sm:text-sm text-slate-600 flex items-start gap-1.5 mb-4">
                            <span class="material-symbols-outlined text-slate-400 text-base flex-shrink-0 mt-0.5">location_on</span>
                            <span>Main Lobby Studio, Lantai 1 Area Photobox</span>
                        </p>
                        <div class="bg-slate-50 rounded-2xl p-3.5 space-y-1.5 text-xs text-slate-600">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Jam Buka:</span>
                                <span class="font-bold text-slate-800">10:00 - 22:00 WIB</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <a href="<?= base_url('booking') ?>" class="w-full inline-flex items-center justify-center gap-2 py-3 rounded-xl text-xs font-bold text-brand-blue bg-blue-50 hover:bg-blue-100 transition-colors">
                            <span>Pesan Slot di Booth Ini</span>
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                </div>

                <!-- Fallback Mock Booth 2 -->
                <div class="glass-card p-6 rounded-3xl border border-slate-200 hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-xs font-bold text-emerald-700">Booth Online & Siap Foto</span>
                            </div>
                            <span class="text-xs font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg">Kapasitas 2-4 Orang</span>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 mb-2">CuitBox Campus Booth #2</h3>
                        <p class="text-xs sm:text-sm text-slate-600 flex items-start gap-1.5 mb-4">
                            <span class="material-symbols-outlined text-slate-400 text-base flex-shrink-0 mt-0.5">location_on</span>
                            <span>Student Center Hub, Area Kafe & Creative Corner</span>
                        </p>
                        <div class="bg-slate-50 rounded-2xl p-3.5 space-y-1.5 text-xs text-slate-600">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Jam Buka:</span>
                                <span class="font-bold text-slate-800">09:00 - 21:00 WIB</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <a href="<?= base_url('booking') ?>" class="w-full inline-flex items-center justify-center gap-2 py-3 rounded-xl text-xs font-bold text-brand-blue bg-blue-50 hover:bg-blue-100 transition-colors">
                            <span>Pesan Slot di Booth Ini</span>
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </section>

    <!-- ============================================================ -->
    <!-- 9. FAQ ACCORDION -->
    <!-- ============================================================ -->
    <section id="faq" class="py-16 sm:py-24 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="inline-block px-3 py-1 bg-slate-200 text-slate-800 text-xs font-bold rounded-full uppercase tracking-wider mb-3">Tanya Jawab</span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Pertanyaan yang Sering Diajukan</h2>
            <p class="text-slate-600 mt-3 text-sm sm:text-base">Temukan jawaban cepat seputar pemesanan, hasil cetak, dan prosedur sesi foto di CuitBox Studio.</p>
        </div>

        <div class="space-y-4">
            
            <!-- FAQ 1 -->
            <div class="glass-card rounded-2xl border border-slate-200 overflow-hidden">
                <button type="button" onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 hover:text-brand-blue transition-colors">
                    <span class="text-sm sm:text-base">Berapa jumlah orang yang muat di dalam satu booth?</span>
                    <span class="material-symbols-outlined transition-transform duration-200">expand_more</span>
                </button>
                <div class="faq-content hidden px-5 pb-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    Booth kami dirancang luas dan nyaman untuk 2 hingga 6 orang sekaligus. Untuk grup yang lebih besar, Anda bisa berpose bertingkat dengan tetap nyaman di depan kamera sudut lebar (*wide-angle lens*).
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="glass-card rounded-2xl border border-slate-200 overflow-hidden">
                <button type="button" onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 hover:text-brand-blue transition-colors">
                    <span class="text-sm sm:text-base">Apakah seluruh file foto digital (softfile) bisa didownload gratis?</span>
                    <span class="material-symbols-outlined transition-transform duration-200">expand_more</span>
                </button>
                <div class="faq-content hidden px-5 pb-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    <strong>Ya, 100% Gratis!</strong> Setiap sesi foto di CuitBox Studio sudah termasuk seluruh softfile resolusi tinggi (HD) dan video rekaman animasi (GIF) yang bisa diunduh langsung ke HP melalui scan QR Code di layar atau struk.
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="glass-card rounded-2xl border border-slate-200 overflow-hidden">
                <button type="button" onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 hover:text-brand-blue transition-colors">
                    <span class="text-sm sm:text-base">Bagaimana cara check-in saat tiba di lokasi booth?</span>
                    <span class="material-symbols-outlined transition-transform duration-200">expand_more</span>
                </button>
                <div class="faq-content hidden px-5 pb-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    Setelah melakukan booking online, Anda akan mendapatkan <strong>Kode Tiket / QR Barcode</strong>. Setibanya di booth, cukup pindai (*scan*) QR code tersebut di scanner kiosk atau masukkan 6-digit kode verifikasi Anda untuk langsung memulai sesi foto.
                </div>
            </div>

            <!-- FAQ 4 -->
            <div class="glass-card rounded-2xl border border-slate-200 overflow-hidden">
                <button type="button" onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 hover:text-brand-blue transition-colors">
                    <span class="text-sm sm:text-base">Metode pembayaran apa saja yang diterima?</span>
                    <span class="material-symbols-outlined transition-transform duration-200">expand_more</span>
                </button>
                <div class="faq-content hidden px-5 pb-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    Kami mendukung pembayaran instan QRIS (GoPay, OVO, ShopeePay, DANA, LinkAja) serta Virtual Account seluruh bank nasional (BCA, Mandiri, BNI, BRI, Permata) yang terverifikasi otomatis dalam 1 detik.
                </div>
            </div>

        </div>
    </section>

    <!-- ============================================================ -->
    <!-- 10. FINAL CALL TO ACTION BANNER -->
    <!-- ============================================================ -->
    <section class="py-12 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="bg-gradient-to-r from-brand-blue via-indigo-600 to-purple-600 rounded-3xl p-8 sm:p-14 text-white text-center relative overflow-hidden shadow-2xl shadow-brand-blue/30">
            <div class="relative z-10 max-w-2xl mx-auto space-y-6">
                <span class="inline-block px-3.5 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-extrabold uppercase tracking-widest text-brand-lime">
                    Level Up Your Memories
                </span>
                <h2 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
                    Siap Mengabadikan Momen Seru Hari Ini?
                </h2>
                <p class="text-sm sm:text-base text-blue-100 max-w-lg mx-auto">
                    Pilih booth favoritmu sekarang, bawa sahabat atau pasanganmu, dan buat kenangan manis tak terlupakan bersama CuitBox Studio!
                </p>
                <div class="pt-2">
                    <a href="<?= base_url('booking') ?>" class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl text-sm sm:text-base font-black text-slate-900 bg-brand-lime hover:bg-lime-300 hover:scale-105 active:scale-95 shadow-lg shadow-black/20 transition-all duration-200">
                        <span>Pesan Jadwal Foto Sekarang</span>
                        <span class="material-symbols-outlined text-xl">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================ -->
    <!-- 11. FOOTER -->
    <!-- ============================================================ -->
    <footer class="bg-white/80 border-t border-slate-200/80 pt-12 pb-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8 pb-10 border-b border-slate-200/70">
            
            <div class="md:col-span-2 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-brand-blue text-white flex items-center justify-center font-black">
                        <span class="material-symbols-outlined text-xl">photo_camera</span>
                    </div>
                    <span class="text-xl font-black text-slate-900 tracking-tight">CuitBox Studio</span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 max-w-sm leading-relaxed">
                    Platform photobox otomatis & self-photo studio modern dengan teknologi kamera DSLR, cetak thermal instan, dan softfile digital lengkap.
                </p>
            </div>

            <div>
                <h4 class="text-xs font-black uppercase tracking-wider text-slate-900 mb-4">Navigasi Cepat</h4>
                <ul class="space-y-2 text-xs sm:text-sm text-slate-600 font-medium">
                    <li><a href="#how-it-works" class="hover:text-brand-blue transition-colors">Cara Kerja</a></li>
                    <li><a href="#pricing" class="hover:text-brand-blue transition-colors">Paket & Harga</a></li>
                    <li><a href="#frames" class="hover:text-brand-blue transition-colors">Desain Frame</a></li>
                    <li><a href="#booths" class="hover:text-brand-blue transition-colors">Lokasi Booth</a></li>
                    <li><a href="#faq" class="hover:text-brand-blue transition-colors">FAQ & Bantuan</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-xs font-black uppercase tracking-wider text-slate-900 mb-4">Layanan & Bantuan</h4>
                <ul class="space-y-2 text-xs sm:text-sm text-slate-600 font-medium">
                    <li><a href="<?= base_url('statusbooking') ?>" class="hover:text-brand-blue transition-colors">Cek Status Tiket</a></li>
                    <li><a href="<?= base_url('help') ?>" class="hover:text-brand-blue transition-colors">Pusat Bantuan</a></li>
                    <li><a href="<?= base_url('login') ?>" class="hover:text-brand-blue transition-colors">Login Pelanggan</a></li>
                    <li><a href="<?= base_url('admin/login') ?>" class="hover:text-brand-blue transition-colors">Admin Portal</a></li>
                </ul>
            </div>

        </div>

        <div class="max-w-7xl mx-auto pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-3">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Semua Sistem CuitBox Beroperasi Normal (Online)</span>
            </div>
            <div>
                © 2026 CuitBox Studio. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- ============================================================ -->
    <!-- JAVASCRIPT CONTROLLERS -->
    <!-- ============================================================ -->
    <script>
        // Toggle Mobile Nav Menu
        function toggleMobileNav() {
            const menu = document.getElementById('mobile-nav-menu');
            const icon = document.getElementById('nav-icon-menu');
            if (menu) {
                const isHidden = menu.classList.contains('hidden');
                if (isHidden) {
                    menu.classList.remove('hidden');
                    if (icon) icon.textContent = 'close';
                } else {
                    menu.classList.add('hidden');
                    if (icon) icon.textContent = 'menu';
                }
            }
        }

        // Frame Category Filter
        function filterFrames(category) {
            const buttons = document.querySelectorAll('.frame-filter-btn');
            buttons.forEach(btn => {
                if (btn.dataset.category === category) {
                    btn.className = 'frame-filter-btn px-4 py-2 rounded-xl text-xs font-bold bg-slate-900 text-white shadow-sm transition-all';
                } else {
                    btn.className = 'frame-filter-btn px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 transition-all';
                }
            });

            const cards = document.querySelectorAll('.frame-card');
            cards.forEach(card => {
                if (category === 'all' || card.dataset.cat === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // FAQ Accordion Toggle
        function toggleFaq(button) {
            const content = button.nextElementSibling;
            const icon = button.querySelector('.material-symbols-outlined');
            if (content) {
                const isHidden = content.classList.contains('hidden');
                // Close other open faqs
                document.querySelectorAll('.faq-content').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('#faq .material-symbols-outlined').forEach(ic => ic.style.transform = 'rotate(0deg)');
                
                if (isHidden) {
                    content.classList.remove('hidden');
                    if (icon) icon.style.transform = 'rotate(180deg)';
                }
            }
        }
    </script>
</body>
</html>