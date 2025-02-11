<?php

class TransactionProvider
{
    private $db;

    public function __construct($databaseService)
    {
        $this->db = $databaseService;
    }

    public function beginTransaction(): void
    {
        $this->db->beginTransaction();
    }

    public function commit(): void
    {
        try {
            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new Exception("Commit failed: " . $e->getMessage());
        }
    }

    public function rollBack(): void
    {
        $this->db->rollBack();
    }
}
