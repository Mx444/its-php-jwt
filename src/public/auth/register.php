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
    $controller->register($data);
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrati - ITS Steve Jobs Academy</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <div class="logo">
        <h1>ITS Steve Jobs Academy</h1>
    </div>

    <div class="form-container">
        <h2>Registrati al tuo account</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message"><?= $_SESSION['error']; ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="success-message"><?= $_SESSION['message']; ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Registrati</button>
        </form>

        <a href="login.php" class="link">Hai già un account? Accedi</a>
    </div>

    <footer>
        <p>&copy; 2025 ITS Steve Jobs Academy - Tutti i diritti riservati</p>
    </footer>

</body>

</html>