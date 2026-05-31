<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../includes/User.php';
require_once __DIR__ . '/../includes/SignLog.php';

$message = '';
$messageType = '';
$user = new User($conn);
$signLog = new SignLog($conn);
$userData = null;

if (isset($_GET['registered'])) {
    $message = "Registration successful! You are now signed in.";
    $messageType = 'success';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usc_id = trim($_POST['usc_id']);

    if (empty($usc_id)) {
        $message = "Please enter your USC ID.";
        $messageType = 'error';
    } else {
        $userData = $user->findByUscId($usc_id);

        if ($userData) {
            // Check if already signed in (last action is 'IN')
            $logs = $signLog->getUserLogs($userData['id']);
            $alreadySignedIn = !empty($logs) && $logs[0]['action'] === 'IN';

            if ($alreadySignedIn) {
                $message = "You are already signed in. Please sign out first.";
                $messageType = 'error';
            } else {
                $signLog->signIn($userData['id']);
                $_SESSION['user_id'] = $userData['id'];
                $_SESSION['usc_id'] = $userData['usc_id'];
                header("Location: confirmation.php?action=signin&user_id=" . $userData['id']);
                exit;
            }
        } else {
            $message = "User not found. Please register first.";
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
    <title>Sign In - Contact Tracing System</title>
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
        <div class="form-container">
            <h2>Sign In</h2>

            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if ($userData && $_SERVER['REQUEST_METHOD'] === 'POST' && $messageType !== 'error'): ?>
                <div class="info-card">
                    <h3>Welcome back!</h3>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($userData['first_name'] . ' ' . $userData['last_name']); ?></p>
                    <p><strong>USC ID:</strong> <?php echo htmlspecialchars($userData['usc_id']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($userData['barangay'] . ', ' . $userData['city'] . ', ' . $userData['province']); ?></p>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="usc_id">Enter Your USC ID *</label>
                    <input type="text" id="usc_id" name="usc_id" required>
                </div>

                <button type="submit" class="btn btn-success">Sign In</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </form>

            <div class="form-footer">
                <p>First time? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Contact Tracing System. All rights reserved.</p>
    </footer>
</body>
</html>
