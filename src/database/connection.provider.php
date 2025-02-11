<?php

require_once __DIR__ . '/../../vendor/autoload.php';


class DatabaseService
{

    private static $connection = null;
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            self::$connection = self::createConnection();
        }
        return self::$connection;
    }
    private static function createConnection(): PDO
    {
        $dotenv = Dotenv\Dotenv::createImmutable(paths: __DIR__ . '/../../');
        $dotenv->load();

        $host = $_ENV['DB_HOST'];
        $username = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASSWORD'];
        $dbname = $_ENV['DB_NAME'];
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

        try {
            $pdo = new PDO(dsn: $dsn, username: $username, password: $password, options: [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT => true
            ]);
            return $pdo;
        } catch (PDOException $error) {
            die("Connection failed: " . $error->getMessage());
        }
    }
}
