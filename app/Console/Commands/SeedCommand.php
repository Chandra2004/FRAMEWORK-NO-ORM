<?php

namespace TheFramework\Console\Commands;

use TheFramework\Console\CommandInterface;

class SeedCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'db:seed';
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

        if (!defined('BASE_PATH')) {
            define('BASE_PATH', dirname(__DIR__, 3));
        }
        $seedersPath = BASE_PATH . '/database/seeders';

        // Ambil semua file PHP di folder seeders
        $files = glob($seedersPath . '/*.php');

        // Urutkan berdasarkan nama file (Timestamp akan otomatis mengurutkan ini)
        sort($files);

        $executed = 0;
        foreach ($files as $file) {
            $filename = basename($file, '.php');

            // Skip DatabaseSeeder jika ada (biasanya manual runner)
            // Kecuali USER ingin kita jalankan semua file, kita treat DatabaseSeeder sbg file biasa 
            // tapi biasanya DatabaseSeeder isinya call() yang mungkin duplikat.
            // Untuk safety kita skip DatabaseSeeder dari auto-scan ini agar tidak loop/double run.
            if ($filename === 'DatabaseSeeder')
                continue;

            // Baca konten file untuk mencari nama class yang benar
            // Karena nama file sekarang ada timestamp: 2026_..._UserSeeder.php
            // Tapi nama class di dalamnya: class Seeder_2026_..._UserSeeder
            $content = file_get_contents($file);
            if (preg_match('/class\s+(\w+)/', $content, $matches)) {
                $className = 'Database\\Seeders\\' . $matches[1];

                // Cek apakah file sudah di-include (require_once aman)
                require_once $file;

                if (class_exists($className)) {
                    $seeder = new $className();
                    if (method_exists($seeder, 'run')) {
                        echo "\033[38;5;245m   ➤ Running: " . basename($file) . "\033[0m\n";
                        $seeder->run();
                        $executed++;
                    }
                }
            }
        }

        if ($executed === 0) {
            echo "\033[33m[Info] Tidak ada seeder yang dijalankan.\033[0m\n";
        } else {
            echo "\033[38;5;28m★ SUCCESS  Semua seeder selesai dijalankan ($executed file)\033[0m\n";
        }
    }
}
