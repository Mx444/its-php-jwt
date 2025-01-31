<?php


require_once __DIR__ . '/../../database/providers/connection.provider.php';
require_once __DIR__ . '/../../database/providers/transaction.provider.php';
require_once __DIR__ . '/../../auth/providers/bcrypt.provider.php';
require_once __DIR__ . '/../../auth/providers/jwt.service.php';
require_once __DIR__ . '/../../auth/repositories/auth.repository.php';
require_once __DIR__ . '/../../utilis/regex.utilis.php';

class AuthService
{
    private $connectionProvider;
    private $transactionProvider;
    private $bcryptProvider;
    private $jwtService;
    private $db;
    private $authRepository;

    public function __construct()
    {
        $this->connectionProvider = new ConnectionProvider();
        $this->transactionProvider = new TransactionProvider($this->connectionProvider);
        $this->bcryptProvider = new BcryptProvider();
        $this->jwtService = new JwtService();
        $this->db = $this->connectionProvider->getConnection();
        $this->authRepository = new AuthRepository($this->db);
    }

    public function register($email, $password)
    {
        validateEmail($email);
        validatePassword($password);

        $existingAuth = $this->authRepository->findByEmail($email);
        if ($existingAuth) {
            throw new Exception("Email già esistente.");
        }

        $hashedPassword = $this->bcryptProvider->hashPassword($password);
        $authId = $this->authRepository->create($email, $hashedPassword);

        return "Auth created successfully" . $authId;
    }

    public function login($email, $password)
    {
        $auth = $this->authRepository->findByEmail($email);
        if (!$auth) {
            throw new Exception("Email non trovata.");
        }

        $isPasswordValid = $this->bcryptProvider->comparePassword($password, $auth['password']);
        if (!$isPasswordValid) {
            throw new Exception("Password non valida.");
        }

        $token = $this->jwtService->generateJwt([
            'id' => $auth['id'],
            'email' => $auth['email']
        ]);

        return $token;
    }

    public function updateAuth($token, $id, $col, $password)
    {
        $auth = $this->authRepository->findById($id);
        if (!$auth) {
            throw new Exception("Utente non trovato.");
        }

        $isTokenValid = $this->jwtService->validateJwt($token);
        if (!$isTokenValid) {
            throw new Exception("Token non valido.");
        }

        $isPasswordValid = $this->bcryptProvider->comparePassword($password, $auth['password']);
        if (!$isPasswordValid) {
            throw new Exception("Password non valida.");
        }

        $this->authRepository->update($id, $col);
        return "Auth updated successfully";
    }

    public function deleteAuth($token, $id, $password)
    {
        $auth = $this->authRepository->findById($id);
        if (!$auth) {
            throw new Exception("Utente non trovato.");
        }

        $isTokenValid = $this->jwtService->validateJwt($token);
        if (!$isTokenValid) {
            throw new Exception("Token non valido.");
        }

        $isPasswordValid = $this->bcryptProvider->comparePassword($password, $auth['password']);
        if (!$isPasswordValid) {
            throw new Exception("Password non valida.");
        }

        $this->authRepository->delete($id);
        return "Auth deleted successfully";
    }

    public function __destruct()
    {
        $this->connectionProvider->closeConnection();
    }
}
