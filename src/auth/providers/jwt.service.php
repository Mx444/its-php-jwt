<?php

use \Firebase\JWT\JWT;

require_once __DIR__ . '/../../auth/config/jwt.config.php';


class JwtService
{
    public function generateJwt($data)
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + jwtConfig()->expiration;

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'iss' => jwtConfig()->issuer,
            'aud' => jwtConfig()->audience,
            'userId' => $data['id'],
            'email' => $data['email'],
        ];

        return JWT::encode($payload, jwtConfig()->secret, 'HS256');
    }

    public function validateJwt($jwt)
    {
        try {
            $secret = jwtConfig()->secret;
            $decoded = JWT::decode($jwt, new \Firebase\JWT\Key($secret, 'HS256'));
            return  $decoded;
        } catch (Exception $error) {
            echo $error->getMessage();
            return false;
        }
    }
}
