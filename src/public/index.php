<?php
session_start();

require_once __DIR__ . '/../utils/token.utils.php';
require_once __DIR__ . '/../utils/role.utils.php';
require_once __DIR__ . '/../products/products.controller.php';
require_once __DIR__ . '/../auth/auth.controller.php';

isNotAuthenticated();
$role = isAdminJWT();
$controller = new ProductsController();
$products = $controller->getAllProducts();
$authController = new AuthController();

if (isset($_POST['addProduct'])) {
    $data = [
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'price' => $_POST['price']
    ];
    $controller->addProduct(data: $data);
}

if (isset($_POST['updateName'])) {
    $data = [
        'id' => $_POST['id'],
        'newValue' => $_POST['newValue']
    ];
    $controller->updateProductName(data: $data);
}

if (isset($_POST['updateDescription'])) {
    $data = [
        'id' => $_POST['id'],
        'newValue' => $_POST['newValue']
    ];
    $controller->updateProductDescription(data: $data);
}

if (isset($_POST['updatePrice'])) {
    $data = [
        'id' => $_POST['id'],
        'newValue' => $_POST['newValue']
    ];
    $controller->updateProductPrice(data: $data);
}

if (isset($_POST['updateVisibility'])) {
    $data = [
        'id' => $_POST['id'],
        'newValue' => $_POST['newValue']
    ];
    $controller->updateProductVisibility(data: $data);
}

if (isset($_POST['deleteProduct'])) {
    $data = [
        'id' => $_POST['id']
    ];
    $controller->deleteProduct(data: $data);
}

if (isset($_POST['logout'])) {
    $authController->logout();
}

?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prodotti</title>
    <link rel="stylesheet" href="./css/index/style.css">
</head>

<body>
    <nav class="navbar">
        <a href="./auth/update.php" class="nav-link">Aggiorna Profilo</a>
        <form action="index.php" method="post" class="logout-form">
            <button type="submit" name="logout">Logout</button>
        </form>
    </nav>

    <h1>Prodotti</h1>
    <?php if ($role == true) : ?>
        <form method="POST" class="add-product-form">
            <input type="text" name="name" placeholder="Nome" required>
            <input type="text" name="description" placeholder="Descrizione" required>
            <input type="number" name="price" placeholder="Prezzo" required>
            <button type="submit" name="addProduct">Aggiungi prodotto</button>
        </form>
    <?php endif; ?>

    <div class="products">
        <?php foreach ($products as $product) : ?>
            <div class="product">
                <h2><?= htmlspecialchars($product['name']) ?></h2>
                <p><?= htmlspecialchars($product['description']) ?></p>
                <p><?= htmlspecialchars($product['price']) ?> €</p>
                <?php if ($role == true) : ?>
                    <form action="index.php" method="post" class="update-product-form">
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        <input type="text" name="newValue" placeholder="Nuovo valore">
                        <button type="submit" name="updateName">Aggiorna nome</button>
                        <button type="submit" name="updateDescription">Aggiorna descrizione</button>
                        <button type="submit" name="updatePrice">Aggiorna prezzo</button>
                        <button type="submit" name="updateVisibility">Aggiorna visibilità</button>
                        <button type="submit" name="deleteProduct">Elimina</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>