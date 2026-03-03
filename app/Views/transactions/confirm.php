<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Transaksi</title>
    <style>
        body{font-family:sans-serif;background:#070d1f;color:#f0f6ff;padding:40px;}
        .box{background:#0d1529;border:1px solid #1e3a8a;border-radius:14px;padding:28px;max-width:500px;margin:0 auto;}
        .game-header{background:#111d36;border-radius:10px;padding:16px;margin-bottom:20px;display:flex;gap:14px;align-items:center;}
        .btn{background:#2563eb;color:white;padding:11px 22px;border:none;border-radius:6px;cursor:pointer;font-size:1rem;width:100%;}
        .btn:hover{background:#1d4ed8;}
        .btn-secondary{background:#4b5563;margin-top:10px;}
        .btn-secondary:hover{background:#374151;}
        .summary-card{background:#111d36;border-radius:10px;padding:20px;margin-bottom:20px;border:1px solid #1e3a8a;}
        .summary-item{display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid #1e3a8a;}
        .summary-item:last-child{border-bottom:none;}
        .summary-item.total{border-top:2px solid #1e3a8a;margin-top:8px;padding-top:15px;font-weight:bold;color:#60a5fa;font-size:1.1rem;}
        .payment-badge{background:#0d1529;padding:4px 10px;border-radius:20px;font-size:0.75rem;color:#60a5fa;border:1px solid #1e3a8a;}
        .error-box{background:rgba(239,68,68,0.1);border:1px solid #ef4444;padding:12px;border-radius:8px;color:#ef4444;font-size:0.85rem;margin-bottom:14px;}
        .info-box{background:rgba(37,99,235,0.1);border:1px solid #2563eb;padding:12px;border-radius:8px;color:#60a5fa;font-size:0.85rem;margin-bottom:14px;}
        .warning-box{background:rgba(251,191,36,0.1);border:1px solid #fbbf24;padding:12px;border-radius:8px;color:#fbbf24;font-size:0.85rem;margin-bottom:14px;}
        .back-link{display:block;text-align:center;margin-top:15px;color:#60a5fa;text-decoration:none;}
        .back-link:hover{text-decoration:underline;}
        .amount-large{font-size:1.8rem;font-weight:bold;color:#60a5fa;text-align:center;padding:15px;background:#111d36;border-radius:8px;margin-bottom:20px;}
        .method-icon{font-size:1.2rem;margin-right:8px;}
    </style>
</head>
<body>
    <div class="box">
        <div class="game-header">
            <div style="font-size:2.5rem;">🎮</div>
            <div>
                <div style="font-weight:700;font-size:1.1rem;"><?= esc($game['name'] ?? 'Unknown Game') ?></div>
                <div style="color:#60a5fa;font-size:0.82rem;">Konfirmasi Transaksi</div>
            </div>
        </div>

        <h2>📋 Konfirmasi Pembayaran</h2>
        
        <div class="amount-large">
            Rp <?= number_format($amount ?? 0, 0, ',', '.') ?>
        </div>

        <div class="summary-card">
            <div class="summary-item">
                <span style="color:#64748b;">Game</span>
                <span style="font-weight:bold;"><?= esc($game['name'] ?? '-') ?></span>
            </div>
            <div class="summary-item">
                <span style="color:#64748b;">Kategori</span>
                <span><?= esc($game['category'] ?? '-') ?></span>
            </div>
            <div class="summary-item">
                <span style="color:#64748b;">Metode Pembayaran</span>
                <span>
                    <span class="method-icon"><?= isset($paymentMethod['type']) && strtolower($paymentMethod['type']) === 'bank' ? '🏦' : '📱' ?></span>
                    <?= esc($paymentMethod['name'] ?? '-') ?> 
                    <span class="payment-badge"><?= esc($paymentMethod['type'] ?? '-') ?></span>
                </span>
            </div>
            <div class="summary-item">
                <span style="color:#64748b;">Jumlah</span>
                <span>Rp <?= number_format($amount ?? 0, 0, ',', '.') ?></span>
            </div>
            <div class="summary-item">
                <span style="color:#64748b;">Biaya Admin</span>
                <span>Rp 0</span>
            </div>
            <div class="summary-item total">
                <span>Total Dibayar</span>
                <span>Rp <?= number_format($amount ?? 0, 0, ',', '.') ?></span>
            </div>
        </div>

        <?php if (session()->has('error')): ?>
            <div class="error-box">
                ⚠️ <?= session('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('success')): ?>
            <div class="info-box">
                ✅ <?= session('success') ?>
            </div>
        <?php endif; ?>

        <div class="warning-box">
            ⏱️ Transaksi akan diproses setelah pembayaran dikonfirmasi sistem
        </div>

        <form action="/transactions/store" method="POST">
            <?= csrf_field() ?>
            <button type="submit" class="btn">✅ Konfirmasi & Bayar</button>
        </form>

        <a href="/payment-methods/topup/<?= $game['id'] ?? '' ?>" class="btn btn-secondary">← Ubah Metode Pembayaran</a>

        <a href="/games" class="back-link">← Kembali ke Daftar Game</a>
    </div>

    <script>
    // Prevent double submit
    document.querySelector('form')?.addEventListener('submit', function(e) {
        const btn = this.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '⏳ Memproses...';
        }
    });
    </script>
</body>
</html>