<header class="fixed top-0 right-0 w-[calc(100%-260px)] z-40 bg-white border-b border-slate-200 shadow-sm flex items-center justify-between px-6 h-16">
    <div class="flex items-center gap-4 flex-1">
        <div class="relative w-full max-w-md">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
            <input
                class="w-full pl-10 pr-4 py-2 bg-slate-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-600 transition-all"
                placeholder="Cari data, reservasi, atau pelanggan..." type="text" />
        </div>
    </div>
    <div class="flex items-center gap-5">
        <!-- Ikon Waktu / Timer -->
        <button onclick="openModal('modalHistory')" class="text-slate-500 hover:bg-slate-50 p-2 rounded-lg transition-colors">
            <span class="material-symbols-outlined">schedule</span>
        </button>
        <!-- Ikon Notifikasi -->
        <button onclick="openModal('modalClock')" class="text-slate-500 hover:bg-slate-50 p-2 rounded-lg transition-colors relative">
            <span class="material-symbols-outlined">notifications</span>
            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
        </button>
        <div class="h-8 w-px bg-slate-200 mx-2"></div>
        <!-- Ikon Profil -->
        <div class="flex items-center gap-3 cursor-pointer hover:opacity-80 transition-opacity" onclick="openModal('modalProfile')">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-bold text-on-surface leading-none">Admin Photobox</p>
                <p class="text-[10px] text-slate-500 uppercase tracking-wider mt-1">Super Admin</p>
            </div>
            <img alt="Admin Profile" class="w-10 h-10 rounded-full border border-slate-100 object-cover"
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDru5yG0kfgrAexiefmfPXrmbf__ddiHYCNse2EGwIxmzqDZQcLg1eAYw22ws1eruImLu7Qldg3CG24mWnx9DCff9YDE9XZ-hDvImkrF230qHqOc86k-j-F1anq2ljfObxwH5H9hH2-DvMUhbdIyghqBUFEDI2QsmbH3YT2XgrMzqIVSj5naElgHfhhqx5y9cKHG1ZXlcAh5jNsuwiIcvAhYUwbXl4Ls23yVAqUlT49ChvMnedJES1cV1aToIn3pmWztakJyeDOrWyn" />
        </div>
    </div>
</header>

<!-- ================= MODALS SECTION ================= -->
<style>
.modal-overlay {
    background-color: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(4px);
    transition: opacity 0.2s ease-out;
}
.modal-content {
    transition: transform 0.2s ease-out, opacity 0.2s ease-out;
}
</style>

