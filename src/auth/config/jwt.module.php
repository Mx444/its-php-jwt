<?php
class JwtModule
{
    private static ?JwtModule $instance = null;

    private string $secret;
    private string $issuer;
    private string $audience;
    private int $expiration;
    private string $head;
    private int $accessTokenExpiration;
    private int $refreshTokenExpiration;

    private function __construct()
    {
        $dotenv = Dotenv\Dotenv::createImmutable(paths: __DIR__ . '/../../../');
        $dotenv->load();

        $this->secret = $_ENV['SECRET'];
        $this->issuer = $_ENV['ISSUER'];
        $this->audience = $_ENV['AUDIENCE'];
        $this->expiration = (int)$_ENV['EXP'];
        $this->head = $_ENV['HEAD'];
        $this->accessTokenExpiration = $_ENV['ACCESS_TOKEN_EXP'];
        $this->refreshTokenExpiration = $_ENV['REFRESH_TOKEN_EXP'];
    }

    public static function getInstance(): JwtModule
    {
        if (self::$instance === null) {
            self::$instance = new self();
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

    public function getAccessTokenExpiration(): int
    {
        return $this->accessTokenExpiration;
    }

    public function getRefreshTokenExpiration(): int
    {
        return $this->refreshTokenExpiration;
    }
}
