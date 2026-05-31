<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../includes/User.php';

$message = '';
$messageType = '';
$user = new User($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usc_id = trim($_POST['usc_id']);
    $first_name = trim($_POST['first_name']);
    $middle_name = trim($_POST['middle_name']);
    $last_name = trim($_POST['last_name']);
    $barangay = trim($_POST['barangay']);
    $city = trim($_POST['city']);
    $province = trim($_POST['province']);
    $contact_number = trim($_POST['contact_number']);
    $email = trim($_POST['email']);

    if (empty($usc_id) || empty($first_name) || empty($last_name) || empty($barangay) || empty($city) || empty($province) || empty($contact_number)) {
        $message = "Please fill in all required fields.";
        $messageType = 'error';
    } else {
        $result = $user->register($usc_id, $first_name, $middle_name, $last_name, $barangay, $city, $province, $contact_number, $email);
        $message = $result['message'];
        $messageType = $result['success'] ? 'success' : 'error';

        if ($result['success']) {
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['usc_id'] = $usc_id;
            header("Location: signin.php?registered=1");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Contact Tracing System</title>
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
            <h2>Registration Form</h2>

            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="usc_id">USC ID *</label>
                    <input type="text" id="usc_id" name="usc_id" required>
                </div>

                <div class="form-group">
                    <label for="first_name">First Name *</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>

                <div class="form-group">
                    <label for="middle_name">Middle Name</label>
                    <input type="text" id="middle_name" name="middle_name">
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name *</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>

                <div class="form-group">
                    <label for="barangay">Barangay *</label>
                    <input type="text" id="barangay" name="barangay" required>
                </div>

                <div class="form-group">
                    <label for="city">City/Town *</label>
                    <input type="text" id="city" name="city" required>
                </div>

                <div class="form-group">
                    <label for="province">Province *</label>
                    <input type="text" id="province" name="province" required>
                </div>

                <div class="form-group">
                    <label for="contact_number">Contact Number *</label>
                    <input type="tel" id="contact_number" name="contact_number" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                </div>

                <button type="submit" class="btn btn-primary">Register</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Contact Tracing System. All rights reserved.</p>
    </footer>
</body>
</html>
