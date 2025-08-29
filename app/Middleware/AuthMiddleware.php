<?php

namespace TheFramework\Middleware;

use TheFramework\App\Config;
use TheFramework\App\SessionManager;
use TheFramework\Helpers\Helper;

class AuthMiddleware implements Middleware
{
    public function before()
    {
        if (session_status() === PHP_SESSION_NONE) {
            SessionManager::startSecureSession();
        }

        if (!isset($_SESSION['user_id']) || !isset($_SESSION['auth_token'])) {
            return Helper::redirect('/login', 'warning', 'User unauthenticated');
        }

        // Validasi token autentikasi
        $storedToken = $_SESSION['auth_token'];
        $expectedToken = hash('sha256', $_SESSION['user_id'] . Config::get('APP_KEY') . $_SESSION['last_activity']);
        if (!hash_equals($storedToken, $expectedToken)) {
            SessionManager::destroySession();
            return Helper::redirect('/login', 'warning', 'Invalid token');
        }

        // Validasi waktu kedaluwarsa (contoh: 1 jam)
        $sessionTimeout = 3600;
        if (time() - ($_SESSION['last_activity'] ?? 0) > $sessionTimeout) {
            SessionManager::destroySession();
            return Helper::redirect('/login', 'warning', 'Session expired');
        }
    }
}
