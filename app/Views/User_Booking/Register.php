<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>CuitBox Studio - Buat Akun</title>
    
    <!-- Tailwind CSS v3 CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
    
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    
    <style>
        /* ===== TYPOGRAPHY ===== */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        
        /* ===== LAYOUT & BACKGROUND ===== */
        .gradient-bg {
            background: linear-gradient(135deg, #bae6fd 0%, #fbcfe8 100%);
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
        
        .photo-frame {
            transform: rotate(-2deg);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .photo-frame:hover {
            transform: rotate(0deg);
            box-shadow: 0 25px 30px -8px rgb(0 0 0 / 0.15);
        }
        
        .glass-badge {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: transform 0.3s ease;
        }
        
        .glass-badge:hover {
            transform: rotate(0deg) scale(1.05);
        }
        
        .cta-button-gradient {
            background: linear-gradient(90deg, #d9f99d 0%, #bef264 100%);
            box-shadow: 0 10px 15px -3px rgba(190, 242, 100, 0.4);
            transition: all 0.3s ease;
            border-radius: 9999px;
            cursor: pointer;
        }
        
        .cta-button-gradient:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 25px -5px rgba(190, 242, 100, 0.5);
        }
        
        .cta-button-gradient:active {
            transform: scale(0.95);
        }
        
        /* ===== COUNTRY CODE SELECTOR ===== */
        .country-code-selector {
            position: relative;
            min-width: 90px;
            max-width: 110px;
            flex-shrink: 0;
        }
        .country-code-selector select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 32px;
            cursor: pointer;
            border: none;
            background-color: transparent;
            padding: 12px 32px 12px 16px;
            font-size: 14px;
            font-weight: 500;
            color: #1e293b;
            width: 100%;
            height: 100%;
            outline: none;
            border-radius: 9999px 0 0 9999px;
        }
        .country-code-selector select:focus {
            outline: none;
        }
        
        /* ===== PHONE INPUT WRAPPER ===== */
        .phone-input-wrapper {
            display: flex;
            align-items: stretch;
            background-color: #f8f7fa;
            border: 2px solid transparent;
            border-radius: 9999px;
            transition: all 0.3s ease;
            width: 100%;
        }
        .phone-input-wrapper:focus-within {
            background-color: #ffffff;
            border-color: #60a5fa;
            box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.1);
        }
        .phone-input-wrapper.input-error {
            border-color: #ef4444;
        }
        .phone-input-wrapper.input-error:focus-within {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }
        .phone-input-wrapper.input-success {
            border-color: #22c55e;
        }
        .phone-input-wrapper .phone-number-input {
            flex: 1;
            border: none;
            padding: 12px 16px;
            font-size: 14px;
            background: transparent;
            outline: none;
            color: #1e293b;
            min-width: 0;
            border-radius: 0 9999px 9999px 0;
        }
        .phone-input-wrapper .phone-number-input::placeholder {
            color: #9ca3af;
        }
        

        /* ===== INPUT FIELDS - Corner Radius 16px (sama dengan button) ===== */
        .input-field-bg {
            background-color: #f8f7fa;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            border-radius: 16px !important;
            padding: 12px 16px;
            width: 100%;
            font-size: 14px;
            color: #1e293b;
            outline: none;
            box-sizing: border-box;
            height: 48px;
        }

        .input-field-bg:focus {
            background-color: #ffffff;
            border-color: #60a5fa;
            box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.1);
            outline: none;
        }

        .input-field-bg.input-error {
            border-color: #ef4444;
        }

        .input-field-bg.input-error:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .input-field-bg.input-success {
            border-color: #22c55e;
        }

        .input-field-bg.input-success:focus {
            border-color: #22c55e;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
        }

        .input-field-bg::placeholder {
            color: #9ca3af;
        }

        /* ===== PHONE INPUT WRAPPER ===== */
        .phone-input-wrapper {
            display: flex;
            align-items: stretch;
            background-color: #f8f7fa;
            border: 2px solid transparent;
            border-radius: 16px !important;
            transition: all 0.3s ease;
            width: 100%;
            overflow: hidden;
        }

        .country-code-selector select {
            border-radius: 16px 0 0 16px !important;
        }

        .phone-input-wrapper .phone-number-input {
            border-radius: 0 16px 16px 0 !important;
        }


        /* ===== ALERT MESSAGES ===== */
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px 16px;
            border-radius: 9999px;
            font-size: 13px;
            margin-bottom: 16px;
            animation: slideDown 0.4s ease-out;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px 16px;
            border-radius: 9999px;
            font-size: 13px;
            margin-bottom: 16px;
            animation: slideDown 0.4s ease-out;
        }
        
        .input-error-text {
            color: #ef4444;
            font-size: 12px;
            margin-top: 4px;
            display: block;
            margin-left: 4px;
            animation: fadeIn 0.3s ease-out;
        }
        
        /* ===== PASSWORD STRENGTH ===== */
        .password-strength-bar {
            height: 4px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 6px;
        }
        
        .password-strength-bar .bar {
            height: 100%;
            width: 0%;
            border-radius: 4px;
            transition: width 0.4s ease, background-color 0.4s ease;
        }
        
        .password-strength-bar .bar.weak {
            width: 33%;
            background: #ef4444;
        }
        
        .password-strength-bar .bar.medium {
            width: 66%;
            background: #f59e0b;
        }
        
        .password-strength-bar .bar.strong {
            width: 100%;
            background: #22c55e;
        }
        
        .password-strength-text {
            font-size: 10px;
            color: #9ca3af;
            margin-top: 4px;
            display: block;
            transition: color 0.3s ease;
        }
        
        .password-strength-text.weak-text {
            color: #ef4444;
        }
        
        .password-strength-text.medium-text {
            color: #f59e0b;
        }
        
        .password-strength-text.strong-text {
            color: #22c55e;
        }
        
        /* ===== ANIMATIONS ===== */
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
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        
        .animate-pulse-slow {
            animation: pulse 3s ease-in-out infinite;
        }
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 640px) {
            .photo-frame {
                transform: rotate(0deg);
            }
            
            .glass-badge {
                bottom: -8px;
                left: -8px;
                padding: 8px 12px;
            }
            
            .glass-badge p:first-child {
                font-size: 7px;
            }
            
            .glass-badge p:last-child {
                font-size: 9px;
            }
            .country-code-selector {
                min-width: 70px;
                max-width: 85px;
            }
            .country-code-selector select {
                padding: 10px 28px 10px 12px;
                font-size: 12px;
                min-width: 60px;
            }
            .phone-input-wrapper .phone-number-input {
                padding: 10px 12px;
                font-size: 13px;
            }
        }
        
        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
        
        /* ===== IMAGE CONTAINER ===== */
        .image-container {
            width: 320px;
            height: 320px;
            overflow: hidden;
            border-radius: 12px;
        }
        
        @media (min-width: 640px) {
            .image-container {
                width: 340px;
                height: 340px;
            }
        }
        
        @media (min-width: 768px) {
            .image-container {
                width: 380px;
                height: 380px;
            }
        }
        
        @media (min-width: 1024px) {
            .image-container {
                width: 400px;
                height: 400px;
            }
        }
        
        /* ===== PHOTOBOX DECORATION ===== */
        .photobox-border {
            border: 3px solid rgba(255, 255, 255, 0.3);
            box-shadow: inset 0 0 30px rgba(0, 0, 0, 0.05);
        }
        
        .flash-effect {
            animation: flash 3s ease-in-out infinite;
        }
        
        @keyframes flash {
            0%, 90%, 100% { opacity: 0.3; }
            95% { opacity: 1; }
        }
        
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        
        .buat-akun-btn {
            transition: all 0.3s ease;
            border-radius: 9999px;
            cursor: pointer;
            background: #d9f99d;
            color: #1e293b;
            font-weight: 700;
            padding: 14px 32px;
            border: none;
            box-shadow: 0 10px 15px -3px rgba(190, 242, 100, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            width: 100%;
            gap: 8px;
            font-size: 16px;
        }
        .buat-akun-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 25px -5px rgba(190, 242, 100, 0.5);
        }
        .buat-akun-btn:active {
            transform: scale(0.98);
        }
        
        /* ===== VALIDATION STYLES ===== */
        .field-error {
            color: #ef4444;
            font-size: 12px;
            margin-top: 4px;
            display: block;
            margin-left: 4px;
            animation: fadeIn 0.3s ease-out;
        }
        .field-success {
            color: #22c55e;
            font-size: 12px;
            margin-top: 4px;
            display: block;
            margin-left: 4px;
            animation: fadeIn 0.3s ease-out;
        }
    </style>
