<?php
require_once __DIR__ . '/../config/db_config.php';

echo "Running migration: Add non-USC visitor support\n";
echo "===============================================\n\n";

$migrations = [
    "ALTER TABLE users MODIFY COLUMN usc_id VARCHAR(20) UNIQUE NULL",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS visitor_type ENUM('USC', 'NON_USC') DEFAULT 'USC' AFTER usc_id",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS visitor_id VARCHAR(20) UNIQUE NULL AFTER visitor_type",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS is_signed_in BOOLEAN DEFAULT FALSE AFTER visitor_id",
    "CREATE INDEX IF NOT EXISTS idx_visitor_id ON users(visitor_id)",
    "CREATE INDEX IF NOT EXISTS idx_visitor_type ON users(visitor_type)"
];

$successful = 0;
$failed = 0;

foreach ($migrations as $migration) {
    echo "Executing: $migration\n";
    if ($conn->query($migration)) {
        echo "Success\n\n";
        $successful++;
    } else {
        echo "Error: " . $conn->error . "\n\n";
        $failed++;
    }
}

echo "===============================================\n";
echo "Migration completed: $successful successful, $failed failed\n";

if ($failed === 0) {
    echo "\nAll migrations completed successfully!\n";
    echo "\nNew columns added to users table:\n";
    echo "- visitor_type: ENUM('USC', 'NON_USC')\n";
    echo "- visitor_id: VARCHAR(20) UNIQUE NULL\n";
    echo "- is_signed_in: BOOLEAN DEFAULT FALSE\n";
} else {
    echo "\nSome migrations failed. Please check the errors above.\n";
}

$conn->close();
?>
