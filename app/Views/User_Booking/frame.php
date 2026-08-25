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
        /* Page wrapper: full height, footer pushed to bottom */
        .page-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: stretch;
            justify-content: center;
            padding: 0;
        }
        .main-container-rounded {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main.main-content {
            flex: 1 0 auto;
        }
        footer {
            flex-shrink: 0;
        }
        .header-bg {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
        }
        .footer-bg {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
        }
        /* === Frame Card: Full-image, all corners visible === */
        .theme-card {
            border-radius: 16px;
            overflow: hidden;
            cursor: pointer;
            position: relative;
            border: 2.5px solid transparent;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease, border-color 0.3s ease;
            box-shadow: 0 6px 20px rgba(0,0,0,0.10);
            background: #fff;
        }
        .theme-card:hover {
            transform: translateY(-6px) scale(1.015);
            box-shadow: 0 20px 44px rgba(30, 41, 59, 0.16);
        }
        .theme-card.selected {
            border-color: #22c55e;
            box-shadow: 0 0 0 5px rgba(34, 197, 94, 0.25), 0 20px 44px rgba(30, 41, 59, 0.14);
        }
        /* Image shows fully — no crop, all corners visible */
        .frame-card-img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.5s ease;
        }
        .theme-card:hover .frame-card-img {
            transform: scale(1.04);
        }
        /* Gradient overlay at bottom */
        .frame-card-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.80) 0%, rgba(0,0,0,0.18) 50%, transparent 100%);
            pointer-events: none;
            transition: background 0.3s;
        }
        .theme-card:hover .frame-card-overlay {
            background: linear-gradient(to top, rgba(0,0,0,0.90) 0%, rgba(0,0,0,0.3) 55%, transparent 100%);
        }
        /* Selected green tint */
        .selected-overlay {
            position: absolute;
            inset: 0;
            background: rgba(34, 197, 94, 0.12);
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
            z-index: 2;
        }
        .theme-card.selected .selected-overlay { opacity: 1; }
        /* Info block at bottom */
        .frame-card-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 18px 16px 18px;
            z-index: 5;
            display: flex;
            flex-direction: column;
            gap: 7px;
        }
        /* Category pill */
        .category-label {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.28);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            padding: 3px 10px;
            border-radius: 999px;
            width: fit-content;
        }
        /* Frame name */
        .frame-card-name {
            color: #fff;
            font-weight: 800;
            font-size: 1rem;
            line-height: 1.3;
            text-shadow: 0 2px 8px rgba(0,0,0,0.5);
        }
        /* Pilih Tema button */
        .cta-button-gradient {
            background: linear-gradient(90deg, #d9f99d 0%, #bef264 100%);
            color: #1e293b;
            font-weight: 800;
            font-size: 13px;
            border-radius: 9999px;
            padding: 10px 0;
            width: 100%;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(190, 242, 100, 0.45);
            position: relative;
            z-index: 10;
        }
        .cta-button-gradient:hover {
            transform: scale(1.03);
            box-shadow: 0 10px 28px rgba(190, 242, 100, 0.65);
        }
        .cta-button-gradient:active { transform: scale(0.97); }
        /* Check badge */
        .check-mark {
            position: absolute;
            top: 14px;
            right: 14px;
            background: #22c55e;
            border-radius: 50%;
            padding: 5px;
            z-index: 6;
            opacity: 0;
            transition: opacity 0.3s;
            box-shadow: 0 2px 8px rgba(34,197,94,0.4);
        }
        .theme-card.selected .check-mark { opacity: 1; }
        /* Vertical grid — uniform cards */
        .frame-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        @media (min-width: 640px) {
            .frame-grid { grid-template-columns: repeat(3, 1fr); gap: 18px; }
        }
        @media (min-width: 1024px) {
            .frame-grid { grid-template-columns: repeat(5, 1fr); gap: 20px; }
        }
        form { position: relative; z-index: 5; }
        .toast-container { position: fixed; top: 28px; left: 50%; transform: translateX(-50%); z-index: 9999; max-width: 340px; width: calc(100% - 32px); pointer-events: none; display: flex; flex-direction: column; align-items: center; }
        .toast { padding: 10px 16px; border-radius: 10px; margin-bottom: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); animation: slideInDown 0.35s ease; display: flex; align-items: center; justify-content: center; text-align: center; font-size: 11px; font-weight: 600; line-height: 1.35; pointer-events: auto; cursor: grab; user-select: none; touch-action: none; transition: transform 0.2s ease, opacity 0.2s ease; }
        .toast:active { cursor: grabbing; }
        .toast-success { background: #10b981; color: white; }
        .toast-error { background: #ef4444; color: white; }
        .toast-info { background: #3b82f6; color: white; }
        @keyframes slideInDown { from { transform: translateY(-20px) scale(0.95); opacity: 0; } to { transform: translateY(0) scale(1); opacity: 1; } }
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
            <main class="main-content relative px-6 sm:px-8 pt-8 pb-8 overflow-hidden bg-white/40 backdrop-blur-sm flex-1">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-extrabold mb-1 text-slate-800">Pilih Tema Frame Fotomu</h2>
                    <p class="text-[10px] text-slate-600 font-medium max-w-2xl mx-auto">Pilih Tema yang paling Tepat Sesuai dengan momen serumu.</p>
                </div>

                <!-- Tab Kategori Aset Desain (Ambil Realtime dari Admin) -->
                <div class="flex flex-wrap justify-center gap-2 mb-8">
                    <a href="<?= base_url('frame') ?>" 
                       class="px-4 py-2 rounded-full text-xs font-bold transition-all shadow-sm <?= (empty($selectedCategory) || $selectedCategory === 'all') ? 'bg-slate-900 text-white shadow-md' : 'bg-white/80 text-slate-700 hover:bg-white hover:shadow' ?>">
                        Semua Tema
                    </a>
                    <?php if (!empty($categories) && is_array($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <a href="<?= base_url('frame?category=' . urlencode($cat)) ?>" 
                               class="px-4 py-2 rounded-full text-xs font-bold transition-all shadow-sm <?= (isset($selectedCategory) && $selectedCategory == $cat) ? 'bg-slate-900 text-white shadow-md' : 'bg-white/80 text-slate-700 hover:bg-white hover:shadow' ?>">
                                <?= esc(ucwords($cat === 'y2k' ? 'Y2K' : $cat)) ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="frame-grid">
                    <?php if (!empty($frames) && is_array($frames)): ?>
                        <?php foreach ($frames as $index => $frame): ?>
                            <div class="theme-card <?= $index === 0 ? 'selected' : '' ?>"
                                 data-card="<?= $frame['id'] ?>"
                                 data-frame-id="<?= $frame['id'] ?>">

                                <!-- Full-bleed image -->
                                <img class="frame-card-img"
                                     src="<?= esc($frame['file_path'] ?? '') ?>"
                                     alt="<?= esc($frame['name']) ?>"
                                     onerror="this.src='https://placehold.co/400x360/1e293b/ffffff?text=<?= urlencode($frame['name']) ?>'" />

                                <!-- Dark gradient overlay -->
                                <div class="frame-card-overlay"></div>

                                <!-- Green selected tint -->
                                <div class="selected-overlay"></div>

                                <!-- Checkmark badge (top-right) -->
                                <div class="check-mark">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>

                                <!-- Info overlay at bottom -->
                                <div class="frame-card-info">
                                    <!-- Category label -->
                                    <span class="category-label">
                                        <span class="material-symbols-outlined" style="font-size:11px">label</span>
                                        <?= esc(ucwords(($frame['category'] ?? '') === 'y2k' ? 'Y2K' : ($frame['category'] ?? 'Umum'))) ?>
                                    </span>

                                    <!-- Frame name -->
                                    <p class="frame-card-name"><?= esc($frame['name']) ?></p>

                                    <!-- Pilih Tema button -->
                                    <form action="<?= base_url('frame/store') ?>" method="post">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="frame_id" value="<?= $frame['id'] ?>">
                                        <button type="submit" class="cta-button-gradient">
                                            Pilih Tema
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full text-center py-16">
                            <p class="text-slate-600">Belum ada tema frame tersedia. Silakan cek kembali nanti.</p>
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
            toast.innerHTML = `<span>${message}</span>`;
            container.appendChild(toast);

            function dismissToast() {
                toast.style.transition = 'transform 0.25s ease, opacity 0.25s ease';
                toast.style.transform = 'translateY(-40px)';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 250);
            }

            // Auto dismiss after 4s
            let autoTimer = setTimeout(dismissToast, 4000);

            // Swipe Up to dismiss logic (Touch & Drag)
            let startY = 0;
            let isDragging = false;

            const onStart = (clientY) => {
                clearTimeout(autoTimer);
                startY = clientY;
                isDragging = true;
                toast.style.transition = 'none';
            };

            const onMove = (clientY) => {
                if (!isDragging) return;
                const diffY = clientY - startY;
                if (diffY < 0) { // Dragging upwards
                    toast.style.transform = `translateY(${diffY}px)`;
                    toast.style.opacity = `${Math.max(0, 1 + diffY / 50)}`;
                }
            };

            const onEnd = (clientY) => {
                if (!isDragging) return;
                isDragging = false;
                const diffY = clientY - startY;
                if (diffY < -15) { // Swiped up by more than 15px
                    dismissToast();
                } else {
                    toast.style.transition = 'transform 0.2s ease, opacity 0.2s ease';
                    toast.style.transform = 'translateY(0)';
                    toast.style.opacity = '1';
                    autoTimer = setTimeout(dismissToast, 3000);
                }
            };

            // Touch events (Mobile)
            toast.addEventListener('touchstart', (e) => onStart(e.touches[0].clientY), { passive: true });
            toast.addEventListener('touchmove', (e) => onMove(e.touches[0].clientY), { passive: true });
            toast.addEventListener('touchend', (e) => onEnd(e.changedTouches[0].clientY));

            // Mouse events (Desktop testing)
            toast.addEventListener('mousedown', (e) => {
                onStart(e.clientY);
                const moveHandler = (ev) => onMove(ev.clientY);
                const upHandler = (ev) => {
                    onEnd(ev.clientY);
                    document.removeEventListener('mousemove', moveHandler);
                    document.removeEventListener('mouseup', upHandler);
                };
                document.addEventListener('mousemove', moveHandler);
                document.addEventListener('mouseup', upHandler);
            });
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