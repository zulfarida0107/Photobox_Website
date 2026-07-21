<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Booking Selesai - CuitBox Studio</title>
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
            flex-direction: column;
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
        .btn-camera {
            background: #1e293b;
            transition: all 0.3s ease;
            border-radius: 16px;
            padding: 16px 24px;
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            font-size: 14px;
        }
        .btn-camera:hover {
            background: #334155;
            transform: scale(1.02);
        }
        .btn-camera:active {
            transform: scale(0.98);
        }
        .btn-camera .material-symbols-outlined {
            font-size: 24px;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .success-circle-animation {
            animation: scale-up 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        @keyframes scale-up {
            from { transform: scale(0.5); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .barcode-wrapper {
            background: white;
            padding: 16px;
            border-radius: 16px;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .barcode-wrapper img {
            display: block;
            width: 200px;
            height: 200px;
            object-fit: contain;
        }
        .detail-card {
            background: #bef264;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 4px 12px rgba(190, 242, 100, 0.2);
            transition: all 0.3s ease;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .detail-card .icon {
            color: #1e293b;
            margin-bottom: 4px;
        }
        .detail-card .icon svg {
            width: 20px;
            height: 20px;
        }
        .detail-card .label {
            color: #1e293b;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .detail-card .value {
            color: #1e293b;
            font-size: 14px;
            font-weight: 700;
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
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-2">
                        Booking Selesai!
                    </h2>
                    <p class="text-slate-600 text-base leading-relaxed max-w-sm mb-6">
                        Terima kasih atas pesanan Anda. Siapkan kode di bawah ini untuk memulai sesi foto Anda!
                    </p>
                    
                    <!-- Booking Code Card -->
                    <div class="w-full bg-[#D9F99D] rounded-2xl p-6 mb-6 shadow-sm">
                        <p class="text-[10px] font-bold text-lime-900 tracking-widest uppercase mb-1">Kode Booking Anda</p>
                        <h3 class="text-3xl md:text-4xl font-black text-slate-800 mb-4">FF-2024-889</h3>
                        
                        <!-- Barcode - Full tanpa tambahan -->
                        <div class="flex justify-center">
                            <div class="barcode-wrapper">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=BOOKING-FF-2024-889" alt="Barcode Booking">
                            </div>
                        </div>
                        
                        <div class="mt-4 bg-lime-900/20 px-4 py-2 rounded-full inline-flex items-center gap-2">
                            <svg class="w-4 h-4 text-lime-900" fill="currentColor" viewBox="0 0 20 20">
                                <path clip-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" fill-rule="evenodd"/>
                            </svg>
                            <p class="text-[10px] md:text-xs font-medium text-lime-900">Scan atau masukkan kode ini di mesin photobooth saat tiba</p>
                        </div>
                    </div>

                    <!-- Details Grid - Style seperti button -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full mb-6">
                        <div class="detail-card">
                            <div class="icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                    <path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                </svg>
                            </div>
                            <p class="label">Tipe Booth</p>
                            <p class="value">Retro Vibes</p>
                        </div>
                        <div class="detail-card">
                            <div class="icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                </svg>
                            </div>
                            <p class="label">Waktu Sesi</p>
                            <p class="value">09 October 2024 • 13:00</p>
                        </div>
                    </div>
                    
                    <!-- Action Button - Camera -->
                    <button class="btn-camera">
                        <span class="material-symbols-outlined">photo_camera</span>
                        <span>take a photo from your device for your Photobooth Booking Code</span>
                    </button>
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
            // Button interactions
            const cameraButton = document.querySelector('.btn-camera');
            if (cameraButton) {
                cameraButton.addEventListener('click', function() {
                    alert('Membuka kamera untuk mengambil foto kode booking...');
                });
            }
        });
    </script>
</body>
</html>