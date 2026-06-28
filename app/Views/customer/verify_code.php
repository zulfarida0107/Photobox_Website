<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CuitBox Studio - Verify Booking</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #9CF0FF 0%, #DBFAFF 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 1100px;
            width: 100%;
            background: white;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        /* ============================================================ */
        /* HEADER - RAPIH
        /* ============================================================ */
        .header {
            background: white;
            padding: 20px 32px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        /* ============================================================ */
        /* MAIN CONTENT - SAMA SEPERTI SEBELUMNYA
        /* ============================================================ */
        .main-content {
            display: flex;
            flex-wrap: wrap;
            padding: 48px;
            gap: 48px;
        }

        .left-side {
            flex: 1;
            min-width: 280px;
        }

        .right-side {
            flex: 1;
            min-width: 380px;
        }

        .title {
            font-size: 42px;
            font-weight: 700;
            color: #008393;
            margin-bottom: 16px;
        }

        .subtitle {
            font-size: 16px;
            color: #000000;
            margin-bottom: 40px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: white;
            border: 1px solid #008393;
            border-radius: 999px;
            padding: 6px 16px;
            font-size: 14px;
            font-weight: 600;
            color: #008393;
            margin-bottom: 24px;
        }

        .payment-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            border: 1px solid #e5e7eb;
            padding: 24px;
            margin-top: 20px;
        }

        .payment-label {
            font-size: 14px;
            font-weight: 600;
            color: #008393;
            margin-bottom: 12px;
        }

        .qris-button {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 16px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            cursor: pointer;
        }

        .qris-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .qris-icon {
            width: 40px;
            height: 40px;
            background: #008393;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .qris-text {
            font-weight: 600;
            color: #008393;
        }

        .arrow {
            color: #008393;
            font-size: 18px;
        }

        .verify-box {
            background: #f9fafb;
            border-radius: 24px;
            padding: 32px;
        }

        .code-display {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 32px;
        }

        .code-digit {
            width: 60px;
            height: 70px;
            background: white;
            border-radius: 16px;
            border: 2px solid #008393;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 700;
            color: #008393;
        }

        .code-digit.empty {
            color: #c0c0c0;
        }

        .keypad {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .keypad-btn {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 18px;
            font-size: 28px;
            font-weight: 600;
            color: #008393;
            cursor: pointer;
            text-align: center;
        }

        .keypad-btn:hover {
            background: #DBFAFF;
            border-color: #008393;
        }

        .keypad-btn.delete, .keypad-btn.clear {
            color: #000000;
            font-size: 24px;
        }

        .submit-btn {
            width: 100%;
            background: #008393;
            border: none;
            border-radius: 40px;
            padding: 16px;
            font-size: 18px;
            font-weight: 600;
            color: white;
            cursor: pointer;
            margin-top: 8px;
        }

        .submit-btn:hover {
            background: #006b78;
        }

        .flash-message {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .flash-error {
            background: #fee2e2;
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }

        .demo-code {
            text-align: center;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }

        .demo-code span {
            background: #DBFAFF;
            padding: 4px 12px;
            border-radius: 20px;
            color: #008393;
        }

        .footer {
            background: white;
            padding: 16px 32px;
            border-top: 1px solid #e5e7eb;
        }

        .status {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #008393;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: #008393;
            border-radius: 50%;
            display: inline-block;
        }

        @media (max-width: 900px) {
            .main-content {
                flex-direction: column;
            }
            .title {
                font-size: 32px;
            }
            .code-digit {
                width: 50px;
                height: 60px;
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <!-- HEADER YANG SUDAH DIRAPIHKAN -->
    <div class="header">
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
    </div>

    <!-- MAIN CONTENT (SAMA SEPERTI SEBELUMNYA) -->
    <div class="main-content">
        <div class="left-side">
            <div class="badge">
                <span>🔐</span>
                <span>Kiosk Verification Mode</span>
            </div>
            <div class="title">Masukkan Kode</div>
            <div class="subtitle">Masukkan Kode Verifikasi 6 Digit yang Dikirimkan ke Email Anda</div>

            <div class="payment-card">
                <div class="payment-label">Metode Pembayaran</div>
                <button class="qris-button" onclick="alert('QRIS Payment coming soon')">
                    <div class="qris-left">
                        <div class="qris-icon">📱</div>
                        <div class="qris-text">Bayar Menggunakan QRIS</div>
                    </div>
                    <div class="arrow">→</div>
                </button>
            </div>
        </div>

        <div class="right-side">
            <div class="verify-box">
                <?php if (session()->getFlashdata('error')): ?>
                <div class="flash-message flash-error"><?php echo session()->getFlashdata('error'); ?></div>
                <?php endif; ?>

                <div class="code-display" id="codeDisplay">
                    <div class="code-digit empty" id="digit0">•</div>
                    <div class="code-digit empty" id="digit1">•</div>
                    <div class="code-digit empty" id="digit2">•</div>
                    <div class="code-digit empty" id="digit3">•</div>
                    <div class="code-digit empty" id="digit4">•</div>
                    <div class="code-digit empty" id="digit5">•</div>
                </div>

                <form id="verifyForm" action="/customer/verify/check" method="POST">
                    <input type="hidden" name="verification_code" id="verification_code" value="">
                </form>

                <div class="keypad">
                    <button class="keypad-btn" data-num="1">1</button>
                    <button class="keypad-btn" data-num="2">2</button>
                    <button class="keypad-btn" data-num="3">3</button>
                    <button class="keypad-btn" data-num="4">4</button>
                    <button class="keypad-btn" data-num="5">5</button>
                    <button class="keypad-btn" data-num="6">6</button>
                    <button class="keypad-btn" data-num="7">7</button>
                    <button class="keypad-btn" data-num="8">8</button>
                    <button class="keypad-btn" data-num="9">9</button>
                    <button class="keypad-btn delete" data-action="delete">⌫</button>
                    <button class="keypad-btn" data-num="0">0</button>
                    <button class="keypad-btn clear" data-action="clear">✕</button>
                </div>

                <button class="submit-btn" id="verifyBtn">Masukkan kode</button>

                <?php if (isset($booking) && $booking): ?>
                <div class="demo-code">
                    Demo Code: <span><?php echo $booking['verification_code']; ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="status">
            <span class="status-dot"></span>
            <span>Connected</span>
        </div>
    </div>
</div>

<script>
    let code = '';

    function updateDisplay() {
        for (let i = 0; i < 6; i++) {
            const digit = document.getElementById('digit' + i);
            if (code[i]) {
                digit.textContent = code[i];
                digit.classList.remove('empty');
            } else {
                digit.textContent = '•';
                digit.classList.add('empty');
            }
        }
        document.getElementById('verification_code').value = code;
    }

    function addNumber(num) {
        if (code.length < 6) {
            code += num;
            updateDisplay();
        }
    }

    function deleteLast() {
        code = code.slice(0, -1);
        updateDisplay();
    }

    function clearAll() {
        code = '';
        updateDisplay();
    }

    function submitForm() {
        if (code.length === 6) {
            document.getElementById('verifyForm').submit();
        } else {
            alert('Please enter 6-digit verification code');
        }
    }

    document.querySelectorAll('.keypad-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const num = btn.dataset.num;
            const action = btn.dataset.action;
            
            if (num) {
                addNumber(num);
            } else if (action === 'delete') {
                deleteLast();
            } else if (action === 'clear') {
                clearAll();
            }
        });
    });

    document.getElementById('verifyBtn').addEventListener('click', submitForm);

    document.addEventListener('keydown', (e) => {
        if (e.key >= '0' && e.key <= '9') {
            addNumber(e.key);
        } else if (e.key === 'Backspace') {
            deleteLast();
        } else if (e.key === 'Delete') {
            clearAll();
        } else if (e.key === 'Enter') {
            submitForm();
        }
    });
</script>
</body>
</html>