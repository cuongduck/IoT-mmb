<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../includes/functions.php';

$type = isset($_GET['type']) ? $_GET['type'] : '5min';

try {
    if ($type === 'shift') {
        // Truy vấn theo ca, bỏ qua giá trị 0 khi tính trung bình
        $query = "SELECT 
            CASE
                WHEN (TIME(Time) >= '06:35:00' AND TIME(Time) < '15:35:00') THEN 'Ca 1'
                WHEN (TIME(Time) >= '15:50:00' AND TIME(Time) < '23:35:00') THEN 'Ca 2'
                WHEN ((TIME(Time) >= '23:36:00' AND TIME(Time) <= '23:59:59') OR
                      (TIME(Time) >= '00:00:00' AND TIME(Time) < '06:35:00')) THEN 'Ca 3'
            END as Shift,
            AVG(CASE WHEN L5_TLTB > 0 THEN L5_TLTB END) as L5_TLTB,
            AVG(CASE WHEN L5_TL_Chuan > 0 THEN L5_TL_Chuan END) as L5_TL_Chuan,
            AVG(CASE WHEN L6_TLTB > 0 THEN L6_TLTB END) as L6_TLTB,
            AVG(CASE WHEN L6_TL_Chuan > 0 THEN L6_TL_Chuan END) as L6_TL_Chuan,
            AVG(CASE WHEN L7_TLTB > 0 THEN L7_TLTB END) as L7_TLTB,
            AVG(CASE WHEN L7_TL_Chuan > 0 THEN L7_TL_Chuan END) as L7_TL_Chuan,
            AVG(CASE WHEN L8_TLTB > 0 THEN L8_TLTB END) as L8_TLTB,
            AVG(CASE WHEN L8_TL_Chuan > 0 THEN L8_TL_Chuan END) as L8_TL_Chuan
        FROM OEE 
        WHERE Time >= CURDATE()
        GROUP BY 
            CASE
                WHEN (TIME(Time) >= '06:35:00' AND TIME(Time) < '15:35:00') THEN 'Ca 1'
                WHEN (TIME(Time) >= '15:50:00' AND TIME(Time) < '23:35:00') THEN 'Ca 2'
                WHEN ((TIME(Time) >= '23:36:00' AND TIME(Time) <= '23:59:59') OR
                      (TIME(Time) >= '00:00:00' AND TIME(Time) < '06:35:00')) THEN 'Ca 3'
            END
        ORDER BY FIELD(Shift, 'Ca 1', 'Ca 2', 'Ca 3')";
    } else if ($type === 'hour') {
        // Truy vấn theo giờ, bỏ qua giá trị 0
        $query = "SELECT 
            Time,
            L5_TLTB, L5_TL_Chuan,
            L6_TLTB, L6_TL_Chuan,
            L7_TLTB, L7_TL_Chuan,
            L8_TLTB, L8_TL_Chuan
        FROM OEE 
        WHERE L5_TLTB > 0 OR L6_TLTB > 0 OR L7_TLTB > 0 OR L8_TLTB > 0 
        ORDER BY id DESC 
        LIMIT 12";
    } else {
        // Truy vấn mặc định 5 phút
        $query = "SELECT 
            Time,
            L5_TLTB, L5_TL_Chuan,
            L6_TLTB, L6_TL_Chuan,
            L7_TLTB, L7_TL_Chuan,
            L8_TLTB, L8_TL_Chuan
        FROM TLTB 
        ORDER BY Time DESC 
        LIMIT 15";
    }

    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception($conn->error);
    }

    $data = [];
    if ($type === 'shift') {
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'shift' => $row['Shift'],
                'L5' => [
                    'actual' => $row['L5_TLTB'] ? round(floatval($row['L5_TLTB']), 2) : 0,
                    'target' => $row['L5_TL_Chuan'] ? round(floatval($row['L5_TL_Chuan']), 2) : 0
                ],
                'L6' => [
                    'actual' => $row['L6_TLTB'] ? round(floatval($row['L6_TLTB']), 2) : 0,
                    'target' => $row['L6_TL_Chuan'] ? round(floatval($row['L6_TL_Chuan']), 2) : 0
                ],
                'L7' => [
                    'actual' => $row['L7_TLTB'] ? round(floatval($row['L7_TLTB']), 2) : 0,
                    'target' => $row['L7_TL_Chuan'] ? round(floatval($row['L7_TL_Chuan']), 2) : 0
                ],
                'L8' => [
                    'actual' => $row['L8_TLTB'] ? round(floatval($row['L8_TLTB']), 2) : 0,
                    'target' => $row['L8_TL_Chuan'] ? round(floatval($row['L8_TL_Chuan']), 2) : 0
                ]
            ];
        }
    } else {
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'time' => date('H:i', strtotime($row['Time'])),
                'L5' => [
                    'actual' => floatval($row['L5_TLTB']),
                    'target' => floatval($row['L5_TL_Chuan'])
                ],
                'L6' => [
                    'actual' => floatval($row['L6_TLTB']),
                    'target' => floatval($row['L6_TL_Chuan'])
                ],
                'L7' => [
                    'actual' => floatval($row['L7_TLTB']),
                    'target' => floatval($row['L7_TL_Chuan'])
                ],
                'L8' => [
                    'actual' => floatval($row['L8_TLTB']),
                    'target' => floatval($row['L8_TL_Chuan'])
                ]
            ];
        }
        $data = array_reverse($data);
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