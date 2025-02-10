<?php

require_once __DIR__ . '/../products/providers/products.service.php';

class ProductsController
{
    private ProductsService $productsService;

    public function __construct()
    {
        $this->productsService = new ProductsService();
    }

    public function addProduct(array $data): void
    {
        if (empty($data['name']) || empty($data['description']) || empty($data['price'])) {
            $_SESSION['error'] = 'Nome, descrizione e prezzo sono obbligatori.';
            http_response_code(400);
            header("Location: products.php");
            exit();
        }

        $name = $data['name'];
        $description = $data['description'];
        $price = $data['price'];

        try {
            $this->productsService->addProduct($name, $description, $price);
            http_response_code(201);
            $_SESSION['success'] = 'Prodotto creato con successo.';
            header("Location: products.php");
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            http_response_code(400);
            header("Location: products.php");
            exit();
        }
    }

    public function getAllProducts(): array
    {
        try {
            return  $this->productsService->getAllProducts();
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            http_response_code(500);
            header("Location: products.php");
            exit();
        }
    }

    public function updateProduct($data)
    {
        if (empty($data['id']) || empty($data['col']) || empty($data['newValue'])) {
            $_SESSION['error'] = 'ID, colonna e nuovo valore sono obbligatori.';
            http_response_code(400);
            header("Location: products.php");
            exit();
        }

        $id = $data['id'];
        $col = $data['col'];
        $newValue = $data['newValue'];

        try {
            $this->productsService->updateProduct($id, $col, $newValue);
            http_response_code(201);
            $_SESSION['success'] = 'Prodotto aggiornato con successo.';
            header("Location: products.php");
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            http_response_code(400);
            header("Location: products.php");
            exit();
        }
    }
    public function deleteProduct($data)
    {
        if (empty($data['id'])) {
            $_SESSION['error'] = 'ID obbligatorio.';
            http_response_code(400);
            header("Location: products.php");
            exit();
        }

        $id = $data['id'];

        try {
            $this->productsService->deleteProduct($id);
            http_response_code(201);
            $_SESSION['success'] = 'Prodotto eliminato con successo.';
            header("Location: products.php");
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            http_response_code(400);
            header("Location: products.php");
            exit();
        }
    }
}
