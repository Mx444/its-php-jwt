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

    /**
     * JwtModule constructor.
     * Initializes the configuration with environment variables.
     */
    private function __construct()
    {
        // Load environment variables
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
        $dotenv->load();

        // Initialize configuration properties with environment variables
        $this->secret = $_ENV['SECRET'];
        $this->issuer = $_ENV['ISSUER'];
        $this->audience = $_ENV['AUDIENCE'];
        $this->expiration = (int)$_ENV['EXP'];
        $this->head = $_ENV['HEAD'];
        $this->accessTokenExpiration = $_ENV['ACCESS_TOKEN_EXP'];
        $this->refreshTokenExpiration = $_ENV['REFRESH_TOKEN_EXP'];
    }

    /**
     * Returns the singleton instance of JwtModule.
     *
     * @return JwtModule The singleton instance of JwtModule.
     */
    public static function getInstance(): JwtModule
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get the JWT secret key.
     *
     * @return string The JWT secret key.
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * Get the JWT issuer.
     *
     * @return string The JWT issuer.
     */
    public function getIssuer(): string
    {
        return $this->issuer;
    }

    /**
     * Get the JWT audience.
     *
     * @return string The JWT audience.
     */
    public function getAudience(): string
    {
        return $this->audience;
    }

    /**
     * Get the JWT expiration time.
     *
     * @return int The JWT expiration time.
     */
    public function getExpiration(): int
    {
        return $this->expiration;
    }

    /**
     * Get the JWT head.
     *
     * @return string The JWT head.
     */
    public function getHead(): string
    {
        return $this->head;
    }

    /**
     * Get the access token expiration time.
     *
     * @return int The access token expiration time.
     */
    public function getAccessTokenExpiration(): int
    {
        return $this->accessTokenExpiration;
    }

    /**
     * Get the refresh token expiration time.
     *
     * @return int The refresh token expiration time.
     */
    public function getRefreshTokenExpiration(): int
    {
        return $this->refreshTokenExpiration;
    }
}
