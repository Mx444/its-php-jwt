<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITS Steve Jobs Accadem - Benvenuto</title>
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
            text-align: center;
        }

        .logo h1 {
            font-size: 3rem;
            font-weight: bold;
            color: #58a6ff;
            margin-bottom: 30px;
        }

        .intro {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: #bbb;
        }

        .cta-container {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 30px;
        }

        .cta-button {
            padding: 12px 25px;
            border-radius: 6px;
            background-color: #58a6ff;
            color: #212121;
            font-size: 1.2rem;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .cta-button:hover {
            background-color: #4a91cc;
        }

        footer {
            position: absolute;
            bottom: 10px;
            font-size: 0.9rem;
            color: #bbb;
        }
    </style>
</head>

<body>

    <div class="logo">
        <h1>ITS Steve Jobs Accademy</h1>
    </div>

    <div class="intro">
        <p>PHP Auth</p>
    </div>

    <div class="cta-container">
        <a href="auth/login.php" class="cta-button">Accedi</a>
        <a href="auth/register.php" class="cta-button">Registrati</a>
    </div>

    <footer>
        <p>&copy; 2025 ITS Steve Jobs Accadem - Tutti i diritti riservati</p>
    </footer>

</body>

</html>