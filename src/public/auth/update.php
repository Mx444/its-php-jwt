<?php
session_start();
require_once __DIR__ . '/../../auth/auth.controller.php';
require_once __DIR__ . '/../../utils/token.utils.php';
require_once __DIR__ . '/../../utils/role.utils.php';

isNotAuthenticated();
$role = isAdminJWT();
$authController = new AuthController();
$users = $authController->getAuths();

// Gestione delle azioni
$actions = [
    'updateEmail' => ['newEmail', 'oldPassword'],
    'updatePassword' => ['oldPassword', 'newPassword'],
    'deleteAuth' => ['password'],
    'updateRoleById' => ['id', 'newRole'],
    'deleteAuthById' => ['id'],
    'enableAuthById' => ['id'],
    'logout' => []
];

foreach ($actions as $action => $fields) {
    if (isset($_POST[$action])) {
        $data = array_combine(keys: $fields, values: array_map(callback: fn($field): mixed => $_POST[$field], array: $fields));
        if ($action === 'logout') {
            $authController->logout();
        } else {
            $authController->{$action}($data);
        }
        header("Location: update.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Account - ITS Steve Jobs Academy</title>
    <link rel="stylesheet" href="../css/auth/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="dashboard-container">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="../index.php">Home</a>
                <form method="post" class="d-flex">
                    <button type="submit" name="logout" class="btn btn-outline-danger">Logout</button>
                </form>
            </div>
        </nav>

        <!-- Messaggi di errore/successo -->
        <?php if (isset($_SESSION['error'])) : ?>
            <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['message'])) : ?>
            <div class="alert alert-success"><?= $_SESSION['message'] ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <!-- Gestione Utenti (solo per admin) -->
        <?php if ($role) : ?>
            <div class="users mb-5">
                <h2 class="text-center mb-4">Gestione Utenti</h2>
                <div class="row">
                    <?php foreach ($users as $user) : ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Email: <?= htmlspecialchars($user['email']) ?></h5>
                                    <p class="card-text">Ruolo: <?= htmlspecialchars($user['role']) ?></p>
                                    <p class="card-text"><small>Registrato il: <?= htmlspecialchars($user['created_At']) ?></small></p>
                                    <p class="card-text"><small>Aggiornato il: <?= htmlspecialchars($user['updated_At']) ?></small></p>
                                    <p class="card-text"><small>Eliminato il: <?= htmlspecialchars($user['deleted_At']) ?></small></p>
                                    <form method="post" class="mt-3">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <div class="mb-3">
                                            <input type="text" name="newRole" class="form-control" placeholder="Nuovo ruolo">
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button type="submit" name="updateRoleById" class="btn btn-sm btn-outline-primary">Aggiorna Ruolo</button>
                                            <?php if ($user['deleted_At'] == null) : ?>
                                                <button type="submit" name="deleteAuthById" class="btn btn-sm btn-outline-danger">Elimina</button>
                                            <?php else : ?>
                                                <button type="submit" name="enableAuthById" class="btn btn-sm btn-outline-success">Abilita</button>
                                            <?php endif; ?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Modifica Profilo -->
        <div class="form-container">
            <h1 class="text-center mb-4">Aggiorna il tuo profilo</h1>

            <!-- Modifica Email -->
            <form method="post" class="mb-4 p-4 bg-light rounded">
                <h3>Modifica Email</h3>
                <div class="mb-3">
                    <input type="email" name="newEmail" class="form-control" placeholder="Nuova Email" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="oldPassword" class="form-control" placeholder="Password Corrente" required>
                </div>
                <button type="submit" name="updateEmail" class="btn btn-primary">Aggiorna Email</button>
            </form>

            <!-- Modifica Password -->
            <form method="post" class="mb-4 p-4 bg-light rounded">
                <h3>Modifica Password</h3>
                <div class="mb-3">
                    <input type="password" name="oldPassword" class="form-control" placeholder="Password Corrente" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="newPassword" class="form-control" placeholder="Nuova Password" required>
                </div>
                <button type="submit" name="updatePassword" class="btn btn-primary">Aggiorna Password</button>
            </form>

            <!-- Elimina Account -->
            <form method="post" class="mb-4 p-4 bg-light rounded">
                <h3>Elimina Account</h3>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password per confermare" required>
                </div>
                <button type="submit" name="deleteAuth" class="btn btn-danger">Elimina Account</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>