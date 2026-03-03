<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Top Up <?= esc($game['name'] ?? '') ?></title>
<style>
body{font-family:sans-serif;background:#070d1f;color:#f0f6ff;padding:40px;}
.box{background:#0d1529;border:1px solid #1e3a8a;border-radius:14px;padding:28px;max-width:500px;margin:0 auto;}
input,select{width:100%;padding:10px;background:#111d36;border:1px solid #1e3a8a;border-radius:6px;color:#f0f6ff;margin-bottom:14px;box-sizing:border-box;}
label{display:block;color:#64748b;font-size:0.85rem;margin-bottom:4px;}
.btn{background:#2563eb;color:white;padding:11px 22px;border:none;border-radius:6px;cursor:pointer;font-size:1rem;}
.btn:hover{background:#1d4ed8;}
h2{color:#60a5fa;}
.game-header{background:#111d36;border-radius:10px;padding:16px;margin-bottom:20px;display:flex;gap:14px;align-items:center;}
select{max-height:250px;overflow-y:auto;cursor:pointer;appearance:none;-webkit-appearance:none;-moz-appearance:none;background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2360a5fa' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'/></svg>");background-repeat:no-repeat;background-position:right 10px center;background-size:16px;padding-right:30px;}
select option{background:#111d36;color:#f0f6ff;padding:8px;}
select option:hover{background:#1e3a8a;}
optgroup{font-weight:bold;color:#60a5fa;background:#0d1529;font-size:0.9rem;}
optgroup option{padding-left:20px;}
/* Custom scrollbar */
select::-webkit-scrollbar{width:8px;}
select::-webkit-scrollbar-track{background:#111d36;border-radius:4px;}
select::-webkit-scrollbar-thumb{background:#1e3a8a;border-radius:4px;}
select::-webkit-scrollbar-thumb:hover{background:#2563eb;}
.method-card{background:#111d36;border:1px solid #1e3a8a;border-radius:10px;padding:15px;margin-bottom:20px;}
.method-icon{font-size:1.5rem;margin-right:10px;}
.error-box{background:rgba(239,68,68,0.1);border:1px solid #ef4444;padding:12px;border-radius:8px;color:#ef4444;font-size:0.85rem;margin-bottom:14px;}
.info-box{background:rgba(37,99,235,0.1);border:1px solid #2563eb;padding:12px;border-radius:8px;color:#60a5fa;font-size:0.85rem;margin-bottom:14px;}
.warning-box{background:rgba(251,191,36,0.1);border:1px solid #fbbf24;padding:12px;border-radius:8px;color:#fbbf24;font-size:0.85rem;margin-bottom:14px;}
.debug-box{background:#1e1e1e;border:1px solid #ff6b6b;padding:10px;border-radius:5px;margin-bottom:15px;font-family:monospace;font-size:12px;color:#ff6b6b;}
</style>
</head>
<body>
<div class="box">
    <div class="game-header">
        <div style="font-size:2.5rem;">🎮</div>
        <div>
            <div style="font-weight:700;font-size:1.1rem;"><?= esc($game['name'] ?? 'Unknown Game') ?></div>
            <div style="color:#60a5fa;font-size:0.82rem;"><?= esc($game['category'] ?? 'General') ?></div>
        </div>
    </div>

    <h2>⚡ Top Up <?= esc($game['name'] ?? '') ?></h2>
    
    <!-- DEBUG: Tampilkan jumlah data (hapus setelah selesai) -->
    <?php if (empty($payments)): ?>
    <div class="debug-box">
        ⚠️ Tidak ada metode pembayaran ditemukan di database. Jumlah: <?= count($payments ?? []) ?>
    </div>
    <?php endif; ?>
    
    <!-- FORM INI SUBMIT KE PAYMENT-METHODS/PROCESS -->
    <form action="/payment-methods/process" method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="game_id" value="<?= $game['id'] ?? '' ?>">

        <div class="method-card">
            <div style="display:flex;align-items:center;margin-bottom:15px;">
                <span class="method-icon">💰</span>
                <span style="font-weight:bold;">Masukkan Jumlah</span>
            </div>
            <label>Jumlah (Rp)</label>
            <input type="number" name="amount" required placeholder="Contoh: 50000" min="1000" step="1000" value="<?= old('amount') ?>">
            <small style="color:#64748b;">Minimal Rp 1.000</small>
        </div>

        <div class="method-card">
            <div style="display:flex;align-items:center;margin-bottom:15px;">
                <span class="method-icon">💳</span>
                <span style="font-weight:bold;">Pilih Metode Pembayaran</span>
            </div>
            
            <label>Metode Pembayaran</label>
            <select name="payment_method_id" required>
                <option value="">-- Pilih Metode Pembayaran --</option>
                
                <?php if (!empty($payments)): ?>
                    <?php 
                    // Kelompokkan berdasarkan type
                    $banks = [];
                    $ewallets = [];
                    
                    foreach ($payments as $p) {
                        if (isset($p['type']) && strtolower($p['type']) === 'bank') {
                            $banks[] = $p;
                        } elseif (isset($p['type']) && strtolower($p['type']) === 'e-wallet') {
                            $ewallets[] = $p;
                        }
                    }
                    ?>
                    
                    <!-- Group Bank -->
                    <?php if (!empty($banks)): ?>
                    <optgroup label="🏦 Bank Transfer">
                        <?php foreach ($banks as $bank): ?>
                            <option value="<?= $bank['id'] ?>" <?= old('payment_method_id') == $bank['id'] ? 'selected' : '' ?>>
                                <?= esc($bank['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </optgroup>
                    <?php endif; ?>

                    <!-- Group E-Wallet -->
                    <?php if (!empty($ewallets)): ?>
                    <optgroup label="📱 E-Wallet">
                        <?php foreach ($ewallets as $ewallet): ?>
                            <option value="<?= $ewallet['id'] ?>" <?= old('payment_method_id') == $ewallet['id'] ? 'selected' : '' ?>>
                                <?= esc($ewallet['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </optgroup>
                    <?php endif; ?>

                    <!-- Jika tidak ada grouping, tampilkan semua -->
                    <?php if (empty($banks) && empty($ewallets)): ?>
                        <?php foreach ($payments as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= old('payment_method_id') == $p['id'] ? 'selected' : '' ?>>
                                <?= esc($p['name']) ?> (<?= esc($p['type'] ?? 'Unknown') ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <!-- Data sementara jika database kosong -->
                    <optgroup label="🏦 Bank Transfer">
                        <option value="1">BCA</option>
                        <option value="2">Mandiri</option>
                        <option value="3">BNI</option>
                        <option value="4">BRI</option>
                        <option value="5">CIMB Niaga</option>
                    </optgroup>
                    <optgroup label="📱 E-Wallet">
                        <option value="6">OVO</option>
                        <option value="7">DANA</option>
                        <option value="8">GoPay</option>
                        <option value="9">LinkAja</option>
                        <option value="10">ShopeePay</option>
                    </optgroup>
                <?php endif; ?>
            </select>
            <small style="color:#64748b;">Gunakan scroll untuk melihat semua metode</small>
        </div>

        <?php if (session()->has('error')): ?>
            <div class="error-box">
                ⚠️ <?= session('error') ?>
            </div>
        <?php endif; ?>

        <?php if (!session()->get('user_id')): ?>
            <div class="warning-box">
                ⚠️ <a href="/user/login" style="color:#fbbf24;font-weight:bold;">Login</a> dulu untuk melanjutkan transaksi.
            </div>
        <?php else: ?>
            <div class="info-box">
                ✔️ Login sebagai: <strong><?= session()->get('username') ?? 'User' ?></strong>
            </div>
        <?php endif; ?>

        <button type="submit" class="btn">Lanjutkan →</button>
    </form>
    
    <br>
    <a href="/games" style="color:#60a5fa;text-decoration:none;display:inline-block;">
        ← Kembali ke Daftar Game
    </a>
</div>

<script>
// Format currency input
document.querySelector('input[name="amount"]')?.addEventListener('input', function(e) {
    let value = this.value.replace(/\D/g, '');
    if (value) {
        this.value = value;
    }
});

// Debug: tampilkan jumlah option di console
document.addEventListener('DOMContentLoaded', function() {
    let select = document.querySelector('select[name="payment_method_id"]');
    if (select) {
        console.log('Jumlah option:', select.options.length);
        console.log('Jumlah optgroup:', select.querySelectorAll('optgroup').length);
    }
});
</script>

</body>
</html>