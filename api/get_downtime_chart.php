<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../includes/functions.php';

$period = isset($_GET['period']) ? $_GET['period'] : 'today';

try {
    // Điều chỉnh điều kiện thời gian từ functions.php 
    // Thay thế 'Time' bằng 'Date' trong câu query
    $originalDateRangeQuery = getDateRangeQuery($period);
    $dateRangeQuery = str_replace('Time', 'Date', $originalDateRangeQuery);

    // Debug: In ra câu query để kiểm tra
    error_log("Date Range Query: " . $dateRangeQuery);

    $query = "SELECT 
        Ten_Loi as ErrorName,
        SUM(Thoi_Gian_Dung) as Duration,
        GROUP_CONCAT(Ghi_Chu SEPARATOR '; ') as Details
    FROM Downtime 
    $dateRangeQuery
    GROUP BY Ten_Loi
    ORDER BY Duration DESC";

    // Debug: In ra câu query hoàn chỉnh
    error_log("Full Query: " . $query);

    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception($conn->error);
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'name' => $row['ErrorName'],
            'value' => floatval($row['Duration']),
            'details' => $row['Details']
        ];
    }

    // Debug: In ra kết quả
    error_log("Query Result: " . json_encode($data));

    echo json_encode($data);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
?>