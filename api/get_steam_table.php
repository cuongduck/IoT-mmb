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
        SUM(L5_Hap + L5_Chien + L6_Hap + L6_Chien) as Tong_F3,
        L1_Hap,
        L1_Chien,
        L2_Hap,
        L2_Chien,
        L3_Hap
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
        AVG(L5_Hap) as avg_L5_Hap,
        AVG(L5_Chien) as avg_L5_Chien,
        AVG(L6_Hap) as avg_L6_Hap,
        AVG(L6_Chien) as avg_L6_Chien,
        AVG(L1_Hap) as avg_L1_Hap,
        AVG(L1_Chien) as avg_L1_Chien,
        AVG(L2_Hap) as avg_L2_Hap,
        AVG(L2_Chien) as avg_L2_Chien,
        AVG(L3_Hap) as avg_L3_Hap,
        AVG(L5_Hap + L5_Chien + L6_Hap + L6_Chien) as avg_Tong_F3,
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
            'Tong_F3' => floatval($row['Tong_F3']),
            'L1_Hap' => floatval($row['L1_Hap']),
            'L1_Chien' => floatval($row['L1_Chien']),
            'L2_Hap' => floatval($row['L2_Hap']),
            'L2_Chien' => floatval($row['L2_Chien']),
            'L3_Hap' => floatval($row['L3_Hap'])
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