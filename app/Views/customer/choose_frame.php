<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CuitBox Studio - Pilih Frame</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(0deg, rgba(249, 249, 255, 1) 0%, rgba(249, 249, 255, 1) 100%);
            min-height: 100vh;
        }

        /* Main Container */
        .kiosk-kategori {
            display: flex;
            flex-direction: column;
            min-height: 1024px;
            align-items: flex-start;
            position: relative;
            background: linear-gradient(0deg, rgba(249, 249, 255, 1) 0%, rgba(249, 249, 255, 1) 100%);
        }

        /* Header - SAMA DENGAN VERIFY_CODE */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 48px;
            position: relative;
            align-self: stretch;
            width: 100%;
            flex: 0 0 auto;
            z-index: 1;
            background-color: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.04);
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            color: #008393;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Language Button */
        .language-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 40px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 14px;
            font-weight: 500;
            color: #008393;
        }

        .language-btn:hover {
            background: #f1f5f9;
            border-color: #008393;
            transform: scale(1.02);
        }

        /* Help Button */
        .help-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 40px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .help-btn:hover {
            background: #f1f5f9;
            border-color: #008393;
            transform: scale(1.02);
        }

        .help-btn span {
            font-size: 14px;
            font-weight: 500;
            color: #008393;
        }

        .help-btn svg {
            width: 18px;
            height: 18px;
        }

        /* Main Content */
        .main {
            display: flex;
            flex-direction: column;
            max-width: 1280px;
            align-items: flex-start;
            gap: 48px;
            padding: 48px 48px 157px;
            position: relative;
            width: 100%;
            flex: 0 0 auto;
            z-index: 0;
            margin: 0 auto;
        }

        /* Title Section */
        .title-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .title-text h1 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            color: #008393;
            font-size: 32px;
            letter-spacing: 0;
            line-height: 41.6px;
            margin-bottom: 8px;
        }

        .title-text p {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 400;
            color: #008393;
            font-size: 20px;
            line-height: 32px;
        }

        /* Progress Indicators */
        .progress-indicators {
            display: flex;
            gap: 8px;
        }

        .progress-active {
            width: 64px;
            height: 12px;
            background-color: #008393;
            border-radius: 9999px;
        }

        .progress-inactive {
            width: 64px;
            height: 12px;
            background-color: #d8e3fb;
            border-radius: 9999px;
        }

        /* Frame Grid - Center the cards */
        .frame-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 32px;
            width: 100%;
            justify-items: center;
            align-items: center;
        }

        /* Frame Card */
        .frame-card {
            background: #ffffff;
            border-radius: 24px;
            border: 2px solid #008393;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0px 8px 24px rgba(0, 51, 102, 0.08);
            position: relative;
            width: 100%;
            max-width: 260px;
        }

        .frame-card:hover {
            transform: translateY(-4px);
            box-shadow: 0px 12px 32px rgba(0, 131, 147, 0.15);
        }

        .frame-card.selected {
            border-color: #008393;
            background: linear-gradient(135deg, #ffffff 0%, #e8f8f9 100%);
            box-shadow: 0px 0px 0px 4px rgba(0, 227, 253, 0.2);
        }

        /* Frame Image Area */
        .frame-image {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 45px 16px;
            background-color: #e7eeff;
            border-radius: 12px;
            margin-bottom: 20px;
            aspect-ratio: 0.67;
        }

        .frame-preview {
            width: 100%;
            max-width: 125px;
            height: auto;
            background: white;
            border-radius: 4px;
            padding: 20px;
            box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.05);
        }

        .frame-preview-icon {
            font-size: 48px;
            text-align: center;
        }

        /* Frame Title */
        .frame-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600;
            color: #008393;
            font-size: 24px;
            text-align: center;
            margin-bottom: 8px;
        }

        .frame-subtitle {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600;
            color: #737780;
            font-size: 16px;
            text-align: center;
            letter-spacing: 0.8px;
        }

        /* Submit Button */
        .button-wrapper {
            display: flex;
            justify-content: center;
            width: 100%;
            padding-top: 16px;
        }

        .submit-btn {
            display: inline-flex;
            align-items: center;
            gap: 16px;
            padding: 24px 48px;
            background-color: #008393;
            border: none;
            border-radius: 9999px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0px 8px 10px -6px rgba(0, 0, 0, 0.1), 0px 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .submit-btn:hover {
            background-color: #006b78;
            transform: scale(1.02);
        }

        .submit-btn:disabled {
            background-color: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }

        .submit-btn span {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            color: #ffffff;
            font-size: 22px;
        }

        .submit-btn img {
            width: 16px;
            height: 16px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .frame-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .main {
                padding: 32px 24px;
            }
        }

        @media (max-width: 640px) {
            .frame-grid {
                grid-template-columns: 1fr;
            }
            .header {
                padding: 16px 24px;
            }
            .title-text h1 {
                font-size: 24px;
            }
            .title-text p {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
<div class="kiosk-kategori">
    <!-- HEADER YANG SUDAH DISAMAKAN DENGAN VERIFY_CODE -->
    <header class="header">
        <div class="logo">CuitBox Studio</div>
        <div class="header-right">
            <!-- Language Button -->
            <div class="language-btn">
                <span>🌐</span>
                <span>ID</span>
            </div>
            
            <!-- Help Button -->
            <div class="help-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" stroke="#008393" stroke-width="2"/>
                    <path d="M9 9C9 7.89543 9.89543 7 11 7H13C14.1046 7 15 7.89543 15 9C15 10.1046 14.1046 11 13 11H12V13" stroke="#008393" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="12" cy="17" r="1" fill="#008393"/>
                </svg>
                <span>Help</span>
            </div>
        </div>
    </header>

    <!-- Main Content (SAMA SEPERTI SEBELUMNYA) -->
    <div class="main">
        <!-- Title Section -->
        <div class="title-section">
            <div class="title-text">
                <h1>Pilih Frame</h1>
                <p>Pilih Tema Frame Terbaik Untuk Memori Anda</p>
            </div>
            <div class="progress-indicators">
                <div class="progress-active"></div>
                <div class="progress-inactive"></div>
                <div class="progress-inactive"></div>
                <div class="progress-inactive"></div>
            </div>
        </div>

        <!-- Frame Grid -->
        <form id="frameForm" action="/customer/save-frame" method="POST">
            <input type="hidden" name="booking_id" value="<?php echo isset($booking) ? htmlspecialchars($booking['id']) : 'DEMO001'; ?>">
            <input type="hidden" name="frame_choice" id="frame_choice" value="">

<div class="frame-grid">
    <!-- Frame 1: Clean Minimalist -->
    <div class="frame-card" data-frame="clean_minimalist">
        <div class="frame-image">
            <img src="https://placehold.co/300x400/f8f9fa/008393?text=Clean+Minimalist" 
                 alt="Clean Minimalist Frame" 
                 class="frame-preview-img">
        </div>
        <div class="frame-title">Clean Minimalist</div>
        <div class="frame-subtitle">White & Simple</div>
    </div>

    <!-- Frame 2: Cute & Kawaii -->
    <div class="frame-card" data-frame="cute_kawaii">
        <div class="frame-image">
            <img src="https://placehold.co/300x400/fce7f3/008393?text=Cute+Kawaii" 
                 alt="Cute & Kawaii Frame" 
                 class="frame-preview-img">
        </div>
        <div class="frame-title">Cute & Kawaii</div>
        <div class="frame-subtitle">Pastel Style</div>
    </div>

    <!-- Frame 3: Retro 80's -->
    <div class="frame-card" data-frame="retro_vintage">
        <div class="frame-image">
            <img src="https://placehold.co/300x400/4a4a4a/ffffff?text=Retro+80's" 
                 alt="Retro 80's Frame" 
                 class="frame-preview-img">
        </div>
        <div class="frame-title">Retro 80's</div>
        <div class="frame-subtitle">Vintage Style</div>
    </div>

    <!-- Frame 4: Magazine News -->
    <div class="frame-card" data-frame="magazine_news">
        <div class="frame-image">
            <img src="https://placehold.co/300x400/ff6b6b/ffffff?text=Magazine+News" 
                 alt="Magazine News Frame" 
                 class="frame-preview-img">
        </div>
        <div class="frame-title">Magazine News</div>
        <div class="frame-subtitle">Lifestyle News</div>
    </div>
</div>

<style>
/* Frame Image dengan img */
.frame-image {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: #e7eeff;
    border-radius: 12px;
    margin-bottom: 20px;
    overflow: hidden;
}

.frame-preview-img {
    width: 100%;
    height: auto;
    aspect-ratio: 3/4;
    object-fit: cover;
    border-radius: 12px;
}

</style>


            <!-- Submit Button -->
            <div class="button-wrapper">
                <button type="submit" class="submit-btn" id="submitBtn" disabled>
                    <span>Konfirmasi Pilihan Frame</span>
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='white' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M9 5l7 7-7 7' /%3E%3C/svg%3E" alt="arrow">
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let selectedFrame = null;
    const frameCards = document.querySelectorAll('.frame-card');
    const submitBtn = document.getElementById('submitBtn');
    const frameChoiceInput = document.getElementById('frame_choice');

    frameCards.forEach(card => {
        card.addEventListener('click', () => {
            frameCards.forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
            selectedFrame = card.dataset.frame;
            frameChoiceInput.value = selectedFrame;
            submitBtn.disabled = false;
        });
    });

    // Active session forced reset check
    const currentBoothId = '<?= esc($booking['booth_id'] ?? '') ?>';
    const currentBookingId = '<?= esc($booking['id'] ?? '') ?>';
    
    if (currentBoothId && currentBookingId) {
        setInterval(() => {
            fetch(`/api/booths/${currentBoothId}/session-check`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 200 && data.active_booking_id !== currentBookingId) {
                        alert('Sesi Anda telah di-reset oleh administrator.');
                        window.location.href = '/customer/verify';
                    }
                })
                .catch(err => console.error('Error checking session status:', err));
        }, 3000);
    }
</script>
</body>
</html>