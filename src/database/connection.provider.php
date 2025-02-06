<?php

namespace Database\Providers;

use PDO;
use PDOException;
use Dotenv\Dotenv;

class DatabaseService
{
    /**
     * @var PDO|null Holds the database connection instance
     */
    private static $connection = null;

    /**
     * Returns the PDO connection instance. If it doesn't exist, it creates one.
     *
     * @return PDO The PDO connection instance
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            self::$connection = self::createConnection();
        }
        return self::$connection;
    }

    /**
     * Creates a new PDO connection instance using environment variables.
     *
     * @return PDO The new PDO connection instance
     */
    private static function createConnection(): PDO
    {
        // Load environment variables
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
        $dotenv->load();

        // Retrieve database connection details from environment variables
        $host = $_ENV['DB_HOST'];
        $username = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASSWORD'];
        $dbname = $_ENV['DB_NAME'];
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

        try {
            // Create and return a new PDO instance
            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT => true
            ]);
            return $pdo;
        } catch (PDOException $error) {
            // Handle connection error
            die("Connection failed: " . $error->getMessage());
        }
    }
}
