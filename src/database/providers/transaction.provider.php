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

    public function beginTransaction()
    {
        $this->db->begin_transaction();
    }

    public function commit()
    {
        $this->db->commit();
    }

    public function rollBack()
    {
        $this->db->rollBack();
    }
}
