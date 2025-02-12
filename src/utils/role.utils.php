<?php
require_once __DIR__ . '/../auth/config/jwt-strategy.php';

function isAdminJWT(): bool
{
    $jwt = new JwtStrategy();
    $token = isset($_SESSION['access_token']) ? $_SESSION['access_token'] : null;
    if ($token) {
        $decoded = $jwt->validateJwt(jwt: $token);
        if ($decoded['role'] === 'admin') {
            return true;
        }
    }
    return false;
}
