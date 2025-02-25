<?php
require_once '../config/database.php';
header('Content-Type: application/json');

try {
   // Sửa query để lấy dữ liệu theo line được chọn
$line = isset($_GET['line']) ? $_GET['line'] : 'L5';
    
$query = "SELECT 
      {$line}_time_kho_1, {$line}_time_uot_1, {$line}_time_kho_2, {$line}_time_uot_2,
  {$line}_KL_silo_1, {$line}_KL_silo_2, {$line}_KL_KS, {$line}_total_so_bot_1, {$line}_total_so_bot_2,
  {$line}_total_kg_bot_1, {$line}_total_kg_bot_2, {$line}_total_kg_KS_1, {$line}_total_kg_KS_2, {$line}_vong_tron_1
  {$line}_vong_tron_2, {$line}_Tron_Hz_1, {$line}_Tron_Hz_2, {$line}_Tron_A_1, {$line}_Tron_A_2, {$line}_Tron_T_1, {$line}_Tron_T_2
FROM Realtime_tron 
WHERE ID = 1";

    $result = $conn->query($query);
    if (!$result) {
        throw new Exception($conn->error);
    }

    $data = $result->fetch_assoc();
    echo json_encode($data);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => true, 'message' => $e->getMessage()]);
}
?>