<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Login - CuitBox Studio') ?></title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: '#003d9b',
                        secondary: '#001848',
                        accent: '#008393'
                    }
                }
            }
        }
    </script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        .bg-pattern {
            background-color: #001848;
            background-image: radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%),
                              radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%),
                              radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%),
                              radial-gradient(at 0% 100%, hsla(339,49%,30%,1) 0, transparent 50%),
                              radial-gradient(at 100% 100%, hsla(253,16%,7%,1) 0, transparent 50%);
        }
    </style>
</head>
<body class="bg-pattern min-h-screen flex items-center justify-center p-4 font-sans antialiased text-slate-800">
    
    <div class="w-full max-w-md">
        <!-- Logo Branding -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 shadow-xl mb-4">
                <span class="material-symbols-outlined text-4xl text-white">photo_camera</span>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">CuitBox Studio</h1>
            <p class="text-slate-300 text-sm mt-1">Sistem Manajemen Portal Admin</p>
        </div>

        <!-- Form Card -->
        <div class="glass border border-white/20 rounded-3xl shadow-2xl p-8">
            <h2 class="text-xl font-bold text-secondary mb-6 text-center">Silakan Masuk ke Akun Anda</h2>

            <!-- Success Alert -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-600">check_circle</span>
                    <span><?= esc(session()->getFlashdata('success')) ?></span>
                </div>
            <?php endif; ?>

            <!-- Error Alert -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-red-600">error</span>
                    <span><?= esc(session()->getFlashdata('error')) ?></span>
                </div>
            <?php endif; ?>

            <!-- Validation Errors -->
            <?php if (!empty(session()->getFlashdata('errors'))): ?>
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('admin/login') ?>" method="POST" class="space-y-5">
                <?= csrf_field() ?>
                
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Email</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-3 text-slate-400 text-[20px]">mail</span>
                        <input type="email" name="email" id="email" 
                               value="<?= old('email') ?>" required
                               placeholder="contoh@cuitboxstudio.com"
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all bg-white/50" />
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kata Sandi</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-3 text-slate-400 text-[20px]">lock</span>
                        <input type="password" name="password" id="password" required
                               placeholder="••••••••"
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all bg-white/50" />
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-primary focus:ring-primary/30" />
                        Ingat Saya
                    </label>
                    <a href="#" onclick="alert('Silakan hubungi administrator utama atau tim developer untuk me-reset password.')" class="text-primary font-bold hover:underline">Lupa password?</a>
                </div>

                <button type="submit" class="w-full py-3 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/20 hover:bg-blue-800 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">login</span>Masuk
                </button>
            </form>
        </div>

        <p class="text-center text-slate-400 text-xs mt-8">
            &copy; 2026 CuitBox Studio. Hak Cipta Dilindungi.
        </p>
    </div>

</body>
</html>
