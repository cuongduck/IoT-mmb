<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../includes/functions.php';

$period = isset($_GET['period']) ? $_GET['period'] : 'today';

try {
    // Lấy điều kiện thời gian từ functions.php
    $dateRangeQuery = getDateRangeQuery($period);
    
    // Thay thế WHERE thành AND vì điều kiện WHERE đã có trong mỗi subquery
    $dateRangeQuery = str_replace('WHERE', 'AND', $dateRangeQuery);
    
    $query = "SELECT 
        'Line 5' as line_name,
        ROUND(
            CASE 
                WHEN SUM(COALESCE(L5_SL_KH, 0)) > 0 
                THEN (SUM(COALESCE(L5_Tong_Goi, 0)) * 100.0) / SUM(COALESCE(L5_SL_KH, 0))
                ELSE 0 
            END,
        2) as avg_oee
        FROM OEE 
        WHERE L5_SL_KH IS NOT NULL $dateRangeQuery
        
        UNION ALL
        
        SELECT 
        'Line 6' as line_name,
        ROUND(
            CASE 
                WHEN SUM(COALESCE(L6_SL_KH, 0)) > 0 
                THEN (SUM(COALESCE(L6_Tong_Goi, 0)) * 100.0) / SUM(COALESCE(L6_SL_KH, 0))
                ELSE 0 
            END,
        2) as avg_oee
        FROM OEE 
        WHERE L6_SL_KH IS NOT NULL $dateRangeQuery
        
        UNION ALL
        
        SELECT 
        'Line 7' as line_name,
        ROUND(
            CASE 
                WHEN SUM(COALESCE(L7_SL_KH, 0)) > 0 
                THEN (SUM(COALESCE(L7_Tong_Goi, 0)) * 100.0) / SUM(COALESCE(L7_SL_KH, 0))
                ELSE 0 
            END,
        2) as avg_oee
        FROM OEE 
        WHERE L7_SL_KH IS NOT NULL $dateRangeQuery
        
        UNION ALL
        
        SELECT 
        'Line 8' as line_name,
        ROUND(
            CASE 
                WHEN SUM(COALESCE(L8_SL_KH, 0)) > 0 
                THEN (SUM(COALESCE(L8_Tong_Goi, 0)) * 100.0) / SUM(COALESCE(L8_SL_KH, 0))
                ELSE 0 
            END,
        2) as avg_oee
        FROM OEE 
        WHERE L8_SL_KH IS NOT NULL $dateRangeQuery
        
        ORDER BY avg_oee DESC";

    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception($conn->error);
    }

    $lines = [];
    $values = [];

    while ($row = $result->fetch_assoc()) {
        if ($row['avg_oee'] !== null) {  // Chỉ thêm vào kết quả nếu có giá trị OEE
            $lines[] = $row['line_name'];
            $values[] = floatval($row['avg_oee']);
        }
    }

    echo json_encode([
        'lines' => $lines,
        'values' => $values
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
?>