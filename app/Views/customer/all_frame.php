<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>CuitBox Studio - Pilih Frame</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .frame-preview {
            aspect-ratio: 3/4;
        }
        .selected-glow {
            box-shadow: 0 0 0 2px #00685f, 0 10px 30px -10px rgba(0, 104, 95, 0.3);
        }
    </style>
</head>
<body class="bg-background text-on-background font-body-md min-h-screen flex flex-col">

<!-- HEADER PUTIH (RAPI) -->
<div class="header">
    <div class="logo">CuitBox Studio</div>
    <div class="header-right">
        <!-- Language Button -->
        <button class="header-btn" onclick="alert('Language selection')">
            <span>🌐</span>
            <span>ID</span>
        </button>
        
        <!-- Help Button -->
        <button class="header-btn" onclick="alert('Help - Contact support@cuitboxstudio.com')">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="10" stroke="#008393" stroke-width="2"/>
                <path d="M9 9C9 7.89543 9.89543 7 11 7H13C14.1046 7 15 7.89543 15 9C15 10.1046 14.1046 11 13 11H12V13" stroke="#008393" stroke-width="2" stroke-linecap="round"/>
                <circle cx="12" cy="17" r="1" fill="#008393"/>
            </svg>
            <span>Help</span>
        </button>
    </div>
</div>

<style>
/* ============================================================ */
/* HEADER PUTIH
/* ============================================================ */
.header {
    background: white;
    padding: 16px 48px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    position: relative;
    z-index: 20;
}

.logo {
    font-size: 24px;
    font-weight: 600;
    color: #008393;
    letter-spacing: 1px;
}

.header-right {
    display: flex;
    gap: 16px;
}

.header-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    background: transparent;
    border: 1px solid #e2e8f0;
    padding: 8px 16px;
    border-radius: 40px;
    font-size: 14px;
    font-weight: 500;
    color: #008393;
    cursor: pointer;
    transition: all 0.2s;
}

.header-btn:hover {
    background: #f1f5f9;
    border-color: #008393;
}

/* Responsive Header */
@media (max-width: 640px) {
    .header {
        padding: 12px 20px;
    }
    .logo {
        font-size: 18px;
    }
    .header-btn {
        padding: 6px 12px;
        font-size: 12px;
    }
}
</style>


<!-- Main Content Canvas - Background Hijau -->
 <style>
 /* Background hijau untuk main content */
.bg-teal-primary {
    background: #ffffff;
}
</style>

<main class="flex-grow pt-24 pb-32 px-6 md:px-12 max-w-7xl mx-auto w-full bg-teal-primary">
    <div class="mb-8 text-center">
<h1 class="font-display text-headline-lg text-[#008393] mb-2">Pilih Frame Terbaik untuk memori Anda</h1>
</div>

<style>
    /* Ubah background body menjadi hijau */
    body {
        background: #ffffff;
    }
    
    /* Header dan Footer tetap putih */
    .header, .footer {
        background: white;
    }
    
    /* Container card tetap putih */
    .frame-grid-container {
        background: white;
    }
