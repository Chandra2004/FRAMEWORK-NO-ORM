<?php

namespace TheFramework\Console\Commands;

use TheFramework\Console\CommandInterface;
use Throwable;

class MigrateCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'migrate';
    }

    public function getDescription(): string
    {
        return 'Menjalankan migrasi database';
    }

    public function run(array $args): void
    {
        $migrationDir = BASE_PATH . '/database/migrations/';
        $migrationFiles = glob($migrationDir . '*.php');

        if (empty($migrationFiles)) {
            echo "\033[38;5;214m⚠ WARNING  Tidak ada file migrasi ditemukan di $migrationDir\033[0m\n";
            return;
        }

        echo "\033[38;5;39m➤ INFO  Menjalankan migrasi";
        for ($i = 0; $i < 3; $i++) {
            echo ".";
            usleep(200000);
        }
        echo "\033[0m\n";

        usort($migrationFiles, function ($a, $b) {
            return filemtime($a) - filemtime($b); // Urutkan berdasarkan waktu modifikasi
        });

        foreach ($migrationFiles as $file) {
            $baseName = basename($file, '.php');
            $migrationClass = 'Database\\Migrations\\Migration_' . $baseName;
            if (!class_exists($migrationClass)) {
                echo "\033[38;5;39m➤ INFO  Memuat file: $file\033[0m\n";
                require_once $file;
            }
            if (class_exists($migrationClass)) {
                try {
                    $migration = new $migrationClass();
                    echo "\033[38;5;39m➤ INFO  Menjalankan migrasi: $baseName...\033[0m\n";
                    $migration->up();
                    echo "\033[38;5;28m★ SUCCESS  Migrasi selesai untuk $baseName\033[0m\n";
                } catch (Throwable $e) {
                    echo "\033[38;5;124m✖ ERROR  Error migrasi pada $baseName: " . $e->getMessage() . "\033[0m\n";
                    return;
                }
            } else {
                echo "\033[38;5;124m✖ ERROR  Kelas migrasi '$migrationClass' tidak ditemukan.\033[0m\n";
            }
        }
    }
}
