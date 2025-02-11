<?php

require_once __DIR__ . '/../products/providers/products.service.php';
require_once __DIR__ . '/../utils/response.utils.php';
require_once __DIR__ . '/../utils/code-message.utils.php';
require_once __DIR__ . '/../utils/role.utils.php';
class ProductsController
{
    private ProductsService $productsService;

    public function __construct()
    {
        $this->productsService = new ProductsService();
    }

    public function addProduct(array $data): void
    {
        if (validateRequiredFields(data: $data, requiredFields: ['name', 'description', 'price'], errorMessage: 'Nome, descrizione e prezzo sono obbligatori.', location: './index.php')) return;
        $name = $data['name'];
        $description = $data['description'];
        $price = $data['price'];
        try {
            $this->productsService->addProduct(name: $name, description: $description, price: $price);
            sendResponse(statusCode: 201, type: 'success', message: 'Prodotto aggiunto con successo.', location: './index.php');
        } catch (Exception $e) {
            sendResponse(statusCode: 400, type: 'error', message: $e->getMessage(), location: './index.php');
        }
    }

    public function getAllProducts(): array
    {
        try {
            if (isAdmin()) return $this->productsService->getAllProducts(bool: true);
            return  $this->productsService->getAllProducts(bool: 1);
        } catch (Exception $e) {
            sendResponse(statusCode: 400, type: 'error', message: $e->getMessage(), location: './index.php');
            return [];
        }
    }

    public function updateProductName($data)
    {
        if (validateRequiredFields(data: $data, requiredFields: ['id', 'newValue'], errorMessage: 'ID e nome sono obbligatori.', location: './index.php')) return;
        $id = $data['id'];
        $newValue = $data['newValue'];
        try {
            $this->productsService->updateProduct(id: $id, col: 'name', newValue: $newValue);
            sendResponse(statusCode: 201, type: 'success', message: 'Nome prodotto aggiornato con successo.', location: './index.php');
        } catch (Exception $e) {
            sendResponse(statusCode: 400, type: 'error', message: $e->getMessage(), location: './index.php');
        }
    }

    public function updateProductDescription($data)
    {
        if (validateRequiredFields(data: $data, requiredFields: ['id', 'newValue'], errorMessage: 'ID e descrizione sono obbligatori.', location: './index.php')) return;
        $id = $data['id'];
        $newValue = $data['newValue'];
        try {
            $this->productsService->updateProduct(id: $id, col: 'description', newValue: $newValue);
            sendResponse(statusCode: 201, type: 'success', message: 'Descrizione prodotto aggiornata con successo.', location: './index.php');
        } catch (Exception $e) {
            sendResponse(statusCode: 400, type: 'error', message: $e->getMessage(), location: './index.php');
        }
    }

    public function updateProductPrice($data)
    {
        if (validateRequiredFields(data: $data, requiredFields: ['id', 'newValue'], errorMessage: 'ID e prezzo sono obbligatori.', location: './index.php')) return;
        $id = $data['id'];
        $newValue = $data['newValue'];
        try {
            $this->productsService->updateProduct(id: $id, col: 'price', newValue: $newValue);
            sendResponse(statusCode: 201, type: 'success', message: 'Prezzo prodotto aggiornato con successo.', location: './index.php');
        } catch (Exception $e) {
            sendResponse(statusCode: 400, type: 'error', message: $e->getMessage(), location: './index.php');
        }
    }

    public function updateProductVisibility($data)
    {
        if (validateRequiredFields(data: $data, requiredFields: ['id', 'newValue'], errorMessage: 'ID e visibilità sono obbligatori.', location: './index.php')) return;
        $id = $data['id'];
        $newValue = $data['newValue'];
        try {
            $this->productsService->updateProduct(id: $id, col: 'visible', newValue: $newValue);
            sendResponse(statusCode: 201, type: 'success', message: 'Visibilità prodotto aggiornata con successo.', location: './index.php');
        } catch (Exception $e) {
            sendResponse(statusCode: 400, type: 'error', message: $e->getMessage(), location: './index.php');
        }
    }

    public function deleteProduct($data)
    {
        if (validateRequiredFields(data: $data, requiredFields: ['id'], errorMessage: 'ID è obbligatorio.', location: './index.php')) return;
        $id = $data['id'];
        try {
            $this->productsService->deleteProduct($id);
            sendResponse(statusCode: 201, type: 'success', message: 'Prodotto eliminato con successo.', location: './index.php');
        } catch (Exception $e) {
            sendResponse(statusCode: 400, type: 'error', message: $e->getMessage(), location: './index.php');
        }
    }
}
