<?php
// Cấu hình
$jsonFile = 'shift_handover_data.json';
$refreshInterval = 250; // tự động làm mới sau 1 giờ

// Đọc dữ liệu từ file JSON
if (file_exists($jsonFile)) {
    $jsonData = file_get_contents($jsonFile);
    $data = json_decode($jsonData, true);
    
    // Kiểm tra lỗi giải mã JSON
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Lỗi khi giải mã JSON: " . json_last_error_msg());
    }
    
    // Lấy thời gian cập nhật cuối
    $lastUpdated = isset($data['lastUpdated']) ? $data['lastUpdated'] : date('Y-m-d H:i:s');
    
    // Lấy dữ liệu chính
    $mainData = $data['mainData'];
    $areaLine = isset($data['areaLine']) ? $data['areaLine'] : 'XƯỞNG MÌ';
    
    // Xác định các hàng quan trọng
    $dateShiftRow = null;
    $dateRow = null;
    $itemPIRow = null;
    $hazardRow = null;
    $oeeRow = null;
    
    foreach ($mainData as $rowIndex => $row) {
        if (isset($row[0])) {
            if ($row[0] == 'Date/ Shift') {
                $dateShiftRow = $rowIndex;
            } else if ($row[0] == 'Item/ PI') {
                $itemPIRow = $rowIndex;
            } else if ($row[0] == 'Hazard') {
                $hazardRow = $rowIndex;
            } else if ($row[0] == 'OEE') {
                $oeeRow = $rowIndex;
            }
        }
    }
    
    // Xác định hàng ngày tháng (thường nằm sau Date/Shift)
    if ($dateShiftRow !== null) {
        $dateRow = $dateShiftRow + 1;
    }
    
    // Xác định các hàng dữ liệu
    $startDataRow = $itemPIRow + 1;
} else {
    die("Không tìm thấy file dữ liệu JSON. Vui lòng đảm bảo script Python đã chạy trước.");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shift Handover (SHO) Sheet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        
        .container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #0000C9;
            color: white;
            padding: 10px 20px;
        }
        
        .logo img {
            height: 60px;
        }
        
        .title-section {
            text-align: center;
            flex-grow: 1;
        }
        
        .sheet-title {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        
        .icons {
            display: flex;
            gap: 15px;
        }
        
        .icon {
            text-align: center;
        }
        
        .icon img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: white;
            padding: 5px;
        }
        
        .icon div {
            font-size: 12px;
            margin-top: 5px;
        }
        
        .area-line {
            background-color: #0000C9;
            color: white;
            padding: 5px 15px;
            text-align: center;
            position: relative;
        }
        
        .area-line-label {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: bold;
        }
        
        .area-line-value {
            display: inline-block;
            background-color: white;
            color: black;
            padding: 5px 15px;
            min-width: 200px;
            text-align: center;
            font-weight: bold;
        }
        
        .date-shift-row {
            background-color: #87CEEB;
            text-align: center;
            font-weight: bold;
        }
        
        .shift-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .shift-table th, .shift-table td {
            border: 1px solid #87CEEB;
            padding: 5px;
            text-align: center;
            font-size: 14px;
        }
        
        .shift-table th {
            background-color: #87CEEB;
            font-weight: bold;
        }
        
        .item-pi-col {
            background-color: #87CEEB;
            font-weight: bold;
            text-align: left;
            padding-left: 10px;
        }
        
        .target-col {
            background-color: #87CEEB;
            font-weight: bold;
            text-align: center;
        }
        
        .info-boxes {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
        }
        
        .info-box {
            width: 24.5%;
            border: 1px solid #87CEEB;
            min-height: 150px;
        }
        
        .info-box h3 {
            margin: 0;
            padding: 10px;
            background-color: white;
            color: #0066CC;
            font-size: 14px;
            text-align: center;
            border-bottom: 1px solid #87CEEB;
        }
        
        .info-box-content {
            padding: 10px;
        }
        
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #666;
            font-size: 0.9em;
            padding-bottom: 20px;
        }
        
        .refresh-counter {
            font-weight: bold;
            color: #0066CC;
        }
        
        /* Màu sắc cho các chỉ số */
        .hazard-alert {
            background-color: #FFF2CC; /* Màu vàng nhạt */
        }
        
        .oee-low {
            background-color: #FFDDDD; /* Màu đỏ nhạt */
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .icon div {
                display: none;
            }
            
            .info-boxes {
                flex-wrap: wrap;
            }
            
            .info-box {
                width: 49%;
                margin-bottom: 10px;
            }
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 10px;
            }
            
            .info-box {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="logo.png" alt="Logo">
            </div>
            <div class="title-section">
                <h1 class="sheet-title">SHIFT HANDOVER (SHO)</h1>
            </div>
            <div class="icons">
                <div class="icon">
                    <img src="safety-icon.png" alt="Safety">
                    <div>Safety</div>
                </div>
                <div class="icon">
                    <img src="quality-icon.png" alt="Quality">
                    <div>Quality</div>
                </div>
                <div class="icon">
                    <img src="people-icon.png" alt="People">
                    <div>People</div>
                </div>
                <div class="icon">
                    <img src="efficiency-icon.png" alt="Efficiency">
                    <div>Efficiency</div>
                </div>
            </div>
        </div>
        
        <div class="area-line">
            <span class="area-line-label">Area/ line</span>
            <span class="area-line-value"><?php echo htmlspecialchars($areaLine); ?></span>
        </div>
        
        <table class="shift-table">
            <?php if (!empty($mainData)): ?>
                <!-- Hàng Date/Shift -->
                <?php if ($dateShiftRow !== null): ?>
                    <tr class="date-shift-row">
                        <td colspan="2"><?php echo htmlspecialchars($mainData[$dateShiftRow][0]); ?></td>
                        <?php
                            // Lấy ngày từ file JSON
                            for ($i = 2; $i < count($mainData[$dateShiftRow]); $i += 3) {
                                $day = isset($mainData[$dateShiftRow][$i]) ? $mainData[$dateShiftRow][$i] : '';
                                echo '<td colspan="3">'. htmlspecialchars($day) .'</td>';
                            }
                        ?>
                    </tr>
                <?php endif; ?>
                
                <!-- Hàng ngày tháng -->
                <?php if ($dateRow !== null && isset($mainData[$dateRow])): ?>
                    <tr>
                        <td colspan="2"></td>
                        <?php
                            // Lấy ngày tháng thực tế
                            for ($i = 2; $i < count($mainData[$dateRow]); $i += 3) {
                                $date = isset($mainData[$dateRow][$i]) ? $mainData[$dateRow][$i] : '';
                                echo '<td colspan="3">'. htmlspecialchars($date) .'</td>';
                            }
                        ?>
                    </tr>
                <?php endif; ?>
                
                <!-- Hàng Item/PI và Target -->
                <?php if ($itemPIRow !== null): ?>
                    <tr>
                        <th><?php echo htmlspecialchars($mainData[$itemPIRow][0]); ?></th>
                        <th><?php echo htmlspecialchars($mainData[$itemPIRow][1]); ?></th>
                        <?php
                            // In các cột số 1, 2, 3 cho mỗi ngày
                            for ($i = 2; $i < count($mainData[$itemPIRow]); $i++) {
                                echo '<th>'. htmlspecialchars($mainData[$itemPIRow][$i]) .'</th>';
                            }
                        ?>
                    </tr>
                <?php endif; ?>
                
                <!-- Các dòng dữ liệu -->
                <?php if ($startDataRow !== null): ?>
                    <?php for ($row = $startDataRow; $row < count($mainData); $row++): ?>
                        <tr>
                            <!-- Cột Item/PI -->
                            <td class="item-pi-col"><?php echo htmlspecialchars($mainData[$row][0]); ?></td>
                            
                            <!-- Cột Target -->
                            <td class="target-col"><?php echo htmlspecialchars($mainData[$row][1]); ?></td>
                            
                            <!-- Các ô dữ liệu cho từng ca -->
                            <?php for ($col = 2; $col < count($mainData[$row]); $col++): ?>
                                <?php
                                    $cellValue = $mainData[$row][$col];
                                    $cellClass = '';
                                    
                                    // Đánh dấu Hazard > 0
                                    if ($row == $hazardRow && is_numeric($cellValue) && $cellValue > 0) {
                                        $cellClass = ' class="hazard-alert"';
                                    }
                                    
                                    // Đánh dấu OEE < Target
                                    if ($row == $oeeRow) {
                                        $target = str_replace('%', '', $mainData[$row][1]); // Loại bỏ ký tự %
                                        if (is_numeric($cellValue) && is_numeric($target) && $cellValue < $target) {
                                            $cellClass = ' class="oee-low"';
                                        }
                                    }
                                ?>
                                <td<?php echo $cellClass; ?>><?php echo htmlspecialchars($cellValue); ?></td>
                            <?php endfor; ?>
                        </tr>
                    <?php endfor; ?>
                <?php endif; ?>
            <?php else: ?>
                <tr>
                    <td colspan="23">Không có dữ liệu</td>
                </tr>
            <?php endif; ?>
        </table>
        
        <div class="info-boxes">
            <div class="info-box">
                <h3>Plant safety pyramid/ Thông Thông tin safety</h3>
                <div class="info-box-content">
                    <?php echo isset($data['safety']) ? htmlspecialchars($data['safety']) : ''; ?>
                </div>
            </div>
            
            <div class="info-box">
                <h3>SOPs/ OPLs</h3>
                <div class="info-box-content">
                    <?php echo isset($data['sops']) ? htmlspecialchars($data['sops']) : ''; ?>
                </div>
            </div>
            
            <div class="info-box">
                <h3>5S</h3>
                <div class="info-box-content">
                    <?php echo isset($data['fiveS']) ? htmlspecialchars($data['fiveS']) : ''; ?>
                </div>
            </div>
            
            <div class="info-box">
                <h3>Other information</h3>
                <div class="info-box-content">
                    <?php echo isset($data['other']) ? htmlspecialchars($data['other']) : ''; ?>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>Cập nhật lần cuối: <?php echo $lastUpdated; ?></p>
            <p>Trang sẽ tự động làm mới sau <span id="countdown" class="refresh-counter"><?php echo $refreshInterval; ?></span> giây</p>
        </div>
    </div>
    
    <script>
        // Script tự động làm mới trang
        var countdownElement = document.getElementById('countdown');
        var seconds = <?php echo $refreshInterval; ?>;
        
        function updateCountdown() {
            countdownElement.textContent = seconds;
            if (seconds <= 0) {
                location.reload();
            } else {
                seconds--;
                setTimeout(updateCountdown, 1000);
            }
        }
        
        // Khởi chạy đếm ngược
        updateCountdown();
    </script>
</body>
</html>