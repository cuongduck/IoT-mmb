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
                    CONCAT('Tuần ', FLOOR(DATEDIFF(Time, DATE_FORMAT(Time, '%Y-%m-01')) / 7) + 1)
            END as period,
            L5_Hap, L5_Chien, L6_Hap, L6_Chien,
            L5_Tong_Goi, L6_Tong_Goi
        FROM OEE 
        $dateRangeQuery
        ORDER BY Time DESC
        $limitClause
    )
    SELECT 
        period as label,
        ROUND(AVG(COALESCE(L5_Hap, 0)), 2) as line5_hap,
        ROUND(AVG(COALESCE(L5_Chien, 0)), 2) as line5_chien,
        ROUND(AVG(COALESCE(L6_Hap, 0)), 2) as line6_hap,
        ROUND(AVG(COALESCE(L6_Chien, 0)), 2) as line6_chien,
        ROUND(AVG(COALESCE(L5_Tong_Goi, 0)), 2) as line5_products,
        ROUND(AVG(COALESCE(L6_Tong_Goi, 0)), 2) as line6_products,
        ROUND(
            CASE 
                WHEN SUM(COALESCE(L5_Tong_Goi, 0) + COALESCE(L6_Tong_Goi, 0)) > 0 THEN
                    (SUM(COALESCE(L5_Hap, 0) + COALESCE(L5_Chien, 0) + 
                     COALESCE(L6_Hap, 0) + COALESCE(L6_Chien, 0)) * 1000.0) / 
                    SUM(COALESCE(L5_Tong_Goi, 0) + COALESCE(L6_Tong_Goi, 0))
                ELSE 0
            END, 
        2) as steam_per_product,
        ROUND(
            CASE 
                WHEN SUM(COALESCE(L5_Tong_Goi, 0)) > 0 THEN
                    (SUM(COALESCE(L5_Hap, 0) + COALESCE(L5_Chien, 0)) * 1000.0) / 
                    SUM(COALESCE(L5_Tong_Goi, 0))
                ELSE 0
            END,
        2) as line5_steam_per_product,
        ROUND(
            CASE 
                WHEN SUM(COALESCE(L6_Tong_Goi, 0)) > 0 THEN
                    (SUM(COALESCE(L6_Hap, 0) + COALESCE(L6_Chien, 0)) * 1000.0) / 
                    SUM(COALESCE(L6_Tong_Goi, 0))
                ELSE 0
            END,
        2) as line6_steam_per_product
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
    $line5Hap = [];
    $line5Chien = [];
    $line6Hap = [];
    $line6Chien = [];
    $steamPerProduct = [];
    $line5SteamPerProduct = [];
    $line6SteamPerProduct = [];

    while ($row = $result->fetch_assoc()) {
        $dates[] = $row['label'];
        $line5Hap[] = floatval($row['line5_hap']);
        $line5Chien[] = floatval($row['line5_chien']);
        $line6Hap[] = floatval($row['line6_hap']);
        $line6Chien[] = floatval($row['line6_chien']);
        $steamPerProduct[] = floatval($row['steam_per_product']);
        $line5SteamPerProduct[] = floatval($row['line5_steam_per_product']);
        $line6SteamPerProduct[] = floatval($row['line6_steam_per_product']);
    }

    echo json_encode([
        'dates' => $dates,
        'line5Hap' => $line5Hap,
        'line5Chien' => $line5Chien,
        'line6Hap' => $line6Hap,
        'line6Chien' => $line6Chien,
        'steamPerProduct' => $steamPerProduct,
        'line5SteamPerProduct' => $line5SteamPerProduct,
        'line6SteamPerProduct' => $line6SteamPerProduct,
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