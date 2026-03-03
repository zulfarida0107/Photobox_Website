<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Transaksi</title>
    <style>
        body{font-family:sans-serif;background:#070d1f;color:#f0f6ff;padding:40px;}
        .box{background:#0d1529;border:1px solid #1e3a8a;border-radius:14px;padding:28px;max-width:500px;margin:0 auto;}
        .header{background:#111d36;border-radius:10px;padding:20px;margin-bottom:20px;text-align:center;border:1px solid #1e3a8a;}
        .status{padding:5px 15px;border-radius:20px;font-size:0.85rem;display:inline-block;font-weight:bold;}
        .status.pending{background:#fbbf24;color:#000;}
        .status.success{background:#10b981;color:#fff;}
        .status.failed{background:#ef4444;color:#fff;}
        .status.processing{background:#3b82f6;color:#fff;}
        .detail-card{background:#111d36;border-radius:10px;padding:20px;margin-bottom:20px;border:1px solid #1e3a8a;}
        .detail-item{display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid #1e3a8a;}
        .detail-item:last-child{border-bottom:none;}
        .invoice-number{font-size:1.2rem;font-weight:bold;color:#60a5fa;letter-spacing:1px;}
        .btn{background:#2563eb;color:white;padding:11px 22px;border:none;border-radius:6px;cursor:pointer;font-size:1rem;text-decoration:none;display:inline-block;box-sizing:border-box;}
        .btn:hover{background:#1d4ed8;}
        .btn-secondary{background:#4b5563;}
        .btn-secondary:hover{background:#374151;}
        .btn-success{background:#10b981;}
        .btn-success:hover{background:#059669;}
        .back-link{color:#60a5fa;text-decoration:none;display:block;margin-top:20px;text-align:center;}
        .back-link:hover{text-decoration:underline;}
        .payment-badge{background:#0d1529;padding:4px 10px;border-radius:20px;font-size:0.75rem;color:#60a5fa;border:1px solid #1e3a8a;margin-left:8px;}
        .amount-display{font-size:2rem;font-weight:bold;color:#60a5fa;text-align:center;margin:15px 0;}
        .method-icon{font-size:1.2rem;margin-right:5px;}
        .warning-box{background:rgba(251,191,36,0.1);border:1px solid #fbbf24;padding:15px;border-radius:8px;color:#fbbf24;font-size:0.9rem;margin-bottom:20px;}
        .info-box{background:rgba(37,99,235,0.1);border:1px solid #2563eb;padding:15px;border-radius:8px;color:#60a5fa;font-size:0.9rem;margin-bottom:20px;}
        .success-box{background:rgba(16,185,129,0.1);border:1px solid #10b981;padding:15px;border-radius:8px;color:#10b981;font-size:0.9rem;margin-bottom:20px;}
        .error-box{background:rgba(239,68,68,0.1);border:1px solid #ef4444;padding:12px;border-radius:8px;color:#ef4444;margin-bottom:14px;}
        .button-group{display:flex;gap:10px;margin-top:20px;}
        .button-group .btn{flex:1;}
        .proof-image{max-width:100%;max-height:200px;border-radius:8px;border:1px solid #1e3a8a;cursor:pointer;margin-top:10px;}
    </style>
</head>
<body>
    <div class="box">
        <!-- Header -->
        <div class="header">
            <div style="font-size:3rem;">🎮</div>
            <h2 style="margin:10px 0;">Detail Transaksi</h2>
            <div class="status <?= $transaction['status'] ?? 'pending' ?>">
                <?= ucfirst($transaction['status'] ?? 'pending') ?>
            </div>
        </div>

        <!-- Invoice Number -->
        <div class="invoice-number" style="text-align:center;margin-bottom:15px;">
            <?= esc($transaction['invoice_number'] ?? '-') ?>
        </div>

        <!-- Amount Display -->
        <div class="amount-display">
            Rp <?= number_format($transaction['amount'] ?? 0, 0, ',', '.') ?>
        </div>

        <!-- Success Message dari Session -->
        <?php if (session()->has('success')): ?>
            <div class="success-box" style="text-align:center;">
                ✅ <?= session('success') ?>
            </div>
        <?php endif; ?>

        <!-- Status Messages -->
        <?php if (($transaction['status'] ?? '') == 'pending'): ?>
            <div class="warning-box">
                ⏳ Menunggu pembayaran. Silakan selesaikan pembayaran sebelum batas waktu.
            </div>
        <?php elseif (($transaction['status'] ?? '') == 'processing'): ?>
            <div class="info-box">
                ⏱️ Pembayaran sedang diproses. Admin akan mengkonfirmasi dalam 1x24 jam.
            </div>
        <?php elseif (($transaction['status'] ?? '') == 'success'): ?>
            <!-- Success Celebration Box -->
            <div class="success-box" style="text-align:center;">
                <div style="font-size:3rem;margin-bottom:10px;">🎉</div>
                <h3 style="margin:0 0 10px 0;color:#10b981;">Pembayaran Berhasil!</h3>
                <p>Top up telah ditambahkan ke akun game Anda.</p>
                <p style="font-size:0.9rem;margin-top:10px;">Silakan cek akun game Anda.</p>
            </div>
        <?php endif; ?>

        <!-- Detail Card -->
        <div class="detail-card">
            <div class="detail-item">
                <span style="color:#64748b;">Game</span>
                <span style="font-weight:bold;"><?= esc($transaction['game_name'] ?? '-') ?></span>
            </div>
            <div class="detail-item">
                <span style="color:#64748b;">Kategori</span>
                <span><?= esc($transaction['category'] ?? '-') ?></span>
            </div>
            <div class="detail-item">
                <span style="color:#64748b;">Metode Pembayaran</span>
                <span>
                    <span class="method-icon"><?= (isset($transaction['payment_type']) && strtolower($transaction['payment_type']) === 'bank') ? '🏦' : '📱' ?></span>
                    <?= esc($transaction['payment_name'] ?? '-') ?>
                    <span class="payment-badge"><?= esc($transaction['payment_type'] ?? '-') ?></span>
                </span>
            </div>
            <div class="detail-item">
                <span style="color:#64748b;">Jumlah</span>
                <span>Rp <?= number_format($transaction['amount'] ?? 0, 0, ',', '.') ?></span>
            </div>
            <div class="detail-item">
                <span style="color:#64748b;">Tanggal</span>
                <span><?= isset($transaction['created_at']) ? date('d/m/Y H:i', strtotime($transaction['created_at'])) : '-' ?></span>
            </div>
            
            <!-- Bukti Pembayaran (jika ada) -->
            <?php if (!empty($transaction['payment_proof'])): ?>
                <div class="detail-item">
                    <span style="color:#64748b;">Bukti Pembayaran</span>
                    <span>
                        <a href="/uploads/bukti_pembayaran/<?= $transaction['payment_proof'] ?>" target="_blank" style="color:#60a5fa;text-decoration:none;">
                            📸 Lihat Bukti
                        </a>
                    </span>
                </div>
                <div style="margin-top:10px;text-align:center;">
                    <a href="/uploads/bukti_pembayaran/<?= $transaction['payment_proof'] ?>" target="_blank">
                        <img src="/uploads/bukti_pembayaran/<?= $transaction['payment_proof'] ?>" 
                             alt="Bukti Pembayaran" 
                             class="proof-image">
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Action Buttons -->
        <?php if (($transaction['status'] ?? '') == 'pending'): ?>
            <div class="button-group">
                <a href="/transactions/payment-instructions/<?= $transaction['id'] ?>" class="btn btn-success">
                    💰 Bayar Sekarang
                </a>
                <a href="/transactions" class="btn btn-secondary">
                    📋 Semua Transaksi
                </a>
            </div>
        <?php else: ?>
            <div style="margin-top:20px;text-align:center;">
                <a href="/transactions" class="btn btn-secondary" style="width:100%;">
                    📋 Semua Transaksi
                </a>
            </div>
        <?php endif; ?>

        <!-- Back Link -->
        <a href="/games" class="back-link">← Kembali ke Daftar Game</a>
    </div>

    <script>
    // Optional: Add click handler for proof image
    document.querySelectorAll('.proof-image').forEach(img => {
        img.addEventListener('click', function() {
            window.open(this.src, '_blank');
        });
    });
    </script>
</body>
</html>