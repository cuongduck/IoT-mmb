<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../includes/functions.php';

$line = isset($_GET['line']) ? $_GET['line'] : 'L5';

try {
    // Xác định các cột dựa trên line
    $columns = [];
    if ($line === 'L5') {
        $columns = [
            'L5_KL_Coi1_Bom',
            'L5_KL_Coi1_Xa',
            'L5_KL_Coi1_KS',
            'L5_KL_Coi2_Bom',
            'L5_KL_Coi2_Xa',
            'L5_KL_Coi2_KS'
        ];
    } else if ($line === 'L6') {
        $columns = [
            'L6_KL_Coi1_Bom',
            'L6_KL_Coi1_Xa',
            'L6_KL_Coi1_KS',
            'L6_KL_Coi2_Bom',
            'L6_KL_Coi2_Xa',
            'L6_KL_Coi2_KS'
        ];
    }

    // Tạo điều kiện WHERE để kiểm tra ít nhất một cột không NULL
    $whereConditions = [];
    foreach ($columns as $col) {
        $whereConditions[] = "$col IS NOT NULL";
    }
    $whereClause = "WHERE (" . implode(' OR ', $whereConditions) . ")";

    // Tạo câu truy vấn
    $columnStr = implode(', ', array_merge(['ID', 'Date'], $columns));
    $query = "SELECT $columnStr 
             FROM Data_tron 
             $whereClause
             ORDER BY Date DESC 
             LIMIT 350";

    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception($conn->error);
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        // Kiểm tra xem dòng này có ít nhất một giá trị không NULL không
        $hasValue = false;
        foreach ($columns as $col) {
            if ($row[$col] !== null) {
                $hasValue = true;
                break;
            }
        }
        
        // Chỉ thêm vào data nếu có ít nhất một giá trị không NULL
        if ($hasValue) {
            $data[] = $row;
        }
    }

    echo json_encode([
        'success' => true,
        'data' => $data
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
?>