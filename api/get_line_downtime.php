<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../includes/functions.php';

$period = isset($_GET['period']) ? $_GET['period'] : 'today';
$line = isset($_GET['line']) ? $_GET['line'] : null;

try {
    // Lấy điều kiện thời gian từ functions.php 
    $originalDateRangeQuery = getDateRangeQuery($period);
    $dateRangeQuery = str_replace('Time', 'Date', $originalDateRangeQuery);



    $query = "SELECT 
        Ten_Loi as ErrorName,
        SUM(Thoi_Gian_Dung) as Duration,
        GROUP_CONCAT(Ghi_Chu SEPARATOR '; ') as Details
    FROM Downtime 
    WHERE Line = ? AND " . substr($dateRangeQuery, 6) . "
    GROUP BY Ten_Loi
    ORDER BY Duration DESC";



    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $line);
    $stmt->execute();
    $result = $stmt->get_result();
    
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



    echo json_encode($data);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
?>