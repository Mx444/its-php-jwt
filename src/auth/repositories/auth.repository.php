<?php

class AuthRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findByEmail($email)
    {
        $query = "SELECT * FROM auth WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public function create($email, $hashedPassword)
    {
        $query = "INSERT INTO auth (email, password) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        if ($stmt === false) {
            throw new Exception("Failed to prepare the query: " . $this->db->error);
        }
        $stmt->bind_param("ss", $email, $hashedPassword);
        if (!$stmt->execute()) {
            throw new Exception("Execution failed: " . $stmt->error);
        }
        $insertedId = $stmt->insert_id;
        $stmt->close();
        return $insertedId;
    }
}
