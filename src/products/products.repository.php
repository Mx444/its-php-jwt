<?php

class ProductsRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Creates a new product.
     * 
     * @param string $name The product's name.
     * @param string $description The product's description.
     * @param float $price The product's price.
     * @return int The ID of the newly created product.
     */
    public function create(string $name, string $description, float $price): int
    {
        $query = "INSERT INTO products (name, description, price) VALUES (:name, :description, :price)";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['name' => $name, 'description' => $description, 'price' => $price]);
        return (int) $this->db->lastInsertId() ?: null;
    }
    public function read($id)
    {
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: [];
    }

    public function readAll()
    {

        $query = "SELECT * FROM products";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
    public function update($id, $col, $newValue)
    {
        if (!in_array($col, ['name', 'description', 'price'])) {
            throw new Exception("Colonna non valida");
        }

        $query = "UPDATE products SET $col = :newValue WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id, 'newValue' => $newValue]);
    }
    /**
     * Deletes a product.
     * 
     * @param int $id The product's ID.
     * @return bool Whether the product was deleted successfully.
     */
    public function delete(int $id): bool
    {
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }
}
