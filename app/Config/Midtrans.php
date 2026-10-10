<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Midtrans extends BaseConfig
{
    public string $serverKey = '';
    public string $clientKey = '';

    public bool $isProduction = false;
    public bool $isSanitized = true;
    public bool $is3ds = true;

    public function __construct()
    {
        $this->serverKey = (string) (env('midtrans.serverKey', ''));
        $this->clientKey = (string) (env('midtrans.clientKey', ''));

        $this->isProduction = (bool) env('midtrans.production', false);
        $this->isSanitized = true;
        $this->is3ds = true;
    }
}