<?php

namespace App\Controllers;

use Config\GoogleDrive;
use Google\Client;
use Google\Service\Drive;

class GoogleAuthController extends BaseController
{
    private Client $client;

    public function __construct()
    {
        $config = config(GoogleDrive::class);

        $this->client = new Client();

        $this->client->setAuthConfig(
            $config->oauthCredentials
        );

        $this->client->setScopes([
            Drive::DRIVE
        ]);

        $this->client->setAccessType('offline');

        // supaya refresh_token selalu dikirim
        $this->client->setPrompt('consent');

        $this->client->setRedirectUri(
            'http://127.0.0.1:8080/api/google/callback'
        );
    }

    public function auth()
    {
        $authUrl = $this->client->createAuthUrl();
        return redirect()->to($authUrl);
    }

    public function callback()
    {
        $code = $this->request->getGet('code');

        if (!$code) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Authorization code tidak ditemukan.'
            ]);
        }

        $token = $this->client->fetchAccessTokenWithAuthCode($code);

        if (isset($token['error'])) {
            return $this->response->setJSON($token);
        }

        if (!is_dir(WRITEPATH . 'google')) {
            mkdir(WRITEPATH . 'google', 0777, true);
        }

        file_put_contents(
            WRITEPATH . 'google/token.json',
            json_encode($token, JSON_PRETTY_PRINT)
        );

        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <title>Google Drive Terhubung - CuitBox</title>
            <style>
                body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #0f172a; color: #ffffff; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
                .card { background: #1e293b; padding: 40px; border-radius: 20px; text-align: center; max-width: 480px; box-shadow: 0 20px 40px rgba(0,0,0,0.5); border: 1px solid #334155; }
                .badge { background: #10b981; color: white; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto; font-size: 32px; }
                h1 { margin: 0 0 10px 0; font-size: 24px; }
                p { color: #94a3b8; font-size: 15px; line-height: 1.6; margin: 0 0 25px 0; }
                .btn { background: #e2f952; color: #0f172a; font-weight: bold; text-decoration: none; padding: 12px 28px; border-radius: 30px; display: inline-block; }
            </style>
        </head>
        <body>
            <div class='card'>
                <div class='badge'>✓</div>
                <h1>Google Drive Berhasil Terhubung!</h1>
                <p>Otentikasi Google OAuth berhasil diverifikasi. Sekarang seluruh foto dan video sesi photobox akan otomatis diunggah langsung ke Google Drive dan link Drive akan dikirimkan ke email customer.</p>
                <a href='javascript:window.close()' class='btn'>Tutup Tab Ini</a>
            </div>
        </body>
        </html>
        ";

        return $this->response->setHeader('Content-Type', 'text/html')->setBody($html);
    }
}
