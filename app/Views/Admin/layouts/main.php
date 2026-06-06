<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= esc($title ?? 'Admin Panel') ?> | Photobox v2</title>

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
<body class="bg-background text-on-surface min-h-screen flex">

    <!-- Sidebar -->
    <?= $this->include('Admin/layouts/sidebar') ?>

    <!-- Main wrapper: offset by sidebar width, allow full height flex column -->
    <div class="flex-1 ml-[260px] flex flex-col min-h-screen">

        <!-- Fixed Header -->
        <?= $this->include('Admin/layouts/header') ?>

        <!-- Page Content (each view handles its own pt-16 top padding) -->
        <?= $this->renderSection('content') ?>

    </div>

</body>
</html>
