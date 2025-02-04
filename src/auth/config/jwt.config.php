<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(paths: __DIR__ . '/../../../');
$dotenv->load();

class JwtConfig
{
    private static ?JwtConfig $instance = null;

    private string $secret;
    private string $issuer;
    private string $audience;
    private int $expiration;
    private string $head;

    /** 
     * JwtConfig constructor.
     * Initializes the configuration with environment variables.
     */
    private function __construct()
    {
        $this->secret = $_ENV['SECRET'];
        $this->issuer = $_ENV['ISSUER'];
        $this->audience = $_ENV['AUDIENCE'];
        $this->expiration = (int)$_ENV['EXP'];
        $this->head = $_ENV['HEAD'];
    }

    /**
     * Returns the singleton instance of JwtConfig.
     * 
     * @return JwtConfig
     */
    public static function getInstance(): JwtConfig
    {
        if (self::$instance === null) {
            self::$instance = new JwtConfig();
        }
        return self::$instance;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function getIssuer(): string
    {
        return $this->issuer;
    }

    public function getAudience(): string
    {
        return $this->audience;
    }

    public function getExpiration(): int
    {
        return $this->expiration;
    }

    public function getHead(): string
    {
        return $this->head;
    }
}
