<?php
require_once __DIR__ . '/../../database/connection.provider.php';
require_once __DIR__ . '/../../database/transaction.provider.php';
require_once __DIR__ . '/../../auth/providers/argon.provider.php';
require_once __DIR__ . '/../../auth/auth.repository.php';
require_once __DIR__ . '/../../auth/config/jwt-strategy.php';
require_once __DIR__ . '/../../auth/config/jwt-payload.dto.php';
require_once __DIR__ . '/../../utils/regex.utils.php';



class AuthService
{
    private DatabaseService $connectionProvider;
    private TransactionProvider $transactionProvider;
    private Argon2idProvider $argonProvider;
    private JwtStrategy $jwtService;
    private AuthRepository $authRepository;
    private PDO $db;

    public function __construct(JwtStrategy $jwtService)
    {
        $this->connectionProvider = new DatabaseService();
        $this->db = $this->connectionProvider->getConnection();
        $this->transactionProvider = new TransactionProvider(databaseService: $this->db);
        $this->argonProvider = new Argon2idProvider();
        $this->jwtService = $jwtService;
        $this->authRepository = new AuthRepository(db: $this->db);
    }

    public function register(string $email, string $password): bool
    {
        validateEmail(email: $email);
        validatePassword(password: $password);
        $auth = $this->authRepository->read(condition: 'email', value: $email);
        if ($auth) throw new Exception(message: "Email già esistente.");
        $hashedPassword = $this->argonProvider->hashPassword(data: $password);
        try {
            $this->transactionProvider->beginTransaction();
            $this->authRepository->create(email: $email, hashedPassword: $hashedPassword);
            $this->transactionProvider->commit();
            return true;
        } catch (Exception $e) {
            $this->transactionProvider->rollBack();
            throw new Exception(message: "Errore nella registrazione: " . $e->getMessage());
        }
    }

    public function login(string $email, string $password): array
    {
        $auth = $this->authRepository->read(condition: 'email', value: $email);
        if (!$auth) throw new Exception(message: "Email non trovata.");
        $mathPassword = $this->argonProvider->comparePassword(data: $password, encrypted: $auth['password']);
        if (!$mathPassword) throw new Exception(message: "Password non valida.");
        $payload = new JwtPayloadDTO(id: $auth['id'], email: $auth['email']);
        $accessToken = $this->jwtService->generateAccessToken(payload: $payload);
        $refreshToken = $this->jwtService->generateRefreshToken(payload: $payload);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'role' => $auth['role']
        ];
    }

    public function updateAuth(string $token, int $id, string $col, string $oldPassword, string $newValue): bool
    {
        $auth = $this->authRepository->read(condition: 'id', value: $id);
        if (!$auth) throw new Exception(message: "Utente non trovato.");
        $jwt = $this->jwtService->validateJwt(jwt: $token);
        if (!$jwt) throw new Exception(message: "Token non valido.");
        $comparePassword = $this->argonProvider->comparePassword(data: $oldPassword, encrypted: $auth['password']);
        if (!$comparePassword) throw new Exception(message: "Password non valida.");
        if (!in_array(needle: $col, haystack: ['email', 'password'])) throw new Exception(message: "Campo aggiornabile non valido.");
        if ($col === 'email' && $auth['email'] === $newValue) throw new Exception(message: "La nuova email non può essere uguale a quella attuale.");
        $samePassword = $this->argonProvider->comparePassword(data: $newValue, encrypted: $auth['password']);
        if ($col === 'password' && $samePassword === true) throw new Exception(message: "La nuova password non può essere uguale a quella attuale.");

        if ($col === 'password') {
            validatePassword(password: $newValue);
            $newValue = $this->argonProvider->hashPassword(data: $newValue);
        }

        try {
            $this->transactionProvider->beginTransaction();
            $this->authRepository->update(id: $id, col: $col, value: $newValue);
            $this->transactionProvider->commit();
            return true;
        } catch (Exception $e) {
            $this->transactionProvider->rollBack();
            throw new Exception(message: "Errore nell'aggiornamento: " . $e->getMessage());
        }
    }

    public function deleteAuth(string $token, int $id, string $password): bool
    {
        $auth = $this->authRepository->read(condition: 'id', value: $id);
        if (!$auth) throw new Exception(message: "Utente non trovato.");
        $this->jwtService->validateJwt(jwt: $token);
        if (!$this->jwtService->validateJwt(jwt: $token)) throw new Exception(message: "Token non valido.");
        $comparePassword = $this->argonProvider->comparePassword(data: $password, encrypted: $auth['password']);
        if (!$comparePassword) throw new Exception(message: "Password non valida.");

        try {
            $this->transactionProvider->beginTransaction();
            $this->authRepository->delete(id: $id);
            $this->transactionProvider->commit();
            return true;
        } catch (Exception $e) {
            $this->transactionProvider->rollBack();
            throw new Exception(message: "Errore nell'eliminazione: " . $e->getMessage());
        }
    }
}
