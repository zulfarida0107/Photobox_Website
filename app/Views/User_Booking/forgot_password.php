<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>CuitBox Studio - Lupa Password</title>
    
    <!-- Tailwind CSS v3 CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
    
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    
    <style>
        /* ===== RESET & BASE ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #bae6fd 0%, #fbcfe8 100%);
            min-height: 100vh;
        }
        
        /* ===== LAYOUT ===== */
        .page-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: stretch;
            justify-content: center;
        }
        
        .main-container {
            width: 100%;
            background: linear-gradient(135deg, #bae6fd 0%, #fbcfe8 100%);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .content-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 24px;
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(4px);
        }
        
        @media (min-width: 1024px) {
            .content-area {
                flex-direction: row;
                padding: 48px 64px;
                gap: 48px;
            }
        }
        
        /* ===== HEADER & FOOTER ===== */
        .header-bg {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid #f3f4f6;
        }
        
        .footer-bg {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border-top: 1px solid #f3f4f6;
        }
        
        /* ===== LEFT COLUMN - IMAGE ===== */
        .left-column {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        @media (min-width: 1024px) {
            .left-column {
                width: 50%;
            }
        }
        
        .photo-frame {
            transform: rotate(-2deg);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: white;
            padding: 8px;
            border-radius: 16px;
            position: relative;
        }
        
        @media (min-width: 640px) {
            .photo-frame {
                padding: 12px;
            }
        }
        
        .photo-frame:hover {
            transform: rotate(0deg);
            box-shadow: 0 25px 30px -8px rgba(0, 0, 0, 0.15);
        }
        
        .image-container {
            width: 320px;
            height: 320px;
            overflow: hidden;
            border-radius: 12px;
            position: relative;
            background: linear-gradient(135deg, #1a1a2e, #2d2d44, #16213e);
            border: 3px solid rgba(255, 255, 255, 0.3);
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
        
        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
        
        /* Decorative Elements */
        .flash-effect {
            animation: flash 3s ease-in-out infinite;
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.05);
        }
        
        @keyframes flash {
            0%, 90%, 100% { opacity: 0.3; }
            95% { opacity: 1; }
        }
        
        .led-lights {
            position: absolute;
            top: 8px;
            left: 8px;
            display: flex;
            gap: 4px;
        }
        
        .led {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            animation: pulse 1.5s ease-in-out infinite;
        }
        
        .led:nth-child(2) { animation-delay: 0.3s; }
        .led:nth-child(3) { animation-delay: 0.6s; }
        .led:nth-child(4) { animation-delay: 0.9s; }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        
        .photobox-label {
            position: absolute;
            top: 12px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            color: white;
            font-size: 10px;
            font-weight: 700;
            padding: 4px 16px;
            border-radius: 9999px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .camera-icon {
            position: absolute;
            bottom: 12px;
            right: 12px;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            border-radius: 50%;
            padding: 8px;
        }
        
        .camera-icon svg {
            width: 20px;
            height: 20px;
            color: white;
        }
        
        .image-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.4), transparent);
            border-radius: 12px;
        }
        
        .image-border {
            position: absolute;
            inset: 0;
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
        }
        
        .image-border-inner {
            position: absolute;
            inset: 8px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 8px;
        }
        
        /* Badge */
        .glass-badge {
            position: absolute;
            bottom: -16px;
            left: -12px;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 12px 16px;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            width: 144px;
        }
        
        @media (min-width: 640px) {
            .glass-badge {
                bottom: -24px;
                left: -24px;
                padding: 16px 24px;
                width: 192px;
            }
        }
        
        .glass-badge:hover {
            transform: scale(1.05);
        }
        
        .glass-badge .est {
            display: block;
            font-size: 8px;
            font-weight: 700;
            color: #3b82f6;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 4px;
        }
        
        @media (min-width: 640px) {
            .glass-badge .est {
                font-size: 10px;
            }
        }
        
        .glass-badge .slogan {
            font-size: 10px;
            font-weight: 800;
            color: #1e293b;
            line-height: 1.2;
        }
        
        @media (min-width: 640px) {
            .glass-badge .slogan {
                font-size: 12px;
            }
        }
        
        /* Decorative Dots */
        .deco-dot-1 {
            position: absolute;
            top: -16px;
            right: -16px;
            width: 48px;
            height: 48px;
            background: rgba(191, 219, 254, 0.4);
            border-radius: 50%;
            filter: blur(24px);
            display: none;
        }
        
        .deco-dot-2 {
            position: absolute;
            bottom: -16px;
            right: -16px;
            width: 32px;
            height: 32px;
            background: rgba(251, 207, 232, 0.4);
            border-radius: 50%;
            filter: blur(24px);
            display: none;
        }
        
        @media (min-width: 640px) {
            .deco-dot-1 { display: block; }
            .deco-dot-2 { display: block; }
        }
        
        /* ===== RIGHT COLUMN - FORM ===== */
        .right-column {
            width: 100%;
            max-width: 448px;
        }
        
        @media (min-width: 1024px) {
            .right-column {
                width: 50%;
                max-width: 512px;
            }
        }
        
        .form-title {
            font-size: 30px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
        }
        
        @media (min-width: 640px) {
            .form-title {
                font-size: 36px;
            }
        }
        
        .form-subtitle {
            color: #475569;
            font-size: 14px;
            line-height: 1.6;
            max-width: 320px;
        }
        
        /* ===== FORM ELEMENTS ===== */
        .form-group {
            margin-bottom: 16px;
        }
        
        @media (min-width: 640px) {
            .form-group {
                margin-bottom: 20px;
            }
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
            margin-left: 4px;
        }
        
        .form-label .required {
            color: #ef4444;
        }
        
        .input-field {
            background-color: #f8f7fa;
            border: 2px solid transparent;
            border-radius: 16px;
            padding: 12px 16px;
            width: 100%;
            font-size: 14px;
            color: #1e293b;
            outline: none;
            height: 48px;
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            background-color: #ffffff;
            border-color: #60a5fa;
            box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.1);
        }
        
        .input-field.input-error {
            border-color: #ef4444;
        }
        
        .input-field.input-error:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }
        
        .input-field.input-success {
            border-color: #22c55e;
        }
        
        .input-field.input-success:focus {
            border-color: #22c55e;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
        }
        
        .input-field::placeholder {
            color: #9ca3af;
        }
        
        /* Password Field */
        .password-wrapper {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            transition: color 0.3s ease;
        }
        
        .password-toggle:hover {
            color: #475569;
        }
        
        .password-toggle svg {
            width: 16px;
            height: 16px;
        }
        
        @media (min-width: 640px) {
            .password-toggle svg {
                width: 20px;
                height: 20px;
            }
        }
        
        /* ===== BUTTON ===== */
        .btn-submit {
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
            width: 100%;
            gap: 8px;
            font-size: 16px;
        }
        
        .btn-submit:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 25px -5px rgba(190, 242, 100, 0.5);
        }
        
        .btn-submit:active {
            transform: scale(0.98);
        }
        
        .btn-submit svg {
            width: 20px;
            height: 20px;
        }
        
        /* ===== ALERT ===== */
        .alert {
            padding: 10px 16px;
            border-radius: 9999px;
            font-size: 13px;
            margin-bottom: 16px;
            animation: slideDown 0.4s ease-out;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert .icon {
            font-weight: 700;
        }
        
        /* ===== FIELD ERROR ===== */
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
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 640px) {
            .content-area {
                padding: 16px;
                gap: 24px;
            }
            
            .photo-frame {
                transform: rotate(0deg);
            }
            
            .glass-badge {
                bottom: -8px;
                left: -8px;
                padding: 8px 12px;
                width: 120px;
            }
            
            .glass-badge .est {
                font-size: 7px;
            }
            
            .glass-badge .slogan {
                font-size: 9px;
            }
            
            .form-title {
                font-size: 24px;
            }
            
            .header-bg {
                padding: 12px 16px;
            }
            
            .footer-bg {
                padding: 12px 16px;
            }
        }
        
        @media (max-width: 1024px) and (min-width: 640px) {
            .content-area {
                padding: 32px;
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

        /* ===== REMEMBER ME CHECKBOX ===== */
        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #60a5fa;
            cursor: pointer;
        }

        .remember-me label {
            color: #475569;
            font-size: 13px;
            cursor: pointer;
        }

        .forgot-link {
            color: #3b82f6;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: #2563eb;
            text-decoration: underline;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .divider::before {
            margin-right: 16px;
        }
        
        .divider::after {
            margin-left: 16px;
        }
        
        .divider span {
            color: #94a3b8;
            font-size: 12px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Main Container -->
        <div class="main-container">
            
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
 
            <!-- Content Area -->
            <div class="content-area">
                
                <!-- Left Column - Image -->
                <div class="left-column">
                    <div class="relative">
                        <div class="photo-frame">
                            <div class="image-container">
                                <img 
                                    alt="4 Teman bersenang-senang di studio photobox" 
                                    src="https://i.pinimg.com/736x/64/d9/80/64d980c84b973450f762481174a04162.jpg"
                                    onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22400%22%3E%3Crect width=%22400%22 height=%22400%22 fill=%22%231a1a2e%22/%3E%3Crect x=%2250%22 y=%2250%22 width=%22300%22 height=%22300%22 rx=%2220%22 fill=%22%232d2d44%22 stroke=%22%23e94560%22 stroke-width=%224%22/%3E%3Ctext x=%2250%25%22 y=%2235%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%23ffffff%22 font-size=%2224%22 font-family=%22sans-serif%22%3E📸 PHOTOBOX%3C/text%3E%3Ctext x=%2250%25%22 y=%2255%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%23e94560%22 font-size=%2218%22 font-family=%22sans-serif%22%3E4 Teman%3C/text%3E%3Ctext x=%2250%25%22 y=%2270%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%239999bb%22 font-size=%2214%22 font-family=%22sans-serif%22%3E2 Pria + 2 Wanita%3C/text%3E%3Ccircle cx=%22100%22 cy=%22320%22 r=%2215%22 fill=%22%23e94560%22 opacity=%220.5%22/%3E%3Ccircle cx=%22300%22 cy=%22320%22 r=%2215%22 fill=%22%23e94560%22 opacity=%220.5%22/%3E%3C/svg%3E'"
                                >
                                
                                <!-- Decorative Elements -->
                                <div class="flash-effect"></div>
                                
                                <div class="led-lights">
                                    <div class="led" style="background:#ef4444;"></div>
                                    <div class="led" style="background:#fbbf24;"></div>
                                    <div class="led" style="background:#34d399;"></div>
                                    <div class="led" style="background:#60a5fa;"></div>
                                </div>
                                
                                <div class="image-border"></div>
                                <div class="image-border-inner"></div>
                                
                                <div class="photobox-label">📸 CUITBOX STUDIO</div>
                                
                                <div class="camera-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                
                                <div class="image-overlay"></div>
                            </div>
                            
                            <!-- Badge -->
                            <div class="glass-badge">
                                <span class="est">EST. 2026</span>
                                <p class="slogan">CIPTAKAN MOMEN MU!</p>
                            </div>
                            
                            <!-- Decorative Dots -->
                            <div class="deco-dot-1"></div>
                            <div class="deco-dot-2"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column - Form -->
                <div class="right-column">
                    <!-- Header -->
                    <div class="mb-6 sm:mb-8">
                        <h2 class="form-title">Lupa Password?</h2>
                        <p class="form-subtitle">Masukkan email Anda untuk mereset password akun.</p>
                    </div>

                    <!-- Alert Messages -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <span class="icon">✓</span> <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-error">
                            <span class="icon">✕</span> <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->getFlashdata('info')): ?>
                        <div class="alert alert-info" style="background-color: #cce5ff; color: #004085; border: 1px solid #b8daff;">
                            <span class="icon">ℹ</span> <?= session()->getFlashdata('info') ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Form Login -->
                    <form action="<?= base_url('forgot-password/process') ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <!-- Email -->
                        <div class="form-group">
                            <label class="form-label" for="email">Email <span class="required">*</span></label>
                            <input
                                class="input-field" 
                                id="email" 
                                name="email"
                                placeholder="masukkan email Anda" 
                                value="<?= old('email') ?>"
                                type="email"
                                required
                                autofocus
                            >
                            <?php if (session('errors.email')): ?>
                                <span class="field-error" style="display:block;"><?= session('errors.email') ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="submit" class="btn-submit">
                                <span>Kirim Password Baru</span>
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" fill-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </form>

                    <!-- Divider -->
                    <div class="divider">
                        <span>atau</span>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center">
                        <p class="text-slate-500 text-sm">
                            Ingat password Anda? 
                            <a class="text-blue-500 font-semibold hover:text-blue-600 hover:underline transition-colors" href="<?= base_url('login') ?>">Kembali ke Login</a>
                        </p>
                        <p class="text-slate-400 text-xs mt-2">
                            Kembali ke 
                            <a class="text-slate-500 hover:text-slate-700 underline transition-colors" href="<?= base_url('/') ?>">Beranda</a>
                        </p>
                    </div>
                </div>
                
            </div>
            
            <!-- Footer -->
            <footer class="footer-bg flex flex-col sm:flex-row items-center justify-between px-6 sm:px-8 py-4 text-slate-500 text-xs font-medium gap-2 sm:gap-0">
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

    <!-- ===== JAVASCRIPT ===== -->
    <script>
            const value = this.value.trim();
            
            if (value.length === 0) {
                error.style.display = 'none';
                this.classList.remove('input-error', 'input-success');
                return;
            }
            
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (emailPattern.test(value)) {
                error.textContent = '✅ Email valid';
                error.style.color = '#22c55e';
                error.style.display = 'block';
                this.classList.remove('input-error');
    <!-- ===== JAVASCRIPT ===== -->
    <script>
    </script>
</body>
</html>