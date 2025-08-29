<?php

namespace TheFramework\App;

use Dotenv\Dotenv;

class Config
{
    public static function loadEnv()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
    }

    public static function get($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}
