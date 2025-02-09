<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
header('Content-Type: application/json');

// Kiểm tra đăng nhập
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$username = $_SESSION['username'];

// Danh sách user được phép sửa
$editableUsers = ['admin', 'ttcn', 'tcsx', 'ktcn'];

// Kiểm tra quyền
if (!in_array($username, $editableUsers)) {
    echo json_encode(['success' => false, 'message' => 'Không có quyền']);
    exit;
}

// Xử lý xóa dữ liệu (chỉ admin)
if (isset($data['action']) && $data['action'] === 'delete') {
    if (!isAdmin()) {
        echo json_encode(['success' => false, 'message' => 'Không có quyền xóa']);
        exit;
    }
    
    $sql = "DELETE FROM Downtime WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $data['id']);
    $success = $stmt->execute();
    
    echo json_encode(['success' => $success]);
    exit;
}

// Xử lý cập nhật
if (isset($data['id']) && isset($data['field']) && isset($data['value'])) {
    // Chỉ cho phép sửa 2 trường này
    if (!in_array($data['field'], ['Ten_Loi', 'Ghi_Chu'])) {
        echo json_encode(['success' => false, 'message' => 'Không được phép sửa trường này']);
        exit;
    }

    $sql = "UPDATE Downtime SET {$data['field']} = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $data['value'], $data['id']);
    $success = $stmt->execute();
    
    echo json_encode(['success' => $success]);
}
?>