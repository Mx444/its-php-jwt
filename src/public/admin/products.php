<?php
session_start();

require_once __DIR__ . '/../../products/products.controller.php';

if (!isset($_SESSION['access_token'])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SESSION['roles'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$productsController = new ProductsController();
$getAllProducts = $productsController->getAllProducts();

if (isset($_POST['addProducts'])) {
    $controller = new ProductsController();
    $data = [
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'price' => $_POST['price']
    ];
    $controller->addProduct($data);
}
if (isset($_POST['updateProduct'])) {
    $data = [
        'id' => $_POST['id'],
        'col' => $_POST['col'],
        'newValue' => $_POST['newValue']
    ];
    $productsController->updateProduct($data);
}

if (isset($_POST['deleteProduct'])) {
    $data = [
        'id' => $_POST['id'],

    ];
    $productsController->deleteProduct($data);
}

?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione prodotti - ITS Steve Jobs Academy</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <div class="logo">
        <h1>ITS Steve Jobs Academy</h1>
    </div>

    <div class="form-container">
        <h2>Gestione prodotti</h2>

        <?php if (isset($_SESSION['error'])) : ?>
            <p class="error-message"><?= $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])) : ?>
            <p class="success-message"><?= $_SESSION['success']; ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="name" placeholder="Nome" required>
            <input type="text" name="description" placeholder="Descrizione" required>
            <input type="number" name="price" placeholder="Prezzo" required>
            <button type="submit" name="addProducts">Aggiungi prodotto</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrizione</th>
                    <th>Prezzo</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($getAllProducts as $product) : ?>
                    <tr>
                        <td><?= $product['id']; ?></td>
                        <td><?= $product['name']; ?></td>
                        <td><?= $product['description']; ?></td>
                        <td><?= $product['price']; ?></td>
                        <td>

                            <form method="POST">
                                <input type="hidden" name="id" value="<?= $product['id']; ?>">
                                <button type="submit" name="deleteProduct">Elimina</button>
                            </form>

                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</html>