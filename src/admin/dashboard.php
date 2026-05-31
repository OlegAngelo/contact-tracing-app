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
$searchType = isset($_POST['searchType']) ? $_POST['searchType'] : '';
$searchValue = isset($_POST['searchValue']) ? $_POST['searchValue'] : '';
$results = [];

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
                <div class="search-tabs">
                    <button class="search-tab active" data-type="city">
                        <span class="tab-icon">📍</span>
                        City
                    </button>
                    <button class="search-tab" data-type="barangay">
                        <span class="tab-icon">📍</span>
                        Barangay
                    </button>
                    <button class="search-tab" data-type="province">
                        <span class="tab-icon">📍</span>
                        Province
                    </button>
                    <button class="search-tab" data-type="usc_id">
                        <span class="tab-icon">#</span>
                        ID Number
                    </button>
                    <button class="search-tab" data-type="name">
                        <span class="tab-icon">👤</span>
                        Name
                    </button>
                    <button class="search-tab" data-type="date">
                        <span class="tab-icon">📅</span>
                        Date/Time
                    </button>
                </div>

                <form method="POST" class="search-form" id="searchForm">
                    <input type="hidden" name="searchType" id="searchType" value="city">

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

        <?php if ($results): ?>
            <div class="results-section">
                <div class="results-header">
                    <h3>Search Results</h3>
                    <p class="results-count"><?php echo count($results); ?> result<?php echo count($results) !== 1 ? 's' : ''; ?> found</p>
                </div>

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
            </div>
        <?php endif; ?>
    </div>

    <script>
        const searchTabs = document.querySelectorAll('.search-tab');
        const searchTypeInput = document.getElementById('searchType');
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');

        const placeholders = {
            city: 'Search by city...',
            barangay: 'Search by barangay...',
            province: 'Search by province...',
            usc_id: 'Search by ID number...',
            name: 'Search by name...',
            date: 'Search by date...'
        };

        searchTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                searchTabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                const type = tab.dataset.type;
                searchTypeInput.value = type;

                if (type === 'date') {
                    searchInput.type = 'date';
                    searchInput.placeholder = '';
                } else {
                    searchInput.type = 'text';
                    searchInput.placeholder = placeholders[type];
                }

                searchInput.focus();
                searchInput.value = '';
            });
        });
    </script>
</body>
</html>

