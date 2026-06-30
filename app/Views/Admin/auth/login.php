<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Login - CuitBox Studio') ?></title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
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
        .bg-gradient-light {
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 40% 20%, #c1def8bb 0px, transparent 50%),
                radial-gradient(at 80% 0%, #badaf7bb 0px, transparent 50%),
                radial-gradient(at 0% 50%, #95c7f3bb 0px, transparent 50%),
                radial-gradient(at 80% 50%, #8ec3f2bb 0px, transparent 50%),
                radial-gradient(at 0% 100%, #7fbcf0bb 0px, transparent 50%),
                radial-gradient(at 80% 100%, #69b0eebb 0px, transparent 50%),
                radial-gradient(at 0% 0%, #a4cff4bb 0px, transparent 50%);
        }
    </style>
</head>
<body class="bg-gradient-light min-h-screen flex items-center justify-center p-4 font-sans antialiased text-slate-800">
    
    <div class="w-full max-w-md">
        <!-- Logo Branding -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white border border-slate-200 shadow-sm mb-4 text-primary">
                <span class="material-symbols-outlined text-4xl">photo_camera</span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">CuitBox Studio</h1>
            <p class="text-slate-500 text-sm mt-1">Sistem Manajemen Portal Admin</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white/80 backdrop-blur-xl border border-white shadow-2xl rounded-3xl p-8">
            <h2 class="text-xl font-bold text-slate-800 mb-6 text-center">Silakan Masuk ke Akun Anda</h2>

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
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all bg-white" />
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kata Sandi</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-3 text-slate-400 text-[20px]">lock</span>
                        <input type="password" name="password" id="password" required
                               placeholder="••••••••"
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all bg-white" />
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-primary focus:ring-primary/30" />
                        Ingat Saya
                    </label>
                    <a href="#" onclick="showResetModal(event)" class="text-primary font-bold hover:underline">Lupa password?</a>
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

    <!-- Modal Lupa Password -->
    <div id="resetModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-sm w-full mx-4 transform transition-all scale-95 opacity-0 duration-300" id="resetModalContent">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl flex justify-center items-center">
                <h4 class="font-bold text-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-500">lock_reset</span>
                    Lupa Password?
                </h4>
            </div>
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center mx-auto mb-4 border border-blue-100">
                    <span class="material-symbols-outlined text-3xl text-blue-500">support_agent</span>
                </div>
                <p class="text-sm text-slate-600 leading-relaxed mb-6">
                    Silakan hubungi <strong>administrator utama</strong> atau <strong>tim developer</strong> CuitBox Studio untuk mereset password akun Anda.
                </p>
                <button onclick="closeResetModal()" class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm rounded-xl transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function showResetModal(e) {
            e.preventDefault();
            const modal = document.getElementById('resetModal');
            const content = document.getElementById('resetModalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeResetModal() {
            const modal = document.getElementById('resetModal');
            const content = document.getElementById('resetModalContent');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }
    </script>
</body>
</html>
