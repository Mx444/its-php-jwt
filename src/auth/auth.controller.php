<?php

require_once __DIR__ . '/../auth/providers/auth.service.php';
require_once __DIR__ . '/../auth/config/jwt-strategy.php';
require_once __DIR__ . '/../auth/config/jwt.middleware.php';
require_once __DIR__ . '/..//utils/response.utils.php';
require_once __DIR__ . '/../utils/code-message.utils.php';



class AuthController
{
    private JwtStrategy $jwtService;
    private AuthService $authService;
    private AuthMiddleware $authMiddleware;

    public function __construct()
    {
        $this->jwtService = new JwtStrategy();
        $this->authService = new AuthService(jwtService: $this->jwtService);
        $this->authMiddleware = new AuthMiddleware(jwtService: $this->jwtService);
    }

    public function getAuths(): array
    {
        try {
            if (isAdmin()) return $this->authService->getAuths();
            return [];
        } catch (Exception $e) {
            sendResponse(statusCode: 400, type: 'error', message: $e->getMessage(), location: 'index.php');
        }
    }
    public function register(array $data): void
    {
        if (validateRequiredFields(data: $data, requiredFields: ['email', 'password'], errorMessage: CodeMessage::ERROR_REQUIRED_FIELDS->value, location: 'register.php')) return;
        $email = $data['email'];
        $password = $data['password'];
        try {
            $this->authService->register(email: $email, password: $password);
            sendResponse(statusCode: 200, type: 'success', message: CodeMessage::SUCCESS_REGISTER->value, location: 'login.php');
        } catch (Exception $e) {
            sendResponse(statusCode: 400, type: 'error', message: $e->getMessage(), location: 'register.php');
        }
    }

    public function login(array $data): void
    {
        if (validateRequiredFields(data: $data, requiredFields: ['email', 'password'], errorMessage: CodeMessage::ERROR_REQUIRED_FIELDS->value, location: 'login.php')) return;
        $email = $data['email'];
        $password = $data['password'];

        try {
            $auth = $this->authService->login(email: $email, password: $password);
            $_SESSION['access_token'] = $auth['access_token'];
            $_SESSION['refresh_token'] = $auth['refresh_token'];
            $_SESSION['role'] = $auth['role'];
            sendResponse(statusCode: 200, type: 'success', message: CodeMessage::SUCCESS_LOGIN->value, location: '../index.php');
        } catch (Exception $e) {
            sendResponse(statusCode: 400, type: 'error', message: $e->getMessage(), location: 'login.php');
        }
    }

    public function updateEmail(array $data): void
    {
        $tokenData = $this->authMiddleware->validateToken();
        $token = $tokenData['token'];
        $userID = $tokenData['id'];
        if (validateRequiredFields(data: $data, requiredFields: ['newEmail', 'oldPassword'], errorMessage: CodeMessage::ERROR_UPDATE_FIELDS->value, location: 'update.php')) return;
        $newEmail = $data['newEmail'];
        $oldPassword = $data['oldPassword'];

        try {
            $this->authService->updateAuth(token: $token, id: $userID, col: 'email', oldPassword: $oldPassword, newValue: $newEmail);
            $this->logout();
        } catch (Exception $e) {
            sendResponse(statusCode: 400, type: 'error', message: $e->getMessage(), location: 'update.php');
        }
    }

    public function updatePassword(array $data): void
    {
        $tokenData = $this->authMiddleware->validateToken();
        $token = $tokenData['token'];
        $userID = $tokenData['id'];
        if (validateRequiredFields(data: $data, requiredFields: ['oldPassword', 'newPassword'], errorMessage: CodeMessage::ERROR_PASSWORD_FIELDS->value, location: 'update.php')) return;
        $oldPassword = $data['oldPassword'];
        $newPassword = $data['newPassword'];

        try {
            $this->authService->updateAuth(token: $token, id: $userID, col: 'password', oldPassword: $oldPassword, newValue: $newPassword);
            $this->logout();
        } catch (Exception $e) {
            sendResponse(statusCode: 400, type: 'error', message: $e->getMessage(), location: 'update.php');
        }
    }

    public function updateRoleById($data)
    {
        $tokenData = $this->authMiddleware->validateToken();
        $token = $tokenData['token'];
        if (validateRequiredFields(data: $data, requiredFields: ['id', 'newRole'], errorMessage: CodeMessage::ERROR_UPDATE_FIELDS->value, location: 'index.php')) return;
        $id = $data['id'];
        $newRole = $data['newRole'];
        try {
            $this->authService->updateAuthRoleById(token: $token, id: $id, newRole: $newRole);
            sendResponse(statusCode: 200, type: 'success', message: '', location: './update.php');
        } catch (Exception $e) {
            sendResponse(statusCode: 400, type: 'error', message: $e->getMessage(), location: './update.php');
        }
    }
    public function deleteAuthById($data)
    {
        $tokenData = $this->authMiddleware->validateToken();
        $token = $tokenData['token'];
        if (validateRequiredFields(data: $data, requiredFields: ['id'], errorMessage: CodeMessage::ERROR_DELETE_FIELDS->value, location: 'index.php')) return;
        $id = $data['id'];
        try {
            $this->authService->deleteAuthById(token: $token, id: $id);
            sendResponse(statusCode: 200, type: 'success', message: '', location: './update.php');
        } catch (Exception $e) {
            sendResponse(statusCode: 400, type: 'error', message: $e->getMessage(), location: './update.php');
        }
    }
    public function enableAuthById($data)
    {
        $tokenData = $this->authMiddleware->validateToken();
        $token = $tokenData['token'];
        if (validateRequiredFields(data: $data, requiredFields: ['id'], errorMessage: CodeMessage::ERROR_DELETE_FIELDS->value, location: 'index.php')) return;
        $id = $data['id'];
        try {
            $this->authService->enableAuthById(token: $token, id: $id);
            sendResponse(statusCode: 200, type: 'success', message: '', location: './update.php');
        } catch (Exception $e) {
            sendResponse(statusCode: 400, type: 'error', message: $e->getMessage(), location: './update.php');
        }
    }

    public function deleteAuth(array $data): void
    {
        $tokenData = $this->authMiddleware->validateToken();
        $token = $tokenData['token'];
        $userID = $tokenData['id'];
        if (validateRequiredFields(data: $data, requiredFields: ['password'], errorMessage: CodeMessage::ERROR_DELETE_FIELDS->value, location: 'delete.php')) return;
        $password = $data['password'];

        try {
            $this->authService->deleteAuth(token: $token, id: $userID, password: $password);
            $this->logout();
        } catch (Exception $e) {
            sendResponse(statusCode: 400, type: 'error', message: $e->getMessage(), location: 'delete.php');
        }
    }

    public function logout(): void
    {
        session_destroy();
        header(header: "Location: /php-auth/src/public/auth/login.php");
        exit();
    }
}
