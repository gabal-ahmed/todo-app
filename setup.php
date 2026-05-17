<?php
require_once 'config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db(DB_NAME);

$conn->query("
    CREATE TABLE IF NOT EXISTS tasks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        priority ENUM('low','medium','high') DEFAULT 'medium',
        category VARCHAR(100) DEFAULT 'General',
        is_completed TINYINT(1) DEFAULT 0,
        due_date DATE NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

echo '<h2 style="font-family:sans-serif;color:green;">✅ Database & table created successfully!</h2>';
echo '<p style="font-family:sans-serif;"><a href="index.php">Go to App →</a></p>';
$conn->close();
