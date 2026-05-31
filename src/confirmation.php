<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../includes/User.php';
require_once __DIR__ . '/../includes/SignLog.php';

$user = new User($conn);
$signLog = new SignLog($conn);
$userData = null;
$action = isset($_GET['action']) ? $_GET['action'] : '';
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($user_id) {
    $userData = $user->getById($user_id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation - Contact Tracing System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <h1>DCE Contact Tracing System</h1>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php">Admin Login</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="confirmation-container">
            <?php if ($action === 'signin'): ?>
                <div class="alert alert-success">
                    <h2>✓ Successfully Signed In</h2>
                </div>
                <?php if ($userData): ?>
                    <div class="info-card">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($userData['first_name'] . ' ' . $userData['middle_name'] . ' ' . $userData['last_name']); ?></p>
                        <p><strong>USC ID:</strong> <?php echo htmlspecialchars($userData['usc_id']); ?></p>
                        <p><strong>Time:</strong> <span id="current-time"></span></p>
                    </div>
                <?php endif; ?>
            <?php elseif ($action === 'signout'): ?>
                <div class="alert alert-info">
                    <h2>✓ Successfully Signed Out</h2>
                </div>
                <?php if ($userData): ?>
                    <div class="info-card">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($userData['first_name'] . ' ' . $userData['middle_name'] . ' ' . $userData['last_name']); ?></p>
                        <p><strong>USC ID:</strong> <?php echo htmlspecialchars($userData['usc_id']); ?></p>
                        <p><strong>Time:</strong> <span id="current-time"></span></p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="confirmation-actions">
                <a href="index.php" class="btn btn-primary">Return to Home</a>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Contact Tracing System. All rights reserved.</p>
    </footer>

    <script>
        document.getElementById('current-time').textContent = new Date().toLocaleString();

        // Redirect to home after 5 seconds
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 5000);
    </script>
</body>
</html>
