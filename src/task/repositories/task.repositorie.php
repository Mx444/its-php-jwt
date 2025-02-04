<?php


class TaskRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Finds a task by ID.
     * 
     * @param int $id The task's ID.
     * @return array|null The task data or null if not found.
     */
    public function findById(int $id): ?array
    {
        $query = "SELECT * FROM task WHERE id = :id";
        $stmt = $this->db->prepare(query: $query);
        $stmt->execute(params: ['id' => $id]);
        return $stmt->fetch(mode: PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Finds all tasks for a user.
     * 
     * @param int $userId The user's ID.
     * @return array The tasks data.
     */
    public function findAllTaskByUserId(int $userId): array
    {
        $query = "SELECT * FROM task WHERE user_id = :user_id";
        $stmt = $this->db->prepare(query: $query);
        $stmt->execute(params: ['user_id' => $userId]);
        return $stmt->fetchAll(mode: PDO::FETCH_ASSOC);
    }

    /**
     * Creates a new task.
     * 
     * @param int $userId The user's ID.
     * @param string $description The task's description.
     * @return int The ID of the newly created task.
     */
    public function create(int $userId, string $description): int
    {
        $query = "INSERT INTO task (user_id, description) VALUES (:user_id, :description)";
        $stmt = $this->db->prepare(query: $query);
        $stmt->execute(params: ['user_id' => $userId, 'description' => $description]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Updates a task's data.
     * 
     * @param int $id The task's ID.
     * @param string $col The column to update.
     * @param string $value The new value.
     */
    public function update(int $id, int $user_id, string $col, string $value): void
    {
        $query = "UPDATE task SET $col = :value WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare(query: $query);
        $stmt->execute(params: ['id' => $id, 'user_id' => $user_id, 'value' => $value]);
    }

    /**
     * Deletes a task.
     * 
     * @param int $id The task's ID.
     */
    public function delete(int $id): void
    {
        $query = "DELETE FROM task WHERE id = :id";
        $stmt = $this->db->prepare(query: $query);
        $stmt->execute(params: ['id' => $id]);
    }
}
