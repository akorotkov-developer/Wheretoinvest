<?php
/**
 * Dependency Injection Container
 */

namespace Cetera\Tools;

use Pimple\Container;

/**
 * Class DIContainer
 * @package Cetera\Tools
 */
class DIContainer
{
    /**
     * @var \Pimple\Container
     */
    public static $DIC;

    /**
     * @var
     */
    protected static $instances;

    final private function __construct()
    {
    }

    final private function __clone()
    {
    }

    public static function init()
    {
        self::$DIC = new Container();
    }

    /**
     * @return \Pimple\Container
     */
    public static function get()
    {
        if (is_null(self::$DIC))
            self::init();

        return self::$DIC;
    }
}
