<?php
require_once __DIR__ . '/jwt-payload.interface.php';
require_once __DIR__ . '/jwt-payload.entity.php';
require_once __DIR__ . '/jwt.module.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if (!class_exists(class: 'JwtService')) {
    class JwtService
    {
        private JwtModule $config;

        public function __construct()
        {
            $this->config = JwtModule::getInstance();
        }

        public function generateAccessToken(JwtPayload $payload): string
        {
            if (!$payload->getId() || !$payload->getEmail()) {
                throw new InvalidArgumentException(message: 'Invalid data provided for JWT generation');
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

            return JWT::encode(payload: $tokenPayload, key: $this->config->getSecret(), alg: 'HS256');
        }

        public function generateRefreshToken(JwtPayload $payload): string
        {
            if (!$payload->getId() || !$payload->getEmail()) {
                throw new InvalidArgumentException(message: 'Invalid data provided for JWT generation');
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

            return JWT::encode(payload: $tokenPayload, key: $this->config->getSecret(), alg: 'HS256');
        }

        public function validateJwt(string $jwt): array
        {
            try {
                $decoded = JWT::decode(jwt: $jwt, keyOrKeyArray: new Key(keyMaterial: $this->config->getSecret(), algorithm: 'HS256'));
                return (array) $decoded;
            } catch (Exception $e) {
                throw new Exception(message: 'Invalid token: ' . $e->getMessage());
            }
        }

        public function refreshAccessToken(string $refreshToken): string
        {
            $decoded = $this->validateJwt(jwt: $refreshToken);
            $payload = new JwtPayloadDTO(id: $decoded['id'], email: $decoded['email']);
            return $this->generateAccessToken(payload: $payload);
        }
    }
}
