<?php
require_once __DIR__ . '/jwt-payload.interface.php';
require_once __DIR__ . '/jwt-payload.entity.php';
require_once __DIR__ . '/jwt.module.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if (!class_exists('JwtService')) {
    class JwtService
    {
        private JwtModule $config;

        /**
         * JwtService constructor.
         * Initializes the JwtModule.
         */
        public function __construct()
        {
            $this->config = JwtModule::getInstance();
        }

        /**
         * Generates an access token.
         * 
         * @param JwtPayload $payload The payload to require_once in the token.
         * @return string The generated access token.
         * @throws InvalidArgumentException If the payload is invalid.
         */
        public function generateAccessToken(JwtPayload $payload): string
        {
            if (!$payload->getId() || !$payload->getEmail()) {
                throw new InvalidArgumentException('Invalid data provided for JWT generation');
            }

            $issuedAt = time();
            $expirationTime = $issuedAt + $this->config->getAccessTokenExpiration();

            $tokenPayload = [
                'iat' => $issuedAt,
                'exp' => $expirationTime,
                'iss' => $this->config->getIssuer(),
                'aud' => $this->config->getAudience(),
                'id' => $payload->getId(),
                'email' => $payload->getEmail(),
            ];

            return JWT::encode($tokenPayload, $this->config->getSecret(), 'HS256');
        }

        /**
         * Generates a refresh token.
         * 
         * @param JwtPayload $payload The payload to require_once in the token.
         * @return string The generated refresh token.
         * @throws InvalidArgumentException If the payload is invalid.
         */
        public function generateRefreshToken(JwtPayload $payload): string
        {
            if (!$payload->getId() || !$payload->getEmail()) {
                throw new InvalidArgumentException('Invalid data provided for JWT generation');
            }

            $issuedAt = time();
            $expirationTime = $issuedAt + $this->config->getRefreshTokenExpiration();

            $tokenPayload = [
                'iat' => $issuedAt,
                'exp' => $expirationTime,
                'iss' => $this->config->getIssuer(),
                'aud' => $this->config->getAudience(),
                'id' => $payload->getId(),
                'email' => $payload->getEmail(),
            ];

            return JWT::encode($tokenPayload, $this->config->getSecret(), 'HS256');
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
                $decoded = JWT::decode($jwt, new Key($this->config->getSecret(), 'HS256'));
                return (array) $decoded;
            } catch (Exception $e) {
                throw new Exception('Invalid token: ' . $e->getMessage());
            }
        }

        /**
         * Refreshes an access token using a refresh token.
         * 
         * @param string $refreshToken The refresh token.
         * @return string The new access token.
         * @throws Exception If the refresh token is invalid.
         */
        public function refreshAccessToken(string $refreshToken): string
        {
            $decoded = $this->validateJwt($refreshToken);

            // Create a new payload with the same user data
            $payload = new JwtPayloadData($decoded['id'], $decoded['email']);

            return $this->generateAccessToken($payload);
        }
    }
}
