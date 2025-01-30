<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();



class ConnectionProvider
{
    private $connection;

    private function createConnection()
    {
        $host = $_ENV['DB_HOST'];
        $username = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASSWORD'];
        $dbname = $_ENV['DB_NAME'];

        try {
            return new mysqli($host, $username, $password, $dbname);
        } catch (Exception $error) {
            die("Connection failed: " . $error->getMessage());
        }
    }

    public function getConnection()
    {
        if ($this->connection === null) {
            $this->connection = $this->createConnection();
        }

        return $this->connection;
    }

    public function closeConnection()
    {
        if ($this->connection) {
            $this->connection->close();
            $this->connection = null;
        }
    }
}
