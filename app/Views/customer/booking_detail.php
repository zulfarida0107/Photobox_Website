<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Detail</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #9CF0FF 0%, #DBFAFF 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            overflow: hidden;
        }
        .header { background: #008393; padding: 24px; color: white; }
        .header h1 { font-size: 24px; }
        .content { padding: 32px; }
        .detail-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e5e7eb; }
        .label { color: #666; font-weight: 500; }
        .value { color: #008393; font-weight: 600; }
        .status { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .status-paid { background: #dcfce7; color: #16a34a; }
        .status-pending { background: #fef3c7; color: #d97706; }
        .btn { display: block; text-align: center; background: #008393; color: white; padding: 12px; border-radius: 40px; text-decoration: none; margin-top: 24px; }
        .btn:hover { background: #006b78; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>📸 Booking Details</h1>
    </div>
    <div class="content">
        <?php if (isset($booking) && $booking): ?>
        <div class="detail-row">
            <span class="label">Booking Code</span>
            <span class="value"><?php echo $booking['booking_code']; ?></span>
        </div>
        <div class="detail-row">
            <span class="label">Package</span>
            <span class="value"><?php echo $booking['package_name'] ?? '-'; ?></span>
        </div>
        <div class="detail-row">
            <span class="label">Price</span>
            <span class="value">Rp <?php echo number_format($booking['price'] ?? 0, 0, ',', '.'); ?></span>
        </div>
        <div class="detail-row">
            <span class="label">Booth</span>
            <span class="value"><?php echo $booking['booth_name'] ?? '-'; ?></span>
        </div>
        <div class="detail-row">
            <span class="label">Date & Time</span>
            <span class="value"><?php echo $booking['slot_date'] ?? date('Y-m-d'); ?> <?php echo date('H:i', strtotime($booking['start_time'] ?? '00:00')); ?></span>
        </div>
        <div class="detail-row">
            <span class="label">Queue Number</span>
            <span class="value">#<?php echo $booking['queue_number'] ?? '1'; ?></span>
        </div>
        <div class="detail-row">
            <span class="label">Customer</span>
            <span class="value"><?php echo $booking['name'] ?? '-'; ?><br><small><?php echo $booking['email'] ?? '-'; ?></small></span>
        </div>
        <div class="detail-row">
            <span class="label">Status</span>
            <span class="value">
                <span class="status <?php echo ($booking['status'] ?? 'pending') == 'paid' ? 'status-paid' : 'status-pending'; ?>">
                    <?php echo ucfirst($booking['status'] ?? 'pending'); ?>
                </span>
            </span>
        </div>
        <a href="/customer/verify" class="btn">Back to Verification</a>
        <?php else: ?>
        <p>Booking not found</p>
        <a href="/customer/verify" class="btn">Back</a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>