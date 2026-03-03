<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nexus Top Up - Games</title>
    <style>
        body { font-family: sans-serif; background: #070d1f; color: #f0f6ff; padding: 20px; }
        h1 { color: #60a5fa; }
        .card { background: #0d1529; border: 1px solid #1e3a8a; border-radius: 10px; padding: 16px; margin: 10px 0; display: inline-block; width: 200px; vertical-align: top; }
        .btn { background: #2563eb; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; display: inline-block; margin-top: 8px; }
        .flash-success { background: rgba(74,222,128,0.1); border: 1px solid #4ade80; padding: 12px; border-radius: 8px; margin-bottom: 16px; color: #4ade80; }
        .flash-error   { background: rgba(248,113,113,0.1); border: 1px solid #f87171; padding: 12px; border-radius: 8px; margin-bottom: 16px; color: #f87171; }
        nav { margin-bottom: 24px; display: flex; gap: 12px; align-items: center; }
        nav a { color: #60a5fa; text-decoration: none; }
    </style>
</head>
<body>

<nav>
    <strong>⚡ Nexus Top Up</strong>
    <?php if (session()->get('user_id')): ?>
        <a href="/user/profile/<?= session()->get('user_id') ?>">👤 <?= session()->get('username') ?></a>
        <a href="/transactions">📋 Riwayat</a>
        <a href="/bank-accounts">🏦 Rekening</a>
        <a href="/user/logout">Logout</a>
    <?php else: ?>
        <a href="/user/login">Login</a>
        <a href="/user/add">Daftar</a>
    <?php endif; ?>
</nav>

<?php if (session()->getFlashdata('success')): ?>
    <div class="flash-success">✓ <?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="flash-error">✕ <?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<h1>🎮 Pilih Game</h1>

<?php if (empty($games)): ?>
    <p style="color:#64748b;">Belum ada game. <a href="/games/create" class="btn">+ Tambah Game</a></p>
<?php else: ?>
    <?php foreach ($games as $game): ?>
        <div class="card">
            <div style="font-size:2rem;">🎮</div>
            <h3 style="margin:8px 0 4px;"><?= esc($game['name']) ?></h3>
            <small style="color:#60a5fa;"><?= esc($game['category']) ?></small>
            <p style="color:#64748b;font-size:0.85rem;"><?= esc($game['description']) ?></p>
            <a href="/payment-methods/topup/<?= $game['id'] ?>" class="btn">⚡ Top Up</a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<br><br>
<a href="/games/create" class="btn">+ Tambah Game</a>

</body>
</html>
