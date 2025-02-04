<?php

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

require_once __DIR__ . '/../../auth/config/jwt.config.php';

class JwtService
{
    private $config;

    public function __construct()
    {
        $this->config = JwtConfig::getInstance();
    }

    /**
     * Generates a JWT token.
     * 
     * @param array $data The data to include in the token.
     * @return string The generated JWT token.
     * @throws InvalidArgumentException If the data is invalid.
     */
    public function generateJwt(array $data): string
    {
        if (!isset($data['id']) || !isset($data['email'])) {
            throw new InvalidArgumentException(message: 'Invalid data provided for JWT generation');
        }

        $issuedAt = time();
        $expirationTime = $issuedAt + $this->config->getExpiration();

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'iss' => $this->config->getIssuer(),
            'aud' => $this->config->getAudience(),
            'id' => $data['id'],
            'email' => $data['email'],
        ];

        return JWT::encode(payload: $payload, key: $this->config->getSecret(), alg: 'HS256');
    }

    /**
     * Validates a JWT token.
     * 
     * @param string $jwt The JWT token to validate.
     * @return array The decoded token data.
     * @throws Exception If the token is invalid.
     */
    public function validateJwt(string $jwt): array
    {
        try {
            $decoded = JWT::decode(jwt: $jwt, keyOrKeyArray: new Key(keyMaterial: $this->config->getSecret(), algorithm: 'HS256'));
            return (array) $decoded;
        } catch (Exception $e) {
            throw new Exception(message: 'Token non valido: ' . $e->getMessage());
        }
    }
}
