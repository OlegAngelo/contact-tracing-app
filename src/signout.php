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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usc_id = trim($_POST['usc_id']);

    if (empty($usc_id)) {
        $message = "Please enter your USC ID.";
        $messageType = 'error';
    } else {
        $userData = $user->findByUscId($usc_id);

        if ($userData) {
            // Check if signed in (last action is 'IN')
            $logs = $signLog->getUserLogs($userData['id']);
            $isSignedIn = !empty($logs) && $logs[0]['action'] === 'IN';

            if (!$isSignedIn) {
                $message = "You are not currently signed in.";
                $messageType = 'error';
            } else {
                $signLog->signOut($userData['id']);
                header("Location: confirmation.php?action=signout&user_id=" . $userData['id']);
                exit;
            }
        } else {
            $message = "User not found.";
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
    <title>Sign Out - Contact Tracing System</title>
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
            <h2>Sign Out</h2>

            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if ($userData && $_SERVER['REQUEST_METHOD'] === 'POST' && $messageType !== 'error'): ?>
                <div class="info-card">
                    <h3>Signing out...</h3>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($userData['first_name'] . ' ' . $userData['last_name']); ?></p>
                    <p><strong>USC ID:</strong> <?php echo htmlspecialchars($userData['usc_id']); ?></p>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="usc_id">Enter Your USC ID *</label>
                    <input type="text" id="usc_id" name="usc_id" required>
                </div>

                <button type="submit" class="btn btn-danger">Sign Out</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Contact Tracing System. All rights reserved.</p>
    </footer>
</body>
</html>