</style>


  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    <!-- Card 1: Pink Dream -->
    <div class="frame-card bg-white rounded-xl p-6 relative group cursor-pointer transition-all duration-300 border-2 border-gray-200 shadow-md hover:shadow-xl text-center" data-frame="Pink Dream">
        <div class="absolute top-4 right-4 z-10 bg-primary text-white rounded-full p-1 shadow-md opacity-0 transition-opacity selected-check">
            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'wght' 700;">check</span>
        </div>
        <div class="frame-preview rounded-lg mb-4 overflow-hidden border-4 border-white shadow-inner flex items-center justify-center">
            <img src="https://placehold.co/400x500/FFB6C1/white?text=Pink+Dream" alt="Pink Dream Frame" class="w-full h-full object-cover">
        </div>
        <div class="flex flex-col items-center">
            <span class="font-display text-label-lg text-primary mb-1 text-center">Pink Dream</span>
            <span class="text-label-sm text-on-surface-variant text-center">Classic Oriental & Stars</span>
            <div class="flex flex-wrap gap-2 mt-2 justify-center">
                <span class="text-xs bg-gray-100 px-2 py-1 rounded-full">Sky Bubble</span>
                <span class="text-xs bg-gray-100 px-2 py-1 rounded-full">Yibam Neez Blue</span>
            </div>
        </div>
    </div>

    <!-- Card 2: Sweet Lavender -->
    <div class="frame-card bg-white rounded-xl p-6 relative group cursor-pointer transition-all duration-300 border-2 border-gray-200 shadow-md hover:shadow-xl text-center" data-frame="Sweet Lavender">
        <div class="absolute top-4 right-4 z-10 bg-primary text-white rounded-full p-1 shadow-md opacity-0 transition-opacity selected-check">
            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'wght' 700;">check</span>
        </div>
        <div class="frame-preview rounded-lg mb-4 overflow-hidden border-4 border-white shadow-inner flex items-center justify-center">
            <img src="https://placehold.co/400x500/E6E6FA/white?text=Sweet+Lavender" alt="Sweet Lavender Frame" class="w-full h-full object-cover">
        </div>
        <div class="flex flex-col items-center">
            <span class="font-display text-label-lg text-primary mb-1 text-center">Sweet Lavender</span>
            <span class="text-label-sm text-on-surface-variant text-center">Pusat Hant Magic</span>
            <div class="flex flex-wrap gap-2 mt-2 justify-center">
                <span class="text-xs bg-gray-100 px-2 py-1 rounded-full">Sweet Lavender</span>
                <span class="text-xs bg-gray-100 px-2 py-1 rounded-full">Pusat Hant Magic</span>
            </div>
        </div>
    </div>

    <!-- Card 3: Sky Bubbles -->
    <div class="frame-card bg-white rounded-xl p-6 relative group cursor-pointer transition-all duration-300 border-2 border-gray-200 shadow-md hover:shadow-xl text-center" data-frame="Sky Bubbles">
        <div class="absolute top-4 right-4 z-10 bg-primary text-white rounded-full p-1 shadow-md opacity-0 transition-opacity selected-check">
            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'wght' 700;">check</span>
        </div>
        <div class="frame-preview rounded-lg mb-4 overflow-hidden border-4 border-white shadow-inner flex items-center justify-center">
            <img src="https://placehold.co/400x500/87CEEB/white?text=Sky+Bubbles" alt="Sky Bubbles Frame" class="w-full h-full object-cover">
        </div>
        <div class="flex flex-col items-center">
            <span class="font-display text-label-lg text-primary mb-1 text-center">Sky Bubbles</span>
            <span class="text-label-sm text-on-surface-variant text-center">Playful and airy</span>
            <div class="flex flex-wrap gap-2 mt-2 justify-center">
                <span class="text-xs bg-gray-100 px-2 py-1 rounded-full">Bubble Pop</span>
                <span class="text-xs bg-gray-100 px-2 py-1 rounded-full">Cloud Nine</span>
            </div>
        </div>
    </div>

    <!-- Card 4: Rainbow Pop -->
    <div class="frame-card bg-white rounded-xl p-6 relative group cursor-pointer transition-all duration-300 border-2 border-gray-200 shadow-md hover:shadow-xl text-center" data-frame="Rainbow Pop">
        <div class="absolute top-4 right-4 z-10 bg-primary text-white rounded-full p-1 shadow-md opacity-0 transition-opacity selected-check">
            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'wght' 700;">check</span>
        </div>
        <div class="frame-preview rounded-lg mb-4 overflow-hidden border-4 border-white shadow-inner flex items-center justify-center">
            <img src="https://placehold.co/400x500/FF6B6B/white?text=Rainbow+Pop" alt="Rainbow Pop Frame" class="w-full h-full object-cover">
        </div>
        <div class="flex flex-col items-center">
            <span class="font-display text-label-lg text-primary mb-1 text-center">Rainbow Pop</span>
            <span class="text-label-sm text-on-surface-variant text-center">Multi-color/Splash</span>
            <div class="flex flex-wrap gap-2 mt-2 justify-center">
                <span class="text-xs bg-gray-100 px-2 py-1 rounded-full">Gold Center: Elegant Pony View</span>
            </div>
        </div>
    </div>

    <!-- Card 5: Gold Confetti -->
    <div class="frame-card bg-white rounded-xl p-6 relative group cursor-pointer transition-all duration-300 border-2 border-gray-200 shadow-md hover:shadow-xl text-center" data-frame="Gold Confetti">
        <div class="absolute top-4 right-4 z-10 bg-primary text-white rounded-full p-1 shadow-md opacity-0 transition-opacity selected-check">
            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'wght' 700;">check</span>
        </div>
        <div class="frame-preview rounded-lg mb-4 overflow-hidden border-4 border-white shadow-inner flex items-center justify-center">
            <img src="https://placehold.co/400x500/FFD700/white?text=Gold+Confetti" alt="Gold Confetti Frame" class="w-full h-full object-cover">
        </div>
        <div class="flex flex-col items-center">
            <span class="font-display text-label-lg text-primary mb-1 text-center">Gold Confetti</span>
            <span class="text-label-sm text-on-surface-variant text-center">Luxury celebrations</span>
            <div class="flex flex-wrap gap-2 mt-2 justify-center">
                <span class="text-xs bg-gray-100 px-2 py-1 rounded-full">Golden Sparkle</span>
                <span class="text-xs bg-gray-100 px-2 py-1 rounded-full">Confetti Burst</span>
            </div>
        </div>
    </div>

    <!-- Card 6: Retro Glitch -->
    <div class="frame-card bg-white rounded-xl p-6 relative group cursor-pointer transition-all duration-300 border-2 border-gray-200 shadow-md hover:shadow-xl text-center" data-frame="Retro Glitch">
        <div class="absolute top-4 right-4 z-10 bg-primary text-white rounded-full p-1 shadow-md opacity-0 transition-opacity selected-check">
            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'wght' 700;">check</span>
        </div>
        <div class="frame-preview rounded-lg mb-4 overflow-hidden border-4 border-white shadow-inner flex items-center justify-center">
            <img src="https://placehold.co/400x500/4B0082/white?text=Retro+Glitch" alt="Retro Glitch Frame" class="w-full h-full object-cover">
        </div>
        <div class="flex flex-col items-center">
            <span class="font-display text-label-lg text-primary mb-1 text-center">Retro Glitch</span>
            <span class="text-label-sm text-on-surface-variant text-center">Cool Glitch Video</span>
            <div class="flex flex-wrap gap-2 mt-2 justify-center">
                <span class="text-xs bg-gray-100 px-2 py-1 rounded-full">Retro Glitch</span>
                <span class="text-xs bg-gray-100 px-2 py-1 rounded-full">Cool Glitch Video</span>
            </div>
        </div>
    </div>
