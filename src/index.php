<?php
session_start();
require_once __DIR__ . '/../includes/icons.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Tracing System - DCE</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="home-page">
    <div class="home-container">
        <!-- Header -->
        <div class="home-header">
            <h1 class="home-title">Contact Tracing System</h1>
            <p class="home-subtitle">Department of Computer Engineering</p>
        </div>

        <!-- Portal Toggle Switch -->
        <div class="portal-toggle">
            <input type="radio" id="user-toggle" name="portal" value="user" checked>
            <input type="radio" id="admin-toggle" name="portal" value="admin">

            <label for="user-toggle" class="toggle-label user-label">
                <span class="toggle-icon"><?php echo Icons::userPortal(); ?></span>
                User Portal
            </label>
            <label for="admin-toggle" class="toggle-label admin-label">
                <span class="toggle-icon"><?php echo Icons::adminPortal(); ?></span>
                Admin Portal
            </label>

            <div class="toggle-slider"></div>
        </div>

        <!-- User Portal Content -->
        <div class="portal-content active" id="user-portal">
            <div class="action-cards">
                <a href="signin.php" class="action-card">
                    <div class="card-icon signin-icon"><?php echo Icons::signIn(); ?></div>
                    <h3>Sign In</h3>
                    <p>Returning visitor? Enter your ID number to sign in.</p>
                </a>

                <a href="register.php" class="action-card">
                    <div class="card-icon register-icon"><?php echo Icons::register(); ?></div>
                    <h3>Register</h3>
                    <p>First time visitor? Register your information here.</p>
                </a>

                <a href="signout.php" class="action-card">
                    <div class="card-icon signout-icon"><?php echo Icons::signOut(); ?></div>
                    <h3>Sign Out</h3>
                    <p>You must be signed in to sign out.</p>
                </a>
            </div>
        </div>

        <!-- Admin Portal Content -->
        <div class="portal-content" id="admin-portal">
            <div class="admin-login-container">
                <h2 class="admin-login-title">Admin Login</h2>
                <p class="admin-login-subtitle">Enter your credentials to access the admin portal.</p>

                <?php
                $login_message = '';
                $login_error = false;

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_login'])) {
                    require_once __DIR__ . '/../config/db_config.php';
                    require_once __DIR__ . '/../includes/Admin.php';

                    $admin = new Admin($conn);
                    $username = trim($_POST['username']);
                    $password = trim($_POST['password']);

                    if (empty($username) || empty($password)) {
                        $login_message = "Please enter username and password.";
                        $login_error = true;
                    } else {
                        if ($admin->verify($username, $password)) {
                            $_SESSION['admin_logged_in'] = true;
                            $_SESSION['admin_username'] = $username;
                            header("Location: admin/dashboard.php");
                            exit;
                        } else {
                            $login_message = "Invalid username or password.";
                            $login_error = true;
                        }
                    }
                }
                ?>

                <?php if ($login_message): ?>
                    <div class="admin-alert <?php echo $login_error ? 'alert-error' : 'alert-success'; ?>">
                        <?php echo $login_message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="admin-login-form">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter username" required autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter password" required>
                    </div>

                    <button type="submit" name="admin_login" class="btn-login">Login</button>
                </form>

                <div class="demo-credentials">
                    <p><strong>Demo credentials:</strong></p>
                    <p><span class="credential-label">Username:</span> <span class="credential-value">admin</span></p>
                    <p><span class="credential-label">Password:</span> <span class="credential-value">admin</span></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const userToggle = document.getElementById('user-toggle');
        const adminToggle = document.getElementById('admin-toggle');

        function updatePortal(portal) {
            document.querySelectorAll('.portal-content').forEach(c => c.classList.remove('active'));
            document.getElementById(portal + '-portal').classList.add('active');
        }

        userToggle.addEventListener('change', () => updatePortal('user'));
        adminToggle.addEventListener('change', () => updatePortal('admin'));
    </script>
</body>
</html>
