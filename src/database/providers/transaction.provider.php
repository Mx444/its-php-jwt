<?php

class TransactionProvider
{
    private $connectionProvider;
    private $db;

    public function __construct(ConnectionProvider $connectionProvider)
    {
        $this->connectionProvider = $connectionProvider;
        $this->db = $this->connectionProvider->getConnection();
    }

    /**
     * Begins a transaction.
     */
    public function beginTransaction(): void
    {
        $this->db->beginTransaction();
    }

    /**
     * Commits the current transaction.
     * 
     * @throws Exception If the commit fails.
     */
    public function commit(): void
    {
        try {
            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new Exception(message: "Commit failed: " . $e->getMessage());
        }
    }

    /**
     * Rolls back the current transaction.
     */
    public function rollBack(): void
    {
        $this->db->rollBack();
    }
}
