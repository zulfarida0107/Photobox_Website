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
        .card-content {
            position: relative;
            z-index: 2;
        }
        .card-content > * {
            position: relative;
            z-index: 2;
        }
        form {
            position: relative;
            z-index: 5;
        }
        .frame-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 24px;
        }
        @media (min-width: 640px) {
            .frame-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (min-width: 1024px) {
            .frame-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        .frame-grid .theme-card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .frame-grid .theme-card .card-image {
            height: 180px;
        }
        @media (min-width: 768px) {
            .frame-grid .theme-card .card-image {
                height: 200px;
            }
        }
        /* Price tag styling */
        .price-tag {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
        }
        .price-tag-light {
            background: #d1fae5;
            color: #065f46;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
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

            <!-- Main Content -->
            <main class="relative px-6 sm:px-8 pt-8 pb-8 overflow-hidden bg-white/40 backdrop-blur-sm flex-1">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-extrabold mb-1 text-slate-800">Pilih Harga Cetak</h2>
                    <p class="text-[10px] text-slate-600 font-medium max-w-2xl mx-auto">Pilih jumlah cetakan fisik yang sesuai dengan kebutuhan serumu.</p>
                </div>

                <div class="frame-grid">
                    <?php if (!empty($print_options) && is_array($print_options)): ?>
                        <?php foreach ($print_options as $index => $option): ?>
                            <div class="theme-card bg-white/90 overflow-hidden flex flex-col group shadow-sm hover:shadow-xl <?= $index === 0 ? 'selected border-2 border-green-500' : '' ?>" 
                                 data-card="<?= $option['id'] ?>" 
                                 data-print-id="<?= $option['id'] ?>">
                                <div class="card-overlay"></div>
                                <div class="relative overflow-hidden card-image flex items-center justify-center bg-gradient-to-r from-blue-100 to-purple-100 p-6">
                                    <span class="material-symbols-outlined text-6xl text-slate-400 group-hover:scale-110 transition-transform duration-500">
                                        print
                                    </span>
                                    
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                    <div class="absolute bottom-4 right-4 bg-green-500 text-white rounded-full p-1 check-mark shadow-lg">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <div class="p-6 flex flex-col flex-grow card-content">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-lg font-bold text-slate-800"><?= esc($option['copies']) ?> Cetak</h3>
                                        <span class="price-tag-light">
                                            Rp <?= number_format($option['extra_price'] ?? 0, 0, ',', '.') ?>
                                        </span>
                                    </div>
                                    <p class="text-sm text-slate-500 mb-2 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-sm align-middle">photo_camera</span>
                                        Mendapatkan <?= esc($option['copies'] ?? 1) ?> lembar cetak foto strip fisik.
                                    </p>
                                    <p class="text-xs text-blue-600 mb-4 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs align-middle">mail</span>
                                        Dapatkan juga soft file/dokumen/file melalui Gmail terdaftar.
                                    </p>
                                    
                                    <form action="<?= base_url('print-option/store') ?>" method="post" class="w-full mt-auto">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="print_option_id" value="<?= $option['id'] ?>">
                                        <button type="submit" class="cta-button-gradient w-full text-slate-800 font-extrabold text-sm py-3 transition-all">
                                            Pilih Cetak
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full text-center py-12">
                            <p class="text-slate-600">Belum ada pilihan cetak tersedia. Silakan cek kembali nanti.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </main>

            <!-- Footer -->
            <footer class="flex flex-col sm:flex-row items-center justify-between px-6 sm:px-8 py-4 footer-bg border-t border-gray-100 text-slate-500 text-xs font-medium gap-2 sm:gap-0">
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

    <script>
        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container') || (() => {
                const div = document.createElement('div');
                div.id = 'toast-container';
                div.className = 'toast-container';
                document.body.appendChild(div);
                return div;
            })();

            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <span>${message}</span>
                <button class="toast-close" onclick="this.parentElement.remove()">×</button>
            `;
            container.appendChild(toast);
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.style.opacity = '0';
                    toast.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => toast.remove(), 500);
                }
            }, 5000);
        }

        // Card selection
        const cards = document.querySelectorAll('.theme-card');
        cards.forEach(card => {
            card.addEventListener('click', function(e) {
                if (e.target.closest('form') || e.target.closest('button')) return;
                cards.forEach(c => {
                    c.classList.remove('selected');
                    c.style.borderColor = 'transparent';
                });
                this.classList.add('selected');
                this.style.borderColor = '#22c55e';
            });
        });

        // Flash messages
        <?php if (session()->getFlashdata('success')): ?>
            showToast('<?= session()->getFlashdata('success') ?>', 'success');
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            showToast('<?= session()->getFlashdata('error') ?>', 'error');
        <?php endif; ?>

        // Form loading state
        document.querySelectorAll('form[action="<?= base_url('frame/store') ?>"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                const button = this.querySelector('button[type="submit"]');
                if (button) {
                    button.textContent = '⏳ Memproses...';
                    button.disabled = true;
                    button.classList.add('opacity-50', 'cursor-not-allowed');
                }
            });
        });

        // Prevent card click on button
        document.querySelectorAll('.cta-button-gradient').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>
</body>
</html>