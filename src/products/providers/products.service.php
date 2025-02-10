<?php
require_once __DIR__ . '/../..//database/connection.provider.php';
require_once __DIR__ . '/../..//database/transaction.provider.php';
require_once __DIR__ . '/../../products/products.repository.php';

class ProductsService
{
    private DatabaseService $connectionProvider;
    private TransactionProvider $transactionProvider;
    private ProductsRepository $productsRepository;

    private PDO $db;

    public function __construct()
    {
        $this->connectionProvider = new DatabaseService();
        $this->db = $this->connectionProvider->getConnection();
        $this->transactionProvider = new TransactionProvider(databaseService: $this->db);
        $this->productsRepository = new ProductsRepository(db: $this->db);
    }

    /**
     * Creates a new product.
     * 
     * @param string $name The product's name.
     * @param string $description The product's description.
     * @param float $price The product's price.
     * @return int The ID of the newly created product.
     */

    public function addProduct(string $name, string $description, float $price): int
    {
        try {
            $this->transactionProvider->beginTransaction();
            $productId = $this->productsRepository->create(name: $name, description: $description, price: $price);
            $this->transactionProvider->commit();
            return $productId;
        } catch (Exception $e) {
            $this->transactionProvider->rollBack();
            throw new Exception(message: "Errore nella creazione del prodotto: " . $e->getMessage());
        }
    }

    public function getAllProducts(): array
    {
        try {
            return $this->productsRepository->readAll();
        } catch (Exception $e) {
            throw new Exception("Errore nella lettura dei prodotti: " . $e->getMessage());
        }
    }

    /**
     * Retrieves a product by ID.
     *
     * @param int $id The ID of the product to retrieve.
     * @return array|null The product data or null if not found
     * .
     */

    public function getProduct(int $id): ?array
    {
        try {
            return $this->productsRepository->read($id);
        } catch (Exception $e) {
            throw new Exception("Errore nella lettura del prodotto: " . $e->getMessage());
        }
    }

    public function updateProduct($id, $col, $newValue)
    {

        try {
            $this->transactionProvider->beginTransaction();
            $result = $this->productsRepository->update($id, $col, $newValue);
            $this->transactionProvider->commit();
            return $result;
        } catch (Exception $e) {
            $this->transactionProvider->rollBack();
            throw new Exception("Errore nell'aggiornamento del prodotto: " . $e->getMessage());
        }
    }
    public function deleteProduct(int $id): bool
    {
        try {
            $this->transactionProvider->beginTransaction();
            $result = $this->productsRepository->delete($id);
            $this->transactionProvider->commit();
            return $result;
        } catch (Exception $e) {
            $this->transactionProvider->rollBack();
            throw new Exception("Errore nella cancellazione del prodotto: " . $e->getMessage());
        }
    }
}
