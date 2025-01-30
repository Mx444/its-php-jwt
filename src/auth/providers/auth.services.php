<?php


require_once __DIR__ . '/../../database/providers/connection.provider.php';
require_once __DIR__ . '/../../database/providers/transaction.provider.php';
require_once __DIR__ . '/../../auth/providers/bcrypt.provider.php';

class AuthService
{
    private $connectionProvider;
    private $transactionProvider;
    private $bcryptProvider;
    private $db;

    public function __construct()
    {
        $this->connectionProvider = new ConnectionProvider();
        $this->transactionProvider = new TransactionProvider($this->connectionProvider);
        $this->bcryptProvider = new BcryptProvider();
        $this->db = $this->connectionProvider->getConnection();
    }

    public function createAuth($email, $password)
    {
        global $existingAuth;
        $this->transactionProvider->beginTransaction();

        try {
            $query = "SELECT * FROM auth WHERE email = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $existingAuth = true;
            }
            $stmt->close();
        } catch (Exception $error) {
            $this->transactionProvider->rollBack();
            die("Error: " . $error->getMessage());
        }

        if ($existingAuth) {
            $this->transactionProvider->rollBack();
            return "Email already exists";
        }

        $hashedPassword = $this->bcryptProvider->hashPassword($password);

        try {
            $query = "INSERT INTO auth (email, password) VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ss", $email, $hashedPassword);
            $stmt->execute();
            $stmt->close();
        } catch (Exception $error) {
            $this->transactionProvider->rollBack();
            die("Error: " . $error->getMessage());
        }

        $this->transactionProvider->commit();
        $this->connectionProvider->closeConnection();
        return "Auth created successfully";
    }
}
