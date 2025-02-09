<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../includes/functions.php';

$period = isset($_GET['period']) ? $_GET['period'] : 'today';

try {
    $dateRangeQuery = getDateRangeQuery($period);
    
    $query = "SELECT 
        Time,
        COALESCE(F3_Thonggio_CS, 0) as thong_gio,
        COALESCE(F3_VP, 0) as van_phong,
        COALESCE(F3_MNK, 0) as mnk,
        COALESCE(F3_AHU_Chiller, 0) as ahu_chiller,
        COALESCE(F3_Kansui, 0) as kansui,
        COALESCE(F3_Line_5, 0) as line5,
        COALESCE(F3_Line_6, 0) as line6,
        COALESCE(F3_Line_7, 0) as line7,
        COALESCE(F3_Line_8, 0) as line8,
        COALESCE(F3_Pho_1, 0) as pho1,
        COALESCE(F3_Pho_2, 0) as pho2,
        COALESCE(F3_Kho, 0) as kho,
        COALESCE(F3_TramDien_Tong, 0) as total
    FROM So_dien_F3
    $dateRangeQuery
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
            'thong_gio' => floatval($row['thong_gio']),
            'van_phong' => floatval($row['van_phong']),
            'mnk' => floatval($row['mnk']),
            'ahu_chiller' => floatval($row['ahu_chiller']),
            'kansui' => floatval($row['kansui']),
            'line5' => floatval($row['line5']),
            'line6' => floatval($row['line6']),
            'line7' => floatval($row['line7']),
            'line8' => floatval($row['line8']),
            'pho1' => floatval($row['pho1']),
            'pho2' => floatval($row['pho2']),
            'kho' => floatval($row['kho']),
            'total' => floatval($row['total'])
        ];
    }

    echo json_encode(['data' => $data]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
?>