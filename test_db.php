<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    echo "MySQL server is accessible\n";
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS libraflow");
    echo "Database 'libraflow' created successfully\n";
    
    // Test connection to the new database
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=libraflow', 'root', '');
    echo "Connected to libraflow database successfully\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
