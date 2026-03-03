<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Login - Nexus Top Up</title>
<style>body{font-family:sans-serif;background:#070d1f;color:#f0f6ff;display:flex;align-items:center;justify-content:center;min-height:100vh;} .box{background:#0d1529;border:1px solid #1e3a8a;border-radius:14px;padding:36px;width:360px;} input{width:100%;padding:10px;background:#111d36;border:1px solid #1e3a8a;border-radius:6px;color:#f0f6ff;margin-bottom:14px;box-sizing:border-box;} label{display:block;color:#64748b;font-size:0.85rem;margin-bottom:4px;} .btn{background:#2563eb;color:white;padding:11px;border:none;border-radius:6px;cursor:pointer;width:100%;font-size:1rem;} h2{color:#60a5fa;margin-bottom:20px;} .err{background:rgba(248,113,113,0.1);border:1px solid #f87171;padding:10px;border-radius:6px;color:#f87171;margin-bottom:14px;font-size:0.85rem;}</style>
</head>
<body>
<div class="box">
    <h2>⚡ Login Nexus</h2>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="err">✕ <?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <form action="/user/authenticate" method="POST">
        <?= csrf_field() ?>
        <label>Email</label>
        <input type="email" name="email" required placeholder="email@kamu.com">
        <label>Password</label>
        <input type="password" name="password" required placeholder="••••••••">
        <button type="submit" class="btn">Login →</button>
    </form>
    <p style="text-align:center;margin-top:14px;color:#64748b;font-size:0.85rem;">Belum punya akun? <a href="/user/add" style="color:#60a5fa;">Daftar</a></p>
</div>
</body>
</html>
