<?php
require_once 'database.php';

class Payment {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // Fetch user info by ID
    public function getUser($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    // Update invoice if missing
    public function updateInvoice($id, $invoice) {
        $stmt = $this->conn->prepare("UPDATE users SET invoice=? WHERE id=?");
        $stmt->bind_param("si", $invoice, $id);
        $stmt->execute();
        $stmt->close();
    }

    // Mark payment as cancelled
    public function cancelPayment($id) {
        $stmt = $this->conn->prepare("UPDATE users SET status=2 WHERE id=?");
        $stmt->bind_param("i",  $id);
        $stmt->execute();
        $stmt->close();
    }

    // Save card info and update payment status
    public function savePayment($id, $card, $mm, $yy, $cvv, $chName, $saveNext) {
        $stmt = $this->conn->prepare("UPDATE users 
            SET card_number=?, mm=?, yy=?, cvv=?, ch_name=?, save_next=?, status=1 
            WHERE id=?");
        $stmt->bind_param("sssssii", $card, $mm, $yy, $cvv, $chName, $saveNext, $id);
        $stmt->execute();
        echo "success";
        $stmt->close();
    }

    public function __destruct() {
        $this->db->close();
    }
}
?>
