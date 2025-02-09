<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../includes/functions.php';

$line = isset($_GET['line']) ? $_GET['line'] : 'L5';
$period = isset($_GET['period']) ? $_GET['period'] : 'today';

try {
    // Xác định các cột dựa trên line
    $tlibCol = "{$line}_TLTB";
    $nhietCol = "{$line}_Nhiet_5";
    $flowCol = "{$line}_Flow";
    
    $query = "SELECT 
        DATE_FORMAT(Time, '%H:%i') as time_label,
        $tlibCol as weight,
        $nhietCol as temperature,
        $flowCol as flow
    FROM Trend_Line
    WHERE Time >= NOW() - INTERVAL 8 HOUR
    ORDER BY Time DESC
    LIMIT 12";

    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception($conn->error);
    }

    $data = [
        'labels' => [],
        'weight' => [],
        'temperature' => [],
        'flow' => []
    ];

    while ($row = $result->fetch_assoc()) {
        $data['labels'][] = $row['time_label'];
        $data['weight'][] = floatval($row['weight']);
        $data['temperature'][] = floatval($row['temperature']);
        $data['flow'][] = floatval($row['flow']);
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