<?php
require_once __DIR__ . '/../vendor/autoload.php';

$oauthFile = __DIR__ . '/../writable/google/oauth.json';
$tokenFile = __DIR__ . '/../writable/google/token.json';

if (!file_exists($oauthFile)) {
    die("File oauth.json belum ada di folder writable/google/ ! Pastikan kamu sudah memindahkan dan me-rename file-nya.");
}

$client = new Google\Client();
$client->setAuthConfig($oauthFile);
// Gunakan redirect URI ke file ini sendiri
$redirectUri = 'http://localhost:8080/google-auth.php';
$client->setRedirectUri($redirectUri);
$client->addScope(Google\Service\Drive::DRIVE);
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (isset($token['error'])) {
        die("Error mendapatkan token: " . $token['error_description']);
    }
    file_put_contents($tokenFile, json_encode($token, JSON_PRETTY_PRINT));
    echo "<h3>Berhasil! 🎉</h3>";
    echo "<p>File <b>token.json</b> sudah berhasil dibuat di folder <code>writable/google/</code>.</p>";
    echo "<p>Sekarang kamu bisa menutup halaman ini dan kembali mencoba aplikasi Kiosk.</p>";
} else {
    $authUrl = $client->createAuthUrl();
    echo "<h3>Langkah Terakhir: Otorisasi Google Drive</h3>";
    echo "<p>Klik tombol di bawah ini untuk login ke akun <b>cuitboxstudio@gmail.com</b> dan memberikan izin agar sistem bisa mengunggah foto ke Google Drive.</p>";
    echo "<a href='$authUrl' style='display:inline-block; padding:10px 20px; background:#4CAF50; color:white; text-decoration:none; border-radius:5px;'>Otorisasi Google Drive</a>";
}
