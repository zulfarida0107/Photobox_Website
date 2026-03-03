<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    public $aliases = [
        'csrf'     => \CodeIgniter\Filters\CSRF::class,
        'toolbar'  => \CodeIgniter\Filters\DebugToolbar::class,
        'auth'     => \App\Filters\AuthFilter::class,
    ];

    public $globals = [
        'before' => [
            // 'csrf', // aktifkan jika mau proteksi CSRF global
        ],
        'after'  => [
            'toolbar',
        ],
    ];
}
