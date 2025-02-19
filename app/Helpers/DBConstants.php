<?php

namespace App\Helpers;

class DBConstants
{
    private static $config;

    public static function get($key)
    {
        self::$config = self::getConfig();
        return self::$config[$key] ?? null;
    }

    private static function getConfig()
    {
        return require __DIR__.'/db_constants.php';
    }
}
