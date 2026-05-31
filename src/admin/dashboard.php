<?php
session_start();
require_once __DIR__ . '/../../config/db_config.php';
require_once __DIR__ . '/../../includes/User.php';
require_once __DIR__ . '/../../includes/SignLog.php';
require_once __DIR__ . '/../../includes/icons.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

$user = new User($conn);
$signLog = new SignLog($conn);
$searchType = isset($_POST['searchType']) ? $_POST['searchType'] : 'city';
$searchValue = isset($_POST['searchValue']) ? $_POST['searchValue'] : '';
$results = [];

// Load all users on page load
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($searchValue)) {
    $users = $user->search($searchType, $searchValue);
    foreach ($users as $u) {
        $results[] = [
            'type' => 'user',
            'user_id' => $u['id'],
            'first_name' => $u['first_name'],
            'last_name' => $u['last_name'],
            'usc_id' => $u['usc_id'],
            'barangay' => $u['barangay'],
            'city' => $u['city'],
            'province' => $u['province'],
            'contact_number' => $u['contact_number'],
            'email' => $u['email']
        ];
    }
} else {
    // Load all users by default on page load
    $allUsers = $user->search('city', '%');
    foreach ($allUsers as $u) {
        $results[] = [
            'type' => 'user',
            'user_id' => $u['id'],
            'first_name' => $u['first_name'],
            'last_name' => $u['last_name'],
            'usc_id' => $u['usc_id'],
            'barangay' => $u['barangay'],
            'city' => $u['city'],
            'province' => $u['province'],
            'contact_number' => $u['contact_number'],
            'email' => $u['email']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Contact Tracing System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="admin-dashboard-page">
    <div class="admin-header">
        <div class="admin-header-container">
            <h1 class="admin-title">Contact Tracing System</h1>
            <p class="admin-subtitle">Department of Computer Engineering</p>
        </div>
    </div>

    <div class="admin-container">
        <div class="admin-dashboard-header">
            <div>
                <h2 class="dashboard-title">Admin Dashboard</h2>
                <p class="dashboard-subtitle">Search and manage visitor records</p>
            </div>
            <a href="logout.php" class="btn-logout">
                <span class="logout-icon">↗</span>
                Logout
            </a>
        </div>

        <div class="search-section">
            <h3 class="section-title">Search Visitors</h3>
            <p class="section-subtitle">Use the tabs below to search by different criteria.</p>

            <div class="search-tabs-container">
                <form method="POST" class="search-form" id="searchForm">
                    <div class="toggle-tabs">
                        <input type="radio" id="tab-city" name="searchType" value="city" checked>
                        <input type="radio" id="tab-barangay" name="searchType" value="barangay">
                        <input type="radio" id="tab-province" name="searchType" value="province">
                        <input type="radio" id="tab-usc_id" name="searchType" value="usc_id">
                        <input type="radio" id="tab-name" name="searchType" value="name">

                        <label for="tab-city" class="toggle-label city-label">
                            <span class="tab-icon">📍</span>
                            City
                        </label>
                        <label for="tab-barangay" class="toggle-label barangay-label">
                            <span class="tab-icon">📍</span>
                            Barangay
                        </label>
                        <label for="tab-province" class="toggle-label province-label">
                            <span class="tab-icon">📍</span>
                            Province
                        </label>
                        <label for="tab-usc_id" class="toggle-label id-label">
                            <span class="tab-icon">#</span>
                            ID Number
                        </label>
                        <label for="tab-name" class="toggle-label name-label">
                            <span class="tab-icon">👤</span>
                            Name
                        </label>

                        <div class="toggle-slider"></div>
                    </div>

                    <div class="search-input-group">
                        <input
                            type="text"
                            id="searchInput"
                            name="searchValue"
                            class="search-input"
                            placeholder="Search by city..."
                            autocomplete="off"
                        >
                        <button type="submit" class="btn-search">
                            <span class="search-icon">🔍</span>
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="results-section">
            <div class="results-header">
                <h3>Visitor Records</h3>
                <p class="results-count"><?php echo count($results); ?> record<?php echo count($results) !== 1 ? 's' : ''; ?> found</p>
            </div>

            <?php if (empty($results)): ?>
                <div class="alert alert-info">No visitor records found.</div>
            <?php else: ?>
                <div class="results-table-container">
                    <table class="results-table">
                        <thead>
                            <tr>
                                <th>ID Number</th>
                                <th>Name</th>
                                <th>Barangay</th>
                                <th>City</th>
                                <th>Province</th>
                                <th>Contact</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $u): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($u['usc_id']); ?></td>
                                    <td><?php echo htmlspecialchars($u['first_name'] . ' ' . $u['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($u['barangay']); ?></td>
                                    <td><?php echo htmlspecialchars($u['city']); ?></td>
                                    <td><?php echo htmlspecialchars($u['province']); ?></td>
                                    <td><?php echo htmlspecialchars($u['contact_number']); ?></td>
                                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const toggleTabs = document.querySelectorAll('input[name="searchType"]');
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');

        const placeholders = {
            city: 'Search by city...',
            barangay: 'Search by barangay...',
            province: 'Search by province...',
            usc_id: 'Search by ID number...',
            name: 'Search by name...'
        };

        toggleTabs.forEach(tab => {
            tab.addEventListener('change', () => {
                const type = tab.value;
                searchInput.placeholder = placeholders[type];
                searchInput.focus();
                searchInput.value = '';
            });
        });
    </script>
</body>
</html>


