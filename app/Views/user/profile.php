<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Profil - Nexus</title>
<style>body{font-family:sans-serif;background:#070d1f;color:#f0f6ff;padding:40px;} .box{background:#0d1529;border:1px solid #1e3a8a;border-radius:14px;padding:28px;max-width:480px;} input{width:100%;padding:10px;background:#111d36;border:1px solid #1e3a8a;border-radius:6px;color:#f0f6ff;margin-bottom:14px;box-sizing:border-box;} label{display:block;color:#64748b;font-size:0.85rem;margin-bottom:4px;} .btn{background:#2563eb;color:white;padding:10px 20px;border:none;border-radius:6px;cursor:pointer;} h2{color:#60a5fa;} .ok{background:rgba(74,222,128,0.1);border:1px solid #4ade80;padding:10px;border-radius:6px;color:#4ade80;margin-bottom:14px;font-size:0.85rem;}</style>
</head>
<body>
<?php if (session()->getFlashdata('success')): ?>
    <div class="ok" style="max-width:480px;margin-bottom:14px;">✓ <?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="box">
    <h2>👤 Profil Saya</h2>
    <form action="/user/show/<?= $user['id'] ?>" method="POST">
        <?= csrf_field() ?>
        <label>Username</label>
        <input type="text" name="username" value="<?= esc($user['username']) ?>" required>
        <label>Email</label>
        <input type="email" name="email" value="<?= esc($user['email']) ?>" required>
        <label>Password Baru <span style="color:#64748b;font-size:0.8rem;">(kosongkan jika tidak ganti)</span></label>
        <input type="password" name="password" placeholder="••••••••" minlength="6">
        <button type="submit" class="btn">💾 Simpan Perubahan</button>
    </form>
    <br>
    <a href="/games" style="color:#60a5fa;">← Kembali ke Games</a> &nbsp;|&nbsp;
    <a href="/transactions" style="color:#60a5fa;">📋 Riwayat Transaksi</a>
</div>
</body>
</html>