<!-- 1. MODAL CLOCK / TIMER -->
<div id="modalClock" class="fixed inset-0 z-[100] hidden items-center justify-center modal-overlay opacity-0" onclick="closeModal(event, 'modalClock')">
    <div class="bg-white rounded-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto modal-content scale-95 opacity-0 p-8 md:p-12 shadow-2xl relative">
        <button onclick="forceClose('modalClock')" class="absolute top-6 right-6 text-slate-400 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 p-2 rounded-full"><span class="material-symbols-outlined">close</span></button>
        
        <div class="text-center space-y-4 mb-10 mt-4">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-bold uppercase tracking-widest border border-blue-100">
                <span class="material-symbols-outlined text-xs animate-spin-slow">sync_alt</span>
                Server Terkoneksi (NTP)
            </div>
            <h2 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Waktu Operasional Presisi</h2>
            <p class="text-slate-500 max-w-xl mx-auto text-sm md:text-base">
                Sinkronisasi langsung dengan server administrasi utama untuk memastikan konsistensi log dan transaksi di seluruh zona waktu.
            </p>
        </div>

        <div class="bg-white border border-slate-100 shadow-sm rounded-3xl p-8 mb-10 flex flex-col md:flex-row items-center justify-between gap-8 mx-auto max-w-3xl">
            <div class="text-center md:text-left space-y-1">
                <p class="text-blue-600 font-bold tracking-widest uppercase text-xs mb-3">Kalender Sistem</p>
                <div class="text-4xl md:text-5xl font-black text-slate-900" id="dateDayDisplay">Selasa</div>
                <div class="text-xl md:text-2xl font-bold text-slate-500" id="dateFullDisplay">24 Oktober 2023</div>
                <div class="flex items-center justify-center md:justify-start gap-1 text-slate-400 text-xs mt-4">
                    <span class="material-symbols-outlined text-[14px]">public</span>
                    WIB (UTC+07:00)
                </div>
            </div>
            
            <div class="bg-blue-600 rounded-2xl p-6 md:p-8 text-white shadow-lg shadow-blue-600/30 text-center w-full md:w-auto min-w-[300px]">
                <p class="text-blue-200 font-bold tracking-widest uppercase text-[10px] mb-2">Timestamp Presisi</p>
                <div class="flex items-baseline justify-center gap-2 tabular-nums">
                    <div class="text-5xl md:text-6xl font-black" id="clockHHDisplay">14</div>
                    <div class="text-3xl font-bold opacity-50">:</div>
                    <div class="text-5xl md:text-6xl font-black" id="clockMMDisplay">32</div>
                    <div class="flex flex-col items-start ml-2">
                        <div class="text-2xl font-black" id="clockSSDisplay">56</div>
                        <div class="text-[10px] font-bold uppercase opacity-75">Detik</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative flex py-5 items-center mb-6">
            <div class="flex-grow border-t border-slate-100"></div>
            <span class="flex-shrink-0 mx-4 text-slate-300 text-[10px] font-bold tracking-widest uppercase">Rincian Komponen Waktu</span>
            <div class="flex-grow border-t border-slate-100"></div>
        </div>

        <div class="grid grid-cols-7 gap-2 md:gap-4 mb-8 text-center mx-auto max-w-4xl">
            <div class="bg-white border border-slate-100 rounded-xl p-3 shadow-sm flex flex-col justify-center">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Hari</p>
                <p class="font-black text-slate-900 text-lg md:text-xl" id="compHari">SEL</p>
            </div>
            <div class="bg-white border border-slate-100 rounded-xl p-3 shadow-sm flex flex-col justify-center">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Tanggal</p>
                <p class="font-black text-slate-900 text-lg md:text-xl" id="compTgl">24</p>
            </div>
            <div class="bg-white border border-slate-100 rounded-xl p-3 shadow-sm flex flex-col justify-center">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Bulan</p>
                <p class="font-black text-slate-900 text-lg md:text-xl" id="compBln">OKT</p>
            </div>
            <div class="bg-white border border-slate-100 rounded-xl p-3 shadow-sm flex flex-col justify-center">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Tahun</p>
                <p class="font-black text-slate-900 text-lg md:text-xl" id="compThn">2023</p>
            </div>
            <div class="bg-white border border-slate-100 rounded-xl p-3 shadow-sm flex flex-col justify-center">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Jam</p>
                <p class="font-black text-slate-900 text-lg md:text-xl" id="compJam">14</p>
            </div>
            <div class="bg-white border border-slate-100 rounded-xl p-3 shadow-sm flex flex-col justify-center">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Menit</p>
                <p class="font-black text-slate-900 text-lg md:text-xl" id="compMnt">32</p>
            </div>
            <div class="bg-blue-600 rounded-xl p-3 shadow-md shadow-blue-600/20 flex flex-col justify-center text-white">
                <p class="text-[10px] text-blue-200 font-bold uppercase tracking-widest mb-1">Detik</p>
                <p class="font-black text-xl md:text-2xl" id="compDtk">56</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mx-auto max-w-4xl">
            <div class="bg-white border border-slate-100 rounded-xl p-4 shadow-sm flex gap-3">
                <div class="text-blue-600 bg-blue-50 rounded-lg p-1.5 h-fit"><span class="material-symbols-outlined text-sm">update</span></div>
                <div>
                    <p class="font-bold text-slate-900 text-xs mb-1">Kalibrasi Latensi</p>
                    <p class="text-[10px] text-slate-500 leading-relaxed">Offset jaringan saat ini: <span class="font-bold text-slate-700">-12ms</span>. Penyesuaian otomatis dilakukan setiap siklus denyut jantung server.</p>
                </div>
            </div>
            <div class="bg-white border border-slate-100 rounded-xl p-4 shadow-sm flex gap-3">
                <div class="text-blue-600 bg-blue-50 rounded-lg p-1.5 h-fit"><span class="material-symbols-outlined text-sm">verified</span></div>
                <div>
                    <p class="font-bold text-slate-900 text-xs mb-1">Audit Operasional</p>
                    <p class="text-[10px] text-slate-500 leading-relaxed">Setiap aktivitas sistem dilabeli dengan timestamp presisi ini untuk keperluan hukum dan audit operasional perusahaan.</p>
                </div>
            </div>
            <div class="bg-white border border-slate-100 rounded-xl p-4 shadow-sm flex gap-3">
                <div class="text-blue-600 bg-blue-50 rounded-lg p-1.5 h-fit"><span class="material-symbols-outlined text-sm">public</span></div>
                <div>
                    <p class="font-bold text-slate-900 text-xs mb-1">Standar Global</p>
                    <p class="text-[10px] text-slate-500 leading-relaxed">Menggunakan format ISO 8601 untuk penyimpanan data, menjamin kompatibilitas dengan mitra logistik internasional.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 2. MODAL RIWAYAT & NOTIFIKASI -->
