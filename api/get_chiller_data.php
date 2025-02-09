<?php
require_once '../config/database.php';
header('Content-Type: application/json');

try {
    $query = "SELECT * FROM Kansui_chiller WHERE ID = 1";
    $result = $conn->query($query);
    if (!$result) {
        throw new Exception($conn->error);
    }
    
    $data = $result->fetch_assoc();
    echo json_encode($data);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>