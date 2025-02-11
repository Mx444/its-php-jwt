<?php


class AuthRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(string $email, string $hashedPassword): int
    {
        $query = "INSERT INTO auth (email, password) VALUES (:email, :password)";
        $stmt = $this->db->prepare(query: $query);
        $stmt->execute(params: ['email' => $email, 'password' => $hashedPassword]);
        return (int) $this->db->lastInsertId() ?: null;
    }

    public function read($condition, $value): mixed
    {
        if (!in_array(needle: $condition, haystack: ['id', 'email'])) throw new InvalidArgumentException(message: "Colonna non valida: $condition");
        $query = "SELECT * FROM auth WHERE $condition = :value";
        $stmt = $this->db->prepare(query: $query);
        $stmt->execute(params: ['value' => $value]);
        return $stmt->fetch() ?: [];
    }

    public function readAll(): array
    {
        $query = "SELECT * FROM auth";
        $stmt = $this->db->query(query: $query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function update(int $id, string $col,  $value): int
    {
        if (!in_array(needle: $col, haystack: ['password', 'email', 'role', 'deleted_At'])) throw new InvalidArgumentException(message: "Colonna non valida: $col");
        $query = "UPDATE auth SET $col = :value, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare(query: $query);
        $stmt->execute(params: ['value' => $value, 'id' => $id]);
        return $stmt->rowCount() ?: null;
    }

    public function delete(int $id): int
    {
        $query = "UPDATE auth SET deleted_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare(query: $query);
        $stmt->execute(params: ['id' => $id]);
        return $stmt->rowCount() ?: 0;
    }
}
