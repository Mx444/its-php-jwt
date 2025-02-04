<?php

class AuthRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Finds a user by email.
     * 
     * @param string $email The user's email.
     * @return array|null The user data or null if not found.
     */
    public function findByEmail(string $email): ?array
    {
        $query = "SELECT * FROM auth WHERE email = :email AND deleted_at IS NULL";
        $stmt = $this->db->prepare(query: $query);
        $stmt->execute(params: ['email' => $email]);
        return $stmt->fetch(mode: PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Finds a user by ID.
     * 
     * @param int $id The user's ID.
     * @return array|null The user data or null if not found.
     */
    public function findById(int $id): ?array
    {
        $query = "SELECT * FROM auth WHERE id = :id AND deleted_at IS NULL";
        $stmt = $this->db->prepare(query: $query);
        $stmt->execute(params: ['id' => $id]);
        return $stmt->fetch(mode: PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Creates a new user.
     * 
     * @param string $email The user's email.
     * @param string $hashedPassword The user's hashed password.
     * @return int The ID of the newly created user.
     */
    public function create(string $email, string $hashedPassword): int
    {
        $query = "INSERT INTO auth (email, password) VALUES (:email, :password)";
        $stmt = $this->db->prepare(query: $query);
        $stmt->execute(params: ['email' => $email, 'password' => $hashedPassword]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Updates a user's data.
     * 
     * @param int $id The user's ID.
     * @param string $col The column to update.
     * @param string $value The new value.
     * @return int The number of affected rows.
     */
    public function update(int $id, string $col, string $value): int
    {
        if (!in_array(needle: $col, haystack: ['password', 'email'])) {
            throw new InvalidArgumentException(message: "Colonna non valida: $col");
        }

        $query = "UPDATE auth SET $col = :value, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare(query: $query);
        $stmt->execute(params: ['value' => $value, 'id' => $id]);
        return $stmt->rowCount();
    }

    /**
     * Deletes a user.
     * 
     * @param int $id The user's ID.
     * @return int The number of affected rows.
     */
    public function delete(int $id): int
    {
        $query = "UPDATE auth SET deleted_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare(query: $query);
        $stmt->execute(params: ['id' => $id]);
        return $stmt->rowCount();
    }
}
