<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photobox - Capture the Moment</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

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

        /* ============================================================ */
        /* MAIN CONTENT (FULL BACKGROUND IMAGE)
        /* ============================================================ */
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            position: relative;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 500px;
        }

        /* Dark Overlay agar teks tetap terbaca */
        .main-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }

        .container {
            text-align: center;
            max-width: 500px;
            width: 100%;
            position: relative;
            z-index: 10;
        }

        /* Camera Icon - DI TENGAH */
        .camera-icon {
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .camera-icon svg {
            width: 80px;
            height: 80px;
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.2));
        }

        /* Main Text */
        .capture-text {
            font-size: 36px;
            font-weight: 300;
            color: white;
            letter-spacing: 1px;
            line-height: 1.2;
            margin-bottom: 8px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .moment-text {
            font-size: 48px;
            font-weight: 700;
            color: white;
            letter-spacing: 2px;
            line-height: 1.2;
            margin-bottom: 40px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        /* Image Slider Dots */
        .slider-dots {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 40px;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s;
        }

        .dot.active {
            background: white;
            width: 24px;
            border-radius: 10px;
        }

        .dot:hover {
            background: white;
        }

        /* Tap to Start Button */
        .tap-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: none;
            padding: 14px 40px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            color: #008393;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .tap-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .tap-btn:active {
            transform: scale(0.98);
        }

        /* ============================================================ */
        /* FOOTER PUTIH
        /* ============================================================ */
        .footer {
            background: white;
            padding: 16px 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
            position: relative;
            z-index: 20;
        }

        .status {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: #4ade80;
            border-radius: 50%;
            display: inline-block;
            animation: pulse 1.5s infinite;
        }

        .status-text {
            font-size: 12px;
            color: #008393;
            opacity: 0.8;
            font-weight: 500;
        }

        .version {
            font-size: 12px;
            color: #008393;
            opacity: 0.6;
        }

        @keyframes pulse {
            0% { opacity: 0.5; transform: scale(0.8); }
            50% { opacity: 1; transform: scale(1); }
            100% { opacity: 0.5; transform: scale(0.8); }
        }

        /* ============================================================ */
        /* RESPONSIVE
        /* ============================================================ */
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
            .footer {
                padding: 12px 20px;
            }
            .camera-icon svg {
                width: 60px;
                height: 60px;
            }
            .capture-text {
                font-size: 28px;
            }
            .moment-text {
                font-size: 36px;
                margin-bottom: 30px;
            }
            .tap-btn {
                padding: 12px 32px;
                font-size: 14px;
            }
            .status-text, .version {
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- HEADER PUTIH (RAPI) -->
    <div class="header">
        <div class="logo">Photobox</div>
        <div class="header-right">
            <button class="header-btn" onclick="alert('Language selection')">
                <span>🌐</span>
                <span>ID</span>
            </button>
            <button class="header-btn" onclick="alert('Help - Contact support@photobox.com')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" stroke="#008393" stroke-width="2"/>
                    <path d="M9 9C9 7.89543 9.89543 7 11 7H13C14.1046 7 15 7.89543 15 9C15 10.1046 14.1046 11 13 11H12V13" stroke="#008393" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="12" cy="17" r="1" fill="#008393"/>
                </svg>
                <span>Help</span>
            </button>
        </div>
    </div>

    <!-- Main Content (Background Image) -->
    <div class="main-content" id="mainContent">
        <div class="container">
            <!-- Camera Icon -->
            <div class="camera-icon">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M23 19C23 19.5304 22.7893 20.0391 22.4142 20.4142C22.0391 20.7893 21.5304 21 21 21H3C2.46957 21 1.96086 20.7893 1.58579 20.4142C1.21071 20.0391 1 19.5304 1 19V8C1 7.46957 1.21071 6.96086 1.58579 6.58579C1.96086 6.21071 2.46957 6 3 6H7L9 3H15L17 6H21C21.5304 6 22.0391 6.21071 22.4142 6.58579C22.7893 6.96086 23 7.46957 23 8V19Z" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    <circle cx="12" cy="13" r="4" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                </svg>
            </div>

            <!-- Title Text -->
            <div class="capture-text">Capture the</div>
            <div class="moment-text">Moment</div>

            <!-- Slider Dots -->
            <div class="slider-dots" id="sliderDots">
                <div class="dot active" data-index="0"></div>
                <div class="dot" data-index="1"></div>
                <div class="dot" data-index="2"></div>
                <div class="dot" data-index="3"></div>
            </div>

            <!-- Button -->
            <button class="tap-btn" id="tapToStart">
                TAP TO START
            </button>
        </div>
    </div>

    <!-- FOOTER PUTIH -->
    <div class="footer">
        <div class="status">
            <span class="status-dot"></span>
            <span class="status-text">Connected</span>
        </div>
    </div>

    <script>
        // Daftar background image (bisa diganti-ganti)
        const backgrounds = [
            'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=1920&h=800&fit=crop',
            'https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?w=1920&h=800&fit=crop',
            'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=1920&h=800&fit=crop',
            'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=1920&h=800&fit=crop'
        ];

        let currentIndex = 0;
        const mainContent = document.getElementById('mainContent');
        const dots = document.querySelectorAll('.dot');

        // Fungsi ganti background image
        function changeBackground(index) {
            currentIndex = index;
            mainContent.style.backgroundImage = `url('${backgrounds[currentIndex]}')`;
            
            // Update active dot
            dots.forEach((dot, i) => {
                if (i === currentIndex) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }

        // Set initial background
        mainContent.style.backgroundImage = `url('${backgrounds[0]}')`;
        mainContent.style.backgroundSize = 'cover';
        mainContent.style.backgroundPosition = 'center';
        mainContent.style.backgroundRepeat = 'no-repeat';

        // Auto slide setiap 5 detik
        let autoSlide = setInterval(() => {
            let nextIndex = (currentIndex + 1) % backgrounds.length;
            changeBackground(nextIndex);
        }, 5000);

        // Click dot untuk ganti background
        dots.forEach(dot => {
            dot.addEventListener('click', () => {
                clearInterval(autoSlide);
                const index = parseInt(dot.dataset.index);
                changeBackground(index);
                // Restart auto slide
                autoSlide = setInterval(() => {
                    let nextIndex = (currentIndex + 1) % backgrounds.length;
                    changeBackground(nextIndex);
                }, 5000);
            });
        });

        // Tap to Start button
        document.getElementById('tapToStart').addEventListener('click', function() {
            window.location.href = '/customer/camera';
        });
    </script>
</body>
</html>