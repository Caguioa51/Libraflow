<?php
require_once __DIR__ . '/vendor/autoload.php';

// Connect to MySQL database
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=libraflow_new', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to MySQL database successfully!" . PHP_EOL;

    // Check if admin exists
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute(['admin@gmail.com']);
    $user = $stmt->fetch();

    if ($user) {
        echo 'Admin user already exists, updating...' . PHP_EOL;
        $userId = $user['id'];
        $sql = 'UPDATE users SET name = ?, password = ?, role = ?, student_id = ?, barcode = ? WHERE id = ?';
    } else {
        echo 'Creating new admin user...' . PHP_EOL;
        $userId = uniqid();
        $sql = 'INSERT INTO users (id, name, email, password, role, student_id, barcode, email_verified_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), NOW())';
    }

    $password = password_hash('11111111', PASSWORD_DEFAULT);

    $stmt = $pdo->prepare($sql);
    if ($user) {
        $stmt->execute(['Admin User', $password, 'admin', 'ADMIN001', 'RFID123456789', $userId]);
    } else {
        $stmt->execute([$userId, 'Admin User', 'admin@gmail.com', $password, 'admin', 'ADMIN001', 'RFID123456789']);
    }

    echo 'Admin user created successfully!' . PHP_EOL;
    echo 'Email: admin@gmail.com' . PHP_EOL;
    echo 'Password: 11111111' . PHP_EOL;
    echo 'RFID: RFID123456789' . PHP_EOL;

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . PHP_EOL;

    // Try with empty password
    try {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=libraflow_new', 'root');
        echo "Connected with empty password!" . PHP_EOL;

        // Check if admin exists
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute(['admin@gmail.com']);
        $user = $stmt->fetch();

        if ($user) {
            echo 'Admin user already exists, updating...' . PHP_EOL;
            $userId = $user['id'];
            $sql = 'UPDATE users SET name = ?, password = ?, role = ?, student_id = ?, barcode = ? WHERE id = ?';
        } else {
            echo 'Creating new admin user...' . PHP_EOL;
            $userId = uniqid();
            $sql = 'INSERT INTO users (id, name, email, password, role, student_id, barcode, email_verified_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), NOW())';
        }

        $password = password_hash('11111111', PASSWORD_DEFAULT);

        $stmt = $pdo->prepare($sql);
        if ($user) {
            $stmt->execute(['Admin User', $password, 'admin', 'ADMIN001', 'RFID123456789', $userId]);
        } else {
            $stmt->execute([$userId, 'Admin User', 'admin@gmail.com', $password, 'admin', 'ADMIN001', 'RFID123456789']);
        }

        echo 'Admin user created successfully!' . PHP_EOL;
        echo 'Email: admin@gmail.com' . PHP_EOL;
        echo 'Password: 11111111' . PHP_EOL;
        echo 'RFID: RFID123456789' . PHP_EOL;

    } catch(PDOException $e2) {
        echo "Second connection attempt failed: " . $e2->getMessage() . PHP_EOL;
    }
}
