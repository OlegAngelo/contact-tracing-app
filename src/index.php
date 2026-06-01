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
    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <span id="toast-message"></span>
    </div>

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

            <div class="toggle-slider-home"></div>
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
                    <p class="signin-subtitle">Enter your USC ID or Visitor ID to retrieve your information and sign in.</p>

                    <form id="signin-form" method="POST" action="api/fetch-user.php">
                        <div class="form-group">
                            <label for="id-number">ID Number or Visitor ID</label>
                            <input type="text" id="id-number" name="usc_id" placeholder="241105130 or VISITOR_00001" required autocomplete="off">
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
                    <h2>Register</h2>
                    <p class="register-subtitle">Please fill in all required fields. You will be automatically signed in after registration.</p>

                    <form id="register-form">
                        <div class="form-group full-width">
                            <label>Registration Type <span class="required">*</span></label>
                            <div class="visitor-type-toggle">
                                <input type="radio" id="reg-type-usc" name="visitor_type" value="USC" checked>
                                <input type="radio" id="reg-type-visitor" name="visitor_type" value="NON_USC">

                                <label for="reg-type-usc" class="toggle-option usc-option">
                                    <span>USC Member</span>
                                </label>
                                <label for="reg-type-visitor" class="toggle-option visitor-option">
                                    <span>Visitor</span>
                                </label>
                                <div class="toggle-indicator"></div>
                            </div>
                        </div>

                        <div class="form-group full-width" id="usc-id-group">
                            <label for="reg-id-number">USC ID Number <span class="required">*</span></label>
                            <input type="text" id="reg-id-number" name="usc_id" placeholder="241105130" autocomplete="off">
                        </div>

                        <div class="form-group full-width" id="visitor-info-message" style="display: none; background-color: #f0f4f8; padding: 12px; border-radius: 6px; border-left: 4px solid #4361ee; color: #4a5568; font-size: 13px;">
                            <strong>Temporary Visitor ID:</strong> You will receive a temporary visitor ID after successfully registering and signing in.
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
                    <p class="signout-subtitle">Enter your USC ID or Visitor ID to confirm you want to sign out.</p>

                    <form id="signout-form">
                        <div class="form-group">
                            <label for="signout-id-number">ID Number or Visitor ID</label>
                            <input type="text" id="signout-id-number" name="usc_id" placeholder="241105130 or VISITOR_00001" required autocomplete="off">
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
                <h2 id="modal-title">Confirm Your Information</h2>
                <p class="modal-subtitle" id="modal-subtitle">Please verify that your information is correct before signing in.</p>

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

                <div id="admin-alert" class="admin-alert" style="display: none;"></div>

                <form id="admin-login-form" class="admin-login-form">
                    <div class="form-group">
                        <label for="admin-username">Username</label>
                        <input type="text" id="admin-username" name="username" placeholder="Enter username" required autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="admin-password">Password</label>
                        <input type="password" id="admin-password" name="password" placeholder="Enter password" required>
                    </div>

                    <button type="submit" class="btn-login">Login</button>
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
        const adminLoginForm = document.getElementById('admin-login-form');
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
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');

        function showToast(message, duration = 2000) {
            toastMessage.textContent = message;
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, duration);
        }

        function checkForMessage() {
            const params = new URLSearchParams(window.location.search);
            const message = params.get('message');
            if (message) {
                showToast(decodeURIComponent(message));
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        }

        checkForMessage();

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
                    const displayId = user.visitor_type === 'NON_USC' ? user.visitor_id : user.usc_id;
                    document.getElementById('modal-id').textContent = displayId + (user.visitor_type === 'NON_USC' ? ' (Temporary Visitor ID)' : '');
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
            const visitorType = document.querySelector('input[name="visitor_type"]:checked').value;

            if (!data.first_name || !data.last_name || !data.barangay || !data.city || !data.province || !data.contact_number || !data.email) {
                alert('Please fill in all required fields');
                return;
            }

            if (visitorType === 'USC' && !data.usc_id) {
                alert('USC ID is required for USC members');
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
                    if (visitorType === 'NON_USC' && result.visitor_id) {
                        const message = 'Registration successful! Your temporary Visitor ID is: ' + result.visitor_id;
                        showToast(message, 4000);
                        setTimeout(() => {
                            window.location.href = 'index.php?message=' + encodeURIComponent(message);
                        }, 500);
                    } else {
                        window.location.href = 'index.php?message=' + encodeURIComponent('Successfully registered and signed in!');
                    }
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
                    const displayId = user.visitor_type === 'NON_USC' ? user.visitor_id : user.usc_id;
                    document.getElementById('modal-id').textContent = displayId + (user.visitor_type === 'NON_USC' ? ' (Temporary Visitor ID)' : '');
                    document.getElementById('modal-name').textContent = user.first_name + ' ' + user.last_name;
                    document.getElementById('modal-address').textContent = (user.barangay ? user.barangay + ', ' : '') + user.city + ', ' + user.province;
                    document.getElementById('modal-contact').textContent = user.contact_number;
                    document.getElementById('modal-email').textContent = user.email;
                    modal.dataset.userId = user.id;
                    modal.dataset.modalType = 'signout';

                    document.getElementById('modal-title').textContent = 'Confirm Sign Out';
                    document.getElementById('modal-subtitle').textContent = 'Please verify your information before signing out.';
                    document.getElementById('modal-confirm').textContent = 'Confirm & Sign Out';

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

        modalConfirmBtn.addEventListener('click', async () => {
            const userId = modal.dataset.userId;
            const modalType = modal.dataset.modalType || 'signin';

            try {
                const endpoint = modalType === 'signout' ? 'api/signout.php' : 'api/signin.php';
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'user_id=' + encodeURIComponent(userId)
                });

                const data = await response.json();

                if (data.success) {
                    if (modalType === 'signout') {
                        window.location.href = 'index.php?message=' + encodeURIComponent('Successfully signed out!');
                    } else {
                        window.location.href = 'index.php?message=' + encodeURIComponent('Successfully signed in!');
                    }
                } else {
                    alert(data.message || (modalType === 'signout' ? 'Sign out failed' : 'Sign in failed'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });

        userToggle.addEventListener('change', () => updatePortal('user'));
        adminToggle.addEventListener('change', () => updatePortal('admin'));

        // Visitor type toggle handler
        const visitorTypeRadios = document.querySelectorAll('input[name="visitor_type"]');
        const uscIdGroup = document.getElementById('usc-id-group');
        const visitorInfoMessage = document.getElementById('visitor-info-message');
        const uscIdInput = document.getElementById('reg-id-number');

        function updateVisitorTypeDisplay() {
            const selectedType = document.querySelector('input[name="visitor_type"]:checked').value;
            if (selectedType === 'USC') {
                uscIdGroup.style.display = 'block';
                visitorInfoMessage.style.display = 'none';
                uscIdInput.required = true;
            } else {
                uscIdGroup.style.display = 'none';
                visitorInfoMessage.style.display = 'block';
                uscIdInput.required = false;
                uscIdInput.value = '';
            }
        }

        visitorTypeRadios.forEach(radio => {
            radio.addEventListener('change', updateVisitorTypeDisplay);
        });

        adminLoginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('admin-username').value.trim();
            const password = document.getElementById('admin-password').value.trim();

            if (!username || !password) {
                const alertDiv = document.getElementById('admin-alert');
                alertDiv.textContent = 'Please enter username and password.';
                alertDiv.className = 'admin-alert alert-error';
                alertDiv.style.display = 'block';
                return;
            }

            try {
                const response = await fetch('admin/api/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'username=' + encodeURIComponent(username) + '&password=' + encodeURIComponent(password)
                });

                const data = await response.json();

                if (data.success) {
                    showToast('Login successful!');
                    setTimeout(() => {
                        window.location.href = 'admin/dashboard.php';
                    }, 1000);
                } else {
                    const alertDiv = document.getElementById('admin-alert');
                    alertDiv.textContent = data.message || 'Invalid username or password.';
                    alertDiv.className = 'admin-alert alert-error';
                    alertDiv.style.display = 'block';
                }
            } catch (error) {
                console.error('Error:', error);
                const alertDiv = document.getElementById('admin-alert');
                alertDiv.textContent = 'An error occurred. Please try again.';
                alertDiv.className = 'admin-alert alert-error';
                alertDiv.style.display = 'block';
            }
        });
    </script>
</body>
</html>
