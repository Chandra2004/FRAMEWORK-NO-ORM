<?php

use TheFramework\BladeInit;
use TheFramework\App\Config;
use TheFramework\App\Router;
use TheFramework\App\SessionManager;
use TheFramework\Http\Controllers\Services\FileController;
use TheFramework\Middleware\CsrfMiddleware;
use TheFramework\Middleware\WAFMiddleware;

SessionManager::startSecureSession();
Config::loadEnv();

// Security headers
header('X-Powered-By: Native-Chandra');
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: no-referrer-when-downgrade');
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');

// Rate limiting sederhana
$clientIp = $_SERVER['REMOTE_ADDR'];
$rateLimitFile = __DIR__ . '/../app/Storage/cache/ratelimit/' . md5($clientIp);
$limit = 100;
$window = 120;

if (!file_exists(dirname($rateLimitFile))) {
    mkdir(dirname($rateLimitFile), 0755, true);
}
$hits = 0;
if (file_exists($rateLimitFile)) {
    $data = json_decode(file_get_contents($rateLimitFile), true);
    $hits = (time() - $data['timestamp'] < $window) ? $data['hits'] : 0;
}
$hits++;
file_put_contents($rateLimitFile, json_encode(['hits' => $hits, 'timestamp' => time()]));
if ($hits > $limit) {
    http_response_code(429);
    exit('Too many requests.');
}

// Jalankan router
CsrfMiddleware::generateToken();

// Middleware routes atau routes dinamis
Router::add('GET', '/file/(.*)', FileController::class, 'Serve');

// ==== Tambahan untuk route cache ====
$cacheFile = __DIR__ . '/../bootstrap/cache/routes.php';
if (file_exists($cacheFile)) {
    $routes = include $cacheFile;
    foreach ($routes as $route) {
        Router::add(
            $route['method'],
            $route['path_original'] ?? $route['path'], // simpan path asli juga
            $route['handler'],
            $route['function'],
            $route['middleware']
        );
    }
} else {
    require_once __DIR__ . '/../routes/web.php';
}


BladeInit::init();
Router::run();
