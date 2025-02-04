<?php
require_once __DIR__ . '/../../database/providers/connection.provider.php';
require_once __DIR__ . '/../repositories/task.repositorie.php';
class TaskService
{

    private ConnectionProvider $connectionProvider;
    private TaskRepository $taskRepository;

    public function __construct()
    {
        $this->connectionProvider = new ConnectionProvider();
        $this->taskRepository = new TaskRepository(db: $this->connectionProvider->getConnection());
    }

    /**
     * Creates a new task.
     * 
     * @param int $userId The ID of the user creating the task.
     * @param string $description The description of the task.
     * @throws Exception If the task creation fails.
     */
    public function createTask(int $userId, string $description): int
    {
        try {
            return $this->taskRepository->create(userId: $userId, description: $description);
        } catch (Exception $e) {
            throw new Exception(message: "Errore nella creazione del task: " . $e->getMessage());
        }
    }

    /**
     * Retrieves all tasks for a user.
     * 
     * @param int $userId The user's ID.
     * @return array The tasks data.
     * @throws Exception If the tasks retrieval fails.
     */
    public function getTasks(int $userId): array
    {
        try {
            return $this->taskRepository->findAllTaskByUserId(userId: $userId);
        } catch (Exception $e) {
            throw new Exception(message: "Errore nel recupero dei task: " . $e->getMessage());
        }
    }

    /**
     * Retrieves a task by ID.
     * 
     * @param int $taskId The task's ID.
     * @return array|null The task data or null if not found.
     * @throws Exception If the task retrieval fails.
     */
    public function getTask(int $taskId): ?array
    {
        try {
            return $this->taskRepository->findById(id: $taskId);
        } catch (Exception $e) {
            throw new Exception(message: "Errore nel recupero del task: " . $e->getMessage());
        }
    }

    /**
     * Updates a task's data.
     * 
     * @param int $taskId The task's ID.
     * @param int $user_id The user's ID.
     * @param string $col The column to update.
     * @param string $value The new value.
     * @throws Exception If the task update fails.
     */
    public function updateTask(int $taskId, int $user_id, string $col, string $value): void
    {
        try {
            $this->taskRepository->update(id: $taskId, user_id: $user_id, col: $col, value: $value);
        } catch (Exception $e) {
            throw new Exception(message: "Errore nell'aggiornamento del task: " . $e->getMessage());
        }
    }

    /**
     * Deletes a task.
     * 
     * @param int $taskId The task's ID.
     * @throws Exception If the task deletion fails.
     */
    public function deleteTask(int $taskId): void
    {
        try {
            $this->taskRepository->delete(id: $taskId);
        } catch (Exception $e) {
            throw new Exception(message: "Errore nell'eliminazione del task: " . $e->getMessage());
        }
    }
}
