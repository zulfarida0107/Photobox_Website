<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>CuitBox Studio - Atur Ulang Password</title>
    
    <!-- Tailwind CSS v3 CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #bae6fd 0%, #fbcfe8 100%);
            min-height: 100vh;
        }
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
            justify-content: center;
            padding: 32px 24px;
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(4px);
        }
        .header-bg, .footer-bg {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
        }
        .form-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            border-radius: 24px;
            padding: 32px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }
        .input-field {
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px 16px;
            width: 100%;
            font-size: 14px;
            color: #1e293b;
            outline: none;
            transition: all 0.2s ease;
        }
        .input-field:focus {
            background-color: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .btn-submit {
            background: #d9f99d;
            color: #1e293b;
            font-weight: 700;
            padding: 14px 24px;
            border-radius: 14px;
            border: none;
            width: 100%;
            cursor: pointer;
            box-shadow: 0 10px 15px -3px rgba(190, 242, 100, 0.4);
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-submit:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 25px -5px rgba(190, 242, 100, 0.5);
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="main-container">
            <header class="flex items-center justify-between px-6 sm:px-8 py-4 header-bg border-b border-gray-100">
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">CuitBox Studio</h1>
            </header>

            <div class="content-area">
                <div class="form-card">
                    <div class="text-center mb-6">
                        <div class="w-12 h-12 bg-lime-100 rounded-2xl flex items-center justify-center text-lime-800 mx-auto mb-3 shadow-sm">
                            <span class="material-symbols-outlined text-2xl">lock_reset</span>
                        </div>
                        <h2 class="text-2xl font-extrabold text-slate-800">Atur Ulang Password</h2>
                        <p class="text-xs text-slate-500 mt-1">Buat password baru yang aman untuk akun Anda</p>
                    </div>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-700 text-xs font-semibold rounded-xl">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('reset-password/process') ?>" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="token" value="<?= esc($token ?? '') ?>">
                        <input type="hidden" name="email" value="<?= esc($email ?? '') ?>">

                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="password">Password Baru</label>
                            <input class="input-field" id="password" name="password" type="password" placeholder="Minimal 8 karakter" required minlength="8" autofocus>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="confirm_password">Konfirmasi Password</label>
                            <input class="input-field" id="confirm_password" name="confirm_password" type="password" placeholder="Ulangi password baru" required minlength="8">
                        </div>

                        <button type="submit" class="btn-submit">
                            <span>Simpan Password Baru</span>
                            <span class="material-symbols-outlined text-xl">arrow_forward</span>
                        </button>
                    </form>

                    <div class="text-center mt-6">
                        <a href="<?= base_url('login') ?>" class="text-xs font-semibold text-blue-600 hover:underline">
                            Kembali ke Login
                        </a>
                    </div>
                </div>
            </div>

            <footer class="footer-bg flex items-center justify-center px-6 py-4 border-t border-gray-100 text-slate-500 text-xs">
                © 2026 CuitBox Studio. All rights reserved.
            </footer>
        </div>
    </div>
</body>
</html>
