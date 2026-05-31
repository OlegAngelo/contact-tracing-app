<?php
session_start();
require_once __DIR__ . '/../../config/db_config.php';
require_once __DIR__ . '/../../includes/User.php';
require_once __DIR__ . '/../../includes/SignLog.php';

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
    <div class="admin-container">
        <div class="admin-header-section">
            <div class="admin-header-content">
                <h1 class="admin-title">Contact Tracing System</h1>
                <p class="admin-subtitle">Department of Computer Engineering</p>
            </div>
            <a href="logout.php" class="btn-logout">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" x2="9" y1="12" y2="12"></line></svg>
                Logout
            </a>
        </div>

        <div class="admin-dashboard-header">
            <div>
                <h2 class="dashboard-title">Admin Dashboard</h2>
                <p class="dashboard-subtitle">Search and manage visitor records</p>
            </div>
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tab-icon"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            City
                        </label>
                        <label for="tab-barangay" class="toggle-label barangay-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tab-icon"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            Barangay
                        </label>
                        <label for="tab-province" class="toggle-label province-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tab-icon"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            Province
                        </label>
                        <label for="tab-usc_id" class="toggle-label id-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tab-icon"><line x1="4" x2="20" y1="9" y2="9"></line><line x1="4" x2="20" y1="15" y2="15"></line><line x1="10" x2="8" y1="3" y2="21"></line><line x1="16" x2="14" y1="3" y2="21"></line></svg>
                            ID Number
                        </label>
                        <label for="tab-name" class="toggle-label name-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tab-icon"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>
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



