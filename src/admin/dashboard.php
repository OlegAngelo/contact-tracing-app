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
$searchType = isset($_GET['searchType']) ? $_GET['searchType'] : '';
$searchValue = isset($_GET['searchValue']) ? $_GET['searchValue'] : '';
$searchDate = isset($_GET['searchDate']) ? $_GET['searchDate'] : '';
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($searchType || $searchDate)) {
    if ($searchDate) {
        $results = $signLog->getLogsByDate($searchDate);
    } elseif ($searchType) {
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
<body>
    <nav class="navbar">
        <div class="container">
            <h1>DCE Contact Tracing System - Admin Dashboard</h1>
            <ul>
                <li><a href="#">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="search-container">
            <h2>Search Users & Logs</h2>

            <div class="search-tabs">
                <h3>Search Options:</h3>

                <!-- Search by Name -->
                <div class="search-card">
                    <h4>Search by Name</h4>
                    <form method="GET">
                        <div class="form-group">
                            <input type="hidden" name="searchType" value="name">
                            <input type="text" name="searchValue" placeholder="Enter first or last name" required>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>

                <!-- Search by Location -->
                <div class="search-card">
                    <h4>Search by City</h4>
                    <form method="GET">
                        <div class="form-group">
                            <input type="hidden" name="searchType" value="city">
                            <input type="text" name="searchValue" placeholder="Enter city" required>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>

                <div class="search-card">
                    <h4>Search by Barangay</h4>
                    <form method="GET">
                        <div class="form-group">
                            <input type="hidden" name="searchType" value="barangay">
                            <input type="text" name="searchValue" placeholder="Enter barangay" required>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>

                <div class="search-card">
                    <h4>Search by Province</h4>
                    <form method="GET">
                        <div class="form-group">
                            <input type="hidden" name="searchType" value="province">
                            <input type="text" name="searchValue" placeholder="Enter province" required>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>

                <!-- Search by ID -->
                <div class="search-card">
                    <h4>Search by USC ID</h4>
                    <form method="GET">
                        <div class="form-group">
                            <input type="hidden" name="searchType" value="usc_id">
                            <input type="text" name="searchValue" placeholder="Enter USC ID" required>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>

                <!-- Search by Date -->
                <div class="search-card">
                    <h4>Search by Date (Entry/Exit)</h4>
                    <form method="GET">
                        <div class="form-group">
                            <input type="date" name="searchDate" required>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Search Results -->
        <?php if ($searchType || $searchDate): ?>
            <div class="results-container">
                <h3>Search Results</h3>

                <?php if (empty($results)): ?>
                    <div class="alert alert-info">No results found.</div>
                <?php else: ?>
                    <?php if ($searchDate): ?>
                        <!-- Display logs -->
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>USC ID</th>
                                    <th>Action</th>
                                    <th>Date & Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($results as $log): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($log['first_name'] . ' ' . $log['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($log['usc_id']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $log['action'] === 'IN' ? 'badge-success' : 'badge-danger'; ?>">
                                                <?php echo $log['action']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($log['timestamp']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <!-- Display users -->
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>USC ID</th>
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
                                        <td><?php echo htmlspecialchars($u['first_name'] . ' ' . $u['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($u['usc_id']); ?></td>
                                        <td><?php echo htmlspecialchars($u['barangay']); ?></td>
                                        <td><?php echo htmlspecialchars($u['city']); ?></td>
                                        <td><?php echo htmlspecialchars($u['province']); ?></td>
                                        <td><?php echo htmlspecialchars($u['contact_number']); ?></td>
                                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2024 Contact Tracing System. All rights reserved.</p>
    </footer>
</body>
</html>
