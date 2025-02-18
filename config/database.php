<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'pnlekoy');
define('DB_PASS', 'Masđ');
define('DB_NAME', 'pnlekoyd');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>