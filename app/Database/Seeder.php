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
