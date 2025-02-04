<?php

require_once __DIR__ . '/../providers/jwt.service.php';

class AuthMiddleware
{
    private JwtService $jwtService;

    /**
     * AuthMiddleware constructor.
     * Initializes the JwtService.
     */
    public function __construct()
    {
        $this->jwtService = new JwtService();
    }

    /**
     * Validates the JWT token stored in the session.
     * 
     * @return array|null Returns user data if the token is valid, otherwise redirects to index.php.
     */
    public function validateToken(): array|null
    {
        if (!isset($_SESSION['token'])) {
            header(header: "Location: index.php");
            exit();
        }
        try {
            $tokenData = $this->jwtService->validateJwt(jwt: $_SESSION['token']);
            if (is_array(value: $tokenData) && isset($tokenData['id'])) {
                return ['id' => $tokenData['id'], 'email' => $tokenData['email'], 'token' => $_SESSION['token']];
            } else {
                throw new Exception(message: 'Token non valido');
            }
        } catch (Exception $e) {
            header(header: "Location: index.php");
            exit();
        }
    }
}
