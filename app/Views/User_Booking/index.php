<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?= $title ?? 'CuitBox Studio' ?></title>
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
        .theme-card {
            transition: transform 0.2s ease-in-out, border-color 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
            border-radius: 24px;
        }
        .theme-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        }
        .theme-card.selected {
            border-color: #22c55e;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.15), 0 20px 40px rgba(0, 0, 0, 0.08);
        }
        .floating-badge {
            font-size: 0.6rem;
            padding: 2px 8px;
            border-radius: 9999px;
            position: absolute;
            text-transform: uppercase;
            font-weight: bold;
        }
        .check-mark {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .theme-card.selected .check-mark {
            opacity: 1;
        }
        .card-overlay {
            position: absolute;
            inset: 0;
            background: rgba(34, 197, 94, 0.05);
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 20px;
        }
        .theme-card.selected .card-overlay {
            opacity: 1;
        }
        .theme-card:active .card-overlay {
            opacity: 0.3;
        }
        .moment-highlight {
            background-color: #4A5568;
            color: white;
            padding: 0 8px;
            border-radius: 4px;
            display: inline-block;
        }
        @keyframes scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee {
            display: flex;
            width: max-content;
            animation: scroll 20s linear infinite;
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .start-booking-btn {
            transition: all 0.3s ease;
            border-radius: 9999px;
            cursor: pointer;
            background: #d9f99d;
            color: #1e293b;
            font-weight: 700;
            padding: 12px 32px;
            border: none;
            box-shadow: 0 10px 15px -3px rgba(190, 242, 100, 0.4);
            display: inline-block;
            text-decoration: none;
        }
        .start-booking-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 25px -5px rgba(190, 242, 100, 0.5);
        }
        .start-booking-btn:active {
            transform: scale(0.95);
        }
        /* Language Dropdown */
        .language-dropdown {
            position: absolute;
            right: 0;
            margin-top: 8px;
            width: 180px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            z-index: 50;
            display: none;
        }
        .language-dropdown.show {
            display: block;
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .language-dropdown a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            transition: background 0.2s ease;
            font-size: 14px;
            color: #1e293b;
            text-decoration: none;
        }
        .language-dropdown a:hover {
            background: #f1f5f9;
        }
        .language-dropdown a.active {
            background: #eff6ff;
            color: #2563eb;
            font-weight: 600;
        }
        .language-dropdown a .check {
            margin-left: auto;
            color: #2563eb;
        }
    </style>
</head>
<body>
    <!-- BEGIN: Page Wrapper -->
    <div class="page-wrapper">
        <!-- BEGIN: MainContainer with Gradient #e0f2fe to #fce7f3 -->
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
        </a>
    </div>
</header>
<!-- END: MainHeader -->

            <!-- BEGIN: HeroSection -->
            <main class="relative px-6 sm:px-8 pt-8 pb-8 overflow-hidden bg-white/40 backdrop-blur-sm" data-purpose="hero-content">
                <!-- Decorative Badges -->
                <span class="absolute top-10 left-8 bg-brand-lightGreen text-[10px] font-bold px-2 py-0.5 rounded-full text-slate-700" style="background:#d9f99d;">LEVEL UP YOUR MEMORIES💖</span>
                <span class="absolute top-24 right-1/4 bg-brand-lightGreen text-[10px] font-bold px-2 py-0.5 rounded-full text-slate-700" style="background:#d9f99d;">NO HD MISSES</span>
                <span class="absolute top-36 right-1/4 bg-brand-lightGreen text-[10px] font-bold px-2 py-0.5 rounded-full text-slate-700" style="background:#d9f99d;">STUDENT DISCOUNT -30% 💖</span>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <!-- Hero Text -->
                    <div class="z-10">
                        <h1 class="text-4xl md:text-2xl font-extrabold leading-tight mb-10">
                            Capture Every <span class="moment-highlight">Moment</span><br> With Love or Best Friends Forever
                        </h1>
                        <p class="text-slate-500 text-sm max-w-sm mb-8 leading-relaxed">
                            <span class="moment-highlight">Premium </span> Photobox Experience
                        </p>
                        
                        <!-- ===== START BOOKING BUTTON - FORM TO REGISTER ===== -->
                        <form action="<?= base_url('register/from-landing') ?>" method="POST" class="inline-block">
                            <?= csrf_field() ?>
                            <input type="hidden" name="from_landing" value="true">
                            <button type="submit" class="start-booking-btn" id="startBookingBtn">
                                Start Booking
                            </button>
                        </form>

                        <div class="flex gap-12 border-t border-slate-300 pt-8 mt-8">
                            <div>
                                <div class="text-2xl font-extrabold">15k+</div>
                                <div class="text-[10px] text-slate-400 uppercase tracking-widest">Moments</div>
                            </div>
                            <div>
                                <div class="text-2xl font-extrabold">4.9/5</div>
                                <div class="text-[10px] text-slate-400 uppercase tracking-widest">Rating</div>
                            </div>
                        </div>
                    </div>
                    <!-- Hero Collage Image -->
                    <div class="relative flex justify-center md:justify-end">
                        <div class="relative w-full max-w-md">
                            <img alt="Photobox Strips" class="w-full h-auto drop-shadow-2xl rounded-lg" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAGgnPUCclbfhn6AtbXFjK1fX_cKPqcVbzY2Rgi9oszZK10a85K99JzNHeU2tdxEVIefMPuzyGzzcQ1yqg_3hKZovYWn8B7QJdwp0_WxhFC8kKmCiSMNOpleKKs5qoOHhv7xo19U-jVO8dZ-gN3KhXWQ-vUexzqSUejqE_mGimGmOU0veDZCh-Lv7_1ulBsfiqwHdT2zsKd_fnB1IWqAH0KrsjOaQuZPqX-foOAXszZ0MtcJcgkQwG5sw">
                        </div>
                    </div>
                </div>
            </main>
            <!-- END: HeroSection -->

            <!-- BEGIN: MarqueeBanner -->
            <div class="bg-brand-blue overflow-hidden py-3 text-white font-bold text-[10px] tracking-widest" style="background:#00B4D8;" data-purpose="scrolling-banner">
                <div class="animate-marquee whitespace-nowrap">
                    <span class="mx-4">INSTA-READY MEMORIES •</span>
                    <span class="mx-4">HD VIBES •</span>
                    <span class="mx-4">STUDENT DISCOUNTS •</span>
                    <span class="mx-4">INSTANT PRINTS •</span>
                    <span class="mx-4">10/10 EXPERIENCE •</span>
                    <span class="mx-4">INSTA-READY MEMORIES •</span>
                    <span class="mx-4">HD VIBES •</span>
                    <span class="mx-4">STUDENT DISCOUNTS •</span>
                    <span class="mx-4">INSTANT PRINTS •</span>
                    <span class="mx-4">10/10 EXPERIENCE •</span>
                </div>
            </div>
            <!-- END: MarqueeBanner -->

            <!-- BEGIN: ThemeSelection -->
            <section class="py-16 px-6 sm:px-8 flex-grow" data-purpose="theme-grid">
                <div class="text-center mb-12">
                    <span class="moment-highlight">CuitBox Studio</span>
                    <p class="text-[20px] text-slate-800 font-medium">This is a Memory Photobox💖</p>
                </div>
                
                <!-- Theme Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 gap-4 max-w-4xl mx-auto">

                    <!-- Theme 1: Magazine News -->
                    <div class="theme-card flex flex-col items-center group p-0 bg-white shadow-sm hover:shadow-xl border border-white rounded-xl" data-card="magazine">
                        <div class="relative w-full aspect-square overflow-hidden rounded-t-xl flex items-center justify-center">
                            <img class="w-full h-full object-cover" src="https://cdn.ayobandung.id/crop/original/medias/2026/Mar/25/fotoboxkoran.jpg" alt="Magazine News Theme">
                            <div class="card-overlay"></div>
                            <div class="absolute bottom-2 left-2 bg-green-500 text-white rounded-full p-0.5 check-mark">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                        <div class="p-2 w-full text-center">
                            <span class="font-bold text-xs text-slate-700">Magazine News</span>
                        </div>
                    </div>

                    <!-- Theme 2: Retro 80s -->
                    <div class="theme-card flex flex-col items-center group p-0 bg-white shadow-sm hover:shadow-xl border border-white rounded-xl" data-card="retro">
                        <div class="relative w-full aspect-square overflow-hidden rounded-t-xl flex items-center justify-center">
                            <img class="w-full h-full object-cover" src="https://i.pinimg.com/originals/02/7b/8d/027b8dc899968a6fb495267e7cdb533d.jpg" alt="Retro 80s Theme">
                            <div class="card-overlay"></div>
                        </div>
                        <div class="p-2 w-full text-center">
                            <span class="font-bold text-xs text-slate-700">Retro 80s</span>
                        </div>
                    </div>

                    <!-- Theme 3: Pastel Color -->
                    <div class="theme-card flex flex-col items-center group p-0 bg-white shadow-sm hover:shadow-xl border border-white rounded-xl" data-card="pastel">
                        <div class="relative w-full aspect-square overflow-hidden rounded-t-xl flex items-center justify-center">
                            <img class="w-full h-full object-cover" src="https://img.magnific.com/premium-photo/beautiful-pastel-color-schemes-homes-interior-design_758367-62769.jpg" alt="Pastel Color Theme">
                            <div class="card-overlay"></div>
                            <div class="absolute bottom-2 left-2 bg-green-500 text-white rounded-full p-0.5 check-mark">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                        <div class="p-2 w-full text-center">
                            <span class="font-bold text-xs text-slate-700">Pastel Color</span>
                        </div>
                    </div>

                    <!-- Theme 4: Minimalist Clean -->
                    <div class="theme-card flex flex-col items-center group p-0 bg-white shadow-sm hover:shadow-xl border border-white rounded-xl" data-card="minimalist">
                        <div class="relative w-full aspect-square overflow-hidden rounded-t-xl flex items-center justify-center">
                            <img class="w-full h-full object-cover" src="https://images.squarespace-cdn.com/content/v1/63dde481bbabc6724d988548/4f246e68-c4c6-4bf7-ab17-36792e1beb8b/_501b5536-1cc2-4675-b813-9d68911eee81.jfif" alt="Minimalist Clean Theme">
                            <div class="card-overlay"></div>
                            <div class="absolute bottom-2 left-2 bg-green-500 text-white rounded-full p-0.5 check-mark">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                        <div class="p-2 w-full text-center">
                            <span class="font-bold text-xs text-slate-700">Minimalist Clean</span>
                        </div>
                    </div>

                    <!-- Theme 5: Cute & Kawaii -->
                    <div class="theme-card flex flex-col items-center group p-0 bg-white shadow-sm hover:shadow-xl border border-white rounded-xl" data-card="cute">
                        <div class="relative w-full aspect-square overflow-hidden rounded-t-xl flex items-center justify-center">
                            <img class="w-full h-full object-cover" src="https://wallpapers.com/images/hd/kawaii-room-1500-x-1500-wallpaper-qh7zrfbr4o71dpae.jpg" alt="Cute & Kawaii Theme">
                            <div class="card-overlay"></div>
                            <div class="absolute bottom-2 left-2 bg-green-500 text-white rounded-full p-0.5 check-mark">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                        <div class="p-2 w-full text-center">
                            <span class="font-bold text-xs text-slate-700">Cute &amp; Kawaii</span>
                        </div>
                    </div>

                    <!-- Theme 6: All Faculty UI -->
                    <div class="theme-card flex flex-col items-center group p-0 bg-white shadow-sm hover:shadow-xl border border-white rounded-xl" data-card="ui">
                        <div class="relative w-full aspect-square overflow-hidden rounded-t-xl flex items-center justify-center">
                            <img class="w-full h-full object-cover" src="https://cdn.antaranews.com/cache/1200x800/2023/04/03/Rektorat-Kampus-UI-Depok.jpg" alt="All Faculty UI Theme">
                            <div class="card-overlay"></div>
                            <div class="absolute bottom-2 left-2 bg-green-500 text-white rounded-full p-0.5 check-mark">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                        <div class="p-2 w-full text-center">
                            <span class="font-bold text-xs text-slate-700">All Faculty University of Indonesia</span>
                        </div>
                    </div>
                </div>
            </section>
            <!-- END: ThemeSelection -->

            <!-- BEGIN: Footer -->
            <footer class="flex flex-col sm:flex-row items-center justify-between px-6 sm:px-8 py-4 footer-bg border-t border-gray-100 text-slate-500 text-xs font-medium gap-2 sm:gap-0" data-purpose="site-footer">
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
        // ===== TOGGLE LANGUAGE DROPDOWN =====
        function toggleLanguage() {
            const dropdown = document.getElementById('languageDropdown');
            if (dropdown) {
                dropdown.classList.toggle('show');
            }
        }

        // ===== TUTUP DROPDOWN SAAT KLIK DI LUAR =====
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('languageDropdown');
            const button = event.target.closest('[aria-label="Language"]');
            const dropdownContent = event.target.closest('.language-dropdown');
            
            if (dropdown && dropdown.classList.contains('show')) {
                if (!button && !dropdownContent) {
                    dropdown.classList.remove('show');
                }
            }
        });

        // ===== TUTUP DROPDOWN DENGAN ESCAPE KEY =====
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const dropdown = document.getElementById('languageDropdown');
                if (dropdown && dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        });

        // ===== THEME CARD SELECTION =====
        document.addEventListener('DOMContentLoaded', function() {
            // Theme Card click/select functionality
            const cards = document.querySelectorAll('.theme-card');
            
            cards.forEach(card => {
                card.addEventListener('click', function(e) {
                    // Remove selected class from all cards
                    cards.forEach(c => {
                        c.classList.remove('selected');
                    });
                    
                    // Add selected class to clicked card
                    this.classList.add('selected');
                });
            });
        });
    </script>
</body>
</html>