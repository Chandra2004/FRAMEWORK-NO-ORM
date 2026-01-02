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
        $timestamp = date('Y_m_d_His');

        // Pastikan nama berakhiran 'Seeder' untuk nama dasar
        $baseName = $name;
        if (!str_ends_with($baseName, 'Seeder')) {
            $baseName .= 'Seeder';
        }

        // Format nama file: Y_m_d_His_NamaSeeder.php
        $filename = "{$timestamp}_{$baseName}.php";

        // Format nama class: Seeder_Y_m_d_His_NamaSeeder (agar unik dan valid sebagai class name)
        // Kita ganti spasi/dash dengan underscore
        $className = "Seeder_{$timestamp}_" . str_replace('-', '_', $baseName);

        // Path project root
        $rootPath = realpath(__DIR__ . '/../../../');
        $seederPath = $rootPath . '/database/seeders/' . $filename;

        // Pastikan folder seeders ada
        if (!is_dir(dirname($seederPath))) {
            mkdir(dirname($seederPath), 0777, true);
        }

        // Konversi nama seeder ke table name
        // Hapus 'Seeder' dari baseName untu tebak tabel
        $paramName = preg_replace('/Seeder$/', '', $baseName);
        $tableName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $paramName));
        $tableName = rtrim($tableName, 's') . 's';

        // Template seeder dengan class name dinamis
        $template = <<<PHP
<?php

namespace Database\Seeders;

use TheFramework\Database\Seeder;
use TheFramework\Helpers\Helper;
use Faker\Factory;

class $className extends Seeder {

    public function run() {
        \$faker = Factory::create();
        Seeder::setTable('$tableName');

        Seeder::create([
            [
                // 'column' => 'value'
            ]
        ]);
    }
}

PHP;

        // Buat file
        if (file_put_contents($seederPath, $template) !== false) {
            echo "\033[38;5;28m★ SUCCESS  Seeder dibuat: $filename (database/seeders/$filename)\033[0m\n";
        } else {
            echo "\033[31m[Error]\033[0m Gagal membuat seeder.\n";
        }
    }
}
