<?php

namespace Auth\Repositories;

use PDO;
use InvalidArgumentException;

class AuthRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
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
        return (int) $this->db->lastInsertId() ?: null;
    }

    /**
     * Reads a user's data.
     * 
     * @param string $condition The column to search by.
     * @param string $value The value to search for.
     * @return array The user's data.
     */
    public function read($condition, $value): array
    {
        if (!in_array(needle: $condition, haystack: ['id', 'email'])) {
            throw new InvalidArgumentException(message: "Colonna non valida: $condition");
        }
        $query = "SELECT * FROM auth WHERE $condition = :value";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['value' => $value]);
        return $stmt->fetchAll() ?: [];
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
        return $stmt->rowCount() ?: null;
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
        return $stmt->rowCount() ?: null;
    }
}
