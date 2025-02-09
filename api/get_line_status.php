<?php
require_once '../config/database.php';
header('Content-Type: application/json');

$sql = "SELECT * FROM Line_Status ORDER BY ID DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(['error' => 'No data found']);
}
?>