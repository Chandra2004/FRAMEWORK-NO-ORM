<?php

namespace TheFramework\Console\Commands;

use TheFramework\Console\CommandInterface;

class SeedCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'seed';
    }

    public function getDescription(): string
    {
        return 'Menjalankan seeder database (semua atau seeder tertentu menggunakan --NamaSeeder)';
    }

    public function run(array $args): void
    {
        echo "\033[38;5;39m➤ INFO  Menjalankan seeder";
        for ($i = 0; $i < 3; $i++) {
            echo ".";
            usleep(200000);
        }
        echo "\033[0m\n";

        $seedersPath = BASE_PATH . '/database/seeders';

        // Cek apakah ada argumen yang dimulai dengan '--'
        $specificSeeder = null;
        foreach ($args as $arg) {
            if (strpos($arg, '--') === 0) {
                $specificSeeder = substr($arg, 2); // Hapus '--' di depan
                break;
            }
        }

        if ($specificSeeder) {
            // Hanya jalankan seeder tertentu
            $seederFile = $seedersPath . '/' . $specificSeeder . '.php';
            $className = 'Database\\Seeders\\' . $specificSeeder;

            if (file_exists($seederFile)) {
                require_once $seederFile;
                if (class_exists($className)) {
                    $seeder = new $className();
                    if (method_exists($seeder, 'run')) {
                        $seeder->run();
                        echo "\033[38;5;28m★ SUCCESS  Seeder {$specificSeeder} selesai\033[0m\n";
                        return;
                    }
                }
            }

            echo "\033[38;5;196m✖ ERROR  Seeder {$specificSeeder} tidak ditemukan atau tidak valid\033[0m\n";
            return;
        }

        // Jika tidak ada argumen khusus, jalankan semua seeder
        foreach (glob($seedersPath . '/*Seeder.php') as $file) {
            require_once $file;
            $className = 'Database\\Seeders\\' . basename($file, '.php');
            if (class_exists($className)) {
                $seeder = new $className();
                if (method_exists($seeder, 'run')) {
                    $seeder->run();
                }
            }
        }

        echo "\033[38;5;28m★ SUCCESS  Semua seeder selesai\033[0m\n";
    }
}
