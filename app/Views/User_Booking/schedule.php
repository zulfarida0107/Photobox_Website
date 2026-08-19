<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>CuitBox Studio - Booking Session</title>
    
    <!-- Tailwind CSS v3 CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
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
                        muted: '#64748b',
                    }
                }
            }
        }
    </script>
    
    <style>
        /* ===== LAYOUT & BACKGROUND ===== */
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
        
        /* ===== CALENDAR ===== */
        .calendar-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        }
        
        .date-cell {
            cursor: pointer;
            transition: all 0.2s ease;
            border-radius: 8px;
            padding: 4px 0;
            font-size: 14px;
            color: #1e293b;
        }
        
        .date-cell:hover:not(.empty) {
            background: rgba(190, 242, 100, 0.3);
        }
        
        .date-cell.selected-date {
            background: #000;
            color: white;
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            padding: 4px 0;
        }
        
        .date-cell.empty {
            cursor: default;
        }
        
        .date-cell.empty:hover {
            background: transparent;
        }
        
        .day-header {
            color: #1e293b;
            font-weight: 600;
        }
        
        /* ===== TIME SLOTS ===== */
        .session-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        }
        
        .session-label {
            color: #1e293b;
            font-weight: 700;
        }
        
        .time-slot {
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
            cursor: pointer;
            background: white;
        }
        
        .time-slot:hover:not(.full) {
            border-color: #bef264;
            box-shadow: 0 4px 12px rgba(190, 242, 100, 0.2);
        }
        
        .time-slot.selected {
            background: #bef264;
            border-color: #bef264;
            box-shadow: 0 4px 12px rgba(190, 242, 100, 0.3);
        }
        
        .time-slot.selected .time-text {
            color: #1e293b;
        }
        
        .time-slot.selected .status-text {
            color: #1e293b;
            font-weight: bold;
        }
        
        .time-slot.available {
            border-color: #bef264;
        }
        
        .time-slot.full {
            background-color: #f8fafc;
            border-color: #e2e8f0;
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .time-slot.full .time-text {
            color: #94a3b8;
        }
        
        .time-slot.past-time {
            background-color: #f1f5f9;
            border-color: #e2e8f0;
            opacity: 0.4;
            cursor: not-allowed;
        }
        
        .time-slot.past-time .time-text {
            color: #94a3b8;
        }
        
        .time-slot.past-time .status-text {
            color: #94a3b8;
        }
        
        /* ===== BUTTONS ===== */
        .btn-primary {
            background: #bef264;
            box-shadow: 0 10px 15px -3px rgba(190, 242, 100, 0.4);
            transition: all 0.3s ease;
            border-radius: 16px;
            padding: 16px;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 25px -5px rgba(190, 242, 100, 0.5);
        }
        
        .btn-primary:active {
            transform: scale(0.98);
        }
        
        .btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        .btn-back {
            background: #1e293b;
            color: white;
            transition: all 0.3s ease;
            border-radius: 16px;
            padding: 14px;
            font-weight: 700;
            font-size: 12px;
            width: 100%;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .btn-back:hover {
            background: #334155;
            transform: scale(1.02);
        }
        
        .btn-back:active {
            background: #bef264;
            color: #1e293b;
            transform: scale(0.98);
            box-shadow: 0 4px 12px rgba(190, 242, 100, 0.3);
        }
        
        /* ===== CONFIRMATION BOX ===== */
        .confirmation-box {
            background: #bef264;
            border: none;
            border-radius: 16px;
            padding: 16px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(190, 242, 100, 0.2);
        }
        
        .confirmation-box .label {
            font-size: 10px;
            font-weight: 700;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .confirmation-box .value {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 2px;
        }
        
        /* ===== DATE CELL DISABLED ===== */
        .date-cell.disabled {
            opacity: 0.3;
            cursor: not-allowed;
            pointer-events: none;
        }
        
        .date-cell.disabled:hover {
            background: transparent;
        }
        
        /* ===== CURRENT TIME INDICATOR ===== */
        .current-time-indicator {
            font-size: 10px;
            color: #64748b;
            padding: 4px 12px;
            background: #f1f5f9;
            border-radius: 9999px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <!-- BEGIN: Page Wrapper -->
    <div class="page-wrapper">
        <!-- BEGIN: MainContainer -->
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

            <!-- BEGIN: HeroSection -->
            <main class="relative px-6 sm:px-8 pt-8 pb-8 overflow-hidden bg-white/40 backdrop-blur-sm flex-1">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-extrabold mb-1 text-slate-800">Booking Session</h2>
                    <p class="text-[10px] text-slate-600 font-medium">Pilih Tanggal &amp; Waktu Sesi Fotobox</p>
                    <div class="mt-2">
                        <span class="current-time-indicator" id="current-time-display">⏰ Memuat waktu...</span>
                    </div>
                </div>
                
<?php
$monthsIndo = ['01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'];
$currentMonthYearPhp = $monthsIndo[date('m')] . ' ' . date('Y');
$currentDateFullPhp = date('d') . ' ' . $monthsIndo[date('m')] . ' ' . date('Y');
?>
                <!-- Content Layout: Calendar & Session -->
                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Left Column: Calendar -->
                    <section class="flex-1">
                        <div class="calendar-card p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-lg font-bold" id="month-year-label"><?= $currentMonthYearPhp ?></h2>
                                <div class="flex space-x-2">
                                    <button class="p-1 rounded-full bg-slate-100 hover:bg-slate-200 transition-all" id="prev-month">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                        </svg>
                                    </button>
                                    <button class="p-1 rounded-full bg-slate-100 hover:bg-slate-200 transition-all" id="next-month">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-7 gap-y-2 text-center text-xs font-medium mb-2">
                                <div class="day-header">MIG</div>
                                <div class="day-header">SEN</div>
                                <div class="day-header">SEL</div>
                                <div class="day-header">RAB</div>
                                <div class="day-header">KAM</div>
                                <div class="day-header">JUM</div>
                                <div class="day-header">SAB</div>
                            </div>
                            <div class="grid grid-cols-7 gap-y-1 text-center" id="calendar-grid">
                                <!-- Calendar akan di-generate oleh JavaScript -->
                            </div>
                            <div class="mt-2 text-xs text-slate-400 text-center">
                                <span class="inline-block w-3 h-3 bg-black rounded-sm mr-1 align-middle"></span> Dipilih
                                <span class="inline-block w-3 h-3 bg-slate-200 rounded-sm ml-3 mr-1 align-middle"></span> Tidak Tersedia
                            </div>
                        </div>
                    </section>

                    <!-- Right Column: Sesi Tersedia -->
                    <section class="flex-1">
                        <div class="session-card p-6 flex flex-col h-full">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="bg-brand-lime p-2 rounded-full" style="background:#d9f99d;">
                                    <svg class="w-5 h-5 text-slate-700" fill="currentColor" viewBox="0 0 20 20">
                                        <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" fill-rule="evenodd"/>
                                    </svg>
                                </div>
                                <h2 class="text-lg font-bold">Sesi Tersedia</h2>
                            </div>
                            <p class="text-sm font-medium mb-4">Tanggal Terpilih: <span class="font-bold" id="selected-date-display"><?= $currentDateFullPhp ?></span></p>
                            
                            <!-- Time Slot Categories -->
                            <div class="space-y-4 flex-1" id="time-slots-container">
                                <!-- Slot akan dimuat dari database Admin secara dinamis -->
                                <div class="col-span-full text-center py-8 text-sm text-slate-400 italic">
                                    Memuat sesi operasional...
                                </div>
                            </div>
                            
                            <!-- Bottom Actions -->
                            <div class="mt-4 space-y-3">
                                <!-- Confirmation Box -->
                                <div class="confirmation-box">
                                    <p class="label">Konfirmasi Pilihan</p>
                                    <p class="value" id="confirmation-display">Pilih tanggal &amp; waktu</p>
                                </div>
                                
                                <!-- Form untuk Submit Schedule -->
                                <form action="<?= base_url('schedule/submit') ?>" method="POST" id="scheduleForm">
                                    <?= csrf_field() ?>
                                    
                                    <!-- Hidden fields -->
                                    <input type="hidden" name="selected_date" id="selected_date" value="">
                                    <input type="hidden" name="selected_time" id="selected_time" value="">
                                    <input type="hidden" name="slot_id" id="slot_id" value="">
                                    <input type="hidden" name="booth_id" id="booth_id" value="">
                                    
                                    <!-- Button Lanjutkan ke frame -->
                                    <button type="submit" class="btn-primary" id="submitScheduleBtn">
                                        <span>Lanjutkan ke Pemilihan Tema Frame</span>
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                        </svg>
                                    </button>
                                </form>
                                
                                <!-- Button Kembali ke Register -->
                                <button class="btn-back" id="btn-back">
                                    Kembali ke Registrasi
                                </button>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
            <!-- END: HeroSection -->

            <!-- BEGIN: Footer -->
            <footer class="flex flex-col sm:flex-row items-center justify-between px-6 sm:px-8 py-4 footer-bg border-t border-gray-100 text-slate-500 text-xs font-medium gap-2 sm:gap-0">
                <div class="flex items-center gap-2">
                    <svg class="h-3 w-3 sm:h-4 sm:w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    </svg>
                    <span class="font-semibold text-slate-600">Connected</span>
                </div>
                <div class="text-center">© 2026 CuitBox Studio. All rights reserved.</div>
            </footer>
            <!-- END: Footer -->

        </div>
        <!-- END: MainContainer -->
    </div>
    <!-- END: Page Wrapper -->

    <!-- ===== JAVASCRIPT ===== -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            const todayDate = new Date(now.getFullYear(), now.getMonth(), now.getDate());
            
            let currentDate = new Date();
            let selectedDate = new Date();
            let selectedTime = '';
            
            const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            const calendarGrid = document.getElementById('calendar-grid');
            const monthYearLabel = document.getElementById('month-year-label');
            const selectedDateDisplay = document.getElementById('selected-date-display');
            const confirmationDisplay = document.getElementById('confirmation-display');
            const selectedDateInput = document.getElementById('selected_date');
            const selectedTimeInput = document.getElementById('selected_time');
            const slotIdInput = document.getElementById('slot_id');
            const boothIdInput = document.getElementById('booth_id');
            const submitBtn = document.getElementById('submitScheduleBtn');
            const scheduleForm = document.getElementById('scheduleForm');
            const btnBack = document.getElementById('btn-back');
            const currentTimeDisplay = document.getElementById('current-time-display');
            
            function updateCurrentTime() {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const dayName = days[now.getDay()];
                const date = now.getDate();
                const month = monthNames[now.getMonth()];
                const year = now.getFullYear();
                
                if (currentTimeDisplay) {
                    currentTimeDisplay.textContent = `⏰ ${dayName}, ${date} ${month} ${year} | ${hours}:${minutes} WIB`;
                }
            }
            
            updateCurrentTime();
            setInterval(updateCurrentTime, 60000);
            
            function formatDate(date) {
                return date.getDate() + ' ' + monthNames[date.getMonth()] + ' ' + date.getFullYear();
            }
            
            function isTimeSlotPast(timeLabel) {
                const today = new Date();
                if (selectedDate.toDateString() !== today.toDateString()) return false;
                
                const parts = timeLabel.split(' - ');
                const startHour = parseInt(parts[0].split(':')[0]);
                const startMinute = parseInt(parts[0].split(':')[1] || 0);
                
                const currentHour = today.getHours();
                const currentMinute = today.getMinutes();
                
                if (startHour < currentHour) return true;
                if (startHour === currentHour && startMinute <= currentMinute) return true;
                return false;
            }
            
            function updateConfirmation() {
                const dateStr = formatDate(selectedDate);
                if (selectedDateInput) selectedDateInput.value = dateStr;
                
                if (selectedTime) {
                    if (selectedTimeInput) selectedTimeInput.value = selectedTime;
                    if (confirmationDisplay) confirmationDisplay.textContent = dateStr + ' | ' + selectedTime + ' WIB';
                } else {
                    if (selectedTimeInput) selectedTimeInput.value = '';
                    if (confirmationDisplay) confirmationDisplay.textContent = dateStr + ' | Pilih waktu';
                }
            }
            
            function fetchSlotsForDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const dateString = `${year}-${month}-${day}`;
                
                const container = document.getElementById('time-slots-container');
                if (container && container.children.length > 0) {
                    container.style.transition = 'opacity 0.15s ease';
                    container.style.opacity = '0.5';
                }
                
                fetch(`/api/user/slots/${dateString}`)
                    .then(response => response.json())
                    .then(res => {
                        if (container) container.style.opacity = '1';
                        if (res.status === 'success' && res.data.length > 0) {
                            renderDynamicSlots(res.data);
                        } else {
                            if (container) {
                                container.innerHTML = '<div class="col-span-full text-center py-8 text-sm text-slate-500 italic">Tidak ada sesi operasional tersedia untuk tanggal ini.</div>';
                            }
                            selectedTime = '';
                            if (selectedTimeInput) selectedTimeInput.value = '';
                            if (slotIdInput) slotIdInput.value = '';
                            if (boothIdInput) boothIdInput.value = '';
                            updateConfirmation();
                        }
                    })
                    .catch(err => {
                        console.error('Error fetching slots:', err);
                        if (container) {
                            container.style.opacity = '1';
                            container.innerHTML = '<div class="col-span-full text-center py-8 text-sm text-red-500">Gagal memuat data sesi.</div>';
                        }
                    });
            }
            
            function renderDynamicSlots(slots) {
                const container = document.getElementById('time-slots-container');
                container.innerHTML = '';
                
                const categories = {
                    'PAGI': [],
                    'SIANG': [],
                    'SORE': [],
                    'MALAM': []
                };
                
                slots.forEach(slot => {
                    const startHour = parseInt(slot.start_time.split(':')[0]);
                    const timeLabel = slot.start_time.substring(0, 5);
                    const endHour = (startHour + 1) % 24;
                    const endTimeLabel = `${String(endHour).padStart(2, '0')}:00`;
                    const fullTimeRange = `${timeLabel} - ${endTimeLabel}`;
                    
                    let category = 'MALAM';
                    if (startHour < 12) category = 'PAGI';
                    else if (startHour < 15) category = 'SIANG';
                    else if (startHour < 18) category = 'SORE';
                    
                    // Gunakan is_available dan is_past langsung dari API (sudah dihitung server)
                    categories[category].push({
                        id: slot.id,
                        booth_id: slot.booth_id,
                        booth_name: slot.booth_name || 'Booth',
                        time: fullTimeRange,
                        start_time: timeLabel,
                        available: slot.is_available,
                        isPast: slot.is_past,
                        remaining: slot.remaining
                    });
                });
                
                let hasAnySlots = false;
                let foundSelected = false;

                Object.keys(categories).forEach(catName => {
                    const list = categories[catName];
                    if (list.length === 0) return;
                    hasAnySlots = true;
                    
                    const catDiv = document.createElement('div');
                    catDiv.className = 'mb-4';
                    catDiv.innerHTML = `<p class="text-[10px] font-bold tracking-wider session-label mb-2">Sesi ${catName}</p>`;
                    
                    const gridDiv = document.createElement('div');
                    gridDiv.className = 'grid grid-cols-2 gap-3';
                    
                    list.forEach(slot => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        
                        let btnClass = 'time-slot p-3 rounded-xl text-left ';
                        let statusText = 'Tersedia';
                        let isDisabled = false;
                        
                        if (slot.isPast) {
                            btnClass += 'past-time ';
                            statusText = 'Lewat';
                            isDisabled = true;
                        } else if (!slot.available) {
                            btnClass += 'full ';
                            statusText = slot.remaining === 0 ? 'Penuh' : 'Tidak Tersedia';
                            isDisabled = true;
                        } else {
                            btnClass += 'available ';
                        }
                        
                        const isThisSelected = (selectedTime === slot.time && !isDisabled);
                        if (isThisSelected) {
                            btnClass += 'selected ';
                            statusText = 'Terpilih';
                            foundSelected = true;
                        }
                        
                        btn.className = btnClass;
                        btn.disabled = isDisabled;
                        if (isThisSelected) {
                            btn.style.background = '#bef264';
                            btn.style.borderColor = '#bef264';
                        }
                        
                        btn.innerHTML = `
                            <div class="text-xs font-bold time-text">${slot.time}</div>
                            <div class="text-[9px] text-slate-400 status-text flex justify-between gap-1 items-center mt-1">
                                <span>${statusText}</span>
                                <span class="text-slate-500">${slot.booth_name}</span>
                            </div>
                        `;
                        
                        btn.addEventListener('click', function() {
                            document.querySelectorAll('.time-slot').forEach(s => {
                                s.classList.remove('selected');
                                s.style.background = 'white';
                                s.style.borderColor = '#e2e8f0';
                                const st = s.querySelector('.status-text span');
                                if (st && !s.classList.contains('full') && !s.classList.contains('past-time')) {
                                    st.textContent = 'Tersedia';
                                }
                            });
                            
                            this.classList.add('selected');
                            this.style.background = '#bef264';
                            this.style.borderColor = '#bef264';
                            
                            const st = this.querySelector('.status-text span');
                            if (st) st.textContent = 'Terpilih';
                            
                            selectedTime = slot.time;
                            if (selectedTimeInput) selectedTimeInput.value = slot.time;
                            if (slotIdInput) slotIdInput.value = slot.id;
                            if (boothIdInput) boothIdInput.value = slot.booth_id;
                            
                            updateConfirmation();
                        });
                        
                        gridDiv.appendChild(btn);
                    });
                    
                    catDiv.appendChild(gridDiv);
                    container.appendChild(catDiv);
                });
                
                if (!hasAnySlots) {
                    container.innerHTML = '<div class="col-span-full text-center py-8 text-sm text-slate-500 italic">Tidak ada sesi operasional tersedia untuk tanggal ini.</div>';
                }

                if (!foundSelected && selectedTime) {
                    selectedTime = '';
                    if (selectedTimeInput) selectedTimeInput.value = '';
                    if (slotIdInput) slotIdInput.value = '';
                    if (boothIdInput) boothIdInput.value = '';
                    updateConfirmation();
                }
            }
            
            function renderCalendar() {
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth();
                
                monthYearLabel.textContent = monthNames[month] + ' ' + year;
                
                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                
                let html = '';
                
                for (let i = 0; i < firstDay; i++) {
                    html += '<div class="date-cell empty py-1"></div>';
                }
                
                for (let day = 1; day <= daysInMonth; day++) {
                    const dateObj = new Date(year, month, day);
                    const isSelected = (dateObj.getDate() === selectedDate.getDate() && 
                                       dateObj.getMonth() === selectedDate.getMonth() && 
                                       dateObj.getFullYear() === selectedDate.getFullYear());
                    const isPast = dateObj < todayDate;
                    
                    let classes = 'date-cell py-1';
                    if (isSelected) classes += ' selected-date';
                    if (isPast) classes += ' disabled';
                    
                    html += `<div class="${classes}" data-day="${day}" data-month="${month}" data-year="${year}">${day}</div>`;
                }
                
                calendarGrid.innerHTML = html;
                
                document.querySelectorAll('.date-cell:not(.empty):not(.disabled)').forEach(cell => {
                    cell.addEventListener('click', function() {
                        const day = parseInt(this.dataset.day);
                        const month = parseInt(this.dataset.month);
                        const year = parseInt(this.dataset.year);
                        
                        selectedDate = new Date(year, month, day);
                        selectedDateDisplay.textContent = formatDate(selectedDate);
                        
                        document.querySelectorAll('.date-cell').forEach(c => c.classList.remove('selected-date'));
                        this.classList.add('selected-date');
                        
                        updateConfirmation();
                        fetchSlotsForDate(selectedDate);
                    });
                });
            }
            
            document.getElementById('prev-month').addEventListener('click', function() {
                const newDate = new Date(currentDate);
                newDate.setMonth(newDate.getMonth() - 1);
                
                const today = new Date();
                if (newDate.getFullYear() < today.getFullYear() || 
                    (newDate.getFullYear() === today.getFullYear() && newDate.getMonth() < today.getMonth())) {
                    return;
                }
                
                currentDate = newDate;
                renderCalendar();
            });
            
            document.getElementById('next-month').addEventListener('click', function() {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar();
            });
            
            if (btnBack) {
                btnBack.addEventListener('click', function() {
                    window.location.href = '<?= base_url('register') ?>';
                });
            }
            
            if (scheduleForm) {
                scheduleForm.addEventListener('submit', function(e) {
                    const dateVal = selectedDateInput ? selectedDateInput.value : '';
                    const timeVal = selectedTimeInput ? selectedTimeInput.value : '';
                    const slotVal = slotIdInput ? slotIdInput.value : '';
                    
                    if (!dateVal || !timeVal || !slotVal) {
                        e.preventDefault();
                        alert('Silakan pilih tanggal dan waktu sesi yang tersedia terlebih dahulu!');
                        return false;
                    }
                    
                    if (submitBtn) {
                        submitBtn.innerHTML = '<span>Memproses...</span>';
                        submitBtn.disabled = true;
                    }
                    
                    return true;
                });
            }
            
            renderCalendar();
            selectedDateDisplay.textContent = formatDate(selectedDate);
            updateConfirmation();
            fetchSlotsForDate(selectedDate);
        });
    </script>
</body>
</html>