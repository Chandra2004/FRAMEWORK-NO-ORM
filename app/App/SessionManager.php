<?php

namespace TheFramework\App;

use TheFramework\App\Config;
use TheFramework\Helpers\Helper;

class SessionManager
{
    public static function startSecureSession()
    {
        if (session_status() === PHP_SESSION_NONE) {

            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_secure', 1);
            ini_set('session.use_strict_mode', 1);
            ini_set('session.cookie_samesite', 'Strict');

            session_start();


            if (!isset($_SESSION['initiated'])) {
                session_regenerate_id(true);
                $_SESSION['initiated'] = true;
            }


            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            if (!isset($_SESSION['user_agent'])) {
                $_SESSION['user_agent'] = $userAgent;
            } elseif ($_SESSION['user_agent'] !== $userAgent) {
                self::destroySession();
                Helper::redirect('/login', 'error', 'Sesi telah habis. Silakan login kembali.');
            }


            $timeout = 30 * 60;
            if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
                self::destroySession();
                Helper::redirect('/login', 'error', 'Sesi telah habis. Silakan login kembali.');
            }
            $_SESSION['last_activity'] = time();
        }
    }

    public static function regenerateSession()
    {
        session_regenerate_id(true);
    }

    public static function destroySession()
    {
        session_unset();
        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/');
    }
}
