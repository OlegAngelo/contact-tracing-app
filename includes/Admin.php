<?php
require_once __DIR__ . '/../config/db_config.php';

class Admin {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Verify admin credentials
    public function verify($username, $password) {
        $stmt = $this->conn->prepare("SELECT id FROM admin WHERE username = ? AND password = MD5(?)");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // Get admin by username
    public function getByUsername($username) {
        $stmt = $this->conn->prepare("SELECT id FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
