<?php

require_once __DIR__ . '/../auth/providers/auth.service.php';
require_once __DIR__ . '/../auth/config/jwt-strategy.php';
require_once __DIR__ . '/../auth/config/jwt.middleware.php';



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

    public function register(array $data): void
    {
        if (empty($data['email']) || empty($data['password'])) {
            $_SESSION['error'] = 'Email e password sono obbligatori.';
            http_response_code(response_code: 400);
            header(header: "Location: register.php");
            exit();
        }

        $email = $data['email'];
        $password = $data['password'];

        try {
            $this->authService->register(email: $email, password: $password);
            http_response_code(response_code: 201);
            $_SESSION['success'] = 'Registrazione effettuata con successo.';
            header(header: "Location: login.php");
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            http_response_code(response_code: 400);
            header(header: "Location: register.php");
            exit();
        }
    }

    public function login(array $data): void
    {
        if (empty($data['email']) || empty($data['password'])) {
            $_SESSION['error'] = 'Email e password sono obbligatori.';
            http_response_code(response_code: 400);
            header(header: "Location: login.php");
            exit();
        }

        $email = $data['email'];
        $password = $data['password'];

        try {
            $tokens = $this->authService->login(email: $email, password: $password);
            $_SESSION['access_token'] = $tokens['access_token'];
            $_SESSION['refresh_token'] = $tokens['refresh_token'];
            $_SESSION['roles'] = $tokens['roles'];
            http_response_code(response_code: 200);
            $_SESSION['success'] = 'Accesso effettuato con successo.';
            header(header: "Location: ../dashboard/index.php");
            exit();
        } catch (Exception $e) {
            http_response_code(response_code: 400);
            $_SESSION['error'] = $e->getMessage();
            header(header: "Location: login.php");
            exit();
        }
    }

    public function updateEmail(array $data): void
    {
        $tokenData = $this->authMiddleware->validateToken();
        $token = $tokenData['token'];
        $userID = $tokenData['id'];

        if (empty($data['newEmail']) || empty($data['oldPassword'])) {
            $_SESSION['error'] = 'Nuova email e vecchia password sono obbligatorie.';
            http_response_code(response_code: 400);
            header(header: "Location: update.php");
            exit();
        }

        $newEmail = $data['newEmail'];
        $oldPassword = $data['oldPassword'];

        try {
            $this->authService->updateAuth(token: $token, id: $userID, col: 'email', oldPassword: $oldPassword, newValue: $newEmail);
            http_response_code(response_code: 200);
            $_SESSION['success'] = 'Email aggiornata con successo.';
            $this->logout();
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            http_response_code(response_code: 400);
            header(header: "Location: update.php");
            exit();
        }
    }

    public function updatePassword(array $data): void
    {
        $tokenData = $this->authMiddleware->validateToken();
        $token = $tokenData['token'];
        $userID = $tokenData['id'];

        if (empty($data['oldPassword']) || empty($data['newPassword'])) {
            $_SESSION['error'] = 'Vecchia password e nuova password sono obbligatorie.';
            http_response_code(response_code: 400);
            header(header: "Location: update.php");
            exit();
        }

        $oldPassword = $data['oldPassword'];
        $newPassword = $data['newPassword'];

        try {
            $this->authService->updateAuth(token: $token, id: $userID, col: 'password', oldPassword: $oldPassword, newValue: $newPassword);
            http_response_code(response_code: 200);
            $_SESSION['success'] = 'Password aggiornata con successo.';
            $this->logout();
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            http_response_code(response_code: 400);
            header(header: "Location: update.php");
            exit();
        }
    }

    public function deleteAuth(array $data): void
    {
        $tokenData = $this->authMiddleware->validateToken();
        $token = $tokenData['token'];
        $userID = $tokenData['id'];

        if (empty($data['password'])) {
            $_SESSION['error'] = 'La password è obbligatoria.';
            http_response_code(response_code: 400);
            header(header: "Location: delete.php");
            exit();
        }

        $password = $data['password'];

        try {
            $this->authService->deleteAuth(token: $token, id: $userID, password: $password);
            http_response_code(response_code: 200);
            $_SESSION['success'] = 'Account eliminato con successo.';
            $this->logout();
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            http_response_code(response_code: 400);
            header(header: "Location: delete.php");
            exit();
        }
    }

    public function logout(): void
    {
        session_destroy();
        header(header: "Location: login.php");
        exit();
    }
}
