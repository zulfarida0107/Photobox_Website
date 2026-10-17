<?php
/**
 * Deteksi segment aktif dari URI saat ini.
 * Contoh: /admin/customers/1/edit → segment ke-2 = 'customers'
 * Kelompokkan: frames & filters → 'frames', slots & bookings → 'bookings'
 */
$uri        = service('request')->getUri();
$seg2       = $uri->getSegment(2) ?? ''; // segment setelah 'admin'
$seg3       = $uri->getSegment(3) ?? '';

// Role Admin
$adminRole    = strtolower(session()->get('adminRole') ?? '');
$isAdmin      = in_array($adminRole, ['admin', 'superadmin'], true);
$isSuperAdmin = ($adminRole === 'superadmin');

// Mapping segment → menu key
$activeMap = [
    'dashboard'     => 'dashboard',
    'customers'     => 'customers',
    'bookings'      => 'bookings',
    'slots'         => 'bookings',      // slot masuk menu Reservasi
    'payments'      => 'payments',
    'print-options' => 'print-options',
    'frames'        => 'frames',
    'filters'       => 'frames',        // filter masuk menu Aset Desain
    'booths'        => 'booths',
    'locations'     => 'booths',
    'sessions'      => 'sessions',
    'photos'        => 'sessions',      // photo masuk menu Galeri Sesi
    'settings'      => 'settings',
];

$activeKey = $activeMap[$seg2] ?? 'dashboard';

// CSS classes
$clsActive  = 'flex items-center gap-3 px-4 py-3 sidebar-active transition-all duration-200';
$clsDefault = 'flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-all duration-200';

function sidebarClass(string $key, string $activeKey, string $clsActive, string $clsDefault): string {
    return $key === $activeKey ? $clsActive : $clsDefault;
}
?>
<aside class="fixed left-0 top-0 h-screen w-[260px] z-50 bg-white border-r border-slate-200 flex flex-col py-6 px-4 space-y-2">
    <div class="px-4 mb-8 flex items-center gap-3">
        <div>
            <h1 class="text-lg font-black tracking-tight text-blue-700">CuitBox Studio</h1>
            <p class="text-xs text-slate-500">Admin Management</p>
        </div>
    </div>

    <nav class="flex-1 space-y-1">

        <!-- Menu Operasional (Bisa diakses semua role admin) -->
        <a class="<?= sidebarClass('dashboard', $activeKey, $clsActive, $clsDefault) ?>"
           href="<?= base_url('admin/dashboard') ?>">
            <span class="material-symbols-outlined">dashboard</span>
            <span>Dashboard</span>
        </a>

        <a class="<?= sidebarClass('customers', $activeKey, $clsActive, $clsDefault) ?>"
           href="<?= base_url('admin/customers') ?>">
            <span class="material-symbols-outlined">group</span>
            <span>Pelanggan</span>
        </a>

        <a class="<?= sidebarClass('bookings', $activeKey, $clsActive, $clsDefault) ?>"
           href="<?= base_url('admin/bookings') ?>">
            <span class="material-symbols-outlined">event_available</span>
            <span>Reservasi</span>
        </a>

        <a class="<?= sidebarClass('payments', $activeKey, $clsActive, $clsDefault) ?>"
           href="<?= base_url('admin/payments') ?>">
            <span class="material-symbols-outlined">payments</span>
            <span>Pembayaran</span>
        </a>

        <a class="<?= sidebarClass('booths', $activeKey, $clsActive, $clsDefault) ?>"
           href="<?= base_url('admin/booths') ?>">
            <span class="material-symbols-outlined">nest_display</span>
            <span>Mesin Booth</span>
        </a>

        <a class="<?= sidebarClass('sessions', $activeKey, $clsActive, $clsDefault) ?>"
           href="<?= base_url('admin/sessions') ?>">
            <span class="material-symbols-outlined">photo_library</span>
            <span>Galeri Sesi</span>
        </a>

        <!-- Menu Katalog (Admin & Superadmin) -->
        <?php if ($isAdmin): ?>
            <a class="<?= sidebarClass('print-options', $activeKey, $clsActive, $clsDefault) ?>"
               href="<?= base_url('admin/print-options') ?>">
                <span class="material-symbols-outlined">print</span>
                <span>Opsi Cetak</span>
            </a>

            <a class="<?= sidebarClass('frames', $activeKey, $clsActive, $clsDefault) ?>"
               href="<?= base_url('admin/frames') ?>">
                <span class="material-symbols-outlined">palette</span>
                <span>Aset Desain</span>
            </a>

        <?php endif; ?>

        <!-- Pengaturan Akses untuk Admin dan Superadmin -->
        <?php if ($isAdmin): ?>
            <a class="<?= sidebarClass('settings', $activeKey, $clsActive, $clsDefault) ?>"
               href="<?= base_url('admin/settings') ?>">
                <span class="material-symbols-outlined">admin_panel_settings</span>
                <span>Pengaturan Akses</span>
            </a>
        <?php endif; ?>

    </nav>

    <div class="pt-4 border-t border-slate-100">
        <a class="flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 hover:text-red-700 transition-all duration-200 rounded-lg"
           href="<?= base_url('admin/logout') ?>">
            <span class="material-symbols-outlined">logout</span>
            <span>Logout</span>
        </a>
    </div>
</aside>