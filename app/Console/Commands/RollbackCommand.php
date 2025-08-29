<?php

namespace TheFramework\Console\Commands;

use Throwable;
use TheFramework\Console\CommandInterface;
use TheFramework\App\Database;
use TheFramework\App\Schema;

class RollbackCommand implements CommandInterface
{
    public function getName(): string
    {
        return 'migrate:rollback';
    }

    public function getDescription(): string
    {
        return 'Membatalkan semua migrasi dengan menghapus semua tabel database';
    }

    public function run(array $args): void
    {
        $db = Database::getInstance();

        $this->info("Menyiapkan rollback migrasi");

        // Konfirmasi penghapusan
        $this->warn("Peringatan: Ini akan menghapus SEMUA tabel di database! Ketik 'yes' untuk melanjutkan: ");
        $handle = fopen("php://stdin", "r");
        $confirmation = trim(fgets($handle));
        fclose($handle);

        if (!in_array(strtolower($confirmation), ['yes', 'y'], true)) {
            $this->error("Rollback dibatalkan oleh pengguna.");
            return;
        }

        $this->infoWait("Menjalankan rollback migrasi");

        // Ambil daftar tabel
        try {
            $tables = $this->getAllTables($db);
        } catch (Throwable $e) {
            $this->error("Gagal mengambil daftar tabel: " . $e->getMessage());
            return;
        }

        if (empty($tables)) {
            $this->warn("Tidak ada tabel yang ditemukan untuk dihapus.");
            return;
        }

        try {
            // Matikan pemeriksaan foreign key agar bisa drop semua tabel
            $db->query("SET FOREIGN_KEY_CHECKS = 0");

            foreach ($tables as $table) {
                try {
                    $this->info("Menghapus tabel: {$table}...");
                    Schema::dropIfExists($table);
                    $this->success("Tabel {$table} telah dihapus.");
                } catch (Throwable $e) {
                    $this->error("Gagal menghapus tabel {$table}: " . $e->getMessage());
                    // lanjut ke tabel berikutnya
                }
            }

            // Aktifkan kembali pemeriksaan foreign key
            $db->query("SET FOREIGN_KEY_CHECKS = 1");

            $this->success("Semua tabel telah dihapus, rollback selesai.");
        } catch (Throwable $e) {
            $this->error("Terjadi kesalahan saat rollback: " . $e->getMessage());
        }
    }

    /**
     * Ambil daftar semua tabel di database.
     */
    private function getAllTables($db): array
    {
        $db->query("SHOW TABLES");
        $tablesResult = $db->resultSet(); // ambil array hasil query

        if (empty($tablesResult)) {
            return [];
        }

        // Ambil nama kolom pertama (SHOW TABLES kolomnya dinamis)
        $tables = [];
        foreach ($tablesResult as $row) {
            $tables[] = reset($row); // reset() ambil value pertama dari array
        }

        // Filter tabel sistem jika perlu
        return array_filter($tables, function ($table) {
            return $table !== 'migrations'; // jangan hapus tabel migrasi kalau mau dicatat
        });
    }


    /**
     * Output helper mirip Laravel
     */
    private function info(string $message): void
    {
        echo "\033[38;5;39m➤ INFO  {$message}\033[0m\n";
    }

    private function infoWait(string $message): void
    {
        echo "\033[38;5;39m➤ INFO  {$message}";
        for ($i = 0; $i < 3; $i++) {
            echo ".";
            usleep(200000);
        }
        echo "\033[0m\n";
    }

    private function warn(string $message): void
    {
        echo "\033[38;5;214m⚠ WARNING  {$message}\033[0m\n";
    }

    private function error(string $message): void
    {
        echo "\033[38;5;124m✖ ERROR  {$message}\033[0m\n";
    }

    private function success(string $message): void
    {
        echo "\033[38;5;28m★ SUCCESS  {$message}\033[0m\n";
    }
}
