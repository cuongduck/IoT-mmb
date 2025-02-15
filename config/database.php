<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'pnlek_cd');
define('DB_PASS', 'fefefefefe');
define('DB_NAME', 'pnlde');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>