</div>

        <!-- Bottom Navigation / Submit Button -->
        <div class="fixed bottom-0 left-0 right-0 flex flex-col md:flex-row justify-between items-center px-8 py-6 bg-white border-t border-slate-100 shadow-[0_-8px_30px_rgb(0,0,0,0.04)] z-50">
            <div class="flex items-center gap-3 mb-4 md:mb-0 text-on-surface-variant">
                <div class="bg-teal-50 p-1.5 rounded-full">
                    <span class="material-symbols-outlined text-[20px] text-teal-600">info</span>
                </div>
<p class="font-body-md text-[#008393]">Pilih frame sesuai dengan gaya Anda</p>
            </div>
            <div class="flex items-center gap-4 w-full md:w-auto">
                <button type="button" class="flex items-center gap-2 text-slate-600 border border-slate-200 rounded-lg px-6 py-3 hover:bg-slate-50 transition-all font-label-lg flex-1 md:flex-none justify-center" onclick="location.reload()">
                    <span class="material-symbols-outlined">refresh</span>
                    Refresh
                </button>
                <button type="submit" class="flex items-center gap-2 bg-teal-600 text-white rounded-lg px-8 py-3 shadow-lg shadow-teal-500/20 hover:opacity-90 transition-all font-label-lg flex-1 md:flex-none justify-center" id="submitBtn" disabled>
                    Konfirmasi & Lanjutkan
                    <span class="material-symbols-outlined">arrow_forward</span>
                </button>
            </div>
        </div>
    </form>
</main>

<script>
    let selectedFrame = null;
    const frameCards = document.querySelectorAll('.frame-card');
    const submitBtn = document.getElementById('submitBtn');
    const frameChoiceInput = document.getElementById('frame_choice');

    frameCards.forEach(card => {
        card.addEventListener('click', () => {
            // Remove selected class and glow from all cards
            frameCards.forEach(c => {
                c.classList.remove('selected-glow');
                c.classList.remove('border-teal-500');
                c.classList.add('border-transparent');
                const checkIcon = c.querySelector('.selected-check');
                if (checkIcon) checkIcon.style.opacity = '0';
            });
            
            // Add selected class to clicked card
            card.classList.add('selected-glow');
            card.classList.remove('border-transparent');
            card.classList.add('border-teal-500');
            const checkIcon = card.querySelector('.selected-check');
            if (checkIcon) checkIcon.style.opacity = '1';
            
            // Set selected frame
            selectedFrame = card.dataset.frame;
            frameChoiceInput.value = selectedFrame;
            submitBtn.disabled = false;
        });
    });
</script>

<style>
    .text-primary {
        color: #00685f;
    }
    .bg-primary {
        background-color: #00685f;
    }
    .bg-teal-600 {
        background-color: #0d9488;
    }
    .text-teal-600 {
        color: #0d9488;
    }
    .border-teal-500 {
        border-color: #14b8a6;
    }
    .bg-surface {
        background-color: #0d9488;
    }
    .text-on-surface-variant {
        color: #3d4947;
    }
    .text-headline-lg {
        font-size: 24px;
        line-height: 1.3;
        font-weight: 700;
    }
    .text-label-lg {
        font-size: 14px;
        line-height: 1.2;
        font-weight: 600;
    }
    .text-label-sm {
        font-size: 10px;
        line-height: 1.2;
        font-weight: 500;
    }
    .font-display {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .font-body-md {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 14px;
        line-height: 1.6;
        font-weight: 400;
    }
    .bg-pink-100 { background-color: #fce7f3; }
    .bg-purple-100 { background-color: #f3e8ff; }
    .bg-sky-100 { background-color: #e0f2fe; }
    .bg-yellow-50 { background-color: #fefce8; }
</style>
</body>
</html>

