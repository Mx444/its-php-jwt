<?php
session_start();
require_once __DIR__ . '/../../auth/auth.controller.php';

$isAuthenticated = isset($_SESSION['token']);
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
    <title>Accedi - ITS Steve Jobs Accademy</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #212121;
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        .logo {
            margin-bottom: 50px;
        }

        .logo h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #58a6ff;
        }

        .form-container {
            background-color: #2e2e2e;
            padding: 40px 40px;
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }

        .form-container h2 {
            text-align: center;
            color: #fff;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #555;
            background-color: #333;
            color: #fff;
            font-size: 1rem;
        }

        input:focus {
            outline: none;
            border-color: #58a6ff;
            background-color: #444;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #58a6ff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1.2rem;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background-color: #4a91cc;
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

        .link {
            color: #58a6ff;
            text-decoration: none;
            font-size: 1rem;
            display: block;
            text-align: center;
            margin-top: 15px;
        }

        footer {
            position: absolute;
            bottom: 10px;
            font-size: 0.9rem;
            color: #bbb;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="logo">
        <h1>ITS Steve Jobs Accademy</h1>
    </div>

    <div class="form-container">
        <h2>Accedi al tuo account</h2>

        <?php if (isset($_SESSION['error'])) : ?>
            <p class="error-message"><?php echo $_SESSION['error']; ?></p>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])) : ?>
            <p class="success-message"><?php echo $_SESSION['success']; ?></p>
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
        <p>&copy; 2025 ITS Steve Jobs Accademy - Tutti i diritti riservati</p>
    </footer>

</body>

</html>