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
            <div class="action-cards" id="action-cards-container">
                <a href="#" class="action-card" id="signin-btn">
                    <div class="card-icon signin-icon"><?php echo Icons::signIn(); ?></div>
                    <h3>Sign In</h3>
                    <p>Returning visitor? Enter your ID number to sign in.</p>
                </a>

                <a href="#" class="action-card" id="register-btn">
                    <div class="card-icon register-icon"><?php echo Icons::register(); ?></div>
                    <h3>Register</h3>
                    <p>First time visitor? Register your information here.</p>
                </a>

                <a href="#" class="action-card" id="signout-btn">
                    <div class="card-icon signout-icon"><?php echo Icons::signOut(); ?></div>
                    <h3>Sign Out</h3>
                    <p>You must be signed in to sign out.</p>
                </a>
            </div>

            <!-- Sign In Form -->
            <div class="signin-form-container" id="signin-form-container">
                <div class="signin-form-box">
                    <h2>Sign In</h2>
                    <p class="signin-subtitle">Enter your ID number to retrieve your information and sign in.</p>

                    <form id="signin-form" method="POST" action="api/fetch-user.php">
                        <div class="form-group">
                            <label for="id-number">ID Number</label>
                            <input type="text" id="id-number" name="usc_id" placeholder="241105130" required autocomplete="off">
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-signin">Sign In</button>
                            <button type="button" class="btn-cancel" id="cancel-signin">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Register Form -->
            <div class="register-form-container" id="register-form-container">
                <div class="register-form-box">
                    <h2>Register New User</h2>
                    <p class="register-subtitle">Please fill in all required fields. You will be automatically signed in after registration.</p>

                    <form id="register-form">
                        <div class="form-group full-width">
                            <label for="reg-id-number">ID Number (if USC student/faculty/staff)</label>
                            <input type="text" id="reg-id-number" name="usc_id" placeholder="241105130" required autocomplete="off">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="first-name">First Name <span class="required">*</span></label>
                                <input type="text" id="first-name" name="first_name" placeholder="Juan" required>
                            </div>
                            <div class="form-group">
                                <label for="middle-name">Middle Name <span class="required">*</span></label>
                                <input type="text" id="middle-name" name="middle_name" placeholder="Dela">
                            </div>
                            <div class="form-group">
                                <label for="last-name">Last Name <span class="required">*</span></label>
                                <input type="text" id="last-name" name="last_name" placeholder="Cruz" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="barangay">Barangay <span class="required">*</span></label>
                                <input type="text" id="barangay" name="barangay" placeholder="Capitol Site" required>
                            </div>
                            <div class="form-group">
                                <label for="city">City/Town <span class="required">*</span></label>
                                <input type="text" id="city" name="city" placeholder="Cebu City" required>
                            </div>
                            <div class="form-group">
                                <label for="province">Province <span class="required">*</span></label>
                                <input type="text" id="province" name="province" placeholder="Cebu" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="contact-number">Contact Number <span class="required">*</span></label>
                                <input type="tel" id="contact-number" name="contact_number" placeholder="09123456789" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email <span class="required">*</span></label>
                                <input type="email" id="email" name="email" placeholder="juan.delacruz@usc.edu.ph" required>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-register">Register & Sign In</button>
                            <button type="button" class="btn-cancel" id="cancel-register">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sign Out Form -->
            <div class="signout-form-container" id="signout-form-container">
                <div class="signout-form-box">
                    <h2>Sign Out</h2>
                    <p class="signout-subtitle">Enter your ID number to confirm you want to sign out.</p>

                    <form id="signout-form">
                        <div class="form-group">
                            <label for="signout-id-number">ID Number</label>
                            <input type="text" id="signout-id-number" name="usc_id" placeholder="241105130" required autocomplete="off">
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-signout">Sign Out</button>
                            <button type="button" class="btn-cancel" id="cancel-signout">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div class="modal" id="confirmation-modal">
            <div class="modal-content">
                <button type="button" class="modal-close" id="modal-close">&times;</button>
                <h2>Confirm Your Information</h2>
                <p class="modal-subtitle">Please verify that your information is correct before signing in.</p>

                <div class="confirmation-info">
                    <div class="info-row">
                        <span class="info-label">ID Number:</span>
                        <span class="info-value" id="modal-id"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Name:</span>
                        <span class="info-value" id="modal-name"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Address:</span>
                        <span class="info-value" id="modal-address"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Contact:</span>
                        <span class="info-value" id="modal-contact"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value" id="modal-email"></span>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-modal-cancel" id="modal-cancel">Cancel</button>
                    <button type="button" class="btn-modal-confirm" id="modal-confirm">Confirm & Sign In</button>
                </div>
            </div>
        </div>
        <div class="modal-overlay" id="modal-overlay"></div>

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
        const signinBtn = document.getElementById('signin-btn');
        const registerBtn = document.getElementById('register-btn');
        const signoutBtn = document.getElementById('signout-btn');
        const cancelSigninBtn = document.getElementById('cancel-signin');
        const cancelRegisterBtn = document.getElementById('cancel-register');
        const cancelSignoutBtn = document.getElementById('cancel-signout');
        const signinForm = document.getElementById('signin-form');
        const registerForm = document.getElementById('register-form');
        const signoutForm = document.getElementById('signout-form');
        const actionCardsContainer = document.getElementById('action-cards-container');
        const signinFormContainer = document.getElementById('signin-form-container');
        const registerFormContainer = document.getElementById('register-form-container');
        const signoutFormContainer = document.getElementById('signout-form-container');
        const modal = document.getElementById('confirmation-modal');
        const modalOverlay = document.getElementById('modal-overlay');
        const modalCloseBtn = document.getElementById('modal-close');
        const modalCancelBtn = document.getElementById('modal-cancel');
        const modalConfirmBtn = document.getElementById('modal-confirm');
        const idInput = document.getElementById('id-number');

        function updatePortal(portal) {
            document.querySelectorAll('.portal-content').forEach(c => c.classList.remove('active'));
            document.getElementById(portal + '-portal').classList.add('active');
            if (portal === 'user') {
                showActionCards();
            }
        }

        function showActionCards() {
            actionCardsContainer.style.display = 'grid';
            signinFormContainer.style.display = 'none';
            registerFormContainer.style.display = 'none';
            signoutFormContainer.style.display = 'none';
        }

        function showSignInForm() {
            actionCardsContainer.style.display = 'none';
            signinFormContainer.style.display = 'block';
            registerFormContainer.style.display = 'none';
            signoutFormContainer.style.display = 'none';
            idInput.focus();
        }

        function showRegisterForm() {
            actionCardsContainer.style.display = 'none';
            signinFormContainer.style.display = 'none';
            registerFormContainer.style.display = 'block';
            signoutFormContainer.style.display = 'none';
            document.getElementById('reg-id-number').focus();
        }

        function showSignOutForm() {
            actionCardsContainer.style.display = 'none';
            signinFormContainer.style.display = 'none';
            registerFormContainer.style.display = 'none';
            signoutFormContainer.style.display = 'block';
            document.getElementById('signout-id-number').focus();
        }

        function closeModal() {
            modal.classList.remove('active');
            modalOverlay.classList.remove('active');
        }

        signinBtn.addEventListener('click', (e) => {
            e.preventDefault();
            showSignInForm();
        });

        registerBtn.addEventListener('click', (e) => {
            e.preventDefault();
            showRegisterForm();
        });

        signoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            showSignOutForm();
        });

        cancelSigninBtn.addEventListener('click', showActionCards);
        cancelRegisterBtn.addEventListener('click', showActionCards);
        cancelSignoutBtn.addEventListener('click', showActionCards);
        modalCloseBtn.addEventListener('click', closeModal);
        modalCancelBtn.addEventListener('click', closeModal);
        modalOverlay.addEventListener('click', closeModal);

        signinForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const uscId = idInput.value.trim();

            if (!uscId) {
                alert('Please enter your ID number');
                return;
            }

            try {
                const response = await fetch('api/fetch-user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'usc_id=' + encodeURIComponent(uscId)
                });

                const data = await response.json();

                if (data.success) {
                    const user = data.user;
                    document.getElementById('modal-id').textContent = user.usc_id;
                    document.getElementById('modal-name').textContent = user.first_name + ' ' + user.last_name;
                    document.getElementById('modal-address').textContent = (user.barangay ? user.barangay + ', ' : '') + user.city + ', ' + user.province;
                    document.getElementById('modal-contact').textContent = user.contact_number;
                    document.getElementById('modal-email').textContent = user.email;
                    modal.dataset.userId = user.id;

                    modal.classList.add('active');
                    modalOverlay.classList.add('active');
                } else {
                    alert(data.message || 'User not found');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });

        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(registerForm);
            const data = Object.fromEntries(formData);

            if (!data.usc_id || !data.first_name || !data.last_name || !data.barangay || !data.city || !data.province || !data.contact_number || !data.email) {
                alert('Please fill in all required fields');
                return;
            }

            try {
                const response = await fetch('api/register.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams(data)
                });

                const result = await response.json();

                if (result.success) {
                    window.location.href = 'confirmation.php';
                } else {
                    alert(result.message || 'Registration failed');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });

        signoutForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const uscId = document.getElementById('signout-id-number').value.trim();

            if (!uscId) {
                alert('Please enter your ID number');
                return;
            }

            try {
                const response = await fetch('api/signout.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'usc_id=' + encodeURIComponent(uscId)
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = 'confirmation.php?action=signout&user_id=' + data.user_id;
                } else {
                    alert(data.message || 'Sign out failed');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });

        modalConfirmBtn.addEventListener('click', async () => {
            const userId = modal.dataset.userId;
            try {
                const response = await fetch('api/signin.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'user_id=' + encodeURIComponent(userId)
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = 'confirmation.php';
                } else {
                    alert(data.message || 'Sign in failed');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });

        userToggle.addEventListener('change', () => updatePortal('user'));
        adminToggle.addEventListener('change', () => updatePortal('admin'));
    </script>
</body>
</html>
