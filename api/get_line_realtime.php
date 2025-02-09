<?php
require_once '../config/database.php';
header('Content-Type: application/json');

try {
   // Sửa query để lấy dữ liệu theo line được chọn
$line = isset($_GET['line']) ? $_GET['line'] : 'L5';
    
$query = "SELECT 
    {$line}_Nhiet1_T, {$line}_Nhiet1_P,
    {$line}_Nhiet2_T, {$line}_Nhiet2_P,
    {$line}_Nhiet3_T, {$line}_Nhiet3_P,
    {$line}_Nhiet4_T, {$line}_Nhiet4_P,
    {$line}_Nhiet5_T, {$line}_Nhiet5_P,
    {$line}_Hap_Temp, {$line}_Hap_Pressure, {$line}_Hap_Flow,
    {$line}_Chien_Temp, {$line}_Chien_Pressure, {$line}_Chien_Flow, {$line}_Speed,
    {$line}_BTH, {$line}_QHD,{$line}_HAP,{$line}_SEA,{$line}_KG1,{$line}_KG2,
    {$line}_Silo1, {$line}_Silo2, {$line}_Kansui, {$line}_Time_Tron_Kho_1,
    {$line}_Time_Tron_uot_1, {$line}_Time_Tron_Kho_2, {$line}_Time_Tron_uot_2,
    {$line}_nhiet_bll, {$line}_nhiet_bc, {$line}_water_sea_PV, {$line}_water_sea_SP, {$line}_water_ks_PV, {$line}_water_ks_SP, {$line}_Time_Hap
FROM Line_Status 
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