</head>
<body>
    <!-- BEGIN: Page Wrapper -->
    <div class="page-wrapper">
        <!-- BEGIN: MainContainer with Gradient -->
        <div class="w-full main-container-rounded gradient-bg flex flex-col min-h-screen" data-purpose="registration-page-wrapper">
            
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
            
            <!-- BEGIN: ContentArea -->
            <div class="flex-1 flex flex-col lg:flex-row items-center p-6 sm:p-8 lg:p-12 xl:p-16 gap-8 lg:gap-12 xl:gap-16 bg-white/40 backdrop-blur-sm" data-purpose="main-content-area">
                
                <!-- BEGIN: LeftColumn (Image Card) -->
                <div class="w-full lg:w-1/2 flex justify-center items-center" data-purpose="hero-image-column">
                    <div class="relative">
                        <!-- Main Image Card -->
                        <div class="relative bg-white p-2 sm:p-3 rounded-2xl photo-frame">
                            <!-- Image Container - Kotak Persegi dengan efek photobox -->
                            <div class="image-container relative overflow-hidden bg-gradient-to-br from-gray-900 via-purple-900 to-blue-900 rounded-xl photobox-border">
                                
                                <!-- ===== GAMBAR TEMAN DI STUDIO PHOTOBOX ===== -->
                                <img 
                                    alt="4 Teman bersenang-senang di studio photobox" 
                                    class="w-full h-full object-cover object-center" 
                                    data-purpose="feature-image" 
                                    src="https://i.pinimg.com/736x/64/d9/80/64d980c84b973450f762481174a04162.jpg"
                                    onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22400%22%3E%3Crect width=%22400%22 height=%22400%22 fill=%22%231a1a2e%22/%3E%3Crect x=%2250%22 y=%2250%22 width=%22300%22 height=%22300%22 rx=%2220%22 fill=%22%232d2d44%22 stroke=%22%23e94560%22 stroke-width=%224%22/%3E%3Ctext x=%2250%25%22 y=%2235%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%23ffffff%22 font-size=%2224%22 font-family=%22sans-serif%22%3E📸 PHOTOBOX%3C/text%3E%3Ctext x=%2250%25%22 y=%2255%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%23e94560%22 font-size=%2218%22 font-family=%22sans-serif%22%3E4 Teman%3C/text%3E%3Ctext x=%2250%25%22 y=%2270%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%239999bb%22 font-size=%2214%22 font-family=%22sans-serif%22%3E2 Pria + 2 Wanita%3C/text%3E%3Ccircle cx=%22100%22 cy=%22320%22 r=%2215%22 fill=%22%23e94560%22 opacity=%220.5%22/%3E%3Ccircle cx=%22300%22 cy=%22320%22 r=%2215%22 fill=%22%23e94560%22 opacity=%220.5%22/%3E%3C/svg%3E'"
                                >
                                
                                <!-- Efek Lampu Photobox (Flash) -->
                                <div class="absolute inset-0 flash-effect bg-white/5"></div>
                                
                                <!-- Decorative - Lampu LED di sudut photobox -->
                                <div class="absolute top-2 left-2 flex gap-1">
                                    <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></div>
                                    <div class="w-2 h-2 rounded-full bg-yellow-400 animate-pulse" style="animation-delay: 0.5s;"></div>
                                    <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse" style="animation-delay: 1s;"></div>
                                    <div class="w-2 h-2 rounded-full bg-blue-400 animate-pulse" style="animation-delay: 1.5s;"></div>
                                </div>
                                
                                <!-- Decorative - Frame photobox -->
                                <div class="absolute inset-0 border-4 border-white/10 rounded-xl"></div>
                                <div class="absolute inset-2 border border-white/5 rounded-lg"></div>
                                
                                <!-- Label "PHOTOBOX" di atas -->
                                <div class="absolute top-3 left-1/2 -translate-x-1/2 bg-black/50 backdrop-blur-sm text-white text-[10px] font-bold px-4 py-1 rounded-full border border-white/20">
                                    📸 CUITBOX STUDIO
                                </div>
                                
                                <!-- Icon kamera di sudut -->
                                <div class="absolute bottom-3 right-3 bg-black/50 backdrop-blur-sm rounded-full p-2">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                
                                <!-- Overlay Gradient -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                            </div>
                            
                            <!-- Badge on bottom left -->
                            <div class="absolute -bottom-4 -left-3 sm:-bottom-6 sm:-left-6 glass-badge p-3 sm:p-4 px-4 sm:px-6 rounded-xl shadow-lg w-36 sm:w-48" data-purpose="info-badge">
                                <span class="block text-[8px] sm:text-[10px] font-bold text-blue-500 uppercase tracking-widest mb-0.5 sm:mb-1">EST. 2026</span>
                                <p class="text-[10px] sm:text-xs font-extrabold text-slate-700 leading-tight">CIPTAKAN MOMEN MU!</p>
                            </div>
                            
                            <!-- Decorative Dots -->
                            <div class="absolute -top-4 -right-4 sm:-top-6 sm:-right-6 w-12 h-12 sm:w-16 sm:h-16 bg-blue-200/40 rounded-full blur-xl hidden sm:block animate-pulse"></div>
                            <div class="absolute -bottom-4 -right-4 sm:-bottom-6 sm:-right-6 w-8 h-8 sm:w-12 sm:h-12 bg-pink-200/40 rounded-full blur-xl hidden sm:block"></div>
                        </div>
                    </div>
                </div>
                <!-- END: LeftColumn -->
                
                <!-- BEGIN: RightColumn (Form) -->
                <div class="w-full lg:w-1/2 max-w-md lg:max-w-lg" data-purpose="registration-form-column">
                    <!-- Header -->
                    <div class="mb-6 sm:mb-8">
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-2">Buat Akun</h2>
                        <p class="text-slate-600 text-sm leading-relaxed max-w-xs" id="timerText">
                            Akun Digunakan Untuk Kemudahan Booking Photobox Anda Selanjutnya, Let's go 5 Seconds!
                        </p>
                    </div>

                    <!-- Alert Messages -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert-success">
                            <span class="font-bold">✓</span> <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert-error">
                            <span class="font-bold">✕</span> <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    
        <!-- Form di register.php -->
