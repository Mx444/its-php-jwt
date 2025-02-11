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

    public function getAllProducts(?bool $bool): array
    {
        try {
            return $this->productsRepository->readAll($bool);
        } catch (Exception $e) {
            throw new Exception(message: "Errore nella lettura dei prodotti: " . $e->getMessage());
        }
    }

    public function addProduct(string $name, string $description, float $price): void
    {
        try {
            $this->transactionProvider->beginTransaction();
            $this->productsRepository->create(name: $name, description: $description, price: $price);
            $this->transactionProvider->commit();
        } catch (Exception $e) {
            $this->transactionProvider->rollBack();
            throw new Exception(message: "Errore nella creazione del prodotto: " . $e->getMessage());
        }
    }

    public function updateProduct($id, $col, $newValue): bool
    {
        try {
            $this->transactionProvider->beginTransaction();
            $result = $this->productsRepository->update(id: $id, col: $col, newValue: $newValue);
            $this->transactionProvider->commit();
            return $result;
        } catch (Exception $e) {
            $this->transactionProvider->rollBack();
            throw new Exception(message: "Errore nell'aggiornamento del prodotto: " . $e->getMessage());
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
            throw new Exception(message: "Errore nella cancellazione del prodotto: " . $e->getMessage());
        }
    }
}
