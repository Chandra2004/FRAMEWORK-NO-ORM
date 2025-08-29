<?php

namespace TheFramework\Middleware;

use TheFramework\App\Config;
use TheFramework\Helpers\Helper;
use TheFramework\Http\Controllers\Services\ErrorController;

class CsrfMiddleware implements Middleware
{
    public static function generateToken()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyToken($token)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $sessionToken = $_SESSION['csrf_token'] ?? '';

        // error_log("SESSION CSRF: " . $sessionToken);
        // error_log("POST CSRF   : " . ($token ?: 'TIDAK ADA'));

        return !empty($sessionToken) && !empty($token) && hash_equals($sessionToken, $token);
    }

    public function before()
    {
        Config::loadEnv();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }

            $token = $_POST['_token'] ?? '';

            if (!self::verifyToken($token)) {
                if (Config::get('APP_ENV') === 'production') {
                    ErrorController::error403();
                    exit;
                } elseif (Config::get('APP_ENV') === 'local') {
                    return Helper::json([
                        'status' => 'failed',
                        'message' => 'CSRF token tidak valid'
                    ]);
                }
            }
        }
    }
}
