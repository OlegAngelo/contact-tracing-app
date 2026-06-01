<?php
header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/../../config/db_config.php';
require_once __DIR__ . '/../../includes/User.php';
require_once __DIR__ . '/../../includes/SignLog.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $visitor_type = trim($_POST['visitor_type'] ?? 'USC');
    $usc_id = trim($_POST['usc_id'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $middle_name = trim($_POST['middle_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $barangay = trim($_POST['barangay'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $province = trim($_POST['province'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($first_name) || empty($last_name) || empty($barangay) || empty($city) || empty($province) || empty($contact_number) || empty($email)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'All required fields must be filled']);
        exit;
    }

    if ($visitor_type === 'USC' && empty($usc_id)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'USC ID is required for USC members']);
        exit;
    }

    $user = new User($conn);

    if ($visitor_type === 'USC') {
        $result = $user->register($usc_id, $first_name, $middle_name, $last_name, $barangay, $city, $province, $contact_number, $email);
        $id_to_return = $usc_id;
        $id_field = 'usc_id';
    } else {
        $result = $user->registerNonUscVisitor($first_name, $middle_name, $last_name, $barangay, $city, $province, $contact_number, $email);
        $id_to_return = $result['visitor_id'] ?? null;
        $id_field = 'visitor_id';
    }

    if ($result['success']) {
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['user_name'] = $first_name . ' ' . $last_name;
        $_SESSION['visitor_type'] = $visitor_type;

        if ($visitor_type === 'USC') {
            $_SESSION['usc_id'] = $usc_id;
        } else {
            $_SESSION['visitor_id'] = $id_to_return;
        }

        $signLog = new SignLog($conn);
        $signLog->signIn($result['user_id']);

        echo json_encode([
            'success' => true,
            'message' => 'Registration successful',
            'visitor_type' => $visitor_type,
            $id_field => $id_to_return
        ]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $result['message']]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>

