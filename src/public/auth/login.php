<?php
session_start();
require_once __DIR__ . '/../../auth/auth.controller.php';

$isAuthenticated = isset($_SESSION['access_token']);
if ($isAuthenticated) {
    header("Location: ../dashboard/index.php");
    exit();
}

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
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <div class="logo">
        <h1>ITS Steve Jobs Academy</h1>
    </div>

    <div class="form-container">
        <h2>Accedi al tuo account</h2>

        <?php if (isset($_SESSION['error'])) : ?>
            <p class="error-message"><?= $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])) : ?>
            <p class="success-message"><?= $_SESSION['success']; ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Accedi</button>
        </form>

        <a href="register.php" class="link">Non hai un account? Registrati</a>
    </div>

    <footer>
        <p>&copy; 2025 ITS Steve Jobs Academy - Tutti i diritti riservati</p>
    </footer>

</body>

</html>