<?php

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../providers/auth.service.php';
require_once __DIR__ . '/../config/jwt.middleware.php';
class AuthController
{
    private AuthService $authService;
    private AuthMiddleware $authMiddleware;

    /**
     * AuthController constructor.
     * Initializes the AuthService and AuthMiddleware.
     */
    public function __construct()
    {
        $this->authService = new AuthService();
        $this->authMiddleware = new AuthMiddleware();
    }

    public function register(array $data)
    {
        if (isset($data['email']) && isset($data['password'])) {
            try {
                $email = $data['email'];
                $password = $data['password'];
                $this->authService->register(email: $email, password: $password);
                http_response_code(response_code: 201);
                $_SESSION['success'] = 'Registrazione effettuata con successo.';
                return 201;
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                http_response_code(response_code: 400);
                header(header: "Location: register.php");
                exit();
            }
        } else {
            $_SESSION['error'] = 'Email e password sono obbligatori.';
            http_response_code(response_code: 400);
            header(header: "Location: register.php");
            exit();
        }
    }

    /**
     * Handles user login.
     * 
     * @param array $data The data containing email and password.
     */
    public function login(array $data): void
    {
        if (isset($data['email']) && isset($data['password'])) {
            try {
                $email = $data['email'];
                $password = $data['password'];
                $token = $this->authService->login(email: $email, password: $password);
                $_SESSION['token'] = $token;
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
        } else {
            http_response_code(response_code: 400);
            $_SESSION['error'] = 'Email e password sono obbligatori.';
            header(header: "Location: login.php");
            exit();
        }
    }

    /**
     * Updates the user's email.
     * 
     * @param array $data The data containing the new email and old password.
     */
    public function updateEmail(array $data): void
    {
        $tokenData = $this->authMiddleware->validateToken();
        $token = $tokenData['token'];
        $userID = $tokenData['id'];
        if (isset($data['newEmail'])) {
            try {
                $newEmail = $data['newEmail'];
                $oldPassword = $data['oldPassword'];
                $this->authService->updateAuth(token: $token, id: $userID, col: 'email', oldPassword: $oldPassword, newValue: $newEmail);
                http_response_code(response_code: 200);
                $_SESSION['success'] = 'Email aggiornata con successo.';
                $this->logout();
            } catch (Exception $e) {
                http_response_code(response_code: 400);
                $_SESSION['error'] = $e->getMessage();
                header(header: "Location: update.php");
                exit();
            }
        } else {
            http_response_code(response_code: 400);
            $_SESSION['error'] = 'Nuova email è obbligatoria.';
            header(header: "Location: update.php");
            exit();
        }
    }

    /**
     * Updates the user's password.
     * 
     * @param array $data The data containing old and new passwords.
     */
    public function updatePassword(array $data): void
    {
        $tokenData = $this->authMiddleware->validateToken();
        $token = $tokenData['token'];
        $userID = $tokenData['id'];
        if (isset($data['oldPassword']) && isset($data['newPassword'])) {
            try {
                $oldPassword = $data['oldPassword'];
                $newPassword = $data['newPassword'];
                $this->authService->updateAuth(token: $token, id: $userID, col: 'password', oldPassword: $oldPassword, newValue: $newPassword);
                http_response_code(response_code: 200);
                $_SESSION['success'] = 'Password aggiornata con successo.';
                $this->logout();
            } catch (Exception $e) {
                http_response_code(response_code: 400);
                $_SESSION['error'] = $e->getMessage();
                header(header: "Location: update.php");
                exit();
            }
        } else {
            http_response_code(response_code: 400);
            $_SESSION['error'] = 'Password corrente e nuova password sono obbligatorie.';
            header(header: "Location: update.php");
            exit();
        }
    }

    public function deleteAuth(array $data): void
    {
        $tokenData = $this->authMiddleware->validateToken();
        $token = $tokenData['token'];
        $userID = $tokenData['id'];
        if (isset($data['password'])) {
            try {
                $password = $data['password'];
                $this->authService->deleteAuth(token: $token, id: $userID, password: $password);
                http_response_code(response_code: 200);
                $_SESSION['success'] = 'Account eliminato con successo.';
                $this->logout();
            } catch (Exception $e) {
                http_response_code(response_code: 400);
                $_SESSION['error'] = $e->getMessage();
                header(header: "Location: update.php");
                exit();
            }
        } else {
            http_response_code(response_code: 400);
            $_SESSION['error'] = 'Password è obbligatoria.';
            header(header: "Location: update.php");
            exit();
        }
    }

    /**
     * Logs out the user by destroying the session.
     */
    public function logout(): void
    {
        $this->authMiddleware->validateToken();
        unset($_SESSION['token']);
        session_destroy();
        header(header: "Location: index.php");
        exit();
    }
}
