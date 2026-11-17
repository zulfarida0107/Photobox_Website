<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= esc($title ?? 'Admin Panel') ?> | CuitBox Studio v2</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "secondary-fixed-dim": "#c5c6c8",
                        "inverse-primary": "#b2c5ff",
                        "outline": "#737685",
                        "on-tertiary-fixed-variant": "#812800",
                        "on-tertiary-container": "#ffc6b2",
                        "background": "#f9f9ff",
                        "primary-fixed": "#dae2ff",
                        "on-primary-fixed": "#001848",
                        "on-secondary-container": "#606365",
                        "tertiary-fixed-dim": "#ffb59b",
                        "on-background": "#041b3c",
                        "primary": "#003d9b",
                        "on-primary": "#ffffff",
                        "inverse-on-surface": "#edf0ff",
                        "error-container": "#ffdad6",
                        "surface-container-high": "#e0e8ff",
                        "on-secondary-fixed": "#191c1e",
                        "on-surface": "#041b3c",
                        "on-secondary-fixed-variant": "#444749",
                        "on-primary-container": "#c4d2ff",
                        "tertiary": "#7b2600",
                        "secondary-container": "#dee0e2",
                        "surface-variant": "#d7e2ff",
                        "surface-dim": "#cadaff",
                        "primary-container": "#0052cc",
                        "on-surface-variant": "#434654",
                        "surface-container-highest": "#d7e2ff",
                        "on-error-container": "#93000a",
                        "tertiary-container": "#a33500",
                        "on-tertiary": "#ffffff",
                        "secondary-fixed": "#e1e2e4",
                        "surface-container": "#e8edff",
                        "on-error": "#ffffff",
                        "primary-fixed-dim": "#b2c5ff",
                        "surface-bright": "#f9f9ff",
                        "outline-variant": "#c3c6d6",
                        "on-tertiary-fixed": "#380d00",
                        "surface-container-lowest": "#ffffff",
                        "on-primary-fixed-variant": "#0040a2",
                        "tertiary-fixed": "#ffdbcf",
                        "error": "#ba1a1a",
                        "on-secondary": "#ffffff",
                        "inverse-surface": "#1d3052",
                        "secondary": "#5c5f60",
                        "surface-tint": "#0c56d0",
                        "surface-container-low": "#f1f3ff",
                        "surface": "#f9f9ff"
                    },
                    borderRadius: {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    spacing: {
                        "sidebar-width": "260px",
                        "gutter": "16px",
                        "container-margin": "24px",
                        "table-cell-padding": "12px 16px",
                        "card-padding": "20px"
                    },
                    fontFamily: {
                        "body-base": ["Inter"],
                        "h2": ["Inter"],
                        "label-bold": ["Inter"],
                        "data-tabular": ["Inter"],
                        "body-sm": ["Inter"],
                        "h1": ["Inter"]
                    },
                    fontSize: {
                        "body-base": ["14px", { lineHeight: "20px", fontWeight: "400" }],
                        "h2": ["20px", { lineHeight: "28px", fontWeight: "600" }],
                        "label-bold": ["12px", { lineHeight: "16px", letterSpacing: "0.02em", fontWeight: "600" }],
                        "data-tabular": ["13px", { lineHeight: "16px", fontWeight: "400" }],
                        "body-sm": ["13px", { lineHeight: "18px", fontWeight: "400" }],
                        "h1": ["24px", { lineHeight: "32px", fontWeight: "700" }]
                    }
                }
            }
        }
    </script>

    <style>
        /* Sembunyikan scrollbar global namun tetap bisa di-scroll */
        ::-webkit-scrollbar {
            display: none;
        }
        * {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
            display: inline-block;
        }
        body { font-family: 'Inter', sans-serif; }

        /* Sidebar active state */
        .sidebar-active {
            background-color: #eff6ff;
            color: #1d4ed8;
            border-left: 4px solid #2563eb;
            font-weight: 700;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            color: #475569;
            border-radius: 0.375rem;
            transition: all 0.2s;
            text-decoration: none;
        }
        .sidebar-link:hover {
            background-color: #f8fafc;
            color: #2563eb;
        }
    </style>
