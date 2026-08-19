<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>CuitBox Studio - Pilih Booth</title>
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
        .header-bg {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
        }
        .footer-bg {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
        }
        .booth-card {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease, border-color 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            border-radius: 16px;
            border: 2px solid transparent;
            cursor: pointer;
            position: relative;
        }
        .booth-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(30, 41, 59, 0.08);
        }
        .booth-card.selected {
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
        .booth-card .check-mark {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .booth-card.selected .check-mark {
            opacity: 1;
        }
        .booth-card .card-overlay {
            position: absolute;
            inset: 0;
            background: rgba(34, 197, 94, 0.05);
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 16px;
            pointer-events: none;
            z-index: 1;
        }
        .booth-card.selected .card-overlay {
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
        
        .booth-row {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            margin-bottom: 0;
            justify-content: center;
        }
        .booth-row .booth-card {
            flex: 0 0 calc(33.333% - 16px);
            max-width: calc(33.333% - 16px);
            min-width: 250px;
        }
        @media (max-width: 1023px) {
            .booth-row .booth-card {
                flex: 0 0 calc(50% - 12px);
                max-width: calc(50% - 12px);
                min-width: 200px;
            }
        }
        @media (max-width: 639px) {
            .booth-row .booth-card {
                flex: 0 0 100%;
                max-width: 100%;
                min-width: unset;
            }
        }
        .booth-row .booth-card .card-image {
            height: 200px;
        }
        @media (min-width: 768px) {
            .booth-row .booth-card .card-image {
                height: 220px;
            }
        }
        .price-tag-light {
            background: #d1fae5;
            color: #065f46;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }
        .booth-image-placeholder {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
            height: 100%;
        }
        .section-divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 32px 0 24px;
            width: 100%;
        }
        .section-divider::before,
        .section-divider::after {
            content: '';
            flex: 1;
            height: 2px;
            background: linear-gradient(90deg, transparent, #94a3b8, transparent);
        }
        .section-divider span {
            font-size: 14px;
            font-weight: 600;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 2px;
            white-space: nowrap;
            padding: 0 8px;
        }
        .premium-badge {
            animation: pulse-badge 2s ease-in-out infinite;
        }
        @keyframes pulse-badge {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .category-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(4px);
            padding: 2px 10px;
            border-radius: 9999px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .capacity-badge {
            display: flex;
            align-items: center;
            gap: 4px;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            padding: 2px 10px;
            border-radius: 9999px;
            font-size: 10px;
            color: white;
        }
        .container-custom {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="w-full main-container-rounded gradient-bg flex flex-col min-h-screen">

            <!-- BEGIN: MainHeader -->
            <header class="flex items-center justify-between px-6 sm:px-8 py-4 header-bg border-b border-gray-100" data-purpose="site-header">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-brand-lime to-brand-active flex items-center justify-center">
                        <span class="material-symbols-outlined text-slate-800 text-2xl">photo_booth</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">CuitBox Studio</h1>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-4 text-slate-600">
                    <a href="<?= base_url('help') ?>" class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center rounded-full hover:bg-gray-100/50 transition-all relative group" aria-label="Help">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                        </svg>
                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-green-500 rounded-full"></span>
                    </a>
                </div>
            </header>
            <!-- END: MainHeader -->

            <!-- Main Content -->
            <main class="relative px-6 sm:px-8 pt-8 pb-8 overflow-hidden bg-white/40 backdrop-blur-sm flex-1">
                <div class="container-custom">
                    <div class="text-center mb-10">
                        <span class="inline-block px-4 py-1 bg-brand-lime/40 text-slate-700 text-xs font-bold rounded-full mb-3 tracking-wider uppercase">
                            🏢 Pilih Booth
                        </span>
                        <h2 class="text-3xl font-extrabold mb-2 text-slate-800">Pilih Booth Fotobox</h2>
                        <p class="text-sm text-slate-600 font-medium max-w-2xl mx-auto">Pilih booth yang sesuai dengan kebutuhan acaramu.</p>
                    </div>

                    <?php 
                    if (!empty($booths) && is_array($booths)):
                        $displayBooths = array_slice($booths, 0, 6);
                        $topBooths = array();
                        $bottomBooths = array();
                        $count = 0;
                        foreach ($displayBooths as $booth) {
                            if ($count < 3) {
                                $topBooths[] = $booth;
                            } else {
                                $bottomBooths[] = $booth;
                            }
                            $count++;
                        }
                    ?>
                    
                    <!-- BAGIAN ATAS (3 BOOTH) -->
                    <div class="booth-row">
                        <?php foreach ($topBooths as $index => $booth): 
                            $isPremium = ($booth['price'] ?? 0) >= 250000;
                            $boothName = $booth['name'] ?? 'Booth';
                            $boothPrice = $booth['price'] ?? 0;
                            $boothId = $booth['id'] ?? '';
                            $imageUrl = $booth['file_path'] ?? '';
                            $category = $booth['category'] ?? 'General';
                            $capacity = $booth['capacity'] ?? 2;
                            $description = $booth['description'] ?? 'Booth eksklusif untuk momen spesialmu';
                        ?>
                            <div class="booth-card bg-white/90 overflow-hidden flex flex-col group shadow-sm hover:shadow-xl <?= $index === 0 ? 'selected border-2 border-green-500' : '' ?>" 
                                 data-card="<?= $boothId ?>" 
                                 data-booth-id="<?= $boothId ?>">
                                <div class="card-overlay"></div>
                                <div class="relative overflow-hidden card-image">
                                    <?php if (!empty($imageUrl)): ?>
                                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                             src="<?= $imageUrl ?>" 
                                             alt="<?= $boothName ?>"
                                             loading="lazy"
                                             onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'w-full h-full booth-image-placeholder\'><span class=\'text-white font-bold text-lg\'><?= $boothName ?></span></div>'"/>
                                    <?php else: ?>
                                        <div class="w-full h-full booth-image-placeholder" style="background: #1e293b;">
                                            <span class="text-white font-bold text-lg"><?= $boothName ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($isPremium): ?>
                                        <div class="absolute top-4 right-4 bg-gradient-to-r from-yellow-400 to-yellow-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-lg premium-badge">
                                            ⭐ PREMIUM
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="absolute top-4 left-4 category-badge text-white">
                                        <?= $category ?>
                                    </div>
                                    
                                    <div class="absolute bottom-4 left-4 capacity-badge">
                                        <span class="material-symbols-outlined text-sm">group</span>
                                        <span><?= $capacity ?> Orang</span>
                                    </div>
                                    
                                    <div class="absolute bottom-4 right-4 bg-black/50 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full">
                                        Rp <?= number_format($boothPrice, 0, ',', '.') ?>
                                    </div>
                                    
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                                    <div class="absolute bottom-16 right-4 bg-green-500 text-white rounded-full p-1 check-mark shadow-lg">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <div class="p-5 flex flex-col flex-grow card-content">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-base font-bold text-slate-800 leading-tight"><?= $boothName ?></h3>
                                        <span class="price-tag-light">
                                            Rp <?= number_format($boothPrice, 0, ',', '.') ?>
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 mb-3 line-clamp-2"><?= $description ?></p>
                                    <div class="flex items-center gap-3 text-xs text-slate-400 mb-3">
                                        <span class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">category</span>
                                            <?= $category ?>
                                        </span>
                                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                        <span class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">group</span>
                                            <?= $capacity ?> Orang
                                        </span>
                                    </div>
                                    
                                    <!-- ACTION PAKAI HURUF KAPITAL -->
                                    <form action="<?= base_url('booth/store') ?>" method="post" class="w-full mt-auto">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="booth_id" value="<?= $boothId ?>">
                                        <button type="submit" class="cta-button-gradient w-full text-slate-800 font-extrabold text-sm py-2.5 transition-all flex items-center justify-center gap-2">
                                            <span class="material-symbols-outlined text-sm">check_circle</span>
                                            Pilih Booth
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- DIVIDER -->
                    <?php if (!empty($bottomBooths)): ?>
                        <div class="section-divider">
                            <span>✨ Booth Lainnya</span>
                        </div>
                    <?php endif; ?>

                    <!-- BAGIAN BAWAH (3 BOOTH) -->
                    <div class="booth-row">
                        <?php foreach ($bottomBooths as $index => $booth): 
                            $isPremium = ($booth['price'] ?? 0) >= 250000;
                            $boothName = $booth['name'] ?? 'Booth';
                            $boothPrice = $booth['price'] ?? 0;
                            $boothId = $booth['id'] ?? '';
                            $imageUrl = $booth['file_path'] ?? '';
                            $category = $booth['category'] ?? 'General';
                            $capacity = $booth['capacity'] ?? 2;
                            $description = $booth['description'] ?? 'Booth eksklusif untuk momen spesialmu';
                        ?>
                            <div class="booth-card bg-white/90 overflow-hidden flex flex-col group shadow-sm hover:shadow-xl" 
                                 data-card="<?= $boothId ?>" 
                                 data-booth-id="<?= $boothId ?>">
                                <div class="card-overlay"></div>
                                <div class="relative overflow-hidden card-image">
                                    <?php if (!empty($imageUrl)): ?>
                                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                             src="<?= $imageUrl ?>" 
                                             alt="<?= $boothName ?>"
                                             loading="lazy"
                                             onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'w-full h-full booth-image-placeholder\'><span class=\'text-white font-bold text-lg\'><?= $boothName ?></span></div>'"/>
                                    <?php else: ?>
                                        <div class="w-full h-full booth-image-placeholder" style="background: #1e293b;">
                                            <span class="text-white font-bold text-lg"><?= $boothName ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($isPremium): ?>
                                        <div class="absolute top-4 right-4 bg-gradient-to-r from-yellow-400 to-yellow-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-lg premium-badge">
                                            ⭐ PREMIUM
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="absolute top-4 left-4 category-badge text-white">
                                        <?= $category ?>
                                    </div>
                                    
                                    <div class="absolute bottom-4 left-4 capacity-badge">
                                        <span class="material-symbols-outlined text-sm">group</span>
                                        <span><?= $capacity ?> Orang</span>
                                    </div>
                                    
                                    <div class="absolute bottom-4 right-4 bg-black/50 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full">
                                        Rp <?= number_format($boothPrice, 0, ',', '.') ?>
                                    </div>
                                    
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                                    <div class="absolute bottom-16 right-4 bg-green-500 text-white rounded-full p-1 check-mark shadow-lg">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <div class="p-5 flex flex-col flex-grow card-content">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-base font-bold text-slate-800 leading-tight"><?= $boothName ?></h3>
                                        <span class="price-tag-light">
                                            Rp <?= number_format($boothPrice, 0, ',', '.') ?>
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 mb-3 line-clamp-2"><?= $description ?></p>
                                    <div class="flex items-center gap-3 text-xs text-slate-400 mb-3">
                                        <span class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">category</span>
                                            <?= $category ?>
                                        </span>
                                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                        <span class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">group</span>
                                            <?= $capacity ?> Orang
                                        </span>
                                    </div>
                                    
                                    <!-- ACTION PAKAI HURUF KAPITAL -->
                                    <form action="<?= base_url('booth/store') ?>" method="post" class="w-full mt-auto">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="booth_id" value="<?= $boothId ?>">
                                        <button type="submit" class="cta-button-gradient w-full text-slate-800 font-extrabold text-sm py-2.5 transition-all flex items-center justify-center gap-2">
                                            <span class="material-symbols-outlined text-sm">check_circle</span>
                                            Pilih Booth
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php else: ?>
                        <div class="text-center py-16">
                            <div class="text-6xl mb-4">🏢</div>
                            <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Booth</h3>
                            <p class="text-slate-600 max-w-md mx-auto">Saat ini belum ada booth yang tersedia. Silakan cek kembali nanti.</p>
                        </div>
                    <?php endif; ?>

                    <!-- Info tambahan -->
                    <div class="mt-10 text-center">
                        <div class="inline-flex flex-wrap items-center justify-center gap-4 bg-white/60 backdrop-blur-sm px-6 py-3 rounded-full shadow-sm">
                            <div class="flex items-center gap-2 text-xs text-slate-600">
                                <span class="material-symbols-outlined text-sm text-green-500">verified</span>
                                <span>Booth Terkurasi</span>
                            </div>
                            <div class="w-px h-4 bg-slate-300 hidden sm:block"></div>
                            <div class="flex items-center gap-2 text-xs text-slate-600">
                                <span class="material-symbols-outlined text-sm text-green-500">payment</span>
                                <span>Harga Kompetitif</span>
                            </div>
                            <div class="w-px h-4 bg-slate-300 hidden sm:block"></div>
                            <div class="flex items-center gap-2 text-xs text-slate-600">
                                <span class="material-symbols-outlined text-sm text-green-500">support_agent</span>
                                <span>Dukungan 24/7</span>
                            </div>
                            <div class="w-px h-4 bg-slate-300 hidden sm:block"></div>
                            <div class="flex items-center gap-2 text-xs text-slate-600">
                                <span class="material-symbols-outlined text-sm text-green-500">install_mobile</span>
                                <span>Setup Cepat</span>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="flex flex-col sm:flex-row items-center justify-between px-6 sm:px-8 py-4 footer-bg border-t border-gray-100 text-slate-500 text-xs font-medium gap-2 sm:gap-0">
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1.5">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                        <span class="font-semibold text-slate-600">Connected</span>
                    </div>
                    <span class="text-slate-300">|</span>
                    <span class="text-slate-400">v2.0</span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="#" class="hover:text-slate-700 transition-colors">Privacy</a>
                    <a href="#" class="hover:text-slate-700 transition-colors">Terms</a>
                    <span>© 2026 CuitBox Studio. All rights reserved.</span>
                </div>
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

        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.booth-card');
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

            // ACTION PAKAI HURUF KAPITAL
            document.querySelectorAll('form[action="<?= base_url('booth/store') ?>"]').forEach(form => {
                form.addEventListener('submit', function(e) {
                    const button = this.querySelector('button[type="submit"]');
                    if (button) {
                        button.innerHTML = '<span class="animate-spin h-4 w-4 border-2 border-slate-800 border-t-transparent rounded-full inline-block"></span> Memproses...';
                        button.disabled = true;
                        button.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                });
            });

            document.querySelectorAll('.cta-button-gradient').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
        });

        <?php if (session()->getFlashdata('success')): ?>
            showToast('<?= session()->getFlashdata('success') ?>', 'success');
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            showToast('<?= session()->getFlashdata('error') ?>', 'error');
        <?php endif; ?>
    </script>
</body>
</html>