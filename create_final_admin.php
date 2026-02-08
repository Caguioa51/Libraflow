<?php
require_once __DIR__ . '/vendor/autoload.php';

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=libraflow_new', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to MySQL database successfully!" . PHP_EOL;

    // Check if admin user exists and update with unique RFID
    $stmt = $pdo->prepare('SELECT id, barcode FROM users WHERE email = ?');
    $stmt->execute(['admin@gmail.com']);
    $user = $stmt->fetch();

    if ($user) {
        echo 'Admin user already exists, updating...' . PHP_EOL;
        $userId = $user['id'];

        // Use a unique RFID if current one conflicts
        $rfidValue = $user['barcode'] === 'RFID123456789' ? 'RFID_ADMIN_' . uniqid() : 'RFID123456789';

        $sql = 'UPDATE users SET name = ?, password = ?, role = ?, student_id = ?, barcode = ? WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $password = password_hash('11111111', PASSWORD_DEFAULT);
        $stmt->execute(['Admin User', $password, 'admin', 'ADMIN001', $rfidValue, $userId]);

        echo 'Admin user updated successfully!' . PHP_EOL;
        echo 'Email: admin@gmail.com' . PHP_EOL;
        echo 'Password: 11111111' . PHP_EOL;
        echo 'RFID: ' . $rfidValue . PHP_EOL;

    } else {
        echo 'Creating new admin user...' . PHP_EOL;
        $userId = uniqid();
        $rfidValue = 'RFID_ADMIN_' . time();

        $sql = 'INSERT INTO users (id, name, email, password, role, student_id, barcode, email_verified_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), NOW())';
        $stmt = $pdo->prepare($sql);
        $password = password_hash('11111111', PASSWORD_DEFAULT);
        $stmt->execute([$userId, 'Admin User', 'admin@gmail.com', $password, 'admin', 'ADMIN001', $rfidValue]);

        echo 'Admin user created successfully!' . PHP_EOL;
        echo 'Email: admin@gmail.com' . PHP_EOL;
        echo 'Password: 11111111' . PHP_EOL;
        echo 'RFID: ' . $rfidValue . PHP_EOL;
    }

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . PHP_EOL;

    // Try with empty password
    try {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=libraflow_new', 'root');
        echo "Connected with empty password!" . PHP_EOL;

        // Check if admin user exists
        $stmt = $pdo->prepare('SELECT id, barcode FROM users WHERE email = ?');
        $stmt->execute(['admin@gmail.com']);
        $user = $stmt->fetch();

        if ($user) {
            echo 'Admin user already exists, updating...' . PHP_EOL;
            $userId = $user['id'];

            // Use a unique RFID if current one conflicts
            $rfidValue = $user['barcode'] === 'RFID123456789' ? 'RFID_ADMIN_' . uniqid() : 'RFID123456789';

            $sql = 'UPDATE users SET name = ?, password = ?, role = ?, student_id = ?, barcode = ? WHERE id = ?';
            $stmt = $pdo->prepare($sql);
            $password = password_hash('11111111', PASSWORD_DEFAULT);
            $stmt->execute(['Admin User', $password, 'admin', 'ADMIN001', $rfidValue, $userId]);

            echo 'Admin user updated successfully!' . PHP_EOL;
            echo 'Email: admin@gmail.com' . PHP_EOL;
            echo 'Password: 11111111' . PHP_EOL;
            echo 'RFID: ' . $rfidValue . PHP_EOL;

        } else {
            echo 'Creating new admin user...' . PHP_EOL;
            $userId = uniqid();
            $rfidValue = 'RFID_ADMIN_' . time();

            $sql = 'INSERT INTO users (id, name, email, password, role, student_id, barcode, email_verified_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), NOW())';
            $stmt = $pdo->prepare($sql);
            $password = password_hash('11111111', PASSWORD_DEFAULT);
            $stmt->execute([$userId, 'Admin User', 'admin@gmail.com', $password, 'admin', 'ADMIN001', $rfidValue]);

            echo 'Admin user created successfully!' . PHP_EOL;
            echo 'Email: admin@gmail.com' . PHP_EOL;
            echo 'Password: 11111111' . PHP_EOL;
            echo 'RFID: ' . $rfidValue . PHP_EOL;
        }

    } catch(PDOException $e2) {
        echo "Second connection attempt failed: " . $e2->getMessage() . PHP_EOL;
    }
