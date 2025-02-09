<?php
require_once 'config/database.php';
require_once 'includes/auth.php';

// Đảm bảo đã đăng nhập và là admin
requireLogin();
if (!isAdmin()) {
    die("Bạn không có quyền truy cập trang này!");
}

// Lấy danh sách users với thời gian đăng nhập gần nhất
$sql = "SELECT username, last_login, 
        CASE 
            WHEN last_login IS NOT NULL THEN 
                TIMESTAMPDIFF(MINUTE, last_login, NOW()) 
            ELSE NULL 
        END as minutes_ago 
        FROM users 
        ORDER BY last_login DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống Kê Đăng Nhập - Hệ Thống IoT MMB</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen p-6">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        Thống Kê Thời Gian Đăng Nhập
                    </h2>
                    <a href="index.php" 
                       class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                        Quay lại
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tên đăng nhập
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Lần đăng nhập gần nhất
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Trạng thái
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($user = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($user['username']); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            <?php 
                                            if ($user['last_login']) {
                                                echo date('d/m/Y H:i:s', strtotime($user['last_login']));
                                            } else {
                                                echo "Chưa đăng nhập lần nào";
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php 
                                        if ($user['minutes_ago'] === NULL) {
                                            echo '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Chưa hoạt động
                                                </span>';
                                        } elseif ($user['minutes_ago'] < 15) {
                                            echo '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Đang hoạt động
                                                </span>';
                                        } else {
                                            echo '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Không hoạt động
                                                </span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>