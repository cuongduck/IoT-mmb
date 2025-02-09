<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../includes/functions.php';

$period = isset($_GET['period']) ? $_GET['period'] : 'today';

try {
    // Lấy điều kiện thời gian từ functions.php
    $dateRangeQuery = getDateRangeQuery($period);
    
    // Thêm LIMIT chỉ khi period là 'today'
    $limitClause = ($period === 'today') ? "LIMIT 8" : "";

    $baseQuery = "WITH time_periods AS (
        SELECT 
            Time,
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
            L5_Tong_Goi, L6_Tong_Goi, L7_Tong_Goi, L8_Tong_Goi,
            L5_SL_KH, L6_SL_KH, L7_SL_KH, L8_SL_KH
        FROM OEE 
        $dateRangeQuery
        ORDER BY Time DESC
        $limitClause
    )
SELECT 
    period as label,
    ROUND(
        CASE 
            WHEN SUM(COALESCE(L5_SL_KH, 0) + COALESCE(L6_SL_KH, 0) + 
                    COALESCE(L7_SL_KH, 0) + COALESCE(L8_SL_KH, 0)) > 0 
            THEN (
                SUM(COALESCE(L5_Tong_Goi, 0) + COALESCE(L6_Tong_Goi, 0) + 
                    COALESCE(L7_Tong_Goi, 0) + COALESCE(L8_Tong_Goi, 0)) * 100.0
            ) / SUM(COALESCE(L5_SL_KH, 0) + COALESCE(L6_SL_KH, 0) + 
                   COALESCE(L7_SL_KH, 0) + COALESCE(L8_SL_KH, 0))
            ELSE 0
        END,
    2) as value,
    ROUND(
        CASE 
            WHEN SUM(COALESCE(L5_SL_KH, 0)) > 0 
            THEN (SUM(COALESCE(L5_Tong_Goi, 0)) * 100.0) / SUM(COALESCE(L5_SL_KH, 0))
            ELSE 0
        END,
    2) as line5_oee,
    ROUND(
        CASE 
            WHEN SUM(COALESCE(L6_SL_KH, 0)) > 0 
            THEN (SUM(COALESCE(L6_Tong_Goi, 0)) * 100.0) / SUM(COALESCE(L6_SL_KH, 0))
            ELSE 0
        END,
    2) as line6_oee,
    ROUND(
        CASE 
            WHEN SUM(COALESCE(L7_SL_KH, 0)) > 0 
            THEN (SUM(COALESCE(L7_Tong_Goi, 0)) * 100.0) / SUM(COALESCE(L7_SL_KH, 0))
            ELSE 0
        END,
    2) as line7_oee,
    ROUND(
        CASE 
            WHEN SUM(COALESCE(L8_SL_KH, 0)) > 0 
            THEN (SUM(COALESCE(L8_Tong_Goi, 0)) * 100.0) / SUM(COALESCE(L8_SL_KH, 0))
            ELSE 0
        END,
    2) as line8_oee
    FROM time_periods
    WHERE period IS NOT NULL
    GROUP BY period
    ORDER BY 
        CASE 
            WHEN '$period' = 'today' THEN Time
            WHEN '$period' = 'yesterday' THEN 
                CASE period
                    WHEN 'Ca 1' THEN 1
                    WHEN 'Ca 2' THEN 2
                    WHEN 'Ca 3' THEN 3
                END
            WHEN '$period' IN ('week', 'last_week') THEN Time
            WHEN '$period' = 'month' THEN 
                SUBSTRING(period, 6)
        END";

    $result = $conn->query($baseQuery);
    
    if (!$result) {
        throw new Exception($conn->error);
    }

    $dates = [];
    $values = [];
    $line5OEE = [];
    $line6OEE = [];
    $line7OEE = [];
    $line8OEE = [];

 while ($row = $result->fetch_assoc()) {
    $dates[] = $row['label'];
    $values[] = floatval($row['value']);
    $line5OEE[] = floatval($row['line5_oee']);
    $line6OEE[] = floatval($row['line6_oee']);
    $line7OEE[] = floatval($row['line7_oee']);
    $line8OEE[] = floatval($row['line8_oee']);
}

echo json_encode([
    'dates' => $dates,
    'values' => $values,
    'line5OEE' => $line5OEE,
    'line6OEE' => $line6OEE,
    'line7OEE' => $line7OEE,
    'line8OEE' => $line8OEE,
    'period' => $period
]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
?>