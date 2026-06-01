<?php
require_once __DIR__ . '/../config/db_config.php';

class SignLog {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Sign user in
    public function signIn($user_id) {
        $stmt = $this->conn->prepare("INSERT INTO sign_logs (user_id, action) VALUES (?, 'IN')");
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }

    // Sign user out
    public function signOut($user_id) {
        $stmt = $this->conn->prepare("INSERT INTO sign_logs (user_id, action) VALUES (?, 'OUT')");
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }

    // Get all logs for a user
    public function getUserLogs($user_id) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM sign_logs WHERE user_id = ? ORDER BY timestamp DESC"
        );
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Get logs by date
    public function getLogsByDate($date) {
        $startDate = $date . " 00:00:00";
        $endDate = $date . " 23:59:59";

        $stmt = $this->conn->prepare(
            "SELECT s.*, u.first_name, u.last_name, u.usc_id FROM sign_logs s
             JOIN users u ON s.user_id = u.id
             WHERE s.timestamp BETWEEN ? AND ?
             ORDER BY s.timestamp DESC"
        );
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Search logs by user or timestamp criteria
    public function searchLogs($searchType, $searchValue) {
        $searchValue = trim($searchValue);
        if (empty($searchValue)) {
            return [];
        }

        $query =
            "SELECT s.id, s.user_id, s.action, s.timestamp, u.first_name, u.last_name, u.usc_id, u.barangay, u.city, u.province
             FROM sign_logs s
             JOIN users u ON s.user_id = u.id
             WHERE ";

        switch ($searchType) {
            case 'name':
                $query .= "(u.first_name LIKE ? OR u.last_name LIKE ?)";
                $searchVal = "%$searchValue%";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("ss", $searchVal, $searchVal);
                break;
            case 'city':
                $query .= "u.city LIKE ?";
                $searchVal = "%$searchValue%";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("s", $searchVal);
                break;
            case 'barangay':
                $query .= "u.barangay LIKE ?";
                $searchVal = "%$searchValue%";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("s", $searchVal);
                break;
            case 'province':
                $query .= "u.province LIKE ?";
                $searchVal = "%$searchValue%";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("s", $searchVal);
                break;
            case 'usc_id':
                $query .= "u.usc_id = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("s", $searchValue);
                break;
            case 'timestamp':
                $query .= "s.timestamp LIKE ?";
                $searchVal = "%$searchValue%";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("s", $searchVal);
                break;
            default:
                return [];
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Get all logs with user info
    public function getAllLogs() {
        $result = $this->conn->query(
            "SELECT s.id, s.user_id, s.action, s.timestamp, u.first_name, u.last_name, u.usc_id, u.barangay, u.city, u.province
             FROM sign_logs s
             JOIN users u ON s.user_id = u.id
             ORDER BY s.timestamp DESC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
