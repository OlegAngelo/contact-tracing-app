<?php
session_start();
require_once __DIR__ . '/../../config/db_config.php';
require_once __DIR__ . '/../../includes/Admin.php';

$message = '';
$messageType = '';
$admin = new Admin($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $message = "Please enter username and password.";
        $messageType = 'error';
    } else {
        if ($admin->verify($username, $password)) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "Invalid username or password.";
            $messageType = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Contact Tracing System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="home-page">
    <div class="home-container">
        <!-- Header -->
        <div class="home-header">
            <h1 class="home-title">Contact Tracing System</h1>
            <p class="home-subtitle">Department of Computer Engineering</p>
        </div>

        <!-- Admin Login -->
        <div class="admin-login-container">
            <h2 class="admin-login-title">Admin Login</h2>
            <p class="admin-login-subtitle">Enter your credentials to access the admin portal.</p>

            <?php if ($message): ?>
                <div class="admin-alert alert-<?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="admin-login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter password" required>
                </div>

                <button type="submit" class="btn-login">Login</button>
            </form>

            <div class="demo-credentials">
                <p><strong>Demo credentials:</strong></p>
                <p><span class="credential-label">Username:</span> <span class="credential-value">admin</span></p>
                <p><span class="credential-label">Password:</span> <span class="credential-value">admin</span></p>
            </div>

            <div style="margin-top: 20px; text-align: center;">
                <a href="../index.php" style="color: #4361ee; text-decoration: none; font-size: 14px;">← Back to Home</a>
            </div>
        </div>
    </div>
</body>
</html>
