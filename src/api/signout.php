<?php
header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/../../config/db_config.php';
require_once __DIR__ . '/../../includes/User.php';
require_once __DIR__ . '/../../includes/SignLog.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id'] ?? 0);

    if (!$user_id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'User ID is required']);
        exit;
    }

    $user = new User($conn);
    $userData = $user->getById($user_id);

    if (!$userData) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }

    $signLog = new SignLog($conn);
    $logs = $signLog->getUserLogs($user_id);
    $isSignedIn = !empty($logs) && $logs[0]['action'] === 'IN';

    if (!$isSignedIn) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'You are not currently signed in']);
        exit;
    }

    $signLog->signOut($user_id);
    echo json_encode(['success' => true, 'message' => 'Signed out successfully']);
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>
