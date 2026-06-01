<?php
header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/../../config/db_config.php';
require_once __DIR__ . '/../../includes/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_input = trim($_POST['usc_id'] ?? $_POST['id'] ?? '');

    if (empty($id_input)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID number or Visitor ID is required']);
        exit;
    }

    $user = new User($conn);
    $userData = null;

    if (strpos($id_input, 'VISITOR_') === 0) {
        $userData = $user->findByVisitorId($id_input);
    } else {
        $userData = $user->findByUscId($id_input);
    }

    if ($userData) {
        echo json_encode([
            'success' => true,
            'user' => $userData
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>
