<?php
// Simple PHP test to bypass Laravel and test basic setup
header('Content-Type: text/plain');

echo "=== PHP TEST ===\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Current Time: " . date('Y-m-d H:i:s') . "\n";

echo "\n=== ENVIRONMENT ===\n";
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "SCRIPT_FILENAME: " . $_SERVER['SCRIPT_FILENAME'] . "\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";

echo "\n=== DATABASE TEST ===\n";
try {
    $host = getenv('DB_HOST') ?: 'dpg-d4uin59r0fns73939jbg-a';
    $database = getenv('DB_DATABASE') ?: 'libraflow_aa5l';
    $username = getenv('DB_USERNAME') ?: 'libraflow_aa5l_user';
    $password = getenv('DB_PASSWORD') ?: 'iluAm4TPp6l1vuPYBTcKUHr1Hgg4ld66';
    
    echo "DB Host: " . $host . "\n";
    echo "DB Name: " . $database . "\n";
    echo "DB User: " . $username . "\n";
    
    $dsn = "pgsql:host=$host;dbname=$database";
    $pdo = new PDO($dsn, $username, $password);
    
    echo "✅ Database Connection: SUCCESS\n";
    
    // Test a simple query
    $stmt = $pdo->query("SELECT version()");
    $version = $stmt->fetchColumn();
    echo "✅ PostgreSQL Version: " . $version . "\n";
    
} catch (Exception $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
}

echo "\n=== FILE PERMISSIONS ===\n";
$storagePath = __DIR__ . '/../storage';
if (is_dir($storagePath)) {
    echo "✅ Storage directory exists\n";
    echo "Storage writable: " . (is_writable($storagePath) ? "YES" : "NO") . "\n";
} else {
    echo "❌ Storage directory missing\n";
}

$cachePath = __DIR__ . '/../bootstrap/cache';
if (is_dir($cachePath)) {
    echo "✅ Bootstrap cache directory exists\n";
    echo "Cache writable: " . (is_writable($cachePath) ? "YES" : "NO") . "\n";
} else {
    echo "❌ Bootstrap cache directory missing\n";
}

echo "\n=== SUCCESS ===\n";
echo "This basic PHP test works!\n";
echo "If you see this, the issue is in Laravel configuration, not PHP/database.\n";
?>
