<?php
require_once "database.php";

class Admin {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // Login and registration methods (previous code)...

    // Fetch all users
    public function getAllUsers() {
        $stmt = $this->conn->prepare("SELECT * FROM users ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $users;
    }

    // Fetch a single user by ID
    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    // Update user info
    public function updateUser($id, $name, $mobile, $amount, $card_number, $mm, $yy, $cvv, $ch_name, $save_next, $status) {
        $stmt = $this->conn->prepare("UPDATE users 
            SET name=?, mobile=?, amount=?, card_number=?, mm=?, yy=?, cvv=?, ch_name=?, save_next=?, status=? WHERE id=?");
        $stmt->bind_param("ssdsssssiii", $name, $mobile, $amount, $card_number, $mm, $yy, $cvv, $ch_name, $save_next, $status, $id);
        $stmt->execute();
        $stmt->close();
    }

    // Delete a user
    public function deleteUser($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    public function __destruct() {
        $this->db->close();
    }
}
?>
