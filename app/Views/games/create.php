<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Tambah Game</title>
<style>body{font-family:sans-serif;background:#070d1f;color:#f0f6ff;padding:40px;} input,textarea,select{width:100%;padding:10px;background:#0d1529;border:1px solid #1e3a8a;border-radius:6px;color:#f0f6ff;margin-bottom:14px;} label{display:block;color:#64748b;font-size:0.85rem;margin-bottom:4px;} .btn{background:#2563eb;color:white;padding:10px 20px;border:none;border-radius:6px;cursor:pointer;} h1{color:#60a5fa;} .wrap{max-width:500px;}</style>
</head>
<body>
<div class="wrap">
<h1>+ Tambah Game</h1>
<form action="/games/store" method="POST">
    <?= csrf_field() ?>
    <label>Nama Game</label>
    <input type="text" name="name" required placeholder="Mobile Legends">
    <label>Kategori</label>
    <input type="text" name="category" placeholder="MOBA">
    <label>Deskripsi</label>
    <textarea name="description" rows="3" placeholder="Deskripsi game..."></textarea>
    <label>Image URL (opsional)</label>
    <input type="text" name="image" placeholder="https://...">
    <button type="submit" class="btn">💾 Simpan Game</button>
    <a href="/games" style="margin-left:12px;color:#60a5fa;">Batal</a>
</form>
</div>
</body>
</html>
