<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>CuitBox Studio - Pilih Booth</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #B8E4FF 0%, #FCD2E8 100%);
            background-attachment: fixed;
        }
        .page-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: stretch;
            justify-content: center;
        }
        .header-bg {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
        }
        .booth-card {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease, border-color 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            border-radius: 20px;
            border: 2px solid transparent;
            cursor: pointer;
            position: relative;
            background: rgba(255, 255, 255, 0.95);
        }
        .booth-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 32px rgba(30, 41, 59, 0.08);
        }
        .booth-card.selected {
            border-color: #84cc16;
            box-shadow: 0 0 0 3px rgba(132, 204, 22, 0.25), 0 16px 32px rgba(30, 41, 59, 0.08);
        }
        .cta-button-gradient {
            background: linear-gradient(90deg, #d9f99d 0%, #bef264 100%);
            box-shadow: 0 8px 16px -3px rgba(190, 242, 100, 0.4);
            transition: all 0.25s ease;
            border-radius: 14px;
        }
        .cta-button-gradient:hover {
            transform: scale(1.02);
            box-shadow: 0 12px 20px -3px rgba(190, 242, 100, 0.55);
        }
        .cta-button-gradient:active {
            transform: scale(0.98);
        }
        .card-image-box {
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
            height: 180px;
        }
        .booth-image-placeholder {
            background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            height: 100%;
        }
        .check-badge {
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.25s ease;
        }
        .booth-card.selected .check-badge {
            opacity: 1;
            transform: scale(1);
        }
        .toast-container {
            position: fixed;
            top: 24px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            max-width: 360px;
            width: calc(100% - 32px);
            pointer-events: none;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .toast {
            padding: 10px 16px;
            border-radius: 12px;
            margin-bottom: 8px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.15);
            animation: slideInDown 0.35s ease;
            display: flex;
            align-items: center;
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            pointer-events: auto;
        }
        .toast-success { background: #16a34a; color: white; }
        .toast-error { background: #ef4444; color: white; }
        .toast-info { background: #3b82f6; color: white; }
        @keyframes slideInDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="w-full gradient-bg flex flex-col min-h-screen">

            <!-- BEGIN: MainHeader -->
            <header class="flex items-center justify-between px-6 sm:px-8 py-4 header-bg border-b border-gray-100" data-purpose="site-header">
                <div class="flex items-center">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">CuitBox Studio</h1>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-4 text-slate-600">
                    <a href="<?= base_url('help') ?>" class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center rounded-full hover:bg-gray-100/50 transition-all relative group" aria-label="Help">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                        </svg>
                    </a>
                </div>
            </header>
            <!-- END: MainHeader -->

            <!-- Main Content -->
            <main class="relative px-8 sm:px-14 md:px-20 pt-8 pb-12 overflow-hidden bg-white/30 backdrop-blur-sm flex-1">
                <div class="max-w-2xl mx-auto">
                    <!-- Title Section -->
                    <div class="text-center mb-8">
                        <h2 class="text-2xl sm:text-3xl font-extrabold mb-1.5 text-slate-800 tracking-tight">Pilih Booth Studio</h2>
                        <p class="text-xs sm:text-sm text-slate-600 font-medium max-w-md mx-auto">Pilih tema booth yang sesuai untuk sesi fotomu.</p>
                    </div>

                    <?php if (!empty($booths) && is_array($booths)): ?>
                    <!-- Booths Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-sm sm:max-w-xl mx-auto">
                        <?php foreach ($booths as $index => $booth): 
                            $boothName = $booth['name'] ?? 'Booth Studio';
                            $boothId = $booth['id'] ?? '';
                            $boothStatus = $booth['status'] ?? 'active';
                            $capacity = $booth['capacity'] ?? 4;
                            $isSelected = ($selectedBoothId === $boothId) || ($selectedBoothId === null && $index === 0);
                            $statusLabel = ($boothStatus === 'active') ? 'Tersedia' : 'Nonaktif';
                            $statusDotBg = ($boothStatus === 'active') ? 'bg-emerald-500' : 'bg-amber-500';
                            $statusTextCol = ($boothStatus === 'active') ? 'text-emerald-700 bg-emerald-50 border-emerald-200' : 'text-amber-700 bg-amber-50 border-amber-200';

                            // Data Lokasi & Maps Link
                            $locationName = !empty($booth['location_name']) ? $booth['location_name'] : 'Pusat';
                            $locationCity = !empty($booth['location_city']) ? $booth['location_city'] : '';
                            $locationAddress = !empty($booth['location_address']) ? $booth['location_address'] : 'Studio Utama';
                            
                            $lat = !empty($booth['latitude']) ? trim($booth['latitude']) : null;
                            $lng = !empty($booth['longitude']) ? trim($booth['longitude']) : null;

                            if (!empty($lat) && !empty($lng)) {
                                $mapsUrl = 'https://www.google.com/maps?q=' . urlencode("{$lat},{$lng}");
                            } else {
                                $query = trim("{$locationName} {$locationAddress} {$locationCity}");
                                $mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($query);
                            }
                        ?>
                            <div class="booth-card flex flex-col group <?= $isSelected ? 'selected' : '' ?> px-4 py-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all cursor-pointer" 
                                 data-booth-id="<?= esc($boothId) ?>">
                                
                                <!-- Header Row: Nama Booth & Kapasitas Badge -->
                                <div class="flex items-center justify-between gap-2 mb-3 pb-3 border-b border-slate-100">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-base font-extrabold text-slate-800 tracking-tight"><?= esc($boothName) ?></h3>
                                        <!-- Selected Check Badge -->
                                        <div class="bg-lime-400 text-slate-900 rounded-full p-0.5 shadow-sm check-badge">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Kapasitas Badge -->
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200 flex-shrink-0">
                                        <svg class="w-3.5 h-3.5 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <span>Max <?= esc($capacity) ?> Orang</span>
                                    </span>
                                </div>

                                <!-- Informasi Ringkas: Lokasi & Alamat Maps -->
                                <div class="space-y-2.5 mb-5 text-xs text-slate-600 flex-grow">
                                    <!-- Lokasi -->
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <div class="leading-relaxed">
                                            <span class="text-slate-400">Lokasi:</span>
                                            <span class="font-bold text-slate-800 ml-1"><?= esc($locationName) ?> <?= !empty($locationCity) ? '<span class="text-slate-500 font-normal">(' . esc($locationCity) . ')</span>' : '' ?></span>
                                        </div>
                                    </div>

                                    <!-- Alamat Lengkap + Maps Link -->
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <div class="leading-relaxed">
                                            <span class="text-slate-400">Alamat:</span>
                                            <a href="<?= esc($mapsUrl) ?>" target="_blank" rel="noopener noreferrer" 
                                               class="font-medium text-blue-600 hover:text-blue-700 hover:underline inline-flex items-center gap-1 ml-1 group/link"
                                               onclick="event.stopPropagation();"
                                               title="Buka rute di Google Maps">
                                                <span><?= esc($locationAddress) ?></span>
                                                <svg class="w-3 h-3 text-blue-500 inline-block flex-shrink-0 group-hover/link:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Action Button -->
                                <form action="<?= base_url('booth/store') ?>" method="post" class="w-full mt-auto">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="booth_id" value="<?= esc($boothId) ?>">
                                    <button type="submit" class="cta-button-gradient w-full text-slate-900 font-extrabold text-xs sm:text-sm py-2.5 transition-all flex items-center justify-center gap-2">
                                        <span>Pilih Booth Ini</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-16 bg-white/70 rounded-2xl border border-slate-100 max-w-md mx-auto p-6 shadow-sm">
                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Booth Tersedia</h3>
                        <p class="text-xs text-slate-500">Saat ini belum ada booth yang dibuka. Silakan cek kembali nanti.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </main>

            <!-- Footer -->
            <footer class="flex flex-col sm:flex-row items-center justify-between px-6 sm:px-8 py-4 footer-bg border-t border-gray-100 text-slate-500 text-xs font-medium gap-2 sm:gap-0">
                <div class="flex items-center gap-2">
                    <svg class="h-3 w-3 sm:h-4 sm:w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                    </svg>
                    <span class="font-semibold text-slate-600">Connected</span>
                </div>
                <div>© 2026 CuitBox Studio. All rights reserved.</div>
            </footer>

        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="toast-container"></div>

    <script>
        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container');
            if (!container) return;
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.textContent = message;
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-10px)';
                toast.style.transition = 'all 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.booth-card');
            cards.forEach(card => {
                card.addEventListener('click', function(e) {
                    if (e.target.closest('button')) return;
                    cards.forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                });
            });

            document.querySelectorAll('form[action="<?= base_url('booth/store') ?>"]').forEach(form => {
                form.addEventListener('submit', function() {
                    const btn = this.querySelector('button[type="submit"]');
                    if (btn) {
                        btn.innerHTML = '<span>Memproses...</span>';
                        btn.disabled = true;
                        btn.classList.add('opacity-75', 'cursor-not-allowed');
                    }
                });
            });
        });

        <?php if (session()->getFlashdata('success')): ?>
            showToast('<?= esc(session()->getFlashdata('success')) ?>', 'success');
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            showToast('<?= esc(session()->getFlashdata('error')) ?>', 'error');
        <?php endif; ?>
        <?php if (session()->getFlashdata('info')): ?>
            showToast('<?= esc(session()->getFlashdata('info')) ?>', 'info');
        <?php endif; ?>
    </script>
</body>
</html>