<?php
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $line = $_POST['Line'];
    $product_name = $_POST['Ten_sp'];
    $quantity = $_POST['San_luong'];
    $start_time = $_POST['Tu_ngay'];
    $end_time = $_POST['den_ngay'];

    // Validate dữ liệu
    if (empty($line) || empty($product_name) || $quantity === '' || empty($start_time) || empty($end_time)) {
        echo json_encode([
            'success' => false,
            'message' => 'Vui lòng điền đầy đủ thông tin'
        ]);
        exit;
    }

    if ($quantity < 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Sản lượng không thể âm'
        ]);
        exit;
    }

    $start = new DateTime($start_time);
    $end = new DateTime($end_time);
    if ($end <= $start) {
        echo json_encode([
            'success' => false,
            'message' => 'Thời gian kết thúc phải sau thời gian bắt đầu'
        ]);
        exit;
    }

    // Kiểm tra trùng lặp thời gian, loại trừ kế hoạch hiện tại
    $sql = "SELECT COUNT(*) as count FROM KHSX 
            WHERE Line = ? 
            AND ((Tu_ngay BETWEEN ? AND ?) 
            OR (den_ngay BETWEEN ? AND ?))
            AND id != ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $line, $start_time, $end_time, $start_time, $end_time, $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Thời gian đã bị trùng với kế hoạch khác'
        ]);
        exit;
    }

    if ($id) {
        // Cập nhật kế hoạch hiện có
        $sql = "UPDATE KHSX 
                SET Ten_sp = ?, San_luong = ?, Tu_ngay = ?, den_ngay = ? 
                WHERE id = ? AND Line = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdssss", $product_name, $quantity, $start_time, $end_time, $id, $line);
    } else {
        // Thêm kế hoạch mới nếu không có id
        $sql = "INSERT INTO KHSX (Line, Ten_sp, San_luong, Tu_ngay, den_ngay) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdss", $line, $product_name, $quantity, $start_time, $end_time);
    }
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Cập nhật kế hoạch thành công'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Lỗi khi cập nhật: ' . $conn->error
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Phương thức không hợp lệ'
    ]);
}
?>