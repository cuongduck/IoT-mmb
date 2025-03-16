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
            L1_Tong_Goi, L2_Tong_Goi, L3_Tong_Goi, L4_Tong_Goi,
            L1_SL_KH, L2_SL_KH, L3_SL_KH, L4_SL_KH
        FROM OEE_F2
        $dateRangeQuery
        ORDER BY Time DESC
        $limitClause
    )
SELECT 
    period as label,
    ROUND(
        CASE 
            WHEN SUM(COALESCE(L1_SL_KH, 0) + COALESCE(L2_SL_KH, 0) + 
                    COALESCE(L3_SL_KH, 0) + COALESCE(L4_SL_KH, 0)) > 0 
            THEN (
                SUM(COALESCE(L1_Tong_Goi, 0) + COALESCE(L2_Tong_Goi, 0) + 
                    COALESCE(L3_Tong_Goi, 0) + COALESCE(L4_Tong_Goi, 0)) * 100.0
            ) / SUM(COALESCE(L1_SL_KH, 0) + COALESCE(L2_SL_KH, 0) + 
                   COALESCE(L3_SL_KH, 0) + COALESCE(L4_SL_KH, 0))
            ELSE 0
        END,
    2) as value,
    ROUND(
        CASE 
            WHEN SUM(COALESCE(L1_SL_KH, 0)) > 0 
            THEN (SUM(COALESCE(L1_Tong_Goi, 0)) * 100.0) / SUM(COALESCE(L1_SL_KH, 0))
            ELSE 0
        END,
    2) as line1_oee,
    ROUND(
        CASE 
            WHEN SUM(COALESCE(L2_SL_KH, 0)) > 0 
            THEN (SUM(COALESCE(L2_Tong_Goi, 0)) * 100.0) / SUM(COALESCE(L2_SL_KH, 0))
            ELSE 0
        END,
    2) as line2_oee,
    ROUND(
        CASE 
            WHEN SUM(COALESCE(L3_SL_KH, 0)) > 0 
            THEN (SUM(COALESCE(L3_Tong_Goi, 0)) * 100.0) / SUM(COALESCE(L3_SL_KH, 0))
            ELSE 0
        END,
    2) as line3_oee,
    ROUND(
        CASE 
            WHEN SUM(COALESCE(L4_SL_KH, 0)) > 0 
            THEN (SUM(COALESCE(L4_Tong_Goi, 0)) * 100.0) / SUM(COALESCE(L4_SL_KH, 0))
            ELSE 0
        END,
    2) as line4_oee
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
    $line1OEE = [];
    $line2OEE = [];
    $line3OEE = [];
    $line4OEE = [];

    while ($row = $result->fetch_assoc()) {
        $dates[] = $row['label'];
        $values[] = floatval($row['value']);
        $line1OEE[] = floatval($row['line1_oee']);
        $line2OEE[] = floatval($row['line2_oee']);
        $line3OEE[] = floatval($row['line3_oee']);
        $line4OEE[] = floatval($row['line4_oee']);
    }

    echo json_encode([
        'dates' => $dates,
        'values' => $values,
        'line1OEE' => $line1OEE,
        'line2OEE' => $line2OEE,
        'line3OEE' => $line3OEE,
        'line4OEE' => $line4OEE,
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