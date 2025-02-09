<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../includes/functions.php';

$period = isset($_GET['period']) ? $_GET['period'] : 'today';
$line = isset($_GET['line']) ? $_GET['line'] : 'L5';

try {
    $dateRangeQuery = getDateRangeQuery($period);
    $limitClause = ($period === 'today') ? "LIMIT 8" : "";
    
    // Xác định các cột dựa trên line
    $hapCol = "{$line}_Hap";
    $chienCol = "{$line}_Chien";
    $productCol = "{$line}_Tong_Goi";
    
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
                    CONCAT('Tuần ', FLOOR(DATEDIFF(Time, DATE_FORMAT(Time, '%Y-%m-01')) / 7) + 1)
            END as period,
            $hapCol as hap,
            $chienCol as chien,
            $productCol as total_products
        FROM OEE 
        $dateRangeQuery
        ORDER BY Time DESC
        $limitClause
    )
    SELECT 
        period as label,
        ROUND(AVG(COALESCE(hap, 0)), 2) as steam_hap,
        ROUND(AVG(COALESCE(chien, 0)), 2) as steam_chien,
        ROUND(
            CASE 
                WHEN SUM(COALESCE(total_products, 0)) > 0 THEN
                    (SUM(COALESCE(hap, 0) + COALESCE(chien, 0)) * 1000.0) / 
                    SUM(COALESCE(total_products, 0))
                ELSE 0
            END,
        2) as steam_per_product
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
            ELSE Time
        END";

    $result = $conn->query($baseQuery);
    
    if (!$result) {
        throw new Exception($conn->error);
    }

    $data = [
        'labels' => [],
        'steam_hap' => [],
        'steam_chien' => [],
        'steam_per_product' => [],
        'period' => $period
    ];

    while ($row = $result->fetch_assoc()) {
        $data['labels'][] = $row['label'];
        $data['steam_hap'][] = floatval($row['steam_hap']);
        $data['steam_chien'][] = floatval($row['steam_chien']);
        $data['steam_per_product'][] = floatval($row['steam_per_product']);
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