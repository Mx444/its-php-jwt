<?php
session_start();

if (!isset($_SESSION['token'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once __DIR__ . '/../../task/controllers/task.controller.php';

$taskController = new TaskController();

$tasks = $taskController->getTasks();

if (isset($_POST['addTask'])) {
    $data = [
        'description' => $_POST['newTask'],
    ];
    $taskController->createTask($data);
}

if (isset($_POST['removeTask'])) {
    $data = [
        'id' => $_POST['taskIdToRemove']
    ];
    $taskController->deleteTask($data);
}

if (isset($_POST['updateDescription'])) {
    $data = [
        'id' => $_POST['taskId'],
        'value' => $_POST['value']
    ];
    $taskController->updateDescription($data);
}


if (isset($_POST['updateStatus'])) {
    $data = [
        'id' => $_POST['taskId'],
        'status' => $_POST['currentStatus']

    ];
    $taskController->updateStatus($data);
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ITS Steve Jobs Accademy</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #181818;
            color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        .navbar {
            background-color: #121212;
            width: 100%;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            font-weight: 600;
        }

        .navbar .dropdown {
            position: relative;
            display: inline-block;
        }

        .navbar .dropdown-content {
            display: none;
            position: absolute;
            background-color: #333;
            min-width: 160px;
            z-index: 1;
            border-radius: 8px;
            margin-top: 10px;
        }

        .navbar .dropdown:hover .dropdown-content {
            display: block;
        }

        .navbar .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .navbar .dropdown-content a:hover {
            background-color: #444;
        }

        .dashboard-container {
            margin-top: 80px;
            padding: 30px;
            background-color: #222;
            border-radius: 12px;
            width: 90%;
            max-width: 800px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            max-height: calc(100vh - 160px);
            overflow-y: auto;
        }

        h2 {
            text-align: center;
            color: #fff;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .todo-list {
            background-color: #333;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .todo-list h3 {
            color: #007bff;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .todo-item {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            margin-bottom: 10px;
            background-color: #444;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .todo-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }

        .todo-item button {
            background-color: #ff4d4d;
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .todo-item button:hover {
            background-color: #ff1a1a;
        }

        .todo-item input[type="text"] {
            padding: 10px;
            border: 1px solid #555;
            border-radius: 6px;
            background-color: #333;
            color: #fff;
            width: 60%;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .todo-item input[type="text"]:focus {
            outline: none;
            border-color: #007bff;
            background-color: #444;
        }

        .form-container input {
            padding: 12px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #555;
            background-color: #333;
            color: #fff;
            font-size: 1rem;
            width: calc(100% - 30px);
            margin-bottom: 20px;
        }

        .form-container input:focus {
            outline: none;
            border-color: #007bff;
            background-color: #444;
        }

        .add-task-btn {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 12px 16px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .add-task-btn:hover {
            background-color: #0056b3;
        }

        footer {
            position: absolute;
            bottom: 10px;
            font-size: 0.9rem;
            color: #888;
            text-align: center;
        }

        button.logout-btn {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 8px 20px;
            border-radius: 6px;
            cursor: pointer;
        }

        button.logout-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <a href="#">Dashboard</a>
        <div class="dropdown">
            <a href="javascript:void(0)">Account</a>
            <div class="dropdown-content">
                <a href="../auth/update.php">Modifica Account</a>
            </div>
        </div>
        <form method="POST" style="margin-left: 20px;">
            <button type="submit" name="logout" class="logout-btn">Logout</button>
        </form>
    </div>

    <div class="dashboard-container">
        <h2>Benvenuto nella Dashboard</h2>


        <?php
        if (isset($_SESSION['success'])) {
            echo '<div style="background-color: #28a745; color: white; padding: 10px; border-radius: 6px; margin-bottom: 20px;">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }

        if (isset($_SESSION['error'])) {
            echo '<div style="background-color: #dc3545; color: white; padding: 10px; border-radius: 6px; margin-bottom: 20px;">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>
        <div class="todo-list">
            <h3>La tua To-Do List</h3>
            <form method="POST" class="form-container">
                <input type="text" name="newTask" placeholder="Aggiungi un nuovo task..." required>
                <button type="submit" name="addTask" class="add-task-btn">Aggiungi</button>
            </form>

            <?php foreach ($tasks as $task): ?>
                <div class="todo-item">
                    <span><?= $task['description'] ?></span>
                    <form method="POST" style="display: inline;">
                        <button type="submit" name="removeTask" value="1">Rimuovi</button>
                        <input type="hidden" name="taskIdToRemove" value="<?= $task['id'] ?>">
                    </form>
                    <form method="POST" style="display: inline;">
                        <input type="text" name="value" placeholder="Nuova descrizione" required>
                        <input type="hidden" name="taskId" value="<?= $task['id'] ?>">
                        <button type="submit" name="updateDescription">Aggiorna</button>
                    </form>

                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="taskId" value="<?= $task['id'] ?>">
                        <input type="hidden" name="currentStatus" value="<?= $task['status'] ?>">
                        <button type="submit" name="updateStatus">
                            <?= $task['status'] ? 'Disattiva' : 'Attiva' ?>
                        </button>
                    </form>


                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 ITS Steve Jobs Accademy - Tutti i diritti riservati</p>
    </footer>

</body>

</html>