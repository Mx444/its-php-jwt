<?php

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../providers/task.service.php';
require_once __DIR__ . '/../../auth/config/jwt.middleware.php';

class TaskController
{

    private TaskService $taskService;
    private AuthMiddleware $authMiddleware;

    public function __construct()
    {
        $this->taskService = new TaskService();
        $this->authMiddleware = new AuthMiddleware();
    }


    /**
     * Summary of createTask
     * @param array $data
     * @return int
     */
    public function createTask(array $data)
    {
        $tokenData = $this->authMiddleware->validateToken();
        $userId = $tokenData['id'];
        if (isset($data['description'])) {
            try {
                $description = $data['description'];
                $this->taskService->createTask(userId: $userId, description: $description);
                http_response_code(201);
                $_SESSION['success'] = 'Task creato con successo.';
                return 201;
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                http_response_code(400);
                header("Location: ../dashboard/index.php");
                exit();
            }
        } else {
            $_SESSION['error'] = 'La descrizione è obbligatoria.';
            http_response_code(400);
            header("Location: ../dashboard/index.php");
            exit();
        }
    }

    /**
     * Summary of getTasks
     * @return array
     */
    public function getTasks()
    {
        $tokenData = $this->authMiddleware->validateToken();
        $userId = $tokenData['id'];
        try {
            return $this->taskService->getTasks(userId: $userId);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            http_response_code(400);
            header("Location: ../dashboard/index.php");
            exit();
        }
    }

    /**
     * Summary of getTask
     * @param array $data
     * @return array|null
     */
    public function getTask(array $data)
    {
        $tokenData = $this->authMiddleware->validateToken();
        $userId = $tokenData['id'];
        if (isset($data['id'])) {
            try {
                $taskId = $data['id'];
                return $this->taskService->getTask(taskId: $taskId);
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                http_response_code(400);
                header("Location: ../dashboard/index.php");
                exit();
            }
        } else {
            $_SESSION['error'] = 'ID del task obbligatorio.';
            http_response_code(400);
            header("Location: ../dashboard/index.php");
            exit();
        }
    }

    /**
     * Summary of updateDescription
     * @param array $data
     * @return int
     */
    public function updateDescription(array $data)
    {
        $tokenData = $this->authMiddleware->validateToken();
        $userId = $tokenData['id'];

        if (isset($data['id']) && isset($data['value'])) {
            try {
                $taskId = $data['id'];
                $newDescription = $data['value'];
                $this->taskService->updateTask(
                    taskId: $taskId,
                    user_id: $userId,
                    col: 'description',
                    value: $newDescription
                );
                $_SESSION['success'] = 'Descrizione aggiornata con successo.';
                http_response_code(200);
                return 200;
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                http_response_code(400);
                header("Location: ../dashboard/index.php");
                exit();
            }
        } else {
            $_SESSION['error'] = 'ID del task e nuova descrizione obbligatori.';
            http_response_code(400);
            header("Location: ../dashboard/index.php");
            exit();
        }
    }


    /**
     * Summary of updateStatus
     * @param array $data
     * @return int
     */
    public function updateStatus(array $data)
    {
        $tokenData = $this->authMiddleware->validateToken();
        $userId = $tokenData['id'];

        if (isset($data['id']) && isset($data['status'])) {
            try {
                $taskId = $data['id'];
                $currentStatus = (bool) $data['status'];
                $newStatus = !$currentStatus;
                $this->taskService->updateTask(
                    taskId: $taskId,
                    user_id: $userId,
                    col: 'status',
                    value: $newStatus
                );
                $_SESSION['success'] = 'Stato aggiornato con successo.';
                http_response_code(200);
                return 200;
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                http_response_code(400);
                header("Location: ../dashboard/index.php");
                exit();
            }
        } else {
            $_SESSION['error'] = 'ID del task e stato obbligatori.';
            http_response_code(400);
            header("Location: ../dashboard/index.php");
            exit();
        }
    }


    /**
     * Summary of deleteTask
     * @param array $data
     * @return int
     */
    public function deleteTask(array $data)
    {
        $tokenData = $this->authMiddleware->validateToken();
        $userId = $tokenData['id'];
        if (isset($data['id'])) {
            try {
                $taskId = $data['id'];
                $this->taskService->deleteTask(taskId: $taskId);
                http_response_code(200);
                $_SESSION['success'] = 'Task eliminato con successo.';
                return 200;
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                http_response_code(400);
                header("Location: ../dashboard/index.php");
                exit();
            }
        } else {
            $_SESSION['error'] = 'ID del task obbligatorio.';
            http_response_code(400);
            header("Location: ../dashboard/index.php");
            exit();
        }
    }
}
