<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>CuitBox Studio - Pilih Tema Background</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
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
        .header-bg {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
        }
        .footer-bg {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
        }
        .theme-card {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease, border-color 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            border-radius: 16px;
            border: 2px solid transparent;
            cursor: pointer;
            position: relative;
        }
        .theme-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(30, 41, 59, 0.08);
        }
        .theme-card:active, .theme-card.active {
            border-color: #22c55e;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.2), 0 20px 40px rgba(30, 41, 59, 0.08);
            transform: translateY(-8px) scale(0.98);
        }
        .theme-card.selected {
            border-color: #22c55e;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.15), 0 20px 40px rgba(30, 41, 59, 0.08);
        }
        .cta-button-gradient {
            background: linear-gradient(90deg, #d9f99d 0%, #bef264 100%);
            box-shadow: 0 10px 15px -3px rgba(190, 242, 100, 0.4);
            transition: all 0.3s ease;
            border-radius: 9999px;
            position: relative;
            z-index: 10;
        }
        .cta-button-gradient:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 25px -5px rgba(190, 242, 100, 0.5);
        }
        .cta-button-gradient:active {
            transform: scale(0.98);
        }
        .card-image {
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
        }
        .theme-card .check-mark {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .theme-card.selected .check-mark {
            opacity: 1;
        }
        .theme-card .card-overlay {
            position: absolute;
            inset: 0;
            background: rgba(34, 197, 94, 0.05);
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 16px;
            pointer-events: none;
            z-index: 1;
        }
        .theme-card.selected .card-overlay {
            opacity: 1;
        }
        .theme-card:active .card-overlay {
            opacity: 0.3;
        }
        /* Toast/Notification Styles */
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
        /* Card content wrapper untuk memastikan klik pada card tidak mengganggu button */
        .card-content {
            position: relative;
            z-index: 2;
        }
        .card-click-area {
            position: absolute;
            inset: 0;
            z-index: 1;
            cursor: pointer;
            border-radius: 16px;
        }
        .card-content > * {
            position: relative;
            z-index: 2;
        }
        form {
            position: relative;
            z-index: 5;
        }
    </style>
</head>
<body>
    <!-- Toast Container untuk notifikasi -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- BEGIN: Page Wrapper with Gray Background -->
    <div class="page-wrapper">
        <!-- BEGIN: MainContainer with Gradient #B8E4FF to #FCD2E8 -->
        <div class="max-w-[1200px] w-full main-container-rounded gradient-bg">

            <!-- BEGIN: MainHeader -->
            <header class="flex items-center justify-between px-6 sm:px-8 py-4 header-bg border-b border-gray-100" data-purpose="site-header">
                <div class="flex items-center">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">CuitBox Studio</h1>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-4 text-slate-600">
                    <button class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center rounded-full hover:bg-gray-100/50 transition-all" aria-label="Help">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                        </svg>
                    </button>
                    <button class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center rounded-full hover:bg-gray-100/50 transition-all" aria-label="Language">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"/>
                        </svg>
                    </button>
                </div>
            </header>
            <!-- END: MainHeader -->

            <!-- BEGIN: HeroSection -->
            <main class="relative px-6 sm:px-8 pt-8 pb-8 overflow-hidden bg-white/40 backdrop-blur-sm" data-purpose="hero-content">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-extrabold mb-1 text-slate-800">Pilih Tema Background Fotomu</h2>
                    <p class="text-[10px] text-slate-600 font-medium max-w-2xl mx-auto">Temukan gaya yang paling sesuai dengan momen serumu. Mulai dari nuansa retro klasik hingga tampilan modern yang minimalis.</p>
                </div>

                <!-- Theme Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                    <!-- Card 1: Retro Vibes 80's -->
                    <div class="theme-card bg-white/90 overflow-hidden flex flex-col group shadow-sm hover:shadow-xl" data-card="retro" data-booth-id="B001">
                        <div class="card-overlay"></div>
                        <div class="h-48 relative overflow-hidden card-image">
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuCzYV-8cdC0AbTlf-tQkhQVFJ7pt3AaSN5mP33TY3iWTzG-o44h0D4lfcnE2r_wrpv_qwJzRanHQJic3fYNCHYMVf5lZGSm6760daZ7Z9M_tTowWzkoV6jjziCX60i_5lioFJZDb32IwBbsPtE3_nSGMbY4Vl8DJgZqleFw4pA7dZd5flPC7TV011fzIhAcPkqEfut1yIcfzD_whVCVvKWgOs4zxBD0DFar_kAIAhxt1ieaLVJ5f03UR38hsnNTEOhpB2cZgoVePkg" 
                                 alt="Retro Vibes 80's"/>
                            <div class="absolute top-4 right-4 bg-slate-800 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">POPULAR</div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                        </div>
                        <div class="p-6 flex flex-col flex-grow card-content">
                            <h3 class="font-headline-md text-headline-md text-on-surface mb-4 text-xl font-bold text-slate-800">Retro Vibes 80's</h3>
                            <ul class="space-y-3 mb-6 flex-grow">
                                <li class="flex items-center gap-3 font-body-md text-body-md text-slate-600">
                                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-slate-600 text-[18px]">auto_awesome</span>
                                    </div>
                                    Warm Tungsten Glow
                                </li>
                                <li class="flex items-center gap-3 font-body-md text-body-md text-slate-600">
                                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-slate-600 text-[18px]">layers</span>
                                    </div>
                                    Orange Velvet Backdrop
                                </li>
                                <li class="flex items-center gap-3 font-body-md text-body-md text-slate-600">
                                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-slate-600 text-[18px]">photo_camera</span>
                                    </div>
                                    Vintage Cameras &amp; Props
                                </li>
                            </ul>
                            <form action="/booth/store" method="post" class="w-full">
                                <?= csrf_field() ?>
                                <input type="hidden" name="booth_id" value="B001">
                                <button type="submit" class="cta-button-gradient w-full text-slate-800 font-extrabold text-sm py-4 transition-all">
                                    Pilih Tema
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Card 2: Cute and Kawaii -->
                    <div class="theme-card bg-white/90 overflow-hidden flex flex-col group shadow-sm hover:shadow-xl" data-card="cute" data-booth-id="B002">
                        <div class="card-overlay"></div>
                        <div class="h-48 relative overflow-hidden card-image">
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuCOlHiOEZJVcjFro2MV1cnR_GgUZl5m9ZUcgOfY4nS1_EywJflWWogozIG_jJq7RgqttjT9mXj_-TjTa3LTNoFMkXDtJjtTc4d3MUpKKTpKBWoQEVMTfDMRgLb2PKIWcjKOZb_DH9l4ZjJsjlTCZ2thhmA05MOSqR9yMZ6l7lPvAwZdUJ5bpSt-wp5Pi0w5MYDrPuCclXEtBcGhuNhjqUnlL_uV49NsAJRrBYTTvVcmhqkBYi02q4d5Lbm36NOAGTrW7HD675i_mMo" 
                                 alt="Cute and Kawaii"/>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                        </div>
                        <div class="p-6 flex flex-col flex-grow card-content">
                            <h3 class="font-headline-md text-headline-md text-on-surface mb-4 text-xl font-bold text-slate-800">Cute and Kawaii</h3>
                            <ul class="space-y-3 mb-6 flex-grow">
                                <li class="flex items-center gap-3 font-body-md text-body-md text-slate-600">
                                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-slate-600 text-[18px]">wb_sunny</span>
                                    </div>
                                    Ultra Cute Decor
                                </li>
                                <li class="flex items-center gap-3 font-body-md text-body-md text-slate-600">
                                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-slate-600 text-[18px]">sentiment_satisfied</span>
                                    </div>
                                    Study &amp; Fun Neon Sign
                                </li>
                                <li class="flex items-center gap-3 font-body-md text-body-md text-slate-600">
                                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-slate-600 text-[18px]">bubble_chart</span>
                                    </div>
                                    Pastel Aesthetic Props
                                </li>
                            </ul>
                            <form action="/booth/store" method="post" class="w-full">
                                <?= csrf_field() ?>
                                <input type="hidden" name="booth_id" value="B002">
                                <button type="submit" class="cta-button-gradient w-full text-slate-800 font-extrabold text-sm py-4 transition-all">
                                    Pilih Tema
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Card 3: Minimalist and Clean -->
                    <div class="theme-card bg-white/90 overflow-hidden flex flex-col group shadow-sm hover:shadow-xl" data-card="minimalist" data-booth-id="B003">
                        <div class="card-overlay"></div>
                        <div class="h-48 relative overflow-hidden card-image">
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuC6ShC5P8yNfLTa6wNpx1MuVMj2OXRXNDpM5h6TgyE_Lkq7-y1SXYAzVMYAY-Cdj6Td0oSycnJMX7NgneE5ieAmKc-765HOYAMpBFLGTMKmIMwmJvVDtwf_teB9t_CFe4ITFnBQ_dk95pdgS9MQ2OsVfYb9sdARYRxmx8SLXPW1UBZgOs20UHY5VRqt2dzoXVtC3-aEjpROauojLWaAnA_hv4Xy-2AA4YOtC8ydpk4SoNdYnctFq7GTYB3H-Zdn3CRMwZlONuLwlpk" 
                                 alt="Minimalist and Clean"/>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                        </div>
                        <div class="p-6 flex flex-col flex-grow card-content">
                            <h3 class="font-headline-md text-headline-md text-on-surface mb-4 text-xl font-bold text-slate-800">Minimalist and Clean</h3>
                            <ul class="space-y-3 mb-6 flex-grow">
                                <li class="flex items-center gap-3 font-body-md text-body-md text-slate-600">
                                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-slate-600 text-[18px]">palette</span>
                                    </div>
                                    Premium Cream &amp; Beige
                                </li>
                                <li class="flex items-center gap-3 font-body-md text-body-md text-slate-600">
                                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-slate-600 text-[18px]">chair</span>
                                    </div>
                                    Elegant Long Sofa
                                </li>
                                <li class="flex items-center gap-3 font-body-md text-body-md text-slate-600">
                                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-slate-600 text-[18px]">camera_indoor</span>
                                    </div>
                                    High-Class Minimalist Studio
                                </li>
                            </ul>
                            <form action="/booth/store" method="post" class="w-full">
                                <?= csrf_field() ?>
                                <input type="hidden" name="booth_id" value="B003">
                                <button type="submit" class="cta-button-gradient w-full text-slate-800 font-extrabold text-sm py-4 transition-all">
                                    Pilih Tema
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Card 4: Soft and Pastel -->
                    <div class="theme-card bg-white/90 overflow-hidden flex flex-col group shadow-sm hover:shadow-xl" data-card="pastel" data-booth-id="B004">
                        <div class="card-overlay"></div>
                        <div class="h-48 relative overflow-hidden card-image">
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuDM9bpqvtaRLRb7wfKpvzayRx4TB9AJOEfg5uhQe35BGla1oGfZl6PC8BypHTAIefXth1B2x-fVBtwb0j7TtIYjojyklbAJR6sE6z4m0fKs94xecKOgF1a8sIJ5TJoo221tRnTA2W9Gtp7UomxBCywtAU1H4SFKV-zuWT0g-LqV5uN0-MdqrFUcK-FYIM8HCoDBszMi6bapAlEZJ9g-9fo4YgjEf0HQOLo36ctXe5wQvs2_sAtoLxmJsiSgMe8LcBswyvI9uFqmEZ4" 
                                 alt="Soft and Pastel"/>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                        </div>
                        <div class="p-6 flex flex-col flex-grow card-content">
                            <h3 class="font-headline-md text-headline-md text-on-surface mb-4 text-xl font-bold text-slate-800">Soft and Pastel</h3>
                            <ul class="space-y-3 mb-6 flex-grow">
                                <li class="flex items-center gap-3 font-body-md text-body-md text-slate-600">
                                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-slate-600 text-[18px]">blur_on</span>
                                    </div>
                                    Dreamy Diffused Glow
                                </li>
                                <li class="flex items-center gap-3 font-body-md text-body-md text-slate-600">
                                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-slate-600 text-[18px]">cloud</span>
                                    </div>
                                    Watercolor Cloud Backdrop
                                </li>
                                <li class="flex items-center gap-3 font-body-md text-body-md text-slate-600">
                                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-slate-600 text-[18px]">favorite</span>
                                    </div>
                                    Ethereal Romantic Props
                                </li>
                            </ul>
                            <form action="/booth/store" method="post" class="w-full">
                                <?= csrf_field() ?>
                                <input type="hidden" name="booth_id" value="B004">
                                <button type="submit" class="cta-button-gradient w-full text-slate-800 font-extrabold text-sm py-4 transition-all">
                                    Pilih Tema
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Card 5: Magazine News -->
                    <div class="theme-card bg-white/90 overflow-hidden flex flex-col group shadow-sm hover:shadow-xl" data-card="magazine" data-booth-id="B005">
                        <div class="card-overlay"></div>
                        <div class="h-48 relative overflow-hidden card-image">
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuB2ZgKUiCPyoryw-dQfk8hwd32U3twwUrn8nEAWA_dg6PoFg3Rzd-i28WGg1IeGPH1HSxvGcZc6ZswWgb4pGde9jLA2SbHRy8xbbHgIe5m960RQT790aLN3TZdQeIl1grETPolxMToIsFuXEpdo8MVHfmF_MNqwGAF0-pUPCuiGqTSBY3ZZdqYq2_C2KrA39aP41m8c45F9vM0AFGo2K4Wfhxb7075QyU3EzIPPT4uEgUxFqbjlwoUmMKTid53xBipXkqNiE4PJzNI" 
                                 alt="Magazine News"/>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                        </div>
                        <div class="p-6 flex flex-col flex-grow card-content">
                            <h3 class="font-headline-md text-headline-md text-on-surface mb-4 text-xl font-bold text-slate-800">Magazine News</h3>
                            <ul class="space-y-3 mb-6 flex-grow">
                                <li class="flex items-center gap-3 font-body-md text-body-md text-slate-600">
                                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-slate-600 text-[18px]">location_city</span>
                                    </div>
                                    Nostalgic Street View
                                </li>
                                <li class="flex items-center gap-3 font-body-md text-body-md text-slate-600">
                                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-slate-600 text-[18px]">photo_album</span>
                                    </div>
                                    Iconic City Backdrops
                                </li>
                                <li class="flex items-center gap-3 font-body-md text-body-md text-slate-600">
                                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-slate-600 text-[18px]">wb_twilight</span>
                                    </div>
                                    Atmospheric Golden Hour
                                </li>
                            </ul>
                            <form action="/booth/store" method="post" class="w-full">
                                <?= csrf_field() ?>
                                <input type="hidden" name="booth_id" value="B005">
                                <button type="submit" class="cta-button-gradient w-full text-slate-800 font-extrabold text-sm py-4 transition-all">
                                    Pilih Tema
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Card 6: General Universitas Indonesia - Selected by default -->
                    <div class="theme-card bg-white/90 overflow-hidden flex flex-col group shadow-sm hover:shadow-xl selected border-2 border-green-500" data-card="ui" data-booth-id="B006">
                        <div class="card-overlay"></div>
                        <div class="h-48 relative overflow-hidden card-image">
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuDHXOQeCPa08AdZ0kkWS6Uqj1r41ZShIC3Q9-_qC7pH_hXnl68S_Ph23er2N-32eLG55jKOmwu2sZUQ806fK5GHKJOXcp1ELG0RYV1QiGArYzjS1nF_yVXtuLveNyIsqw4g79ARc9irvMxV7i7d2lqkRvE86F0Zt26nI1AgXyJEYXDKokIkHOJYxUXP0DOeGOYjHpR4xiDnma0XL60U2AOxJHdM8dSb4HDAJBbknyPoXfwIYDbu2AvYPxhN5CKT6giPb2GNKZjvhDI" 
                                 alt="All Faculty in University of Indonesia"/>
                            <div class="absolute top-4 right-4 bg-green-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">EXCLUSIVE</div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                            <!-- Check mark -->
                            <div class="absolute bottom-4 right-4 bg-green-500 text-white rounded-full p-1 check-mark">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                        <div class="p-6 flex flex-col flex-grow card-content">
                            <h3 class="font-headline-md text-headline-md text-on-surface mb-4 text-xl font-bold text-slate-800">All Faculty in University of Indonesia</h3>
                            <ul class="space-y-3 mb-6 flex-grow">
                                <li class="flex items-center gap-3 font-body-md text-body-md text-slate-600">
                                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-slate-600 text-[18px]">palette</span>
                                    </div>
                                    UI Yellow &amp; Black Theme
                                </li>
                                <li class="flex items-center gap-3 font-body-md text-body-md text-slate-600">
                                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-slate-600 text-[18px]">school</span>
                                    </div>
                                    Iconic Makara Logo
                                </li>
                                <li class="flex items-center gap-3 font-body-md text-body-md text-slate-600">
                                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-slate-600 text-[18px]">location_on</span>
                                    </div>
                                    University Campus Vibe
                                </li>
                            </ul>
                            <form action="/booth/store" method="post" class="w-full">
                                <?= csrf_field() ?>
                                <input type="hidden" name="booth_id" value="B006">
                                <button type="submit" class="cta-button-gradient w-full text-slate-800 font-extrabold text-sm py-4 transition-all">
                                    Pilih Tema
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
            <!-- END: HeroSection -->

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
          // Click event untuk memilih card secara visual
            cards.forEach(card => {
                    // Remove selected class from all cards
                    cards.forEach(c => {
                        c.classList.remove('selected');
                        c.style.borderColor = 'transparent';
                    });
                    
                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.style.opacity = '0';
                        toast.style.transition = 'opacity 0.5s ease';
                        setTimeout(() => toast.remove(), 500);
                    }
                }, 5000);
            }

            // Handle flash messages from session
            <?php if (session()->getFlashdata('success')): ?>
                showToast('<?= session()->getFlashdata('success') ?>', 'success');
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('error')): ?>
                showToast('<?= session()->getFlashdata('error') ?>', 'error');
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('errors')): ?>
                <?php $errors = session()->getFlashdata('errors'); ?>
                <?php if (is_array($errors)): ?>
                    <?php foreach ($errors as $error): ?>
                        showToast('<?= $error ?>', 'error');
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endif; ?>

            // Handle form submissions with loading state - tanpa mengganggu event click card
            const forms = document.querySelectorAll('form[action="/booth/store"]');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const button = this.querySelector('button[type="submit"]');
                    if (button) {
                        const originalText = button.textContent;
                        button.textContent = '⏳ Memproses...';
                        button.disabled = true;
                        button.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                });
            });

            // Mencegah event click pada card ketika mengklik button
            document.querySelectorAll('.cta-button-gradient').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
    </script>
</body>
</html>