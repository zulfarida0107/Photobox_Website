<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Instruksi Pembayaran</title>
    <style>
        body{font-family:sans-serif;background:#070d1f;color:#f0f6ff;padding:40px;}
        .box{background:#0d1529;border:1px solid #1e3a8a;border-radius:14px;padding:28px;max-width:500px;margin:0 auto;}
        .game-header{background:#111d36;border-radius:10px;padding:16px;margin-bottom:20px;display:flex;gap:14px;align-items:center;}
        .btn{background:#2563eb;color:white;padding:11px 22px;border:none;border-radius:6px;cursor:pointer;font-size:1rem;width:100%;}
        .btn:hover{background:#1d4ed8;}
        .btn-success{background:#10b981;}
        .btn-success:hover{background:#059669;}
        .btn-secondary{background:#4b5563;margin-top:10px;}
        .btn-secondary:hover{background:#374151;}
        .payment-card{background:#111d36;border-radius:10px;padding:20px;margin-bottom:20px;border:1px solid #1e3a8a;}
        .payment-item{display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid #1e3a8a;}
        .payment-item:last-child{border-bottom:none;}
        .payment-item.total{border-top:2px solid #1e3a8a;margin-top:8px;padding-top:15px;font-weight:bold;color:#60a5fa;font-size:1.1rem;}
        .amount-large{font-size:2rem;font-weight:bold;color:#60a5fa;text-align:center;padding:15px;background:#111d36;border-radius:8px;margin-bottom:20px;}
        .method-icon{font-size:1.5rem;margin-right:10px;}
        .info-box{background:rgba(37,99,235,0.1);border:1px solid #2563eb;padding:15px;border-radius:8px;color:#60a5fa;margin-bottom:20px;}
        .warning-box{background:rgba(251,191,36,0.1);border:1px solid #fbbf24;padding:15px;border-radius:8px;color:#fbbf24;margin-bottom:20px;}
        .error-box{background:rgba(239,68,68,0.1);border:1px solid #ef4444;padding:12px;border-radius:8px;color:#ef4444;font-size:0.85rem;margin-bottom:14px;}
        .copy-btn{background:#1e3a8a;border:none;color:#60a5fa;padding:5px 10px;border-radius:4px;cursor:pointer;margin-left:10px;}
        .copy-btn:hover{background:#2563eb;color:white;}
        .upload-area{border:2px dashed #1e3a8a;border-radius:8px;padding:20px;text-align:center;margin-bottom:20px;cursor:pointer;}
        .upload-area:hover{background:#111d36;}
        .back-link{color:#60a5fa;text-decoration:none;display:block;margin-top:15px;text-align:center;}
        .back-link:hover{text-decoration:underline;}
        .badge{background:#1e3a8a;padding:4px 8px;border-radius:12px;font-size:0.75rem;}
    </style>
</head>
<body>
    <div class="box">
        <div class="game-header">
            <div style="font-size:2.5rem;">💰</div>
            <div>
                <div style="font-weight:700;font-size:1.1rem;">Instruksi Pembayaran</div>
                <div style="color:#60a5fa;font-size:0.82rem;"><?= esc($transaction['invoice_number']) ?></div>
            </div>
        </div>

        <div class="amount-large">
            Rp <?= number_format($transaction['amount'], 0, ',', '.') ?>
        </div>

        <?php if ($transaction['payment_type'] == 'bank'): ?>
            <!-- INSTRUKSI TRANSFER BANK -->
            <div class="payment-card">
                <div style="display:flex;align-items:center;margin-bottom:15px;">
                    <span class="method-icon">🏦</span>
                    <span style="font-weight:bold;">Transfer Bank <?= esc($instructions['bank_name']) ?></span>
                </div>
                
                <div class="payment-item">
                    <span style="color:#64748b;">Bank Tujuan</span>
                    <span style="font-weight:bold;"><?= esc($instructions['bank_name']) ?></span>
                </div>
                <div class="payment-item">
                    <span style="color:#64748b;">Nomor Rekening</span>
                    <span>
                        <?= esc($instructions['account_number']) ?>
                        <button class="copy-btn" onclick="copyText('<?= $instructions['account_number'] ?>')">📋 Copy</button>
                    </span>
                </div>
                <div class="payment-item">
                    <span style="color:#64748b;">Atas Nama</span>
                    <span><?= esc($instructions['account_name']) ?></span>
                </div>
                <div class="payment-item total">
                    <span>Total Transfer</span>
                    <span>Rp <?= number_format($instructions['amount'], 0, ',', '.') ?></span>
                </div>
            </div>
        <?php else: ?>
            <!-- INSTRUKSI E-WALLET -->
            <div class="payment-card">
                <div style="display:flex;align-items:center;margin-bottom:15px;">
                    <span class="method-icon">📱</span>
                    <span style="font-weight:bold;">Pembayaran <?= esc($instructions['ewallet_name']) ?></span>
                </div>
                
                <div class="payment-item">
                    <span style="color:#64748b;">E-Wallet</span>
                    <span style="font-weight:bold;"><?= esc($instructions['ewallet_name']) ?></span>
                </div>
                <div class="payment-item">
                    <span style="color:#64748b;">Nomor Tujuan</span>
                    <span>
                        <?= esc($instructions['phone_number']) ?>
                        <button class="copy-btn" onclick="copyText('<?= $instructions['phone_number'] ?>')">📋 Copy</button>
                    </span>
                </div>
                <div class="payment-item total">
                    <span>Total Pembayaran</span>
                    <span>Rp <?= number_format($instructions['amount'], 0, ',', '.') ?></span>
                </div>
            </div>
        <?php endif; ?>

        <div class="info-box">
            <strong>⏱️ Batas Waktu Pembayaran:</strong> 24 jam dari sekarang<br>
            <small>Pembayaran setelah batas waktu akan kadaluarsa</small>
        </div>

        <div class="warning-box">
            <strong>📸 Upload Bukti Pembayaran</strong>
            <p style="margin-top:10px;font-size:0.9rem;">Setelah melakukan pembayaran, upload bukti transfer untuk mempercepat proses konfirmasi</p>
        </div>

        <?php if (session()->has('errors')): ?>
            <div class="error-box">
                <?php foreach (session('errors') as $error): ?>
                    <div>⚠️ <?= esc($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- FORM UPLOAD BUKTI PEMBAYARAN -->
        <form action="/transactions/confirm-payment/<?= $transaction['id'] ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="upload-area" onclick="document.getElementById('payment_proof').click()">
                <div style="font-size:3rem;margin-bottom:10px;">📎</div>
                <div style="color:#60a5fa;">Klik untuk upload bukti pembayaran</div>
                <small style="color:#64748b;">Format: JPG, PNG, PDF (Max 2MB)</small>
                <input type="file" id="payment_proof" name="payment_proof" accept="image/*,.pdf" style="display:none;" required>
            </div>

            <button type="submit" class="btn btn-success">✅ Upload & Konfirmasi</button>
        </form>

        <a href="/transactions/detail/<?= $transaction['id'] ?>" class="btn btn-secondary">Lihat Detail Transaksi</a>

        <a href="/games" class="back-link">← Kembali ke Daftar Game</a>
    </div>

    <script>
    // Copy text function
    function copyText(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Teks berhasil dicopy!');
        }, function() {
            alert('Gagal menyalin teks');
        });
    }

    // Show selected file name
    document.getElementById('payment_proof')?.addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        if (fileName) {
            const uploadArea = document.querySelector('.upload-area div');
            uploadArea.innerHTML = `📎 ${fileName}`;
        }
    });

    // Prevent double submit
    document.querySelector('form')?.addEventListener('submit', function(e) {
        const btn = this.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '⏳ Mengupload...';
        }
    });
    </script>
</body>
</html>