<?php

namespace TheFramework\Console\Commands;

use TheFramework\Console\CommandInterface;
use TheFramework\App\Router;

class RouteCacheCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'route:cache';
    }
    public function getDescription(): string
    {
        return 'Menyimpan cache rute aplikasi';
    }
    public function run(array $args): void
    {
        echo "\033[38;5;39m➤ INFO  Menyimpan cache rute";
        for ($i = 0; $i < 3; $i++) {
            echo ".";
            usleep(200000);
        }
        echo "\033[0m\n";

        // 1. Load route file manual (sama seperti di bootstrap/app.php)
        $webRoutesFile = __DIR__ . '/../../../routes/web.php';
        if (file_exists($webRoutesFile)) {
            require $webRoutesFile;
        }

        // 2. Ambil semua route yang sudah didaftarkan
        $routes = Router::getRouteDefinitions();

        // 3. Simpan ke cache
        $cacheDir = __DIR__ . '/../../../bootstrap/cache';
        $cacheFile = $cacheDir . '/routes.php';

        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }

        $content = "<?php\nreturn " . var_export($routes, true) . ";\n";
        file_put_contents($cacheFile, $content);

        echo "\033[38;5;28m★ SUCCESS  Rute telah disimpan ke cache di: {$cacheFile}\033[0m\n";
    }
}
