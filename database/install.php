<?php
/**
 * Run once: http://localhost/webfinal/database/install.php
 * Creates database and all tables with sample data.
 */
$sql = file_get_contents(__DIR__ . '/schema.sql');

try {
    $pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    $statements = array_filter(array_map('trim', explode(';', $sql)));
    foreach ($statements as $stmt) {
        if ($stmt !== '') {
            $pdo->exec($stmt);
        }
    }

    echo '<h2 style="color:#006994;">Database installed successfully!</h2>';
    echo '<p><a href="/webfinal/index.php">Go to Homepage</a></p>';
    echo '<p>Admin login: admin@campus.com / password</p>';
} catch (PDOException $e) {
    echo '<h2 style="color:red;">Error: ' . htmlspecialchars($e->getMessage()) . '</h2>';
    echo '<p>Make sure XAMPP MySQL is running.</p>';
}
