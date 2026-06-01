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
    public function searchLogs($searchType, $searchValue, $visitorTypeFilter = 'all') {
        $searchValue = trim($searchValue);
        if (empty($searchValue)) {
            return [];
        }

        $query =
            "SELECT s.id, s.user_id, s.action, s.timestamp, u.first_name, u.last_name, u.usc_id, u.visitor_id, u.visitor_type, u.barangay, u.city, u.province
             FROM sign_logs s
             JOIN users u ON s.user_id = u.id
             WHERE ";

        $whereConditions = [];
        $params = [];
        $paramTypes = '';

        switch ($searchType) {
            case 'name':
                $whereConditions[] = "(u.first_name LIKE ? OR u.last_name LIKE ?)";
                $searchVal = "%$searchValue%";
                $params = [$searchVal, $searchVal];
                $paramTypes = "ss";
                break;
            case 'city':
                $whereConditions[] = "u.city LIKE ?";
                $searchVal = "%$searchValue%";
                $params = [$searchVal];
                $paramTypes = "s";
                break;
            case 'barangay':
                $whereConditions[] = "u.barangay LIKE ?";
                $searchVal = "%$searchValue%";
                $params = [$searchVal];
                $paramTypes = "s";
                break;
            case 'province':
                $whereConditions[] = "u.province LIKE ?";
                $searchVal = "%$searchValue%";
                $params = [$searchVal];
                $paramTypes = "s";
                break;
            case 'usc_id':
                $whereConditions[] = "u.usc_id = ?";
                $params = [$searchValue];
                $paramTypes = "s";
                break;
            case 'visitor_id':
                $whereConditions[] = "u.visitor_id = ?";
                $params = [$searchValue];
                $paramTypes = "s";
                break;
            case 'timestamp':
                $whereConditions[] = "s.timestamp LIKE ?";
                $searchVal = "%$searchValue%";
                $params = [$searchVal];
                $paramTypes = "s";
                break;
            default:
                return [];
        }

        if ($visitorTypeFilter !== 'all') {
            $whereConditions[] = "u.visitor_type = ?";
            $params[] = $visitorTypeFilter;
            $paramTypes .= "s";
        }

        $query .= implode(" AND ", $whereConditions) . " ORDER BY s.timestamp DESC";

        $stmt = $this->conn->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($paramTypes, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Get all logs with user info
    public function getAllLogs($visitorTypeFilter = 'all') {
        $query = "SELECT s.id, s.user_id, s.action, s.timestamp, u.first_name, u.last_name, u.usc_id, u.visitor_id, u.visitor_type, u.barangay, u.city, u.province
             FROM sign_logs s
             JOIN users u ON s.user_id = u.id";

        if ($visitorTypeFilter !== 'all') {
            $query .= " WHERE u.visitor_type = ?";
            $stmt = $this->conn->prepare($query . " ORDER BY s.timestamp DESC");
            $stmt->bind_param("s", $visitorTypeFilter);
            $stmt->execute();
        } else {
            $stmt = $this->conn->prepare($query . " ORDER BY s.timestamp DESC");
            $stmt->execute();
        }

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
