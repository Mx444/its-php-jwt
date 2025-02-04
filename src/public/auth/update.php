<?php
session_start();

if (!isset($_SESSION['token'])) {
    header(header: "Location: index.php");
    exit();
}

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../auth/controllers/auth.controller.php';

$authController = new AuthController();

if (isset($_POST['updateEmail'])) {
    $data = [
        'newEmail' => $_POST['newEmail'],
        'oldPassword' => $_POST['oldPassword'],
    ];
    $authController->updateEmail(data: $data);
}

if (isset($_POST['updatePassword'])) {
    $data = [
        'oldPassword' => $_POST['oldPassword'],
        'newPassword' => $_POST['newPassword'],
    ];
    $authController->updatePassword(data: $data);
}

if (isset($_POST['deleteAuth'])) {
    $data = [
        'password' => $_POST['password']
    ];
    $authController->deleteAuth(data: $data);
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
    <title>Gestione Account - ITS Steve Jobs Accademy</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #121212;
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            box-sizing: border-box;
        }

        .dashboard-container {
            background-color: #2e2e2e;
            padding: 40px;
            border-radius: 15px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
            margin-top: 50px;
        }

        .dashboard-container h2 {
            text-align: center;
            color: #fff;
            margin-bottom: 30px;
            font-size: 2.2rem;
            font-weight: 600;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-container h3 {
            color: #58a6ff;
            font-size: 1.2rem;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .form-container input,
        .form-container button {
            width: 100%;
            padding: 14px;
            margin: 8px 0;
            border-radius: 10px;
            border: 1px solid #444;
            background-color: #333;
            color: #fff;
            font-size: 1rem;
            transition: all 0.3s;
            box-sizing: border-box;
        }

        .form-container input:focus,
        .form-container button:focus {
            outline: none;
            border-color: #58a6ff;
            background-color: #444;
        }

        .form-container button {
            background-color: #58a6ff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: #4a91cc;
        }

        .form-container button:active {
            background-color: #3f80b3;
        }

        .error-message {
            color: #ff4d4d;
            font-size: 1rem;
            text-align: center;
            margin-bottom: 20px;
        }

        .success-message {
            color: #58a6ff;
            font-size: 1rem;
            text-align: center;
            margin-bottom: 20px;
        }

        footer {
            font-size: 0.9rem;
            color: #bbb;
            text-align: center;
            width: 100%;
            margin-top: 20px;
        }

        .footer-link {
            color: #58a6ff;
            text-decoration: none;
        }

        .back-link {
            color: #58a6ff;
            text-decoration: none;
            font-size: 1rem;
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
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
                <button type="submit" name="logout" style="background-color: #1e3a6e;">Logout</button>
            </form>
        </div>

        <a href="../dashboard/index.php" class="back-link">Torna alla Home</a>
    </div>

    <footer>
        <p>&copy; 2025 ITS Steve Jobs Accademy - Tutti i diritti riservati | <a href="#" class="footer-link">Privacy Policy</a></p>
    </footer>

</body>

</html>