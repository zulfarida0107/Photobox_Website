<aside
    class="fixed left-0 top-0 h-screen w-[260px] z-50 bg-white border-r border-slate-200 flex flex-col py-6 px-4 space-y-2">
    <div class="px-4 mb-8 flex items-center gap-3">
        <div>
            <h1 class="text-lg font-black tracking-tight text-blue-700">Photobox</h1>
            <p class="text-xs text-slate-500">Admin Management</p>
        </div>
    </div>
    <nav class="flex-1 space-y-1">
        <a class="flex items-center gap-3 px-4 py-3 sidebar-active transition-all duration-200"
            href="<?= base_url('admin/dashboard') ?>">
            <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
            <span>Dashboard</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-all duration-200"
            href="<?= base_url('admin/customers') ?>">
            <span class="material-symbols-outlined" data-icon="group">group</span>
            <span>Pelanggan</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-all duration-200"
            href="<?= base_url('admin/bookings') ?>">
            <span class="material-symbols-outlined" data-icon="event_available">event_available</span>
            <span>Reservasi</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-all duration-200"
            href="<?= base_url('admin/payments') ?>">
            <span class="material-symbols-outlined" data-icon="payments">payments</span>
            <span>Pembayaran</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-all duration-200"
            href="<?= base_url('admin/packages') ?>">
            <span class="material-symbols-outlined" data-icon="print">print</span>
            <span>Paket &amp; Cetak</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-all duration-200"
            href="<?= base_url('admin/frames') ?>">
            <span class="material-symbols-outlined" data-icon="palette">palette</span>
            <span>Aset Desain</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-all duration-200"
            href="<?= base_url('admin/booths') ?>">
            <span class="material-symbols-outlined" data-icon="nest_display">nest_display</span>
            <span>Mesin Booth</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-all duration-200"
            href="<?= base_url('admin/sessions') ?>">
            <span class="material-symbols-outlined" data-icon="photo_library">photo_library</span>
            <span>Galeri Sesi</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-all duration-200"
            href="<?= base_url('admin/settings') ?>">
            <span class="material-symbols-outlined" data-icon="admin_panel_settings">admin_panel_settings</span>
            <span>Pengaturan Akses</span>
        </a>
    </nav>
    <div class="mt-auto pt-6 border-t border-slate-100">
        <div class="flex items-center gap-3 px-2">
            <img class="w-10 h-10 rounded-full border border-slate-200"
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuCz-kc3dCs8mRa_ZPes5IzxMDJOsnu_ur_XrjYpVeZ7yodO4CxAapyqzQz2ZNXgRMEM0l_8dRK9PLuJ7DG9sXRJ6O_oqXJhwC7B5EZDgSRM_XvYc0krk7UBbzzSFDYSm53EaYIs11YSexwrv0JrB8McAYT2js7XEZlSADom_JlRDpIfxmTCRijDr9P-5pNnM_Tm75Gr5Zf6ewujkS5I60oA4xG-HWgJGAUtmgIcLdakimjzg-YcVNGynGtsk_jBPTxKwjQo3UOA-dxY"
                alt="Admin Utama" />
            <div>
                <p class="text-sm font-bold text-slate-900">Admin Utama</p>
                <p class="text-[10px] text-slate-500 uppercase font-black">Super Administrator</p>
            </div>
        </div>
    </div>
</aside>