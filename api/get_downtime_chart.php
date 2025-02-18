<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../includes/functions.php';

$period = isset($_GET['period']) ? $_GET['period'] : 'today';

try {
    $originalDateRangeQuery = getDateRangeQuery($period);
    $dateRangeQuery = str_replace('Time', 'Date', $originalDateRangeQuery);

    // Query chính cho total F3
    $mainQuery = "SELECT 
        Ten_Loi as ErrorName,
        SUM(Thoi_Gian_Dung) as Duration,
        GROUP_CONCAT(Ghi_Chu SEPARATOR '; ') as Details
    FROM Downtime 
    $dateRangeQuery
    GROUP BY Ten_Loi
    ORDER BY Duration DESC";

    // Query phụ để lấy thông tin line
    $lineQuery = "SELECT 
        Line,
        COUNT(*) as StopCount,
        SUM(Thoi_Gian_Dung) as TotalDuration
    FROM Downtime 
    $dateRangeQuery
    GROUP BY Line
    ORDER BY Line";

    $mainResult = $conn->query($mainQuery);
    $lineResult = $conn->query($lineQuery);
    
    if (!$mainResult || !$lineResult) {
        throw new Exception($conn->error);
    }

    $data = [
        'totalF3' => [],
        'lineData' => []
    ];

    // Xử lý data cho total F3
    while ($row = $mainResult->fetch_assoc()) {
        $data['totalF3'][] = [
            'name' => $row['ErrorName'],
            'value' => floatval($row['Duration']),
            'details' => $row['Details']
        ];
    }

    // Xử lý data cho line
    while ($row = $lineResult->fetch_assoc()) {
        $data['lineData'][] = [
            'line' => $row['Line'],
            'stopCount' => intval($row['StopCount']),
            'duration' => floatval($row['TotalDuration'])
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