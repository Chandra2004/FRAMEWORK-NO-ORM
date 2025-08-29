<?php

namespace TheFramework\Console\Commands;

use TheFramework\Console\CommandInterface;

class MakeModelCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'make:model';
    }
    public function getDescription(): string
    {
        return 'Membuat kelas model baru';
    }

    public function run(array $args): void
    {
        $name = $args[0] ?? null;
        if (!$name) {
            echo "\033[38;5;124m✖ ERROR  Harap masukkan nama model\033[0m\n";
            exit(1);
        }

        $parts = explode('/', $name);
        $className = array_pop($parts);
        $subNamespace = implode('\\', $parts);
        $folderPath = implode('/', $parts);

        $path = BASE_PATH . "/app/Models/" . ($folderPath ? $folderPath . '/' : '') . "$className.php";
        if (file_exists($path)) {
            echo "\033[38;5;124m✖ ERROR  Model sudah ada: $className\033[0m\n";
            exit(1);
        }

        $namespace = "TheFramework\\Models" . ($subNamespace ? "\\$subNamespace" : '');
        $content = <<<PHP
        <?php

        namespace $namespace;

        use TheFramework\App\CacheManager;
        use TheFramework\App\Database;
        use TheFramework\App\Config;
        use TheFramework\App\Logging;
        use Exception;
        use TheFramework\Helpers\Helper;

        class HomeModel {
            private \$db;

            public function __construct() {
                \$this->db = Database::getInstance();
            }
        }
        PHP;

        if (!is_dir(dirname($path))) mkdir(dirname($path), 0755, true);
        file_put_contents($path, $content);
        echo "\033[38;5;28m★ SUCCESS  Model dibuat: $className (app/Models/" . ($folderPath ? $folderPath . '/' : '') . "$className.php)\033[0m\n";
    }
}