<div id="modalHistory" class="fixed inset-0 z-[100] hidden items-center justify-center modal-overlay opacity-0" onclick="closeModal(event, 'modalHistory')">
    <div class="bg-surface rounded-xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto modal-content scale-95 opacity-0 p-8 relative">
        <button onclick="forceClose('modalHistory')" class="absolute top-6 right-6 text-slate-400 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 p-2 rounded-full"><span class="material-symbols-outlined">close</span></button>

        <div class="mb-8">
            <h2 class="font-h1 text-h1 text-slate-900 mb-2">Pusat Riwayat Notifikasi</h2>
            <p class="font-body-base text-body-base text-slate-500">Pantau semua aktivitas sistem dan pemberitahuan operasional dalam satu tempat.</p>
        </div>
        
        <div class="flex items-center justify-between border-b border-slate-200 mb-8 pb-3">
            <div class="flex gap-8">
                <button class="relative text-blue-600 font-label-bold text-label-bold">
                    Semua
                    <span class="absolute -bottom-3 left-0 w-full h-0.5 bg-blue-600"></span>
                </button>
                <button class="text-slate-500 font-label-bold text-label-bold hover:text-blue-600 transition-colors">Urgent</button>
                <button class="text-slate-500 font-label-bold text-label-bold hover:text-blue-600 transition-colors">Sistem</button>
            </div>
            
            <div class="relative inline-block">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">calendar_today</span>
                <select class="pl-10 pr-8 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-600 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent cursor-pointer hover:bg-slate-50 transition-colors shadow-sm">
                    <option>Satu Tahun</option>
                    <option>Satu Bulan</option>
                    <option>Satu Minggu</option>
                    <option>Kemarin</option>
                    <option selected>Hari ini</option>
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">arrow_drop_down</span>
            </div>
        </div>

        <div class="space-y-8">
            <!-- HARI INI -->
            <section>
                <div class="flex items-center gap-4 mb-4">
                    <span class="font-label-bold text-slate-500 uppercase tracking-widest text-xs">HARI INI</span>
                    <div class="h-px flex-1 bg-slate-100"></div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-start gap-4 p-4 bg-white border border-slate-100 shadow-sm transition-all rounded-xl relative">
                        <div class="absolute right-4 top-4 text-xs text-slate-400">2 menit yang lalu</div>
                        <div class="p-3 bg-red-100 text-red-600 rounded-xl"><span class="material-symbols-outlined">error</span></div>
                        <div class="flex-1 pr-24">
                            <h3 class="font-bold text-slate-900 flex items-center gap-2 mb-1">
                                Booth Error (Critical)
                                <span class="px-2 py-0.5 bg-red-600 text-white text-[10px] font-bold rounded uppercase">Urgent</span>
                            </h3>
                            <p class="text-sm text-slate-500">Sensor suhu pada Booth #042 terdeteksi di atas ambang batas normal. Diperlukan tindakan segera.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-4 bg-white border border-slate-100 shadow-sm transition-all rounded-xl relative">
                        <div class="absolute right-4 top-4 text-xs text-slate-400">10:45</div>
                        <div class="p-3 bg-blue-100 text-blue-600 rounded-xl"><span class="material-symbols-outlined">calendar_today</span></div>
                        <div class="flex-1 pr-24">
                            <h3 class="font-bold text-slate-900 mb-1">New Booking</h3>
                            <p class="text-sm text-slate-500">Reservasi baru diterima untuk Layanan Konsultasi Premium oleh Klien ID #8812.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-4 bg-white border border-slate-100 shadow-sm transition-all rounded-xl relative">
                        <div class="absolute right-4 top-4 text-xs text-slate-400">09:20</div>
                        <div class="p-3 bg-green-100 text-green-600 rounded-xl"><span class="material-symbols-outlined">payments</span></div>
                        <div class="flex-1 pr-24">
                            <h3 class="font-bold text-slate-900 mb-1">Payment Received</h3>
                            <p class="text-sm text-slate-500">Pembayaran sebesar Rp 2.500.000 telah diverifikasi untuk Invoice #INV-2024-001.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- KEMARIN -->
            <section>
                <div class="flex items-center gap-4 mb-4">
                    <span class="font-label-bold text-slate-500 uppercase tracking-widest text-xs">KEMARIN</span>
                    <div class="h-px flex-1 bg-slate-100"></div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-start gap-4 p-4 bg-white border border-slate-100 shadow-sm transition-all rounded-xl relative">
                        <div class="absolute right-4 top-4 text-xs text-slate-400">16:15</div>
                        <div class="p-3 bg-slate-100 text-slate-600 rounded-xl"><span class="material-symbols-outlined">person_add</span></div>
                        <div class="flex-1 pr-24">
                            <h3 class="font-bold text-slate-900 mb-1">User Baru Terdaftar</h3>
                            <p class="text-sm text-slate-500">Pengguna baru "Andi Pratama" telah berhasil mendaftar melalui portal web.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 24 MEI 2024 -->
            <section>
                <div class="flex items-center gap-4 mb-4">
                    <span class="font-label-bold text-slate-500 uppercase tracking-widest text-xs">24 MEI 2024</span>
                    <div class="h-px flex-1 bg-slate-100"></div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-start gap-4 p-4 bg-white border border-slate-100 shadow-sm transition-all rounded-xl relative">
                        <div class="absolute right-4 top-4 text-xs text-slate-400">23:00</div>
                        <div class="p-3 bg-blue-100 text-blue-600 rounded-xl"><span class="material-symbols-outlined">settings_suggest</span></div>
                        <div class="flex-1 pr-24">
                            <h3 class="font-bold text-slate-900 mb-1">Pembaruan Sistem Selesai</h3>
                            <p class="text-sm text-slate-500">Maintenance mingguan berhasil dilaksanakan. Seluruh layanan kembali online dengan performa optimal.</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        
        <div class="mt-10 border-t border-slate-100 pt-8 text-center">
            <button class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-[#003d9b] text-white rounded-lg font-bold hover:bg-blue-800 transition-colors shadow-md">
                Lihat Lebih Banyak
                <span class="material-symbols-outlined text-xl">expand_more</span>
            </button>
        </div>
    </div>
</div>

<!-- 3. MODAL PROFILE -->
<div id="modalProfile" class="fixed inset-0 z-[100] hidden items-center justify-center modal-overlay opacity-0" onclick="closeModal(event, 'modalProfile')">
    <div class="bg-surface rounded-xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto modal-content scale-95 opacity-0 p-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="font-h1 text-h1 text-[#001848] mb-1">Informasi Profil</h2>
                <p class="text-slate-500 text-sm">Kelola preferensi akun dan konfigurasi profil PhotoBox Anda.</p>
            </div>
            <button onclick="forceClose('modalProfile')" class="text-slate-400 hover:bg-slate-100 p-2 rounded-full"><span class="material-symbols-outlined">close</span></button>
        </div>

        <div class="space-y-6">
            <!-- Card 1: Informasi Profil -->
            <section class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden p-6">
                <h3 class="font-bold text-[#001848] mb-6 text-lg">Informasi Profil</h3>
                <div class="flex flex-col md:flex-row gap-8 items-start">
                    <div class="relative group mx-auto md:mx-0 w-32 h-32 flex-shrink-0">
                        <div class="w-full h-full rounded-2xl overflow-hidden border border-slate-100 shadow-sm bg-white p-1.5">
                            <img alt="Current profile" class="w-full h-full object-cover rounded-xl" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAgzPXgOYOFnUaa01GpekBQj1KlquZyBSw9tRg9K6QBvo0jvb-7uJ3A44o1y_G3VBfpwSvg-nP_lTdAy9xLYSuRL5ZF-fmflDtaZMb07vXrzDCvgTLyhTRoqFJ0dJndmn09KlhnAlH9Fz7IJ3pkNDWjS009yTI18hpvsuGnT6ruezfsRUWNtj8WefpyGfGwbX7ug1sSf3MST3l_QQfeBK9zHgjYgFBNSOAVWMOH_mdTapP4nU5zXr5eFYJZts2Jc3QwGYgXdJkM_BBl"/>
                        </div>
                        <button class="absolute -bottom-2 -right-2 bg-[#003d9b] text-white p-1.5 rounded-lg shadow-md hover:scale-105 transition-transform"><span class="material-symbols-outlined text-sm">photo_camera</span></button>
                    </div>
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4 w-full">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">NAMA LENGKAP</label>
                            <input class="w-full rounded-lg border border-slate-200 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 text-sm py-2.5 px-3 text-slate-700 outline-none" type="text" value="Admin User"/>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">PERAN</label>
                            <input class="w-full rounded-lg border border-slate-200 text-sm py-2.5 px-3 bg-white text-slate-700 outline-none" readonly type="text" value="Super Admin"/>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">ALAMAT EMAIL</label>
                            <input class="w-full rounded-lg border border-slate-200 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 text-sm py-2.5 px-3 text-slate-700 outline-none" type="email" value="admin@photobox.com"/>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Card 2: Preferensi Notifikasi -->
            <section class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden p-6">
                <h3 class="font-bold text-[#001848] mb-6 text-lg">Preferensi Notifikasi</h3>
                <div class="space-y-3">
                    <!-- Toggle 1 -->
                    <div class="flex items-center justify-between p-4 bg-[#f8fafc] rounded-xl border border-slate-50">
                        <div class="flex items-center gap-4">
                            <div class="p-2 bg-[#e0e7ff] text-[#4f46e5] rounded-lg"><span class="material-symbols-outlined text-[20px]">mail</span></div>
                            <div>
                                <p class="font-bold text-[#001848] text-sm mb-0.5">Laporan Email Mingguan</p>
                                <p class="text-xs text-slate-500">Terima ringkasan transaksi mingguan via email.</p>
                            </div>
                        </div>
                        <!-- Custom Toggle Switch -->
                        <div class="w-10 h-6 bg-[#003d9b] rounded-full relative cursor-pointer shadow-inner">
                            <div class="absolute right-1 top-1 w-4 h-4 bg-white rounded-full shadow-sm"></div>
                        </div>
                    </div>
                    <!-- Toggle 2 -->
                    <div class="flex items-center justify-between p-4 bg-[#f8fafc] rounded-xl border border-slate-50">
                        <div class="flex items-center gap-4">
                            <div class="p-2 bg-[#e0e7ff] text-[#4f46e5] rounded-lg"><span class="material-symbols-outlined text-[20px]">bolt</span></div>
                            <div>
                                <p class="font-bold text-[#001848] text-sm mb-0.5">Push Notifications</p>
                                <p class="text-xs text-slate-500">Notifikasi instan untuk aktivitas sistem yang kritis.</p>
                            </div>
                        </div>
                        <!-- Custom Toggle Switch OFF -->
                        <div class="w-10 h-6 bg-slate-200 rounded-full relative cursor-pointer shadow-inner">
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow-sm"></div>
                        </div>
                    </div>
                    <!-- Toggle 3 -->
                    <div class="flex items-center justify-between p-4 bg-[#f8fafc] rounded-xl border border-slate-50">
                        <div class="flex items-center gap-4">
                            <div class="p-2 bg-[#e0e7ff] text-[#4f46e5] rounded-lg"><span class="material-symbols-outlined text-[20px]">security</span></div>
                            <div>
                                <p class="font-bold text-[#001848] text-sm mb-0.5">Peringatan Keamanan</p>
                                <p class="text-xs text-slate-500">Notifikasi saat ada upaya login yang mencurigakan.</p>
                            </div>
                        </div>
                        <!-- Custom Toggle Switch ON -->
                        <div class="w-10 h-6 bg-[#003d9b] rounded-full relative cursor-pointer shadow-inner">
                            <div class="absolute right-1 top-1 w-4 h-4 bg-white rounded-full shadow-sm"></div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="flex items-center justify-end gap-4 pt-4">
                <button onclick="forceClose('modalProfile')" class="px-6 py-2.5 rounded-lg border border-[#003d9b] text-[#003d9b] font-bold text-sm hover:bg-blue-50 transition-colors bg-white">Batalkan Perubahan</button>
                <button class="px-6 py-2.5 rounded-lg bg-[#003d9b] text-white font-bold text-sm shadow-md hover:bg-blue-800 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>Simpan Pengaturan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple Modal Controller
    function openModal(id) {
        const modal = document.getElementById(id);
        const content = modal.querySelector('.modal-content');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Timeout for transition
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function forceClose(id) {
        const modal = document.getElementById(id);
        const content = modal.querySelector('.modal-content');
        
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 200);
    }

    function closeModal(event, id) {
        // If clicking outside the modal content
        if (event.target.id === id) {
            forceClose(id);
        }
    }

    // Simple Clock Auto Update
    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const daysShort = ['MIN', 'SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB'];
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const monthsShort = ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGU', 'SEP', 'OKT', 'NOV', 'DES'];

    setInterval(() => {
        const now = new Date();
        
        const d_day = now.getDay();
        const d_date = String(now.getDate()).padStart(2, '0');
        const d_month = now.getMonth();
        const d_year = now.getFullYear();
        
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        
        const elDateDay = document.getElementById('dateDayDisplay');
        if (elDateDay) {
            elDateDay.innerText = days[d_day];
            document.getElementById('dateFullDisplay').innerText = `${d_date} ${months[d_month]} ${d_year}`;
            
            document.getElementById('clockHHDisplay').innerText = h;
            document.getElementById('clockMMDisplay').innerText = m;
            document.getElementById('clockSSDisplay').innerText = s;
            
            document.getElementById('compHari').innerText = daysShort[d_day];
            document.getElementById('compTgl').innerText = d_date;
            document.getElementById('compBln').innerText = monthsShort[d_month];
            document.getElementById('compThn').innerText = d_year;
            document.getElementById('compJam').innerText = h;
            document.getElementById('compMnt').innerText = m;
            document.getElementById('compDtk').innerText = s;
        }
    }, 1000);
</script>
