<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Google\Client;
use Google\Service\Drive;

class GoogleAuthCommand extends BaseCommand
{
    protected $group       = 'Google';
    protected $name        = 'google:auth';
    protected $description = 'Generate Google OAuth URL or save authorization code to generate token.json';
    protected $usage       = 'google:auth [auth_code]';
    protected $arguments   = [
        'auth_code' => 'Optional authorization code from Google OAuth callback'
    ];

    public function run(array $params)
    {
        $config = config(\Config\GoogleDrive::class);
        $client = new Client();
        $client->setAuthConfig($config->oauthCredentials);
        $client->setScopes([Drive::DRIVE]);
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $client->setRedirectUri('http://127.0.0.1:8080/api/google/callback');

        $code = $params[0] ?? null;

        if (empty($code)) {
            $authUrl = $client->createAuthUrl();
            CLI::write("=== GOOGLE DRIVE OAUTH SETUP ===", 'yellow');
            CLI::write("1. Buka URL berikut di browser:");
            CLI::write($authUrl, 'cyan');
            CLI::write("\n2. Login dengan akun Google Photobox dan berikan izin akses Google Drive.");
            CLI::write("3. Setelah berhasil login, browser akan dialihkan ke halaman callback.");
            CLI::write("   Jika otomatis berhasil, token akan tersimpan langsung.");
            CLI::write("   Jika muncul error 'Unable to connect' atau URL callback berisi '?code=...', salin nilai code tersebut.");
            CLI::write("4. Jalankan perintah: php spark google:auth <KODE_AUTHORIZATION>\n");
            return;
        }

        // Clean code if user pasted entire URL
        if (str_contains($code, 'code=')) {
            parse_str(parse_url($code, PHP_URL_QUERY) ?? '', $queryParams);
            if (!empty($queryParams['code'])) {
                $code = $queryParams['code'];
            }
        }

        CLI::write("Menukarkan code dengan OAuth token...", 'yellow');
        $token = $client->fetchAccessTokenWithAuthCode($code);

        if (isset($token['error'])) {
            CLI::error("Gagal mendapatkan token: " . ($token['error_description'] ?? $token['error']));
            return;
        }

        if (!is_dir(WRITEPATH . 'google')) {
            mkdir(WRITEPATH . 'google', 0777, true);
        }

        $tokenPath = WRITEPATH . 'google/token.json';
        file_put_contents($tokenPath, json_encode($token, JSON_PRETTY_PRINT));

        CLI::write("[SUKSES] Token Google Drive berhasil disimpan di {$tokenPath}!", 'green');
    }
}
