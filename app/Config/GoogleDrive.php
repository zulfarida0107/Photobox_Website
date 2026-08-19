<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class GoogleDrive extends BaseConfig
{
    public string $oauthCredentials;
    public string $folderId;

    public function __construct()
    {
        parent::__construct();

        $configuredPath = env('google.drive.oauth', 'writable/google/oauth.json');
        if (file_exists($configuredPath)) {
            $this->oauthCredentials = $configuredPath;
        } elseif (file_exists(WRITEPATH . 'google/oauth.json')) {
            $this->oauthCredentials = WRITEPATH . 'google/oauth.json';
        } else {
            $this->oauthCredentials = rtrim(ROOTPATH, '/\\') . DIRECTORY_SEPARATOR . ltrim($configuredPath, '/\\');
        }

        $this->folderId = env(
            'google.drive.folderId',
            ''
        );
    }
}
