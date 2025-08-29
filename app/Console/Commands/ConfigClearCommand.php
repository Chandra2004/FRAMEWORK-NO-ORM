<?php

namespace TheFramework\Console\Commands;

use TheFramework\Console\CommandInterface;

class ConfigClearCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'config:clear';
    }
    public function getDescription(): string
    {
        return 'Menghapus cache konfigurasi';
    }

    public function run(array $args): void
    {
        $cachePath = BASE_PATH . '/storage/cache/config.php';
        if (file_exists($cachePath)) {
            unlink($cachePath);
            echo "\033[38;5;28m★ SUCCESS  Cache konfigurasi dihapus\033[0m\n";
        } else {
            echo "\033[38;5;214m⚠ WARNING  Tidak ada cache konfigurasi ditemukan\033[0m\n";
        }
    }
}
