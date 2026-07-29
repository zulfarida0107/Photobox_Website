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

        $this->oauthCredentials = ROOTPATH . env(
            'google.drive.oauth',
            'writable/google/oauth.json'
        );

        $this->folderId = env(
            'google.drive.folderId'
        );
    }
}
