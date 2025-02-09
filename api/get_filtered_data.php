<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
header('Content-Type: application/json');

try {
    $period = isset($_GET['period']) ? $_GET['period'] : 'today';

    // Lấy điều kiện thời gian từ functions.php và thay thế WHERE thành AND
    $dateRangeQuery = getDateRangeQuery($period);
    $dateRangeQuery = str_replace('WHERE', 'AND', $dateRangeQuery);

    $query = "SELECT 
        -- Tổng sản lượng
        SUM(COALESCE(L5_Tong_Goi, 0)) as l5_production,
        SUM(COALESCE(L6_Tong_Goi, 0)) as l6_production,
        SUM(COALESCE(L7_Tong_Goi, 0)) as l7_production,
        SUM(COALESCE(L8_Tong_Goi, 0)) as l8_production,
        SUM(COALESCE(L5_Tong_Goi, 0) + COALESCE(L6_Tong_Goi, 0) + COALESCE(L7_Tong_Goi, 0) + COALESCE(L8_Tong_Goi, 0)) as total_production,
        SUM(COALESCE(L5_SL_KH, 0) + COALESCE(L6_SL_KH, 0) + COALESCE(L7_SL_KH, 0) + COALESCE(L8_SL_KH, 0)) as total_plan,
        
        -- OEE từng line
        ROUND(
            CASE 
                WHEN SUM(COALESCE(L5_SL_KH, 0)) > 0 
                THEN (SUM(COALESCE(L5_Tong_Goi, 0)) * 100.0) / SUM(COALESCE(L5_SL_KH, 0))
                ELSE 0 
            END, 
        2) as l5_oee,
        
        ROUND(
            CASE 
                WHEN SUM(COALESCE(L6_SL_KH, 0)) > 0 
                THEN (SUM(COALESCE(L6_Tong_Goi, 0)) * 100.0) / SUM(COALESCE(L6_SL_KH, 0))
                ELSE 0 
            END, 
        2) as l6_oee,

        ROUND(
            CASE 
                WHEN SUM(COALESCE(L7_SL_KH, 0)) > 0 
                THEN (SUM(COALESCE(L7_Tong_Goi, 0)) * 100.0) / SUM(COALESCE(L7_SL_KH, 0))
                ELSE 0 
            END, 
        2) as l7_oee,

        ROUND(
            CASE 
                WHEN SUM(COALESCE(L8_SL_KH, 0)) > 0 
                THEN (SUM(COALESCE(L8_Tong_Goi, 0)) * 100.0) / SUM(COALESCE(L8_SL_KH, 0))
                ELSE 0 
            END, 
        2) as l8_oee,
        
        -- Tổng OEE
        ROUND(
            CASE 
                WHEN SUM(COALESCE(L5_SL_KH, 0) + COALESCE(L6_SL_KH, 0) + COALESCE(L7_SL_KH, 0) + COALESCE(L8_SL_KH, 0)) > 0 
                THEN (
                    SUM(COALESCE(L5_Tong_Goi, 0) + COALESCE(L6_Tong_Goi, 0) + COALESCE(L7_Tong_Goi, 0) + COALESCE(L8_Tong_Goi, 0)) * 100.0
                ) / SUM(COALESCE(L5_SL_KH, 0) + COALESCE(L6_SL_KH, 0) + COALESCE(L7_SL_KH, 0) + COALESCE(L8_SL_KH, 0))
                ELSE 0 
            END,
        2) as total_oee,
        
        -- Tiêu hao hơi từng line
        ROUND(
            CASE 
                WHEN SUM(COALESCE(L5_Tong_Goi, 0)) > 0 
                THEN (SUM(COALESCE(L5_Hap, 0) + COALESCE(L5_Chien, 0)) * 1000.0) / SUM(COALESCE(L5_Tong_Goi, 0))
                ELSE 0 
            END,
        2) as l5_steam,
        
        ROUND(
            CASE 
                WHEN SUM(COALESCE(L6_Tong_Goi, 0)) > 0 
                THEN (SUM(COALESCE(L6_Hap, 0) + COALESCE(L6_Chien, 0)) * 1000.0) / SUM(COALESCE(L6_Tong_Goi, 0))
                ELSE 0 
            END,
        2) as l6_steam,
        
        -- Tổng tiêu hao hơi
        ROUND(
            CASE 
                WHEN SUM(COALESCE(L5_Tong_Goi, 0) + COALESCE(L6_Tong_Goi, 0)) > 0 
                THEN (
                    SUM(COALESCE(L5_Hap, 0) + COALESCE(L5_Chien, 0) + 
                        COALESCE(L6_Hap, 0) + COALESCE(L6_Chien, 0)) * 1000.0
                ) / SUM(COALESCE(L5_Tong_Goi, 0) + COALESCE(L6_Tong_Goi, 0))
                ELSE 0 
            END,
        2) as total_steam,
         -- Lấy dữ liệu từ So_dien_F3
        COALESCE(
            (SELECT SUM(COALESCE(F3_TramDien_Tong, 0)) 
             FROM So_dien_F3 
             WHERE 1=1 $dateRangeQuery),
            0
        ) as total_power,
        
        COALESCE(
            (SELECT SUM(COALESCE(Tong_Mi, 0))
             FROM So_dien_F3 
             WHERE 1=1 $dateRangeQuery),
            0
        ) as power_target,
        
        COALESCE(
            (SELECT SUM(COALESCE(F3_Line_5, 0))
             FROM So_dien_F3 
             WHERE 1=1 $dateRangeQuery),
            0
        ) as l5_power,
        
        COALESCE(
            (SELECT SUM(COALESCE(F3_Line_6, 0))
             FROM So_dien_F3 
             WHERE 1=1 $dateRangeQuery),
            0
        ) as l6_power,
        
        COALESCE(
            (SELECT SUM(COALESCE(F3_Line_7, 0))
             FROM So_dien_F3 
             WHERE 1=1 $dateRangeQuery),
            0
        ) as l7_power,
        
        COALESCE(
            (SELECT SUM(COALESCE(F3_Line_8, 0))
             FROM So_dien_F3 
             WHERE 1=1 $dateRangeQuery),
            0
        ) as l8_power,
        
        COALESCE(
            (SELECT SUM(COALESCE(F3_MNK, 0))
             FROM So_dien_F3 
             WHERE 1=1 $dateRangeQuery),
            0
        ) as mnk_power,
        
        COALESCE(
            (SELECT SUM(COALESCE(F3_AHU_Chiller, 0))
             FROM So_dien_F3 
             WHERE 1=1 $dateRangeQuery),
            0
        ) as ahu_power       
    FROM OEE 
    WHERE 1=1 $dateRangeQuery";

    $result = $conn->query($query);
    if (!$result) {
        throw new Exception($conn->error);
    }

    $row = $result->fetch_assoc();
    echo json_encode($row);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
?>