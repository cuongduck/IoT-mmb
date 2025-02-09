<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../includes/functions.php';

$period = isset($_GET['period']) ? $_GET['period'] : 'today';

try {
    $dateRangeQuery = getDateRangeQuery($period);
    
     $query = "SELECT 
    Time,
    L5_ten_SP,
    CASE 
        WHEN COUNT(CASE WHEN L5_TLTB != 0 THEN 1 END) > 0
        THEN AVG(CASE WHEN L5_TLTB != 0 THEN L5_TLTB END)
        ELSE 0 
    END as L5_TLTB,
    L5_TL_Chuan,
    CASE 
        WHEN L5_TLTB = 0 OR L5_TL_Chuan = 0 THEN 0 
        ELSE (L5_TLTB - L5_TL_Chuan) 
    END as Chenh_lech_L5,
    
    L6_ten_SP,
    CASE 
        WHEN COUNT(CASE WHEN L6_TLTB != 0 THEN 1 END) > 0
        THEN AVG(CASE WHEN L6_TLTB != 0 THEN L6_TLTB END)
        ELSE 0 
    END as L6_TLTB,
    L6_TL_Chuan,
    CASE 
        WHEN L6_TLTB = 0 OR L6_TL_Chuan = 0 THEN 0 
        ELSE (L6_TLTB - L6_TL_Chuan) 
    END as Chenh_lech_L6,
    
    L7_ten_SP,
    CASE 
        WHEN COUNT(CASE WHEN L7_TLTB != 0 THEN 1 END) > 0
        THEN AVG(CASE WHEN L7_TLTB != 0 THEN L7_TLTB END)
        ELSE 0 
    END as L7_TLTB,
    L7_TL_Chuan,
    CASE 
        WHEN L7_TLTB = 0 OR L7_TL_Chuan = 0 THEN 0 
        ELSE (L7_TLTB - L7_TL_Chuan) 
    END as Chenh_lech_L7,
    
    L8_ten_SP,
    CASE 
        WHEN COUNT(CASE WHEN L8_TLTB != 0 THEN 1 END) > 0
        THEN AVG(CASE WHEN L8_TLTB != 0 THEN L8_TLTB END)
        ELSE 0 
    END as L8_TLTB,
    L8_TL_Chuan,
    CASE 
        WHEN L8_TLTB = 0 OR L8_TL_Chuan = 0 THEN 0 
        ELSE (L8_TLTB - L8_TL_Chuan) 
    END as Chenh_lech_L8
FROM `OEE`
$dateRangeQuery
GROUP BY Time
ORDER BY Time DESC";

    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception($conn->error);
    }

       $data = [];
    while ($row = $result->fetch_assoc()) {
        $time = $row['Time'];
        $data[] = [
            'time' => $time,
            'L5_sp' => $row['L5_ten_SP'],
            'L5_chuan' => floatval($row['L5_TL_Chuan']),
            'L5_TLTB' => floatval($row['L5_TLTB']),
            'L5_chenh_lech' => floatval($row['Chenh_lech_L5']),
            'L6_sp' => $row['L6_ten_SP'],
            'L6_chuan' => floatval($row['L6_TL_Chuan']),
            'L6_TLTB' => floatval($row['L6_TLTB']),
            'L6_chenh_lech' => floatval($row['Chenh_lech_L6']),
            'L7_sp' => $row['L7_ten_SP'],
            'L7_chuan' => floatval($row['L7_TL_Chuan']),
            'L7_TLTB' => floatval($row['L7_TLTB']),
            'L7_chenh_lech' => floatval($row['Chenh_lech_L7']),
            'L8_sp' => $row['L8_ten_SP'],
            'L8_chuan' => floatval($row['L8_TL_Chuan']),
            'L8_TLTB' => floatval($row['L8_TLTB']),
            'L8_chenh_lech' => floatval($row['Chenh_lech_L8']),
        ];
    }

    echo json_encode([
        'data' => $data
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
?>