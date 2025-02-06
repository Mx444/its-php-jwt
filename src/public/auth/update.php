<?php
session_start();

require_once __DIR__ . '/../../auth/auth.controller.php';

if (!isset($_SESSION['access_token'])) {
    header("Location: ./login.php");
    exit();
}

$authController = new AuthController();
if (isset($_POST['updateEmail'])) {
    $data = [
        'newEmail' => $_POST['newEmail'],
        'oldPassword' => $_POST['oldPassword'],
    ];
    $authController->updateEmail($data);
}

if (isset($_POST['updatePassword'])) {
    $data = [
        'oldPassword' => $_POST['oldPassword'],
        'newPassword' => $_POST['newPassword'],
    ];
    $authController->updatePassword($data);
}

if (isset($_POST['deleteAuth'])) {
    $data = [
        'password' => $_POST['password']
    ];
    $authController->deleteAuth($data);
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
    <title>Gestione Account - ITS Steve Jobs Academy</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <div class="dashboard-container">
        <h2>Gestione del Tuo Account</h2>

        <div class="form-container">
            <?php if (isset($_SESSION['error'])) : ?>
                <p class="error-message"><?php echo $_SESSION['error']; ?></p>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['message'])) : ?>
                <p class="success-message"><?php echo $_SESSION['message']; ?></p>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <form method="POST">
                <h3>Modifica Email</h3>
                <input type="email" name="newEmail" placeholder="Nuova Email" required>
                <input type="password" name="oldPassword" placeholder="Password Corrente" required>
                <button type="submit" name="updateEmail">Aggiorna Email</button>
            </form>

            <form method="POST">
                <h3>Modifica Password</h3>
                <input type="password" name="oldPassword" placeholder="Password Corrente" required>
                <input type="password" name="newPassword" placeholder="Nuova Password" required>
                <button type="submit" name="updatePassword">Aggiorna Password</button>
            </form>

            <form method="POST">
                <h3>Elimina Account</h3>
                <input type="password" name="password" placeholder="Password per confermare" required>
                <button type="submit" name="deleteAuth">Elimina Account</button>
            </form>

            <form method="POST">
                <button type="submit" name="logout" class="logout-button">Logout</button>
            </form>
        </div>

        <a href="../dashboard/index.php" class="back-link">Torna alla Home</a>
    </div>

</body>

</html>