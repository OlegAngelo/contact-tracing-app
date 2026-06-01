<?php
require_once __DIR__ . '/../config/db_config.php';

class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Find user by USC ID
    public function findByUscId($usc_id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE usc_id = ?");
        $stmt->bind_param("s", $usc_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Register new user
    public function register($usc_id, $first_name, $middle_name, $last_name, $barangay, $city, $province, $contact_number, $email) {
        // Check if user already exists
        if ($this->findByUscId($usc_id)) {
            return ['success' => false, 'message' => 'User with this ID already exists'];
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO users (usc_id, first_name, middle_name, last_name, barangay, city, province, contact_number, email)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param("sssssssss", $usc_id, $first_name, $middle_name, $last_name, $barangay, $city, $province, $contact_number, $email);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'User registered successfully', 'user_id' => $this->conn->insert_id];
        } else {
            return ['success' => false, 'message' => 'Registration failed: ' . $stmt->error];
        }
    }

    // Update user info
    public function update($id, $first_name, $middle_name, $last_name, $barangay, $city, $province, $contact_number, $email) {
        $stmt = $this->conn->prepare(
            "UPDATE users SET first_name = ?, middle_name = ?, last_name = ?, barangay = ?, city = ?, province = ?, contact_number = ?, email = ?, updated_at = CURRENT_TIMESTAMP
             WHERE id = ?"
        );

        $stmt->bind_param("ssssssssi", $first_name, $middle_name, $last_name, $barangay, $city, $province, $contact_number, $email, $id);

        return $stmt->execute();
    }

    // Get user by ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Search users
    public function search($searchType, $searchValue) {
        $query = "SELECT * FROM users WHERE ";

        switch($searchType) {
            case 'name':
                $query .= "(first_name LIKE ? OR last_name LIKE ?)";
                $searchVal = "%$searchValue%";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("ss", $searchVal, $searchVal);
                break;
            case 'city':
                $query .= "city LIKE ?";
                $searchVal = "%$searchValue%";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("s", $searchVal);
                break;
            case 'barangay':
                $query .= "barangay LIKE ?";
                $searchVal = "%$searchValue%";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("s", $searchVal);
                break;
            case 'province':
                $query .= "province LIKE ?";
                $searchVal = "%$searchValue%";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("s", $searchVal);
                break;
            case 'usc_id':
                $query .= "usc_id = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("s", $searchValue);
                break;
            case 'timestamp':
                $query .= "created_at LIKE ?";
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
}
?>
