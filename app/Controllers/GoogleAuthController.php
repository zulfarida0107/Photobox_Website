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
            base_url('api/google/callback')
        );
    }

    public function auth()
    {
        return redirect()->to(
            $this->client->createAuthUrl()
        );
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

        return $this->response->setJSON([
            'success' => true,
            'message' => 'OAuth berhasil.',
            'token_saved' => true
        ]);
    }
}
