<?php

require_once __DIR__ . '/../../src/auth/providers/auth.service.php';

class AuthController
{
    private $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function register($email, $password)
    {
        try {
            $response = $this->authService->register($email, $password);
            echo json_encode(['message' => $response]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function login($email, $password)
    {
        try {
            $token = $this->authService->login($email, $password);
            echo json_encode(['token' => $token]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
