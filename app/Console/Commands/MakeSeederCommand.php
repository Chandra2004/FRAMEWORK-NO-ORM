<?php

namespace TheFramework\Console\Commands;

use TheFramework\Console\CommandInterface;

class MakeSeederCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'make:seeder';
    }

    public function getDescription(): string
    {
        return 'Membuat file seeder baru di database/seeders';
    }

    public function run(array $args): void
    {
        echo "\033[38;5;39m➤ INFO  Memuat perintah";
        for ($i = 0; $i < 3; $i++) {
            echo ".";
            usleep(200000);
        }
        echo "\033[0m\n";

        if (empty($args)) {
            echo "\033[31m[Error]\033[0m Nama seeder harus diberikan.\n";
            echo "Contoh: php artisan make:seeder UserSeeder\n";
            return;
        }

        $name = $args[0];

        // Konversi nama seeder ke table name dengan aturan: UserSessionSeeder -> user_sessions
        $tableName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', preg_replace('/Seeder$/', '', $name)));
        $tableName = rtrim($tableName, 's') . 's'; // pastikan plural sederhana

        if (!str_ends_with($name, 'Seeder')) {
            $name .= 'Seeder';
        }

        // Path project root
        $rootPath = realpath(__DIR__ . '/../../../');
        $seederPath = $rootPath . '/database/seeders/' . $name . '.php';

        // Pastikan folder seeders ada
        if (!is_dir(dirname($seederPath))) {
            mkdir(dirname($seederPath), 0777, true);
        }

        // Jika file sudah ada
        if (file_exists($seederPath)) {
            echo "\033[33m[Warning]\033[0m Seeder '$name' sudah ada.\n";
            return;
        }

        // Template seeder dengan contoh data untuk user_sessions
        $template = <<<PHP
<?php

namespace Database\Seeders;

use TheFramework\App\Config;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Faker\Factory;
use TheFramework\Database\Seeder;
use TheFramework\Helpers\Helper;

class $name  {

    public function run() {
        \$faker = Factory::create();
        Seeder::setTable('$tableName');

        Seeder::create([
            [
                // Seedernya disini
            ]
        ]);
    }
}

PHP;

        // Buat file
        if (file_put_contents($seederPath, $template) !== false) {
            echo "\033[38;5;28m★ SUCCESS  Seeder dibuat: $name.php (database/seeders/$name.php)\033[0m\n";
        } else {
            echo "\033[31m[Error]\033[0m Gagal membuat seeder.\n";
        }
    }
}
