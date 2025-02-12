<?php

class ProductsRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(string $name, string $description, float $price): int
    {
        $query = "INSERT INTO products (name, description, price) VALUES (:name, :description, :price)";
        $stmt = $this->db->prepare(query: $query);
        $stmt->execute(params: ['name' => $name, 'description' => $description, 'price' => $price]);
        return $this->db->lastInsertId() ?: null;
    }
    public function read($id): mixed
    {
        $query = "SELECT * FROM products WHERE id = :id AND visible = 1";
        $stmt = $this->db->prepare(query: $query);
        $stmt->execute(params: ['id' => $id]);
        return $stmt->fetch() ?: [];
    }

    public function readAll(?bool $bool): array
    {
        if ($bool === true) {
            $query = "SELECT * FROM products";
            $stmt = $this->db->prepare(query: $query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        }

        $query = "SELECT * FROM products WHERE visible = 1";
        $stmt = $this->db->prepare(query: $query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
    public function update($id, $col, $newValue)
    {
        if (!in_array(needle: $col, haystack: ['name', 'description', 'price', 'visible'])) throw new Exception(message: "Colonna non valida");
        $query = "UPDATE products SET $col = :newValue WHERE id = :id";
        $stmt = $this->db->prepare(query: $query);
        return $stmt->execute(params: ['id' => $id, 'newValue' => $newValue]);
    }

    public function delete(int $id): bool
    {
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = $this->db->prepare(query: $query);
        return $stmt->execute(params: ['id' => $id]);
    }
}
