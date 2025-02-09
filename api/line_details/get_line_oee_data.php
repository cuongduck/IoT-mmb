<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../includes/functions.php';

$period = isset($_GET['period']) ? $_GET['period'] : 'today';
$line = isset($_GET['line']) ? $_GET['line'] : 'L5';

try {
    // Lấy điều kiện thời gian
    $dateRangeQuery = getDateRangeQuery($period);
    
    // Thêm LIMIT chỉ khi period là 'today'
    $limitClause = ($period === 'today') ? "LIMIT 8" : "";
    
    // Xác định các cột dựa trên line được chọn
    $oeeCol = "{$line}_Tong_Goi";
    $targetCol = "{$line}_SL_KH";
    
$baseQuery = "WITH raw_data AS (
    SELECT 
        Time,
        HOUR(Time) as hour_val,
        CASE 
            WHEN '$period' = 'today' THEN
                DATE_FORMAT(Time, '%H:00')
            WHEN '$period' = 'yesterday' THEN
                CASE 
                    WHEN (TIME(Time) >= '06:35:00' AND TIME(Time) < '15:35:00') THEN 'Ca 1'
                    WHEN (TIME(Time) >= '15:50:00' AND TIME(Time) < '23:35:00') THEN 'Ca 2'
                    WHEN ((TIME(Time) >= '23:36:00' AND TIME(Time) <= '23:59:59') OR
                          (TIME(Time) >= '00:00:00' AND TIME(Time) < '06:35:00')) THEN 'Ca 3'
                END
            WHEN '$period' IN ('week', 'last_week') THEN
                DATE_FORMAT(Time, '%d/%m')
            WHEN '$period' = 'month' THEN
                CONCAT('Tuần ', FLOOR(DATEDIFF(Time, 
                DATE_FORMAT(Time, '%Y-%m-01')) / 7) + 1)
        END as period,
        $oeeCol as actual,
        $targetCol as target
    FROM OEE 
    $dateRangeQuery
),
grouped_data AS (
    SELECT 
        period,
        MAX(hour_val) as hour_sort,
        ROUND(
            CASE 
                WHEN SUM(COALESCE(target, 0)) > 0 
                THEN (SUM(COALESCE(actual, 0)) * 100.0) / SUM(COALESCE(target, 0))
                ELSE 0
            END,
        2) as oee_value,
        SUM(COALESCE(actual, 0)) as actual_production,
        SUM(COALESCE(target, 0)) as target_production
    FROM raw_data
    WHERE period IS NOT NULL
    GROUP BY period
),
latest_data AS (
    SELECT *
    FROM grouped_data
    ORDER BY hour_sort DESC
    LIMIT 8
)
SELECT 
    period as label,
    oee_value,
    actual_production,
    target_production
FROM latest_data
ORDER BY hour_sort ASC";

    $result = $conn->query($baseQuery);
    
    if (!$result) {
        throw new Exception("Database query error: " . $conn->error);
    }

    $data = [
        'labels' => [],
        'oee_values' => [],
        'actual_production' => [],
        'target_production' => [],
        'period' => $period
    ];

    while ($row = $result->fetch_assoc()) {
        $data['labels'][] = $row['label'];
        $data['oee_values'][] = floatval($row['oee_value']);
        $data['actual_production'][] = intval($row['actual_production']);
        $data['target_production'][] = intval($row['target_production']);
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