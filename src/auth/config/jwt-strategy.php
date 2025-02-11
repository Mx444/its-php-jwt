<?php
require_once __DIR__ . '/jwt-payload.interface.php';
require_once __DIR__ . '/jwt-payload.dto.php';
require_once __DIR__ . '/jwt.module.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if (!class_exists(class: 'JwtStrategy')) {
    class JwtStrategy
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
                'role' => $payload->getRole(),
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
                'role' => $payload->getRole(),

            ];

            return JWT::encode(payload: $tokenPayload, key: $this->config->getSecret(), alg: 'HS256');
        }

        public function validateJwt(string $jwt): array | null
        {

            $decoded = JWT::decode(jwt: $jwt, keyOrKeyArray: new Key(keyMaterial: $this->config->getSecret(), algorithm: 'HS256'));
            return (array) $decoded ?? null;
        }

        public function refreshAccessToken(string $refreshToken): string
        {
            $decoded = $this->validateJwt(jwt: $refreshToken);
            $payload = new JwtPayloadDTO(id: $decoded['id'], email: $decoded['email'], role: $decoded['role']);
            return $this->generateAccessToken(payload: $payload);
        }
    }
}
