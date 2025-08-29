<?php

namespace TheFramework\app;

use TheFramework\BladeInit;
use TheFramework\Helpers\Helper;

class View
{
    public static function render(string $view, $model)
    {
        $bladeView = str_replace('/', '.', $view);

        try {
            $rendered = BladeInit::getInstance()->make($bladeView, $model)->render();

            // Jika AJAX request (SPA), ambil hanya isi <main id="app">...</main>
            if (Helper::is_ajax()) {
                if (preg_match('/<main[^>]*id=["\']app["\'][^>]*>(.*?)<\/main>/si', $rendered, $matches)) {
                    echo '<main id="app">' . $matches[1] . '</main>';
                } else {
                    // fallback: tampilkan semua
                    echo $rendered;
                }
                return;
            }

            // Jika bukan AJAX, tampilkan full HTML
            echo $rendered;
            return;
        } catch (\Exception $e) {
            error_log("Blade rendering failed for view {$bladeView}: " . $e->getMessage());
        }

        // Fallback: PHP Native
        $defaultPath = dirname(__DIR__, 2) . '/resources/Views/' . $view . '.php';
        $fallbackPath = dirname(__DIR__, 2) . '/services/' . $view . '.php';

        if (file_exists($defaultPath)) {
            require $defaultPath;
        } elseif (file_exists($fallbackPath)) {
            require $fallbackPath;
        } else {
            http_response_code(500);
            echo "View not found: {$view}";
            exit;
        }
    }
}
