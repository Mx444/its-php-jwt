<?php
require_once __DIR__ . '/../utils/token.utils.php';
require_once __DIR__ . '/../utils/role.utils.php';
require_once __DIR__ . '/../products/products.controller.php';
require_once __DIR__ . '/../auth/auth.controller.php';

isNotAuthenticated();
$role = isAdminJWT();
$controller = new ProductsController();
$authController = new AuthController();
$products = $controller->getAllProducts();

$actions = [
    'addProduct' => ['name', 'description', 'price'],
    'updateName' => ['id', 'newValue'],
    'updateDescription' => ['id', 'newValue'],
    'updatePrice' => ['id', 'newValue'],
    'updateVisibility' => ['id', 'newValue'],
    'deleteProduct' => ['id'],
    'logout' => []
];

foreach ($actions as $action => $fields) {
    if (isset($_POST[$action])) {
        $data = array_combine($fields, array_map(fn($field) => $_POST[$field], $fields));
        try {
            if ($action === 'logout') {
                $authController->logout();
            } else {
                $controller->{$action}($data);
            }
            header("Location: index.php");
            exit();
        } catch (Exception $e) {
            error_log("Errore durante l'esecuzione dell'azione $action: " . $e->getMessage());
            header("Location: index.php?error=" . urlencode($e->getMessage()));
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Prodotti</title>
    <link rel="stylesheet" href="./css/index/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <a class="navbar-brand me-4" href="#">Prodotti</a>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="./auth/update.php">Aggiorna Profilo</a>
                    </li>
                </ul>
            </div>

            <div class="d-flex">
                <form method="post" class="d-flex">
                    <button type="submit" name="logout" class="btn btn-outline-danger">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Gestione Prodotti</h1>
        <?php if (isset($_SESSION['success'])) : ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])) : ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php endif; ?>
        <?php if ($role) : ?>
            <form method="post" class="mb-4 p-3 bg-light rounded">
                <h3>Aggiungi Prodotto</h3>
                <div class="mb-3">
                    <input type="text" name="name" class="form-control" placeholder="Nome" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="description" class="form-control" placeholder="Descrizione" required>
                </div>
                <div class="mb-3">
                    <input type="number" name="price" class="form-control" placeholder="Prezzo" required>
                </div>
                <button type="submit" name="addProduct" class="btn btn-primary">Aggiungi</button>
            </form>
        <?php endif; ?>
        <div class="row">
            <?php foreach ($products as $product) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
                            <p class="card-text"><strong><?= htmlspecialchars($product['price']) ?> €</strong></p>
                            <?php if ($role) : ?>
                                <form method="post" class="mt-3">
                                    <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                    <div class="mb-3">
                                        <input type="text" name="newValue" class="form-control" placeholder="Nuovo valore">
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" name="updateName" class="btn btn-sm btn-outline-secondary">Aggiorna Nome</button>
                                        <button type="submit" name="updateDescription" class="btn btn-sm btn-outline-secondary">Aggiorna Descrizione</button>
                                        <button type="submit" name="updatePrice" class="btn btn-sm btn-outline-secondary">Aggiorna Prezzo</button>
                                        <button type="submit" name="updateVisibility" class="btn btn-sm btn-outline-warning">Cambia Visibilità</button>
                                        <button type="submit" name="deleteProduct" class="btn btn-sm btn-outline-danger">Elimina</button>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>