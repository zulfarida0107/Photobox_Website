<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CuitBox Studio - Booking Kiosk</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', Helvetica, sans-serif;
            background: linear-gradient(0deg, rgba(156, 240, 255, 1) 0%, rgba(156, 240, 255, 1) 100%);
            min-height: 100vh;
            position: relative;
        }

        .kiosk-booking {
            width: 100%;
            min-width: 1280px;
            min-height: 1040px;
            position: relative;
        }

        .main {
            position: absolute;
            width: 100%;
            top: 0;
            left: 0;
            height: 1041px;
            display: flex;
            flex-direction: column;
            background: radial-gradient(50% 50% at 30% 66%, rgba(156, 240, 255, 0.5) 0%, rgba(255, 255, 255, 1) 100%);
        }

        /* Header */
        .header-topappbar {
            display: flex;
            width: 100%;
            height: 80px;
            align-items: center;
            justify-content: center;
            background-color: #ffffff;
            border-bottom: 1px solid rgba(139, 92, 246, 0.05);
            box-shadow: 0px 1px 2px rgba(139, 92, 246, 0.05);
        }

        .container-header {
            display: flex;
            max-width: 1280px;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            width: 100%;
        }

        .logo {
            font-family: 'Inter', sans-serif;
            font-weight: 900;
            color: #008393;
            font-size: 24px;
            letter-spacing: -0.6px;
        }

        .help-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px;
            background: none;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            color: #008393;
            font-size: 14px;
        }

        /* Footer */
        .footer-status-bar {
            display: flex;
            width: 100%;
            align-items: center;
            justify-content: space-between;
            padding: 12px 24px;
            position: absolute;
            left: 0;
            bottom: 0;
            background-color: #ffffff;
        }

        .status-connected {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            color: #008393;
            font-size: 12px;
        }

        .version {
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            color: #008393;
            font-size: 12px;
        }

        /* Main Content - Scrollable */
        .container-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 40px 24px;
            margin-bottom: 60px;
        }

        /* Cards Grid */
        .packages-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-top: 24px;
            margin-bottom: 40px;
        }

        .package-card {
            background: white;
            border-radius: 24px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            border: 2px solid #e5eeff;
            transition: all 0.2s;
        }

        .package-card:hover {
            transform: translateY(-4px);
            border-color: #008393;
            box-shadow: 0 10px 25px -5px rgba(0, 131, 147, 0.2);
        }

        .package-card.selected {
            border-color: #008393;
            background: #f0fdfe;
        }

        .package-icon {
            font-size: 48px;
            margin-bottom: 16px;
        }

        .package-name {
            font-size: 20px;
            font-weight: 700;
            color: #008393;
            margin-bottom: 8px;
        }

        .package-detail {
            font-size: 14px;
            color: #666;
            margin-bottom: 12px;
        }

        .package-price {
            font-size: 24px;
            font-weight: 700;
            color: #008393;
        }

        /* Booth & Slot Grid */
        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: #008393;
            margin: 24px 0 16px;
        }

        .booths-grid, .slots-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .booth-card, .slot-card {
            background: white;
            border-radius: 16px;
            padding: 16px;
            text-align: center;
            cursor: pointer;
            border: 2px solid #e5eeff;
            transition: all 0.2s;
        }

        .booth-card:hover, .slot-card:hover {
            border-color: #008393;
        }

        .booth-card.selected, .slot-card.selected {
            border-color: #008393;
            background: #f0fdfe;
        }

        .slot-card.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: #f5f5f5;
        }

        .slot-time {
            font-size: 16px;
            font-weight: 600;
            color: #008393;
        }

        .slot-available {
            font-size: 12px;
            color: #666;
            margin-top: 4px;
        }

        /* Form Inputs */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #008393;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5eeff;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #008393;
        }

        textarea.form-input {
            resize: vertical;
        }

        /* Confirmation Table */
        .confirm-table {
            width: 100%;
            background: #f8fafc;
            border-radius: 16px;
            padding: 20px;
            margin: 20px 0;
        }

        .confirm-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .confirm-label {
            color: #666;
            font-weight: 500;
        }

        .confirm-value {
            color: #008393;
            font-weight: 600;
        }

        /* Buttons */
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .btn-back, .btn-next, .btn-submit {
            padding: 12px 32px;
            border-radius: 40px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }

        .btn-back {
            background: #e2e8f0;
            color: #475569;
        }

        .btn-next, .btn-submit {
            background: #008393;
            color: white;
        }

        .btn-next:hover, .btn-submit:hover {
            background: #006b78;
            transform: scale(1.02);
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        /* Flash Messages */
        .flash-message {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .flash-error {
            background-color: #fee2e2;
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }

        .flash-success {
            background-color: #dcfce7;
            color: #16a34a;
            border-left: 4px solid #16a34a;
        }

        /* Step Indicators */
        .step-indicators {
            display: flex;
            justify-content: space-between;
            max-width: 500px;
            margin: 0 auto 40px;
        }

        .step-indicator {
            text-align: center;
            flex: 1;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            background: #e2e8f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin: 0 auto 8px;
            color: #475569;
        }

        .step-indicator.active .step-circle {
            background: #008393;
            color: white;
        }

        .step-indicator.completed .step-circle {
            background: #10b981;
            color: white;
        }

        .step-label {
            font-size: 12px;
            color: #666;
        }

        .step-indicator.active .step-label {
            color: #008393;
            font-weight: 600;
        }
    </style>
</head>
<body>
<div class="kiosk-booking">
    <div class="main">
        <!-- Header -->
        <div class="header-topappbar">
            <div class="container-header">
                <div class="logo">📸 CuitBox Studio</div>
                <button class="help-btn" onclick="alert('Need help? Contact support@cuitboxstudio.com')">
                    <span>❓</span>
                    <span>Help</span>
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container-scroll">
            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('error')): ?>
            <div class="flash-message flash-error">
                <?php echo session()->getFlashdata('error'); ?>
            </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
            <div class="flash-message flash-success">
                <?php echo session()->getFlashdata('success'); ?>
            </div>
            <?php endif; ?>

            <!-- Step Indicators -->
            <div class="step-indicators">
                <div class="step-indicator" data-step="1">
                    <div class="step-circle">1</div>
                    <div class="step-label">Package</div>
                </div>
                <div class="step-indicator" data-step="2">
                    <div class="step-circle">2</div>
                    <div class="step-label">Booth & Time</div>
                </div>
                <div class="step-indicator" data-step="3">
                    <div class="step-circle">3</div>
                    <div class="step-label">Details</div>
                </div>
                <div class="step-indicator" data-step="4">
                    <div class="step-circle">4</div>
                    <div class="step-label">Confirm</div>
                </div>
            </div>

            <form id="bookingForm" action="/customer/bookings" method="POST">
                <!-- STEP 1: Package -->
                <div id="step1" class="step active">
                    <h2 class="section-title">Choose Your Package</h2>
                    <div class="packages-grid">
                        <?php if (!empty($packages) && is_array($packages)): ?>
                            <?php foreach ($packages as $pkg): ?>
                            <div class="package-card" data-id="<?php echo $pkg['id']; ?>" 
                                 data-price="<?php echo $pkg['base_price']; ?>" 
                                 data-name="<?php echo htmlspecialchars($pkg['name']); ?>">
                                <div class="package-icon">📷</div>
                                <div class="package-name"><?php echo htmlspecialchars($pkg['name']); ?></div>
                                <div class="package-detail"><?php echo $pkg['photo_count']; ?> photos • <?php echo $pkg['duration']; ?> min</div>
                                <div class="package-price">Rp <?php echo number_format($pkg['base_price'], 0, ',', '.'); ?></div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No packages available</p>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="package_id" id="package_id">
                    <div class="button-group">
                        <div></div>
                        <button type="button" class="btn-next next-step">Next →</button>
                    </div>
                </div>

                <!-- STEP 2: Booth & Slot -->
                <div id="step2" class="step">
                    <h2 class="section-title">Choose Booth</h2>
                    <div class="booths-grid">
                        <?php if (!empty($booths) && is_array($booths)): ?>
                            <?php foreach ($booths as $booth): ?>
                            <div class="booth-card" data-id="<?php echo $booth['id']; ?>" 
                                 data-name="<?php echo htmlspecialchars($booth['name']); ?>">
                                <div class="package-icon">🎪</div>
                                <div class="package-name"><?php echo htmlspecialchars($booth['name']); ?></div>
                                <div class="package-detail">Queue: <?php echo $booth['current_queue'] ?? 0; ?></div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No booths available</p>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="booth_id" id="booth_id">

                    <h2 class="section-title">Choose Time Slot</h2>
                    <div class="slots-grid" id="slotsContainer">
                        <?php if (!empty($slots) && is_array($slots)): ?>
                            <?php foreach ($slots as $slot): ?>
                            <div class="slot-card <?php echo !$slot['available'] ? 'disabled' : ''; ?>" 
                                 data-id="<?php echo $slot['id']; ?>"
                                 data-start="<?php echo $slot['start_time_formatted'] ?? $slot['start_time']; ?>"
                                 data-available="<?php echo $slot['available'] ? '1' : '0'; ?>">
                                <div class="slot-time"><?php echo $slot['start_time_formatted'] ?? date('H:i', strtotime($slot['start_time'])); ?></div>
                                <div class="slot-available">Available: <?php echo $slot['available_count'] ?? 0; ?></div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No slots available</p>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="slot_id" id="slot_id">

                    <div class="button-group">
                        <button type="button" class="btn-back prev-step">← Back</button>
                        <button type="button" class="btn-next next-step">Next →</button>
                    </div>
                </div>

                <!-- STEP 3: Customer Details -->
                <div id="step3" class="step">
                    <h2 class="section-title">Your Details</h2>
                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" required class="form-input" placeholder="your@email.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Full Name (Optional)</label>
                        <input type="text" name="name" class="form-input" placeholder="Your name">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number (Optional)</label>
                        <input type="tel" name="phone" class="form-input" placeholder="0812-3456-7890">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea name="notes" class="form-input" rows="2" placeholder="Special requests..."></textarea>
                    </div>
                    <div class="button-group">
                        <button type="button" class="btn-back prev-step">← Back</button>
                        <button type="button" class="btn-next next-step">Next →</button>
                    </div>
                </div>

                <!-- STEP 4: Confirmation -->
                <div id="step4" class="step">
                    <h2 class="section-title">Confirm Booking</h2>
                    <div class="confirm-table">
                        <div class="confirm-row">
                            <span class="confirm-label">Package:</span>
                            <span class="confirm-value" id="confirmPackage">-</span>
                        </div>
                        <div class="confirm-row">
                            <span class="confirm-label">Booth:</span>
                            <span class="confirm-value" id="confirmBooth">-</span>
                        </div>
                        <div class="confirm-row">
                            <span class="confirm-label">Time Slot:</span>
                            <span class="confirm-value" id="confirmSlot">-</span>
                        </div>
                        <div class="confirm-row">
                            <span class="confirm-label">Email:</span>
                            <span class="confirm-value" id="confirmEmail">-</span>
                        </div>
                        <div class="confirm-row">
                            <span class="confirm-label">Total Payment:</span>
                            <span class="confirm-value" id="confirmPrice">-</span>
                        </div>
                    </div>
                    <div class="button-group">
                        <button type="button" class="btn-back prev-step">← Back</button>
                        <button type="submit" class="btn-submit">Confirm Booking ✓</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="footer-status-bar">
            <div class="status-connected">
                <span>🟢</span>
                <span>Connected</span>
            </div>
            <div class="version">Vivid Focus v2.4.0</div>
        </div>
    </div>
</div>

<script>
    let selectedPackage = null, selectedBooth = null, selectedSlot = null;
    let currentStep = 1;

    // Step navigation
    document.querySelectorAll('.next-step').forEach(btn => {
        btn.addEventListener('click', () => changeStep(1));
    });
    document.querySelectorAll('.prev-step').forEach(btn => {
        btn.addEventListener('click', () => changeStep(-1));
    });

    function changeStep(delta) {
        document.getElementById('step' + currentStep).classList.remove('active');
        document.querySelector('.step-indicator[data-step="' + currentStep + '"]').classList.remove('active');
        
        currentStep += delta;
        
        document.getElementById('step' + currentStep).classList.add('active');
        document.querySelector('.step-indicator[data-step="' + currentStep + '"]').classList.add('active');
    }

    // Package selection
    document.querySelectorAll('.package-card').forEach(card => {
        card.addEventListener('click', () => {
            document.querySelectorAll('.package-card').forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
            selectedPackage = {
                id: card.dataset.id,
                name: card.dataset.name,
                price: card.dataset.price
            };
            document.getElementById('package_id').value = selectedPackage.id;
            document.getElementById('confirmPackage').textContent = selectedPackage.name;
            document.getElementById('confirmPrice').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(selectedPackage.price);
        });
    });

    // Booth selection
    document.querySelectorAll('.booth-card').forEach(card => {
        card.addEventListener('click', () => {
            document.querySelectorAll('.booth-card').forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
            selectedBooth = {
                id: card.dataset.id,
                name: card.dataset.name
            };
            document.getElementById('booth_id').value = selectedBooth.id;
            document.getElementById('confirmBooth').textContent = selectedBooth.name;
        });
    });

    // Slot selection
    document.querySelectorAll('.slot-card:not(.disabled)').forEach(card => {
        card.addEventListener('click', () => {
            document.querySelectorAll('.slot-card').forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
            selectedSlot = {
                id: card.dataset.id,
                start: card.dataset.start
            };
            document.getElementById('slot_id').value = selectedSlot.id;
            document.getElementById('confirmSlot').textContent = selectedSlot.start;
        });
    });

    // Update confirm email
    document.querySelector('input[name="email"]').addEventListener('input', (e) => {
        document.getElementById('confirmEmail').textContent = e.target.value || '-';
    });

    // Auto-select booth if booth_id query parameter is present in URL
    window.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const preselectedBoothId = urlParams.get('booth_id');
        if (preselectedBoothId) {
            const matchingCard = document.querySelector(`.booth-card[data-id="${preselectedBoothId}"]`);
            if (matchingCard) {
                // If we are at step 1, clicking won't automatically advance steps but will preselect the card
                matchingCard.click();
            }
        }
    });
</script>
</body>
</html>