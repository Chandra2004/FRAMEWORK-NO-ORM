<?php

namespace TheFramework\Database;

use TheFramework\App\Database;

class Seeder
{
    protected static Database $db;
    protected static string $table;

    public static function setTable(string $tableName)
    {
        self::$table = $tableName;
    }

    public static function create(array $data)
    {
        if (empty(self::$table)) {
            throw new \Exception("Table belum di-set. Gunakan setTable() sebelum create().");
        }

        self::$db = Database::getInstance();

        // Jika array multidimensi (banyak rows)
        if (isset($data[0]) && is_array($data[0])) {
            foreach ($data as $row) {
                self::insertRow($row);
            }
        } else {
            self::insertRow($data);
        }
    }

    public function call($class)
    {
        // Resolve class name (bisa terima string atau array)
        $classes = is_array($class) ? $class : [$class];

        foreach ($classes as $className) {
            if (!class_exists($className)) {
                // Coba namespace default jika belum ada
                $className = "Database\\Seeders\\" . $className;
            }

            if (class_exists($className)) {
                $seeder = new $className();
                // Tampilkan info di console (optional, but nice)
                echo "\033[38;5;245m   ➤ Running: " . basename(str_replace('\\', '/', $className)) . "\033[0m\n";
                $seeder->run();
            } else {
                echo "\033[31m[Error] Seeder class not found: $className\033[0m\n";
            }
        }
    }

    private static function insertRow(array $row)
    {
        $columns = implode(", ", array_keys($row));
        $placeholders = ":" . implode(", :", array_keys($row));

        $sql = "INSERT INTO " . self::$table . " ($columns) VALUES ($placeholders)";
        self::$db->query($sql);

        foreach ($row as $key => $value) {
            self::$db->bind(":$key", $value);
        }

        self::$db->execute();
    }
}
