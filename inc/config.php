<?php
// Database configuration - isi sesuai environment Anda
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'minihris');
define('DB_USER', 'root');
define('DB_PASS', '');

// Create PDO connection
try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    // Friendly message for dev environment
    echo "<h2>Database connection failed</h2>";
    echo "<p>Check your settings in <code>inc/config.php</code>.</p>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    exit;
}
