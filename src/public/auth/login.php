<?php
session_start();

require_once __DIR__ . '/../../auth/auth.controller.php';
require_once __DIR__ . '/../../utils/token.utils.php';

isAuthenticated();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new AuthController();
    $data = [
        'email' => $_POST['email'],
        'password' => $_POST['password']
    ];
    $controller->login($data);
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accedi - ITS Steve Jobs Academy</title>
    <link rel="stylesheet" href="../css/auth/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="form-container w-100" style="max-width: 400px;">
        <h2 class="text-center mb-4">Accedi al tuo account</h2>

        <?php if (isset($_SESSION['error'])) : ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])) : ?>
            <div class="alert alert-success"><?= $_SESSION['success']; ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form method="POST" class="bg-light p-4 rounded shadow-sm">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-outline-primary w-100">Accedi</button>
        </form>

        <div class="text-center mt-3">
            <a href="register.php" class="btn btn-link">Non hai un account? Registrati</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>