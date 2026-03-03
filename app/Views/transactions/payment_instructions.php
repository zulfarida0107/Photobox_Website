<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Instruksi Pembayaran</title>
    <style>
        body{font-family:sans-serif;background:#070d1f;color:#f0f6ff;padding:40px;}
        .box{background:#0d1529;border:1px solid #1e3a8a;border-radius:14px;padding:28px;max-width:500px;margin:0 auto;}
        .game-header{background:#111d36;border-radius:10px;padding:16px;margin-bottom:20px;display:flex;gap:14px;align-items:center;}
        .btn{background:#2563eb;color:white;padding:11px 22px;border:none;border-radius:6px;cursor:pointer;font-size:1rem;width:100%;text-decoration:none;display:inline-block;box-sizing:border-box;}
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
        .success-box{background:rgba(16,185,129,0.1);border:1px solid #10b981;padding:15px;border-radius:8px;color:#10b981;margin-bottom:20px;}
        .error-box{background:rgba(239,68,68,0.1);border:1px solid #ef4444;padding:12px;border-radius:8px;color:#ef4444;margin-bottom:14px;}
        .back-link{color:#60a5fa;text-decoration:none;display:block;margin-top:15px;text-align:center;}
        .back-link:hover{text-decoration:underline;}
        .copy-btn{background:#1e3a8a;border:none;color:#60a5fa;padding:5px 10px;border-radius:4px;cursor:pointer;margin-left:10px;font-size:0.8rem;}
        .copy-btn:hover{background:#2563eb;color:white;}
        .upload-area{border:2px dashed #1e3a8a;border-radius:8px;padding:20px;text-align:center;margin:20px 0;cursor:pointer;}
        .upload-area:hover{background:#111d36;}
        .file-name{color:#60a5fa;margin-top:10px;text-align:center;}
    </style>
</head>
<body>
    <div class="box">
        <div class="game-header">
            <div style="font-size:2.5rem;">💰</div>
            <div>
                <div style="font-weight:700;font-size:1.1rem;">Instruksi Pembayaran</div>
                <div style="color:#60a5fa;font-size:0.82rem;"><?= esc($transaction['invoice_number'] ?? '-') ?></div>
            </div>
        </div>

        <div class="amount-large">
            Rp <?= number_format($transaction['amount'] ?? 0, 0, ',', '.') ?>
        </div>

        <div class="success-box">
            <strong>⚡ AUTO CONFIRM</strong>
            <p style="margin-top:5px;font-size:0.9rem;">
                Setelah upload bukti pembayaran, status akan langsung berubah menjadi <strong>SUCCESS</strong> secara otomatis.
            </p>
        </div>

        <div class="payment-card">
            <div style="display:flex;align-items:center;margin-bottom:15px;">
                <span class="method-icon">🏦</span>
                <span style="font-weight:bold;">Transfer Bank <?= esc($transaction['payment_name'] ?? 'Transfer') ?></span>
            </div>
            
            <div class="payment-item">
                <span style="color:#64748b;">Bank Tujuan</span>
                <span style="font-weight:bold;"><?= esc($transaction['payment_name'] ?? '-') ?></span>
            </div>
            <div class="payment-item">
                <span style="color:#64748b;">Nomor Rekening</span>
                <span>
                    <span id="accountNumber">1234567890</span>
                    <button class="copy-btn" onclick="copyText('1234567890')">📋 Copy</button>
                </span>
            </div>
            <div class="payment-item">
                <span style="color:#64748b;">Atas Nama</span>
                <span>PT Topup Indonesia</span>
            </div>
            <div class="payment-item total">
                <span>Total Transfer</span>
                <span>Rp <?= number_format($transaction['amount'] ?? 0, 0, ',', '.') ?></span>
            </div>
        </div>

        <div class="warning-box">
            <strong>⏱️ Batas Waktu Pembayaran:</strong> 24 jam
            <div style="font-size:0.9rem;margin-top:5px;">
                Pembayaran setelah batas waktu akan kadaluarsa
            </div>
        </div>

        <div class="info-box">
            <strong>📸 Upload Bukti Pembayaran</strong>
            <p style="margin-top:5px;font-size:0.9rem;">
                Setelah transfer, upload bukti pembayaran untuk konfirmasi otomatis
            </p>
        </div>

        <?php if (session()->has('error')): ?>
            <div class="error-box">
                ⚠️ <?= session('error') ?>
            </div>
        <?php endif; ?>

        <form action="/transactions/upload-proof/<?= $transaction['id'] ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="upload-area" onclick="document.getElementById('payment_proof').click()">
                <div style="font-size:3rem;margin-bottom:10px;">📎</div>
                <div style="color:#60a5fa;">Klik untuk pilih file</div>
                <small style="color:#64748b;">Format: JPG, PNG, PDF (Max 2MB)</small>
                <input type="file" id="payment_proof" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" style="display:none;" required>
            </div>
            <div id="fileName" class="file-name"></div>

            <button type="submit" class="btn btn-success" id="submitBtn">
                ✅ Upload & Konfirmasi
            </button>
        </form>

        <a href="/transactions/detail/<?= $transaction['id'] ?>" class="btn btn-secondary">
            ⏪ Kembali ke Detail
        </a>

        <a href="/games" class="back-link">← Kembali ke Daftar Game</a>
    </div>

    <script>
    function copyText(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Nomor rekening berhasil dicopy!');
        }).catch(function() {
            alert('Gagal menyalin, silakan copy manual');
        });
    }

    document.getElementById('payment_proof')?.addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        const fileNameDisplay = document.getElementById('fileName');
        fileNameDisplay.innerHTML = fileName ? '📎 File dipilih: ' + fileName : '';
    });

    document.querySelector('form')?.addEventListener('submit', function(e) {
        const btn = document.getElementById('submitBtn');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '⏳ Mengupload...';
        }
    });
    </script>
</body>
</html>