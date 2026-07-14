<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Booking Success | CuitBox Studio</title>
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
            cursor: pointer;
        }
        .btn-primary:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 25px -5px rgba(190, 242, 100, 0.5);
        }
        .btn-primary:active {
            transform: scale(0.98);
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
    </style>
</head>
<body>
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
            <main class="relative px-6 sm:px-8 pt-12 pb-12 overflow-hidden bg-white/40 backdrop-blur-sm flex items-center justify-center" data-purpose="hero-content">
                <div class="w-full max-w-[600px] mx-auto flex flex-col items-center text-center success-circle-animation">
                    <!-- Icon Graphic -->
                    <div class="relative w-32 h-32 mb-6">
                        <div class="absolute inset-0 border-[6px] border-[#d9f99d]/40 rounded-full scale-[1.4]"></div>
                        <div class="absolute inset-0 border-[6px] border-[#d9f99d]/60 rounded-full scale-[1.2]"></div>
                        <div class="w-full h-full rounded-full bg-[#bef264] flex items-center justify-center shadow-lg">
                            <span class="material-symbols-outlined text-[#365314] text-[64px] font-bold">check</span>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-4">
                        Booking Berhasil!
                    </h2>
                    <p class="text-slate-500 text-base leading-relaxed max-w-sm mb-2">
                        kode booking Anda telah dikirim ke email Anda.
                    </p>
                    <p class="text-slate-700 text-base font-semibold max-w-sm mb-6">
                        CuitBox Team sudah menyimpan Jadwal Booking Photo untuk momen spesial Anda!
                    </p>
                    
                    <!-- Action Button -->
                    <div class="w-full max-w-sm">
                        <button class="btn-primary">
                            <span>Simpan dan Lihat Kode Booking Anda</span>
                            <span class="material-symbols-outlined text-xl">arrow_forward</span>
                        </button>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Button interaction
            const buttons = document.querySelectorAll('button');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    if (this.classList.contains('btn-primary')) {
                        console.log('Navigating to booking details...');
                        alert('Mengarahkan ke halaman detail booking...');
                    }
                    if (this.textContent.includes('Kembali ke Beranda')) {
                        console.log('Navigating to home...');
                        alert('Kembali ke beranda...');
                    }
                });
            });
        });
    </script>
</body>
</html>