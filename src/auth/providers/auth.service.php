<?php
require_once __DIR__ . '/../../database/connection.provider.php';
require_once __DIR__ . '/../../database/transaction.provider.php';
require_once __DIR__ . '/../../auth/providers/argon.provider.php';
require_once __DIR__ . '/../../auth/auth.repository.php';
require_once __DIR__ . '/../../auth/config/jwt-strategy.php';
require_once __DIR__ . '/../../auth/config/jwt-payload.entity.php';
require_once __DIR__ . '/../../utilis/regex.utilis.php';



class AuthService
{
    private DatabaseService $connectionProvider;
    private TransactionProvider $transactionProvider;
    private ArgonProvider $argonProvider;
    private JwtService $jwtService;
    private AuthRepository $authRepository;
    private PDO $db;

    public function __construct(JwtService $jwtService)
    {
        $this->connectionProvider = new DatabaseService();
        $this->db = $this->connectionProvider->getConnection();
        $this->transactionProvider = new TransactionProvider(databaseService: $this->db);
        $this->argonProvider = new ArgonProvider();
        $this->jwtService = $jwtService;
        $this->authRepository = new AuthRepository(db: $this->db);
    }

    /**
     * Registers a new user.
     * 
     * @param string $email The user's email.
     * @param string $password The user's password.
     * @return string The JWT token.
     * @throws Exception If the email is already registered.
     */
    public function register(string $email, string $password): string
    {
        validateEmail(email: $email);
        validatePassword(password: $password);

        if ($this->authRepository->read(condition: 'email', value: $email)) {
            throw new Exception(message: "Email già esistente.");
        }

        $hashedPassword = $this->argonProvider->hashPassword(data: $password);

        try {
            $this->transactionProvider->beginTransaction();
            $authId = $this->authRepository->create(email: $email, hashedPassword: $hashedPassword);
            $this->transactionProvider->commit();
            return "Auth created successfully, ID: " . $authId;
        } catch (Exception $e) {
            $this->transactionProvider->rollBack();
            throw new Exception(message: "Errore nella registrazione: " . $e->getMessage());
        }
    }

    /**
     * Logs in a user.
     * 
     * @param string $email The user's email.
     * @param string $password The user's password.
     * @return string The JWT token.
     * @throws Exception If the login fails.
     */
    public function login(string $email, string $password): array
    {
        $auth = $this->authRepository->read(condition: 'email', value: $email);
        if (!$auth) {
            throw new Exception(message: "Email non trovata.");
        }

        if (!$this->argonProvider->comparePassword(data: $password, encrypted: $auth['password'])) {
            throw new Exception(message: "Password non valida.");
        }

        $payload = new JwtPayloadData(id: $auth['id'], email: $auth['email']);
        $accessToken = $this->jwtService->generateAccessToken($payload);
        $refreshToken = $this->jwtService->generateRefreshToken($payload);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'roles' => $auth['roles']
        ];
    }

    /**
     * Updates the user's authentication details.
     * 
     * @param string $token The JWT token.
     * @param int $userId The user's ID.
     * @param string $field The field to update (email or password).
     * @param string $oldValue The old value (password).
     * @param string $newValue The new value (email or password).
     * @throws Exception If the update fails.
     */
    public function updateAuth(string $token, int $id, string $col, string $oldPassword, string $newValue): string
    {
        $auth = $this->authRepository->read(condition: 'id', value: $id);
        if (!$auth) {
            throw new Exception(message: "Utente non trovato.");
        }

        if (!$this->jwtService->validateJwt(jwt: $token)) {
            throw new Exception(message: "Token non valido.");
        }

        if (!$this->argonProvider->comparePassword(data: $oldPassword, encrypted: $auth['password'])) {
            throw new Exception(message: "Password non valida.");
        }

        if (!in_array(needle: $col, haystack: ['email', 'password'])) {
            throw new Exception(message: "Campo aggiornabile non valido.");
        }

        if ($col === 'email' && $auth['email'] === $newValue) {
            throw new Exception(message: "La nuova email non può essere uguale a quella attuale.");
        }

        $samePassword = $this->argonProvider->comparePassword(data: $newValue, encrypted: $auth['password']);
        if ($col === 'password' && $samePassword === true) {
            throw new Exception(message: "La nuova password non può essere uguale a quella attuale.");
        }

        if ($col === 'password') {
            validatePassword(password: $newValue);
            $newValue = $this->argonProvider->hashPassword(data: $newValue);
        }

        try {
            $this->transactionProvider->beginTransaction();
            $this->authRepository->update(id: $id, col: $col, value: $newValue);
            $this->transactionProvider->commit();
            return "Auth updated successfully";
        } catch (Exception $e) {
            $this->transactionProvider->rollBack();
            throw new Exception(message: "Errore nell'aggiornamento: " . $e->getMessage());
        }
    }

    /**
     * Deletes the user's authentication details.
     * 
     * @param string $token The JWT token.
     * @param int $userId The user's ID.
     * @param string $password The user's password.
     * @throws Exception If the deletion fails.
     */
    public function deleteAuth(string $token, int $id, string $password): string
    {
        $auth = $this->authRepository->read(condition: 'id', value: $id);
        if (!$auth) {
            throw new Exception(message: "Utente non trovato.");
        }

        if (!$this->jwtService->validateJwt(jwt: $token)) {
            throw new Exception(message: "Token non valido.");
        }

        if (!$this->argonProvider->comparePassword(data: $password, encrypted: $auth['password'])) {
            throw new Exception(message: "Password non valida.");
        }

        try {
            $this->transactionProvider->beginTransaction();
            $this->authRepository->delete(id: $id);
            $this->transactionProvider->commit();
            return "Auth deleted successfully";
        } catch (Exception $e) {
            $this->transactionProvider->rollBack();
            throw new Exception(message: "Errore nell'eliminazione: " . $e->getMessage());
        }
    }
}
