<?php
require_once "database.php";

class User {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // Create new user
    public function create($name, $mobile, $amount) {
        $stmt = $this->conn->prepare("INSERT INTO users (name, mobile, amount, status) VALUES (?, ?, ?, 0)");
        $stmt->bind_param("ssd", $name, $mobile, $amount);
        if ($stmt->execute()) {
            $id = $stmt->insert_id;
            $stmt->close();
            return $id;
        }
        $stmt->close();
        return false;
    }

    public function __destruct() {
        $this->db->close();
    }
}
?>
