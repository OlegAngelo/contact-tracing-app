<?php
header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/../../config/db_config.php';
require_once __DIR__ . '/../../includes/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usc_id = trim($_POST['usc_id'] ?? '');

    if (empty($usc_id)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID number is required']);
        exit;
    }

    $user = new User($conn);
    $userData = $user->findByUscId($usc_id);

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
