<?php

namespace Config;

use CodeIgniter\Config\View as BaseView;

class View extends BaseView
{
    /**
     * When false, the view method will clear the data between each
     * call.
     *
     * @var bool
     */
    public $saveData = true;

    /**
     * Parser Filters map a filter name with any PHP callable. When the
     * Parser prepares a variable for display, it will chain it
     * through the filters in the order defined, inserting any parameters.
     * To prevent potential abuse, all filters MUST be defined here
     * in order to be available for use within the Parser.
     *
     * @var array
     */
    public $filters = [];

    /**
     * Parser Plugins provide a way to extend the functionality of
     * the Parser by creating aliases that will be replaced with
     * any callable. Can be single or tag pair.
     *
     * @var array
     */
    public $plugins = [];

    /**
     * View Decorators are class methods that will be run in sequence to
     * have a chance to alter the generated output just prior to caching
     * the results.
     *
     * All classes must implement CodeIgniter\View\ViewDecoratorInterface
     *
     * @var array
     */
    public array $decorators = [];
}