</head>
<body class="bg-background text-on-surface min-h-screen flex antialiased">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div id="sidebar-backdrop" 
         onclick="toggleSidebar(false)" 
         class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden md:hidden transition-opacity duration-300 opacity-0"></div>

    <!-- Sidebar -->
    <?= $this->include('Admin/layouts/sidebar') ?>

    <!-- Main wrapper: offset on desktop (md:ml-[260px]), full width on mobile (ml-0) -->
    <div class="flex-1 ml-0 md:ml-[260px] flex flex-col min-h-screen min-w-0 transition-all duration-300">

        <!-- Fixed Header -->
        <?= $this->include('Admin/layouts/header') ?>

        <!-- Page Content (each view handles its own pt-16 top padding) -->
        <?= $this->renderSection('content') ?>

    </div>
    <!-- Global Floating Toast for Export -->
    <div id="export-toast" class="fixed top-20 right-6 z-[9999] bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg text-sm font-medium flex items-center gap-2 shadow-lg transition-opacity duration-300 opacity-0 pointer-events-none">
        <span class="material-symbols-outlined text-blue-500">cloud_download</span>
        <span>Berhasil! Laporan sedang diunduh.</span>
    </div>
    <script>
        // Controller Sidebar Responsif Mobile
        function toggleSidebar(forceState) {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (!sidebar || !backdrop) return;
            
            const isCurrentlyClosed = sidebar.classList.contains('-translate-x-full');
            const shouldOpen = forceState !== undefined ? forceState : isCurrentlyClosed;
            
            if (shouldOpen) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                setTimeout(() => {
                    backdrop.classList.remove('opacity-0');
                }, 10);
                document.body.classList.add('overflow-hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('opacity-0');
                setTimeout(() => {
                    backdrop.classList.add('hidden');
                }, 300);
                document.body.classList.remove('overflow-hidden');
            }
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                const backdrop = document.getElementById('sidebar-backdrop');
                if (backdrop) {
                    backdrop.classList.add('hidden', 'opacity-0');
                }
                document.body.classList.remove('overflow-hidden');
            }
        });

        function showExportNotif() {
            const toast = document.getElementById('export-toast');
            toast.classList.remove('opacity-0', 'pointer-events-none');
            
            setTimeout(() => {
                toast.classList.add('opacity-0', 'pointer-events-none');
            }, 1000);

            // Determine report name
            let reportName = "Sistem";
            const path = window.location.pathname;
            if (path.includes('booths')) reportName = "Mesin Booth";
            else if (path.includes('packages')) reportName = "Paket & Cetak";
            else if (path.includes('payments')) reportName = "Pembayaran";
            else if (path.includes('sessions')) reportName = "Galeri Sesi";
            else {
                const titleText = document.title || "";
                if (titleText.includes("Booth")) reportName = "Mesin Booth";
                else if (titleText.includes("Paket")) reportName = "Paket & Cetak";
                else if (titleText.includes("Pembayaran")) reportName = "Pembayaran";
                else if (titleText.includes("Sesi")) reportName = "Galeri Sesi";
            }

            // Save to localStorage
            let exports = JSON.parse(localStorage.getItem('export_reports') || '[]');
            exports.unshift({
                name: reportName,
                time: new Date().toISOString(),
                id: 'export_' + Date.now()
            });
            localStorage.setItem('export_reports', JSON.stringify(exports));
            localStorage.setItem('notifsRead', 'false');

            // Trigger header update if function exists
            const badge = document.getElementById('notif-badge');
            if (badge) badge.classList.remove('hidden');
            if (window.renderExportNotifications) {
                window.renderExportNotifications();
            }
        }
    </script>
</body>
</html>
