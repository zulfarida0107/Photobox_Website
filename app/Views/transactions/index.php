<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Transaksi</title>
    <style>
        body{font-family:sans-serif;background:#070d1f;color:#f0f6ff;padding:40px;}
        .container{max-width:900px;margin:0 auto;}
        .header{display:flex;justify-content:space-between;align-items:center;margin-bottom:30px;background:#0d1529;padding:20px;border-radius:10px;border:1px solid #1e3a8a;}
        .btn{background:#2563eb;color:white;padding:11px 22px;border:none;border-radius:6px;cursor:pointer;font-size:1rem;text-decoration:none;display:inline-block;}
        .btn:hover{background:#1d4ed8;}
        .btn-secondary{background:#4b5563;}
        .btn-secondary:hover{background:#374151;}
        .table-container{background:#0d1529;border-radius:10px;padding:20px;border:1px solid #1e3a8a;overflow-x:auto;}
        .table{width:100%;border-collapse:collapse;}
        .table th{text-align:left;padding:15px 10px;background:#111d36;color:#60a5fa;font-weight:bold;}
        .table td{padding:15px 10px;border-bottom:1px solid #1e3a8a;}
        .table tr:hover{background:#111d36;}
        .status{padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:bold;display:inline-block;}
        .status.pending{background:#fbbf24;color:#000;}
        .status.success{background:#10b981;color:#fff;}
        .status.failed{background:#ef4444;color:#fff;}
        .empty-state{text-align:center;padding:50px;background:#111d36;border-radius:10px;}
        .empty-state p{color:#64748b;margin-bottom:20px;}
        .back-link{color:#60a5fa;text-decoration:none;display:block;margin-top:30px;text-align:center;}
        .back-link:hover{text-decoration:underline;}
        .badge{background:#1e3a8a;padding:3px 8px;border-radius:12px;font-size:0.7rem;margin-left:5px;}
        .method-icon{font-size:1rem;margin-right:3px;}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h2 style="margin:0;color:#60a5fa;">📋 Riwayat Transaksi</h2>
                <small style="color:#64748b;">Semua transaksi top up kamu</small>
            </div>
            <a href="/games" class="btn">+ Top Up Baru</a>
        </div>

        <?php if (empty($transactions)): ?>
            <div class="empty-state">
                <div style="font-size:4rem;margin-bottom:20px;">🕳️</div>
                <p>Belum ada transaksi</p>
                <a href="/games" class="btn">Mulai Top Up Sekarang</a>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Game</th>
                            <th>Metode</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $t): ?>
                        <tr>
                            <td style="font-family:monospace;color:#60a5fa;"><?= esc($t['invoice_number'] ?? '-') ?></td>
                            <td>
                                <?= esc($t['game_name'] ?? '-') ?>
                                <?php if (isset($t['category'])): ?>
                                    <span class="badge"><?= esc($t['category']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="method-icon"><?= (isset($t['payment_type']) && strtolower($t['payment_type']) === 'bank') ? '🏦' : '📱' ?></span>
                                <?= esc($t['payment_name'] ?? '-') ?>
                            </td>
                            <td>Rp <?= number_format($t['amount'] ?? 0, 0, ',', '.') ?></td>
                            <td><span class="status <?= $t['status'] ?? 'pending' ?>"><?= ucfirst($t['status'] ?? 'pending') ?></span></td>
                            <td><?= isset($t['created_at']) ? date('d/m/Y H:i', strtotime($t['created_at'])) : '-' ?></td>
                            <td><a href="/transactions/detail/<?= $t['id'] ?? '#' ?>" style="color:#60a5fa;text-decoration:none;">Detail →</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <a href="/games" class="back-link">← Kembali ke Daftar Game</a>
    </div>
</body>
</html>