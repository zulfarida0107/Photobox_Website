<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Rekening Bank</title>
<style>body{font-family:sans-serif;background:#070d1f;color:#f0f6ff;padding:40px;} .card{background:#0d1529;border:1px solid #1e3a8a;border-radius:10px;padding:18px;max-width:480px;margin-bottom:14px;} .btn{background:#2563eb;color:white;padding:8px 16px;border:none;border-radius:6px;cursor:pointer;} input{padding:10px;background:#111d36;border:1px solid #1e3a8a;border-radius:6px;color:#f0f6ff;margin-bottom:10px;width:100%;box-sizing:border-box;} label{display:block;color:#64748b;font-size:0.82rem;margin-bottom:4px;} h1{color:#60a5fa;} .ok{background:rgba(74,222,128,0.1);border:1px solid #4ade80;padding:10px;border-radius:8px;color:#4ade80;margin-bottom:14px;font-size:0.85rem;max-width:480px;}</style>
</head>
<body>
<a href="/games" style="color:#60a5fa;">← Games</a>

<h1>🏦 Rekening Bank</h1>

<?php if (session()->getFlashdata('success')): ?>
    <div class="ok">✓ <?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<?php if (empty($banks)): ?>
    <p style="color:#64748b;">Belum ada rekening.</p>
<?php else: ?>
    <?php foreach ($banks as $bank): ?>
    <div class="card">
        <div style="font-weight:700;font-size:1.05rem;margin-bottom:4px;">🏦 <?= esc($bank['bank_name']) ?></div>
        <div style="font-family:monospace;font-size:1.1rem;color:#60a5fa;letter-spacing:0.1em;margin-bottom:4px;"><?= esc($bank['account_number']) ?></div>
        <div style="color:#64748b;font-size:0.85rem;">a.n. <?= esc($bank['account_name']) ?></div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<div class="card" style="margin-top:24px;">
    <h3 style="color:#60a5fa;margin-bottom:14px;">+ Tambah Rekening</h3>
    <form action="/store" method="POST">
        <?= csrf_field() ?>
        <label>Nama Bank</label>
        <input type="text" name="bank_name" required placeholder="BCA">
        <label>Nomor Rekening</label>
        <input type="text" name="account_number" required placeholder="1234567890">
        <label>Nama Pemilik</label>
        <input type="text" name="account_name" required placeholder="Nexus Top Up">
        <button type="submit" class="btn">💾 Simpan</button>
    </form>
</div>
</body>
</html>