<form action="<?= base_url('schedule') ?>" method="POST" class="space-y-4 sm:space-y-5" data-purpose="signup-form" id="registerForm">
    <?= csrf_field() ?>
                        
                        <!-- Field: Full Name -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5 ml-1" for="full_name">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input 
                                class="input-field-bg" 
                                id="full_name" 
                                name="name"
                                placeholder="Gen z Gengs" 
                                value="<?= old('name') ?>"
                                type="text"
                                required
                                autofocus
                            >
                            <span class="field-error" id="name-error" style="display:none;"></span>
                        </div>
                        
                        <!-- Field: Email -->
                        <div>
                           <label class="block text-sm font-semibold text-slate-700 mb-1.5 ml-1" for="email">Email <span class="text-red-500">*</span></label>
                           <input
                                class="input-field-bg" 
                                id="email" 
                                name="email"
                                placeholder="gen_z_gengs@example.com" 
                                value="<?= old('email') ?>"
                                type="email"
                                required
                            >
                            <span class="field-error" id="email-error" style="display:none;"></span>
                        </div>

                        <!-- Field: Phone -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5 ml-1" for="phone">No. Telepon <span class="text-red-500">*</span></label>
                            <div class="phone-input-wrapper">
                                <div class="country-code-selector">
                                    <select id="country_code" name="country_code">
                                        <option value="+62" selected>🇮🇩 +62</option>
                                        <option value="+60">🇲🇾 +60</option>
                                        <option value="+65">🇸🇬 +65</option>
                                        <option value="+63">🇵🇭 +63</option>
                                        <option value="+66">🇹🇭 +66</option>
                                        <option value="+84">🇻🇳 +84</option>
                                        <option value="+91">🇮🇳 +91</option>
                                        <option value="+86">🇨🇳 +86</option>
                                        <option value="+81">🇯🇵 +81</option>
                                        <option value="+82">🇰🇷 +82</option>
                                        <option value="+1">🇺🇸 +1</option>
                                        <option value="+44">🇬🇧 +44</option>
                                        <option value="+61">🇦🇺 +61</option>
                                        <option value="+49">🇩🇪 +49</option>
                                        <option value="+33">🇫🇷 +33</option>
                                        <option value="+39">🇮🇹 +39</option>
                                        <option value="+34">🇪🇸 +34</option>
                                        <option value="+55">🇧🇷 +55</option>
                                        <option value="+7">🇷🇺 +7</option>
                                        <option value="+27">🇿🇦 +27</option>
                                        <option value="+20">🇪🇬 +20</option>
                                        <option value="+234">🇳🇬 +234</option>
                                        <option value="+254">🇰🇪 +254</option>
                                    </select>
                                </div>
                                <input 
                                    class="phone-number-input" 
                                    id="phone" 
                                    name="phone"
                                    placeholder="88212341234" 
                                    value="<?= old('phone') ?>"
                                    type="text"
                                    inputmode="numeric"
                                    autocomplete="tel"
                                    maxlength="12"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.startsWith('0')) this.value = this.value.slice(1);"
                                    required
                                >
                            </div>
                            <span class="field-error" id="phone-error" style="display:none;"></span>
                            <div class="text-xs text-slate-400 mt-1 ml-1">Contoh: 88212341234 (hanya angka, tanpa 0 di depan)</div>
                        </div>

                        <!-- Field: Password -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5 ml-1" for="password">Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input 
                                    class="input-field-bg" 
                                    id="password" 
                                    name="password"
                                    placeholder="********" 
                                    type="password"
                                    required
                                >
                                <button 
                                    class="absolute right-3 sm:right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors" 
                                    type="button" 
                                    onclick="togglePassword('password')"
                                >
                                    <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="password-strength-bar">
                                <div class="bar" id="passwordStrengthBar"></div>
                            </div>
                            <span class="password-strength-text" id="passwordStrengthText"></span>
                            <span class="field-error" id="password-error" style="display:none;"></span>
                        </div>

                        <!-- Field: Confirm Password -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5 ml-1" for="confirm_password">Konfirmasi Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input 
                                    class="input-field-bg" 
                                    id="confirm_password" 
                                    name="confirm_password"
                                    placeholder="********" 
                                    type="password"
                                    required
                                >
                                <button 
                                    class="absolute right-3 sm:right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors" 
                                    type="button" 
                                    onclick="togglePassword('confirm_password')"
                                >
                                    <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                    </svg>
                                </button>
                            </div>
                            <span class="field-error" id="confirm-error" style="display:none;"></span>
                        </div>
                        
                        <!-- CTA Button -->
                        <div class="pt-2 sm:pt-4">
                            <button type="button" class="buat-akun-btn" id="submitBtn">
                                <span>Buat Akun</span>
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" fill-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Login Link -->
                        <div class="text-center mt-4 sm:mt-6">
                            <p class="text-slate-500 text-sm">
                                Sudah punya akun? <a class="text-blue-500 font-semibold hover:text-blue-600 hover:underline transition-colors" href="#">Masuk di sini</a>
                            </p>
                        </div>
                    </form>
                </div>
                <!-- END: RightColumn -->
                
            </div>
            <!-- END: ContentArea -->
            
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

    <!-- ===== JAVASCRIPT ===== -->
    <script>
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            if (!input) return;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const firstNameInput = document.getElementById('full_name');
            if (firstNameInput) firstNameInput.focus();
        });

        let seconds = 5;
        const timerElement = document.getElementById('timerText');
        if (timerElement) {
            setInterval(() => {
                if (seconds > 0) {
                    timerElement.textContent = `Akun Digunakan Untuk Kemudahan Booking Photobox Anda Selanjutnya, Let's go ${seconds} Seconds!`;
                    seconds--;
                } else {
                    timerElement.textContent = `Akun Digunakan Untuk Kemudahan Booking Photobox Anda Selanjutnya, Let's go!`;
                }
            }, 1000);
        }

        // ===== PASSWORD STRENGTH VALIDATION =====
        document.getElementById('password')?.addEventListener('input', function() {
            const password = this.value;
            const bar = document.getElementById('passwordStrengthBar');
            const text = document.getElementById('passwordStrengthText');
            if (!bar || !text) return;
            
            let strength = 0;
            let message = '';
            let className = '';
            let textClass = '';
            let errors = [];
            
            if (password.length === 0) {
                bar.style.width = '0%';
                bar.className = 'bar';
                text.textContent = '';
                text.className = 'password-strength-text';
                return;
            }
            
            const hasUpper = /[A-Z]/.test(password);
            const hasLower = /[a-z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
            const hasDot = /\./.test(password);
            const isLengthValid = password.length >= 8;
            
            if (hasUpper) strength += 25;
            if (hasLower) strength += 25;
            if (hasNumber) strength += 25;
            if (hasSpecial) strength += 25;
            
            if (!isLengthValid) errors.push('Minimal 8 karakter');
            if (!hasUpper) errors.push('Huruf kapital');
            if (!hasLower) errors.push('Huruf kecil');
            if (!hasNumber) errors.push('Angka');
            if (!hasSpecial) errors.push('Karakter khusus (!@#$%^&*)');
            if (hasDot) errors.push('Tidak boleh mengandung titik (.)');
            
            if (strength === 100 && !hasDot && isLengthValid) {
                message = '✅ Password kuat!';
                className = 'strong';
                textClass = 'strong-text';
            } else if (strength >= 50 && !hasDot) {
                message = '⚠️ Password sedang. ' + errors.join(', ');
                className = 'medium';
                textClass = 'medium-text';
            } else {
                message = '❌ Password lemah. ' + errors.join(', ');
                className = 'weak';
                textClass = 'weak-text';
            }
            
            bar.style.width = strength + '%';
            bar.className = 'bar ' + className;
            text.textContent = message;
            text.className = 'password-strength-text ' + textClass;
        });

        // ===== VALIDASI KONFIRMASI PASSWORD =====
        document.getElementById('confirm_password')?.addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirm = this.value;
            const errorText = document.getElementById('confirm-error');
            
            if (confirm.length > 0 && password !== confirm) {
                this.style.borderColor = '#ef4444';
                errorText.textContent = '❌ Password tidak cocok';
                errorText.style.color = '#ef4444';
                errorText.style.display = 'block';
                this.classList.remove('input-success');
                this.classList.add('input-error');
            } else if (confirm.length > 0 && password === confirm) {
                this.style.borderColor = '#22c55e';
                errorText.textContent = '✅ Password cocok';
                errorText.style.color = '#22c55e';
                errorText.style.display = 'block';
                this.classList.remove('input-error');
                this.classList.add('input-success');
            } else {
                this.style.borderColor = '';
                errorText.style.display = 'none';
                this.classList.remove('input-error', 'input-success');
            }
        });

        // ===== VALIDASI NAMA LENGKAP (Harus ada Huruf Kapital dan Huruf Kecil) =====
        document.getElementById('full_name')?.addEventListener('input', function() {
            const error = document.getElementById('name-error');
            const value = this.value.trim();
            
            if (value.length === 0) {
                error.style.display = 'none';
                this.classList.remove('input-error', 'input-success');
                return;
            }
            
            const hasUpper = /[A-Z]/.test(value);
            const hasLower = /[a-z]/.test(value);
            const hasNumber = /\d/.test(value);
            
            if (value.length < 4) {
                error.textContent = '❌ Minimal 4 karakter';
                error.style.display = 'block';
                this.classList.remove('input-success');
                this.classList.add('input-error');
            } else if (!hasUpper || !hasLower) {
                error.textContent = '❌ Nama harus mengandung huruf kapital dan huruf kecil';
                error.style.display = 'block';
                this.classList.remove('input-success');
                this.classList.add('input-error');
            } else if (hasNumber) {
                error.textContent = '❌ Nama tidak boleh mengandung angka';
                error.style.display = 'block';
                this.classList.remove('input-success');
                this.classList.add('input-error');
            } else {
                error.textContent = '✅ Nama valid';
                error.style.color = '#22c55e';
                error.style.display = 'block';
                this.classList.remove('input-error');
                this.classList.add('input-success');
            }
        });

        // ===== VALIDASI EMAIL (Hanya huruf kecil, angka, dan format email) =====
        document.getElementById('email')?.addEventListener('input', function() {
            const error = document.getElementById('email-error');
            const value = this.value.trim();
            
            if (value.length === 0) {
                error.style.display = 'none';
                this.classList.remove('input-error', 'input-success');
                return;
            }
            
            // Cek apakah ada huruf kapital
            if (/[A-Z]/.test(value)) {
                error.textContent = '❌ Email tidak boleh menggunakan huruf kapital';
                error.style.color = '#ef4444';
                error.style.display = 'block';
                this.classList.remove('input-success');
                this.classList.add('input-error');
                return;
            }
            
            // Cek format email (hanya huruf kecil, angka, @, dan domain)
            const emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;
            if (emailPattern.test(value)) {
                error.textContent = '✅ Email valid';
                error.style.color = '#22c55e';
                error.style.display = 'block';
                this.classList.remove('input-error');
                this.classList.add('input-success');
            } else {
                error.textContent = '❌ Format email tidak valid (contoh: nama@domain.com)';
                error.style.color = '#ef4444';
                error.style.display = 'block';
                this.classList.remove('input-success');
                this.classList.add('input-error');
            }
        });

        // ===== VALIDASI FORM SEBELUM SUBMIT =====
        document.getElementById('submitBtn')?.addEventListener('click', function(e) {
            e.preventDefault();
            
            let isValid = true;
            
            // 1. Validasi Nama Lengkap
            const name = document.getElementById('full_name');
            const nameError = document.getElementById('name-error');
            const nameValue = name.value.trim();
            const hasUpper = /[A-Z]/.test(nameValue);
            const hasLower = /[a-z]/.test(nameValue);
            const hasNumber = /\d/.test(nameValue);
            
            if (nameValue.length < 4) {
                nameError.textContent = '❌ Nama lengkap minimal 4 karakter';
                nameError.style.color = '#ef4444';
                nameError.style.display = 'block';
                name.classList.add('input-error');
                isValid = false;
            } else if (!hasUpper || !hasLower) {
                nameError.textContent = '❌ Nama harus mengandung huruf kapital dan huruf kecil';
                nameError.style.color = '#ef4444';
                nameError.style.display = 'block';
                name.classList.add('input-error');
                isValid = false;
            } else if (hasNumber) {
                nameError.textContent = '❌ Nama tidak boleh mengandung angka';
                nameError.style.color = '#ef4444';
                nameError.style.display = 'block';
                name.classList.add('input-error');
                isValid = false;
            } else {
                nameError.textContent = '✅ Nama valid';
                nameError.style.color = '#22c55e';
                nameError.style.display = 'block';
                name.classList.remove('input-error');
                name.classList.add('input-success');
            }
            
            // 2. Validasi Email
            const email = document.getElementById('email');
            const emailError = document.getElementById('email-error');
            const emailValue = email.value.trim();
            const emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;
            
            if (!emailValue) {
                emailError.textContent = '❌ Email wajib diisi';
                emailError.style.color = '#ef4444';
                emailError.style.display = 'block';
                email.classList.add('input-error');
                isValid = false;
            } else if (/[A-Z]/.test(emailValue)) {
                emailError.textContent = '❌ Email tidak boleh menggunakan huruf kapital';
                emailError.style.color = '#ef4444';
                emailError.style.display = 'block';
                email.classList.add('input-error');
                isValid = false;
            } else if (!emailPattern.test(emailValue)) {
                emailError.textContent = '❌ Format email tidak valid (contoh: nama@domain.com)';
                emailError.style.color = '#ef4444';
                emailError.style.display = 'block';
                email.classList.add('input-error');
                isValid = false;
            } else {
                emailError.textContent = '✅ Email valid';
                emailError.style.color = '#22c55e';
                emailError.style.display = 'block';
                email.classList.remove('input-error');
                email.classList.add('input-success');
            }
            
            // 3. Validasi Nomor Telepon
            const phone = document.getElementById('phone');
            const phoneError = document.getElementById('phone-error');
            const phoneValue = phone.value.replace(/[^0-9]/g, '');
            const wrapper = phone.parentElement;
            if (!phoneValue || phoneValue.length < 11 || phoneValue.length > 12) {
                phoneError.textContent = '❌ Nomor telepon minimal 11 digit dan maksimal 12 digit (hanya angka)';
                phoneError.style.color = '#ef4444';
                phoneError.style.display = 'block';
                wrapper.classList.add('input-error');
                isValid = false;
            } else {
                phoneError.textContent = '✅ Nomor telepon valid';
                phoneError.style.color = '#22c55e';
                phoneError.style.display = 'block';
                wrapper.classList.remove('input-error');
                wrapper.classList.add('input-success');
            }
            
            // 4. Validasi Password
            const password = document.getElementById('password');
            const passwordError = document.getElementById('password-error');
            const hasUpperPw = /[A-Z]/.test(password.value);
            const hasLowerPw = /[a-z]/.test(password.value);
            const hasNumberPw = /\d/.test(password.value);
            const hasSpecialPw = /[!@#$%^&*(),.?":{}|<>]/.test(password.value);
            const hasDotPw = /\./.test(password.value);
            
            if (password.value.length < 8) {
                passwordError.textContent = '❌ Password minimal 8 karakter';
                passwordError.style.color = '#ef4444';
                passwordError.style.display = 'block';
                password.classList.add('input-error');
                isValid = false;
            } else if (!hasUpperPw || !hasLowerPw || !hasNumberPw || !hasSpecialPw || hasDotPw) {
                let msg = '❌ Password harus mengandung: ';
                if (!hasUpperPw) msg += 'huruf kapital, ';
                if (!hasLowerPw) msg += 'huruf kecil, ';
                if (!hasNumberPw) msg += 'angka, ';
                if (!hasSpecialPw) msg += 'karakter khusus (!@#$%^&*), ';
                if (hasDotPw) msg += 'tidak boleh ada titik (.)';
                passwordError.textContent = msg;
                passwordError.style.color = '#ef4444';
                passwordError.style.display = 'block';
                password.classList.add('input-error');
                isValid = false;
            } else {
                passwordError.textContent = '✅ Password kuat';
                passwordError.style.color = '#22c55e';
                passwordError.style.display = 'block';
                password.classList.remove('input-error');
                password.classList.add('input-success');
            }
            
            // 5. Validasi Konfirmasi Password
            const confirm = document.getElementById('confirm_password');
            const confirmError = document.getElementById('confirm-error');
            if (!confirm.value || confirm.value !== password.value) {
                confirmError.textContent = '❌ Konfirmasi password harus sama dengan password';
                confirmError.style.color = '#ef4444';
                confirmError.style.display = 'block';
                confirm.classList.add('input-error');
                isValid = false;
            } else {
                confirmError.textContent = '✅ Password cocok';
                confirmError.style.color = '#22c55e';
                confirmError.style.display = 'block';
                confirm.classList.remove('input-error');
                confirm.classList.add('input-success');
            }
            
            // Jika semua valid, submit form
            if (isValid) {
                document.getElementById('registerForm').submit();
            } else {
                const firstError = document.querySelector('.input-error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            }
        });
    </script>
</body>
</html>