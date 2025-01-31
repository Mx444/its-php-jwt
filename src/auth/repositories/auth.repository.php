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
        $query = "SELECT * FROM auth WHERE email = ? AND deleted_at IS NULL";
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

    public function update($id, $col, $hashedPassword)
    {
        $col = $col == 'password' ? 'password' : 'email';
        $query = "UPDATE auth SET $col = ?, updated_At = NOW() WHERE id = ? AND password = ?";
        $stmt = $this->db->prepare($query);
        if ($stmt === false) {
            throw new Exception("Failed to prepare the query: " . $this->db->error);
        }
        $stmt->bind_param("is", $id, $hashedPassword);
        if (!$stmt->execute()) {
            throw new Exception("Execution failed: " . $stmt->error);
        }
        $updatedRows = $stmt->affected_rows;
        $stmt->close();
        return $updatedRows;
    }

    public function delete($id, $email, $hashedPassword)
    {
        $query = "UPDATE auth SET deleted_at = NOW() WHERE id = ? AND email = ? AND password = ?";
        $stmt = $this->db->prepare($query);
        if ($stmt === false) {
            throw new Exception("Failed to prepare the query: " . $this->db->error);
        }
        $stmt->bind_param("iss", $id, $email, $hashedPassword);
        if (!$stmt->execute()) {
            throw new Exception("Execution failed: " . $stmt->error);
        }
        $deletedRows = $stmt->affected_rows;
        $stmt->close();
        return $deletedRows;
    }
}
