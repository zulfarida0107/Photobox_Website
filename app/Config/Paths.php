<?php

namespace Config;

class Paths
{
    /**
     * Path to the system directory
     */
    public $systemDirectory = __DIR__ . '/../../vendor/codeigniter4/framework/system/';

    /**
     * The path to the application directory
     */
    public $appDirectory = __DIR__ . '/../';

    /**
     * The path to the writable directory
     */
    public $writableDirectory = __DIR__ . '/../../writable/';

    /**
     * The path to the tests directory
     */
    public $testsDirectory = __DIR__ . '/../../tests/';

    /**
     * The path to the views directory
     */
    public $viewDirectory = __DIR__ . '/../Views/';
}