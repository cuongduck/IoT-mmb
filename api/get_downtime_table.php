<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../includes/functions.php';

$period = isset($_GET['period']) ? $_GET['period'] : 'today';
$line = isset($_GET['line']) ? $_GET['line'] : 'all';

try {
    // Lấy điều kiện thời gian từ functions.php và thay thế 'Time' bằng 'Date'
    $originalDateRangeQuery = getDateRangeQuery($period);
    $dateRangeQuery = str_replace('Time', 'Date', $originalDateRangeQuery);
    
    // Loại bỏ 'WHERE' từ câu điều kiện vì đã có trong query chính
    $dateRangeQuery = str_replace('WHERE', 'AND', $dateRangeQuery);
    
    // Thêm điều kiện lọc Line
    $lineFilter = $line !== 'all' ? "AND Line = ?" : "AND Line IN ('L5', 'L6', 'L7', 'L8')";
    
    $sql = "SELECT ID, Date, Line, Ten_Loi, Thoi_Gian_Dung, Ghi_Chu, Created_At, Updated_At 
            FROM Downtime 
            WHERE 1=1 $dateRangeQuery $lineFilter
            ORDER BY Date DESC";

    $stmt = $conn->prepare($sql);
    if ($line !== 'all') {
        $stmt->bind_param("s", $line);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($data);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => true, 'message' => $e->getMessage()]);
}
?>