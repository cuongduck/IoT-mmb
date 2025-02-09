<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
header('Content-Type: application/json');

$line = isset($_GET['line']) ? $_GET['line'] : 'L5';
$period = isset($_GET['period']) ? $_GET['period'] : 'today';
$dateRangeQuery = getDateRangeQuery($period);

$sql = "SELECT Date, Ten_Loi, Thoi_Gian_Dung, Ghi_Chu 
        FROM Downtime 
        WHERE Line = ? AND " . substr($dateRangeQuery, 6) . 
        " ORDER BY Date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $line);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while($row = $result->fetch_assoc()) {
    $data[] = [
        'time' => $row['Date'],
        'error' => $row['Ten_Loi'],
        'duration' => $row['Thoi_Gian_Dung'],
        'note' => $row['Ghi_Chu']
    ];
}

echo json_encode($data);
?>