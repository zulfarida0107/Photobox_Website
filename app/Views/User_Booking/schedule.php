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
    </style>
</head>
<body>
    <!-- BEGIN: Page Wrapper -->
    <div class="page-wrapper">
        <!-- BEGIN: MainContainer -->
        <div class="w-full main-container-rounded gradient-bg flex flex-col min-h-screen">

            <!-- BEGIN: Header -->
            <header class="flex items-center justify-between px-6 sm:px-8 py-4 header-bg border-b border-gray-100">
                <div class="flex items-center">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">CuitBox Studio</h1>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-4 text-slate-600">
                    <button class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center rounded-full hover:bg-gray-100/50 transition-all" aria-label="Help">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                        </svg>
                    </button>
                    <button class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center rounded-full hover:bg-gray-100/50 transition-all" aria-label="Language">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"/>
                        </svg>
                    </button>
                </div>
            </header>
            <!-- END: Header -->

            <!-- BEGIN: HeroSection -->
            <main class="relative px-6 sm:px-8 pt-8 pb-8 overflow-hidden bg-white/40 backdrop-blur-sm">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-extrabold mb-1 text-slate-800">Booking Session</h2>
                    <p class="text-[10px] text-slate-600 font-medium">Pilih Tanggal &amp; Waktu Sesi Fotobox</p>
                </div>
                
                <!-- Content Layout: Calendar & Session -->
                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Left Column: Calendar -->
                    <section class="flex-1">
                        <div class="calendar-card p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-lg font-bold" id="month-year-label">Oktober 2024</h2>
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
                            <p class="text-sm font-medium mb-4">Tanggal Terpilih: <span class="font-bold" id="selected-date-display">09 Oktober 2024</span></p>
                            
                            <!-- Time Slot Categories -->
                            <div class="space-y-4 flex-1" id="time-slots-container">
                                <!-- A. PAGI -->
                                <div>
                                    <p class="text-[10px] font-bold tracking-wider session-label">A. PAGI</p>
                                    <div class="grid grid-cols-2 gap-3">
                                        <button class="time-slot available p-3 rounded-xl text-left" data-time="09:00 - 11:00" data-slot="09:00">
                                            <div class="text-xs font-bold time-text">09:00 - 11:00</div>
                                            <div class="text-[9px] text-slate-400 status-text">Tersedia</div>
                                        </button>
                                        <button class="time-slot available p-3 rounded-xl text-left" data-time="11:00 - 13:00" data-slot="11:00">
                                            <div class="text-xs font-bold time-text">11:00 - 13:00</div>
                                            <div class="text-[9px] text-slate-400 status-text">Tersedia</div>
                                        </button>
                                    </div>
                                </div>
                                <!-- B. SIANG -->
                                <div>
                                    <p class="text-[10px] font-bold tracking-wider session-label">B. SIANG</p>
                                    <div class="grid grid-cols-2 gap-3">
                                        <button class="time-slot selected p-3 rounded-xl text-left" data-time="13:00 - 15:00" data-slot="13:00">
                                            <div class="text-xs font-bold time-text">13:00 - 15:00</div>
                                            <div class="text-[9px] status-text">Terpilih</div>
                                        </button>
                                        <button class="time-slot available p-3 rounded-xl text-left" data-time="15:00 - 17:00" data-slot="15:00">
                                            <div class="text-xs font-bold time-text">15:00 - 17:00</div>
                                            <div class="text-[9px] text-slate-400 status-text">Tersedia</div>
                                        </button>
                                    </div>
                                </div>
                                <!-- C. SORE -->
                                <div>
                                    <p class="text-[10px] font-bold tracking-wider session-label">C. SORE</p>
                                    <div class="grid grid-cols-2 gap-3">
                                        <button class="time-slot full p-3 rounded-xl text-left" data-time="17:00 - 19:00" data-slot="17:00" disabled>
                                            <div class="text-xs font-bold time-text">17:00 - 19:00</div>
                                            <div class="text-[9px] text-slate-300 status-text">Penuh</div>
                                        </button>
                                        <button class="time-slot available p-3 rounded-xl text-left" data-time="19:00 - 21:00" data-slot="19:00">
                                            <div class="text-xs font-bold time-text">19:00 - 21:00</div>
                                            <div class="text-[9px] text-slate-400 status-text">Tersedia</div>
                                        </button>
                                    </div>
                                </div>
                                <!-- D. MALAM -->
                                <div>
                                    <p class="text-[10px] font-bold tracking-wider session-label">D. MALAM</p>
                                    <div class="grid grid-cols-2 gap-3">
                                        <button class="time-slot available p-3 rounded-xl text-left" data-time="21:00 - 23:00" data-slot="21:00">
                                            <div class="text-xs font-bold time-text">21:00 - 23:00</div>
                                            <div class="text-[9px] text-slate-400 status-text">Tersedia</div>
                                        </button>
                                    </div>
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
                                    
                                    <!-- Button Lanjutkan ke Booth -->
                                    <button type="submit" class="btn-primary" id="submitScheduleBtn">
                                        <span>Lanjutkan ke Pemilihan Booth</span>
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
            // ===== VARIABLES =====
            let currentDate = new Date(2024, 9, 9);
            let selectedDate = new Date(2024, 9, 9);
            let selectedTime = '13:00 - 15:00';
            
            const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            // ===== DOM ELEMENTS =====
            const calendarGrid = document.getElementById('calendar-grid');
            const monthYearLabel = document.getElementById('month-year-label');
            const selectedDateDisplay = document.getElementById('selected-date-display');
            const confirmationDisplay = document.getElementById('confirmation-display');
            const selectedDateInput = document.getElementById('selected_date');
            const selectedTimeInput = document.getElementById('selected_time');
            const submitBtn = document.getElementById('submitScheduleBtn');
            const scheduleForm = document.getElementById('scheduleForm');
            const btnBack = document.getElementById('btn-back');
            
            // ===== HELPER FUNCTIONS =====
            function formatDate(date) {
                return date.getDate() + ' ' + monthNames[date.getMonth()] + ' ' + date.getFullYear();
            }
            
            function updateHiddenFields() {
                const dateStr = formatDate(selectedDate);
                const timeStr = selectedTime;
                
                if (selectedDateInput) {
                    selectedDateInput.value = dateStr;
                }
                if (selectedTimeInput) {
                    selectedTimeInput.value = timeStr;
                }
                
                // Update confirmation display
                if (selectedTime && confirmationDisplay) {
                    confirmationDisplay.textContent = dateStr + ' | ' + selectedTime + ' WIB';
                } else if (confirmationDisplay) {
                    confirmationDisplay.textContent = dateStr + ' | Pilih waktu';
                }
            }
            
            function updateConfirmation() {
                updateHiddenFields();
            }
            
            // ===== CALENDAR LOGIC =====
            function renderCalendar() {
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth();
                
                monthYearLabel.textContent = monthNames[month] + ' ' + year;
                
                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                
                let html = '';
                
                // Empty cells before first day
                for (let i = 0; i < firstDay; i++) {
                    html += '<div class="date-cell empty py-1"></div>';
                }
                
                // Date cells
                for (let day = 1; day <= daysInMonth; day++) {
                    const dateObj = new Date(year, month, day);
                    const isSelected = (dateObj.getDate() === selectedDate.getDate() && 
                                       dateObj.getMonth() === selectedDate.getMonth() && 
                                       dateObj.getFullYear() === selectedDate.getFullYear());
                    
                    let classes = 'date-cell py-1';
                    if (isSelected) classes += ' selected-date';
                    
                    html += `<div class="${classes}" data-day="${day}" data-month="${month}" data-year="${year}">${day}</div>`;
                }
                
                calendarGrid.innerHTML = html;
                
                // Add click event to date cells
                document.querySelectorAll('.date-cell:not(.empty)').forEach(cell => {
                    cell.addEventListener('click', function() {
                        const day = parseInt(this.dataset.day);
                        const month = parseInt(this.dataset.month);
                        const year = parseInt(this.dataset.year);
                        
                        selectedDate = new Date(year, month, day);
                        selectedDateDisplay.textContent = formatDate(selectedDate);
                        updateConfirmation();
                        renderCalendar();
                    });
                });
            }
            
            // Month navigation
            document.getElementById('prev-month').addEventListener('click', function() {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar();
            });
            
            document.getElementById('next-month').addEventListener('click', function() {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar();
            });
            
            // ===== TIME SLOT LOGIC =====
            const timeSlots = document.querySelectorAll('.time-slot:not(.full)');
            
            timeSlots.forEach(slot => {
                slot.addEventListener('click', function() {
                    // Reset all time slots
                    timeSlots.forEach(s => {
                        s.classList.remove('selected');
                        s.style.background = 'white';
                        s.style.borderColor = '#bef264';
                        const statusText = s.querySelector('.status-text');
                        if (statusText) {
                            statusText.className = 'text-[9px] text-slate-400 status-text';
                            statusText.textContent = 'Tersedia';
                        }
                        const timeText = s.querySelector('.time-text');
                        if (timeText) {
                            timeText.className = 'text-xs font-bold time-text';
                            timeText.style.color = '#1e293b';
                        }
                    });
                    
                    // Select current slot
                    this.classList.add('selected');
                    this.style.background = '#bef264';
                    this.style.borderColor = '#bef264';
                    
                    const statusText = this.querySelector('.status-text');
                    if (statusText) {
                        statusText.className = 'text-[9px] font-bold status-text';
                        statusText.style.color = '#1e293b';
                        statusText.textContent = 'Terpilih';
                    }
                    
                    const timeText = this.querySelector('.time-text');
                    if (timeText) {
                        timeText.className = 'text-xs font-bold time-text';
                        timeText.style.color = '#1e293b';
                    }
                    
                    const timeLabel = this.querySelector('.time-text');
                    if (timeLabel) {
                        selectedTime = timeLabel.textContent;
                        updateConfirmation();
                    }
                });
            });
            
            // ===== BACK BUTTON LOGIC =====
            if (btnBack) {
                btnBack.addEventListener('click', function() {
                    window.location.href = '<?= base_url('register') ?>';
                });
            }
            
            // ===== FORM SUBMIT VALIDATION =====
            if (scheduleForm) {
                scheduleForm.addEventListener('submit', function(e) {
                    const dateVal = selectedDateInput ? selectedDateInput.value : '';
                    const timeVal = selectedTimeInput ? selectedTimeInput.value : '';
                    
                    if (!dateVal || !timeVal) {
                        e.preventDefault();
                        alert('Silakan pilih tanggal dan waktu sesi terlebih dahulu!');
                        return false;
                    }
                    
                    // Loading state
                    if (submitBtn) {
                        submitBtn.innerHTML = '<span>Memproses...</span>';
                        submitBtn.disabled = true;
                    }
                    
                    return true;
                });
            }
            
            // ===== INITIAL RENDER =====
            renderCalendar();
            selectedDateDisplay.textContent = formatDate(selectedDate);
            updateConfirmation();
            
            // Set default selected time slot
            const defaultSlot = document.querySelector('.time-slot[data-time="13:00 - 15:00"]');
            if (defaultSlot) {
                defaultSlot.click();
            }
        });
    </script>
</body>
</html>