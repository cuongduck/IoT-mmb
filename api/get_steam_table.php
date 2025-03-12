<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../includes/functions.php';
$period = isset($_GET['period']) ? $_GET['period'] : 'today';
try {
    $dateRangeQuery = getDateRangeQuery($period);
    
     $query = "SELECT 
        Time,
        L5_Hap,
        L5_Chien,
        L6_Hap,
        L6_Chien,
        L1_Hap,
        L1_Chien,
        L2_Hap,
        L2_Chien,
        L3_Hap,
        L3_Chien,
        F2_triviet,
        Mam,
        (F2_triviet - Mam) as Tong_F2,
        (F2_triviet - Mam) - (L1_Hap + L1_Chien + L2_Hap + L2_Chien + L3_Hap + L3_Chien ) as cl_F2
    FROM So_hoi_su_dung
    $dateRangeQuery
    GROUP BY Time
    ORDER BY Time DESC";
    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception($conn->error);
    }
    // Query để tính trung bình
    $avgQuery = "SELECT 
    AVG(NULLIF(L5_Hap, 0)) as avg_L5_Hap,
    AVG(NULLIF(L5_Chien, 0)) as avg_L5_Chien,
    AVG(NULLIF(L6_Hap, 0)) as avg_L6_Hap,
    AVG(NULLIF(L6_Chien, 0)) as avg_L6_Chien,
    AVG(NULLIF(L1_Hap, 0)) as avg_L1_Hap,
    AVG(NULLIF(L1_Chien, 0)) as avg_L1_Chien,
    AVG(NULLIF(L2_Hap, 0)) as avg_L2_Hap,
    AVG(NULLIF(L2_Chien, 0)) as avg_L2_Chien,
    AVG(NULLIF(L3_Hap, 0)) as avg_L3_Hap,
    AVG(NULLIF(L3_Chien, 0)) as avg_L3_Chien,
    AVG(NULLIF(F2_triviet, 0)) as avg_F2_triviet,
    AVG(NULLIF(Mam, 0)) as avg_Mam,
    AVG(NULLIF(F2_triviet, 0) - NULLIF(Mam, 0)) as avg_Tong_F2,
    (AVG(NULLIF(F2_triviet, 0) - NULLIF(Mam, 0)) - (AVG(NULLIF(L1_Hap, 0)) + AVG(NULLIF(L1_Chien, 0)) + AVG(NULLIF(L2_Hap, 0)) + AVG(NULLIF(L2_Chien, 0)) + AVG(NULLIF(L3_Hap, 0)) + AVG(NULLIF(L3_Chien, 0)))) as avg_cl_F2,
    COUNT(*) as total_records
FROM So_hoi_su_dung
    $dateRangeQuery";
    $avgResult = $conn->query($avgQuery);
    $averages = $avgResult->fetch_assoc();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $time = $row['Time'];
        $data[] = [
            'time' => $time,
            'L5_Hap' => floatval($row['L5_Hap']),
            'L5_Chien' => floatval($row['L5_Chien']),
            'L6_Hap' => floatval($row['L6_Hap']),
            'L6_Chien' => floatval($row['L6_Chien']),
            'L1_Hap' => floatval($row['L1_Hap']),
            'L1_Chien' => floatval($row['L1_Chien']),
            'L2_Hap' => floatval($row['L2_Hap']),
            'L2_Chien' => floatval($row['L2_Chien']),
            'L3_Hap' => floatval($row['L3_Hap']),
            'L3_Chien' => floatval($row['L3_Chien']),
            'F2_triviet' => floatval($row['F2_triviet']),
            'Mam' => floatval($row['Mam']),
            'Tong_F2' => floatval($row['Tong_F2']),
            'cl_F2' => floatval($row['cl_F2'])
        ];
    }
    echo json_encode([
        'data' => $data,
        'averages' => $averages
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
?>