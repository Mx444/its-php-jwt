<?php

namespace Database\Providers;

use PDOException;
use Exception;

class TransactionProvider
{
    private $db;

    /**
     * TransactionProvider constructor.
     *
     * @param DatabaseService $databaseService The database service instance.
     */
    public function __construct(DatabaseService $databaseService)
    {
        $this->db = $databaseService->getConnection();
    }

    /**
     * Begins a transaction.
     *
     * @return void
     */
    public function beginTransaction(): void
    {
        $this->db->beginTransaction();
    }

    /**
     * Commits the current transaction.
     *
     * @return void
     * @throws Exception If the commit fails.
     */
    public function commit(): void
    {
        try {
            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new Exception("Commit failed: " . $e->getMessage());
        }
    }

    /**
     * Rolls back the current transaction.
     *
     * @return void
     */
    public function rollBack(): void
    {
        $this->db->rollBack();
    }
}
