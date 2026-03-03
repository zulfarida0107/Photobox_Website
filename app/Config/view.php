<?php

namespace Config;

use CodeIgniter\Config\View as BaseView;

class View extends BaseView
{
    /**
     * When false, the view method will clear the data between each
     * call. This keeps your data safe and ensures there is no accidental
     * leaking between calls, so you would need to explicitly pass the data
     * to each view. You might prefer to have the data stick around between
     * calls so that it is available to all views. If that is the case,
     * set $saveData to true.
     *
     * @var bool
     */
    public $saveData = true;

    /**
     * List of built-in filters to use when rendering views.
     *
     * @var array<string, string>
     */
    public $filters = [
        'abs' => \CodeIgniter\View\Filters::class,
        'capitalize' => \CodeIgniter\View\Filters::class,
        'date' => \CodeIgniter\View\Filters::class,
        'excerpt' => \CodeIgniter\View\Filters::class,
        'highlight' => \CodeIgniter\View\Filters::class,
        'highlight_code' => \CodeIgniter\View\Filters::class,
        'limit_words' => \CodeIgniter\View\Filters::class,
        'limit_chars' => \CodeIgniter\View\Filters::class,
        'local_currency' => \CodeIgniter\View\Filters::class,
        'local_number' => \CodeIgniter\View\Filters::class,
        'lower' => \CodeIgniter\View\Filters::class,
        'nl2br' => \CodeIgniter\View\Filters::class,
        'number_format' => \CodeIgniter\View\Filters::class,
        'prose' => \CodeIgniter\View\Filters::class,
        'round' => \CodeIgniter\View\Filters::class,
        'strip_tags' => \CodeIgniter\View\Filters::class,
        'title' => \CodeIgniter\View\Filters::class,
        'upper' => \CodeIgniter\View\Filters::class,
    ];
}