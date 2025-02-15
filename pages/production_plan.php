<?php
require_once 'config/database.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kế Hoạch Sản Xuất</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/react@17/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom@17/umd/react-dom.production.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8">Kế Hoạch Sản Xuất</h1>
        
        <div class="space-y-4">
            <?php
            $lines = ['Line 1', 'Line 2', 'Line 3', 'Line 4', 'Line 5', 'Line 6', 'Line 7', 'Line 8'];
            foreach ($lines as $line) {
                $query = "SELECT * FROM KHSX WHERE Line = ? ORDER BY Tu_ngay DESC LIMIT 1";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $line);
                $stmt->execute();
                $result = $stmt->get_result();
                $plan = $result->fetch_assoc();
                
                // Xác định trạng thái
                $status = 'bg-yellow-500'; // pending
                $statusText = 'Chưa bắt đầu';
                if ($plan) {
                    $now = new DateTime();
                    $start = new DateTime($plan['Tu_ngay']);
                    $end = new DateTime($plan['den_ngay']);
                    
                    if ($now > $end) {
                        $status = 'bg-gray-500';
                        $statusText = 'Đã hoàn thành';
                    } elseif ($now >= $start && $now <= $end) {
                        $status = 'bg-green-500';
                        $statusText = 'Đang sản xuất';
                    }
                }
            ?>
            <div class="bg-white rounded-lg shadow-md p-6" 
     data-id="<?php echo $plan ? $plan['id'] : ''; ?>"
     data-start-time="<?php echo $plan ? date('Y-m-d H:i:s', strtotime($plan['Tu_ngay'])) : ''; ?>"
     data-end-time="<?php echo $plan ? date('Y-m-d H:i:s', strtotime($plan['den_ngay'])) : ''; ?>"
     id="plan-<?php echo $line; ?>">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="font-bold text-lg"><?php echo $line; ?></span>
                        <div class="<?php echo $status; ?> w-3 h-3 rounded-full" title="<?php echo $statusText; ?>"></div>
                    </div>
                    <button onclick="editPlan('<?php echo $line; ?>')" 
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">
                        Chỉnh sửa
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <span class="font-semibold">Sản phẩm:</span>
                        <span id="product-<?php echo $line; ?>" class="ml-2">
                            <?php echo $plan ? htmlspecialchars($plan['Ten_sp']) : 'Chưa có kế hoạch'; ?>
                        </span>
                    </div>
                    <div>
                        <span class="font-semibold">Sản lượng:</span>
                        <span id="quantity-<?php echo $line; ?>" class="ml-2">
                            <?php echo $plan ? number_format($plan['San_luong'], 2) : '0'; ?> Gói
                        </span>
                    </div>
                    <div>
                        <span class="font-semibold">Thời gian:</span>
                        <span id="time-<?php echo $line; ?>" class="ml-2">
                            <?php 
                            if ($plan) {
                                echo date('H:i d/m/Y', strtotime($plan['Tu_ngay'])) . ' - ' . 
                                     date('H:i d/m/Y', strtotime($plan['den_ngay']));
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </span>
                    </div>
                </div>

                <!-- Timeline container -->
                <div id="timeline-<?php echo $line; ?>" class="mt-4"></div>
            </div>
            <?php } ?>
        </div>
    </div>

    <!-- Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white z-[51]">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Chỉnh Sửa Kế Hoạch</h3>
                <form id="editForm" class="space-y-4">
                    <input type="hidden" id="line_number" name="Line">
                    <input type="hidden" id="plan_id" name="id">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tên sản phẩm</label>
                        <input type="text" id="product_name" name="Ten_sp" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sản lượng (Gói)</label>
                        <input type="number" step="0.01" id="quantity" name="San_luong" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Thời gian bắt đầu</label>
                        <input type="datetime-local" id="start_time" name="Tu_ngay" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Thời gian kết thúc</label>
                        <input type="datetime-local" id="end_time" name="den_ngay" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeModal()" 
                                class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300 transition-colors">
                            Đóng
                        </button>
                        <button type="button" onclick="savePlan()" 
                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">
                            Lưu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Timeline Script -->
    <script src="assets/js/timeline.js?v=<?php echo time(); ?>"></script>
<!-- Khởi tạo timeline -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        <?php
        // Reset con trỏ kết quả về đầu
        mysqli_data_seek($result, 0);
        foreach ($lines as $line) {
            $query = "SELECT * FROM KHSX WHERE Line = ? ORDER BY Tu_ngay DESC LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $line);
            $stmt->execute();
            $result = $stmt->get_result();
            $plan = $result->fetch_assoc();
        ?>
            initializeTimeline('<?php echo $line; ?>', <?php echo json_encode($plan); ?>);
        <?php } ?>
    });
    </script>

    <!-- Modal Functions -->
    <script src="assets/js/modal.js?v=<?php echo time(); ?>"></script>
</body>
</html>