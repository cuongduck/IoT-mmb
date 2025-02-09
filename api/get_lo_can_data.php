<?php
require_once '../config/database.php';
header('Content-Type: application/json');

try {
    $line = isset($_GET['line']) ? $_GET['line'] : 'L5';
    
    // Lấy danh sách các motor cần theo dõi
    $motors = [
        'BT', 'Nhoi1', 'Nhoi2', 
        'Tho1', 'Tho2', 'Tho3',
        'BTTho', 'BTTinh',
        'Tinh1', 'Tinh2', 'Tinh3', 'Tinh4', 'Tinh5', 'Tinh6', 'Tinh7',
        'DCS', 'LNhung'
    ];
    
    // Xây dựng câu query động dựa trên line và motors
    $selectFields = [];
    foreach ($motors as $motor) {
        $selectFields[] = "{$line}_{$motor}_Hz";
        $selectFields[] = "{$line}_{$motor}_A";
        $selectFields[] = "{$line}_{$motor}_T";
    }
    
    $selectString = implode(", ", $selectFields);
    $query = "SELECT {$selectString} FROM Realtime_can_F3 WHERE ID = 1";

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