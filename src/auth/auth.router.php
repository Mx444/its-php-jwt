<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../src//auth/providers/auth.service.php';

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_GET['action'] ?? '';

    try {
        $authService = new AuthService();

        $input = json_decode(file_get_contents('php://input'), true);

        switch ($action) {
            case 'register':
                $email = $input['email'] ?? '';
                $password = $input['password'] ?? '';
                $result = $authService->register($email, $password);
                echo json_encode(['success' => true, 'message' => $result]);
                break;

            case 'login':
                $email = $input['email'] ?? '';
                $password = $input['password'] ?? '';
                $token = $authService->login($email, $password);
                echo json_encode(['success' => true, 'token' => $token]);
                break;

            default:
                throw new Exception("Azione non valida.");
        }
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metodo non consentito.']);
}
