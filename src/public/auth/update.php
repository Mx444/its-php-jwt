<?php
session_start();

require_once __DIR__ . '/../../auth/auth.controller.php';
require_once __DIR__ . '/../../utils/token.utils.php';
require_once __DIR__ . '/../../utils/role.utils.php';

isNotAuthenticated();
$role = isAdminJWT();
$authController = new AuthController();
$users = $authController->getAuths();

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

if (isset($_POST['updateRoleById'])) {
    $data = [
        'id' => $_POST['id'],
        'newRole' => $_POST['newRole']
    ];
    $authController->updateRoleById($data);
}

if (isset($_POST['deleteAuthById'])) {
    $data = [
        'id' => $_POST['id']
    ];
    $authController->deleteAuthById($data);
}
if (isset($_POST['enableAuthById'])) {
    $data = [
        'id' => $_POST['id']
    ];
    $authController->enableAuthById($data);
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
    <link rel="stylesheet" href="../css/auth/style.css">
</head>

<body>

    <div class="dashboard-container">
        <div class="form-container">
            <?php if (isset($_SESSION['error'])) : ?>
                <p class="error-message"><?php echo $_SESSION['error']; ?></p>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['message'])) : ?>
                <p class="success-message"><?php echo $_SESSION['message']; ?></p>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <div class="users">
                <?php if ($role == true) : ?>
                    <h2>Gestione Utenti</h2>
                    <?php foreach ($users as $user) : ?>
                        <div class="user">
                            <h2>Email : <?= htmlspecialchars($user['email']) ?></h2>
                            <p>Ruolo : <?= htmlspecialchars($user['role']) ?> </p>
                            <p>Registrato il : <?= htmlspecialchars($user['created_At']) ?></p>
                            <p>Aggiornato il : <?= htmlspecialchars($user['updated_At']) ?></p>
                            <p>Eliminato il : <?= htmlspecialchars($user['deleted_At']) ?></p>
                            <form action="./update.php" method="post" class="update-user-form">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <input type="text" name="newRole" placeholder="Nuovo ruolo">
                                <button type="submit" name="updateRoleById">Aggiorna ruolo</button>
                                <?php if ($user['deleted_At'] == null) : ?>
                                    <button type="submit" name="deleteAuthById">Elimina</button>
                                <?php else : ?>
                                    <button type="submit" name="enableAuthById">Abilita</button>
                                <?php endif; ?>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>

            <form method="POST">
                <br> <br> <br>
                <h1>Aggiorna il tuo profilo</h1>
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

        <a href="../index.php" class="back-link">Torna alla Home</a>
    </div>

</body>

</html>