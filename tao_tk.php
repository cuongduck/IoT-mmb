<?php
require_once 'config/database.php';
require_once 'includes/auth.php';

// Đảm bảo chỉ admin mới có thể tạo user mới
requireLogin();
if (!isAdmin()) {
    die("Bạn không có quyền truy cập trang này!");
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    // Validate input
    if (empty($username) || empty($password)) {
        $error = 'Vui lòng nhập đầy đủ thông tin';
    } elseif ($password !== $confirm_password) {
        $error = 'Mật khẩu xác nhận không khớp';
    } else {
        // Kiểm tra username đã tồn tại chưa
        $check_sql = "SELECT id FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Tên đăng nhập đã tồn tại';
        } else {
            // Tạo hash từ password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Tạo SQL query với prepared statement
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $hashed_password);
            
            // Thực thi và kiểm tra
            if ($stmt->execute()) {
                $message = "Tạo tài khoản thành công!";
            } else {
                $error = "Lỗi khi tạo tài khoản: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo Tài Khoản Mới - Hệ Thống IoT MMB</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full space-y-8 p-10 bg-white rounded-xl shadow-lg">
            <div>
                <h2 class="text-center text-3xl font-bold text-gray-900">
                    Tạo Tài Khoản Mới
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Hệ Thống IoT MMB
                </p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>

            <?php if ($message): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                    <p><?php echo htmlspecialchars($message); ?></p>
                </div>
            <?php endif; ?>

            <form class="mt-8 space-y-6" method="POST">
                <div class="rounded-md shadow-sm space-y-4">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">
                            Tên đăng nhập
                        </label>
                        <input id="username" name="username" type="text" required 
                               class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Mật khẩu
                        </label>
                        <input id="password" name="password" type="password" required 
                               class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700">
                            Xác nhận mật khẩu
                        </label>
                        <input id="confirm_password" name="confirm_password" type="password" required 
                               class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="flex items-center justify-between gap-4">
                    <a href="index.php" 
                       class="flex-1 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 text-center">
                        Quay lại
                    </a>
                    <button type="submit" 
                            class="flex-1 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Tạo tài khoản
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>