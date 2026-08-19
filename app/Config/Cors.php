<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Cors extends BaseConfig
{
    public array $default = [
        'allowedOrigins' => [
            'http://localhost:5173',
        ],

        'allowedOriginsPatterns' => [],

        'allowedHeaders' => [
            '*',
        ],

        'allowedMethods' => [
            '*',
        ],

        'exposedHeaders' => [],

        'maxAge' => 7200,

        'supportsCredentials' => false,
    ];
}