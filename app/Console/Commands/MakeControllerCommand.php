<?php

namespace TheFramework\Console\Commands;

use TheFramework\Console\CommandInterface;

class MakeControllerCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'make:controller';
    }

    public function getDescription(): string
    {
        return 'Membuat kelas controller baru';
    }

    public function run(array $args): void
    {
        $name = $args[0] ?? null;
        if (!$name) {
            echo "\033[38;5;124m✖ ERROR  Harap masukkan nama controller\033[0m\n";
            exit(1);
        }

        $parts = explode('/', $name);
        $className = array_pop($parts);
        $subNamespace = implode('\\', $parts);
        $folderPath = implode('/', $parts);

        $path = BASE_PATH . "/app/Http/Controllers/" . ($folderPath ? $folderPath . '/' : '') . "$className.php";
        if (file_exists($path)) {
            echo "\033[38;5;124m✖ ERROR  Controller sudah ada: $className\033[0m\n";
            exit(1);
        }

        $namespace = "TheFramework\\Http\\Controllers" . ($subNamespace ? "\\$subNamespace" : '');
        $content = <<<PHP
<?php

namespace $namespace;

use Exception;
use TheFramework\App\View;
use TheFramework\Helpers\Helper;

class $className extends Controller {
    public function Index() {
        \$notification = Helper::get_flash('notification');
        
        return View::render('your.view', [
            'notification' => \$notification,
            'title' => 'Your title'
        ]);
    }
}
PHP;

        if (!is_dir(dirname($path))) mkdir(dirname($path), 0755, true);
        file_put_contents($path, $content);
        echo "\033[38;5;28m★ SUCCESS  Controller dibuat: $className (app/Http/Controllers/" . ($folderPath ? $folderPath . '/' : '') . "$className.php)\033[0m\n";
    }
}
