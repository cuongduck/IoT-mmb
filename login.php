<?php
require_once 'config/database.php';
require_once 'includes/auth.php';

session_start([
    'cookie_lifetime' => 30 * 24 * 60 * 60, // 30 days
    'cookie_secure' => true,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax'
]);

if (isLoggedIn()) {
    // Kiểm tra nếu username là "Tv_F2" thì redirect tới trang tv.php
    if (isset($_SESSION['username']) && $_SESSION['username'] === 'Tv_F2') {
        header('Location: tv.php');
        exit();
    }
    
    // Nếu không, redirect đến trang index.php như thông thường
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;
    
    // Validate input
    if (empty($username) || empty($password)) {
        $error = 'Vui lòng nhập đầy đủ thông tin';
    } else {
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                // Cập nhật thời gian đăng nhập
                $update_login = "UPDATE users SET last_login = NOW() WHERE id = ?";
                $update_stmt = $conn->prepare($update_login);
                $update_stmt->bind_param("i", $user['id']);
                $update_stmt->execute();

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                if ($remember) {
                    $rememberSuccess = setRememberMe($user['id']);
                    error_log("Remember me set: " . ($rememberSuccess ? 'Success' : 'Failed') . " for user " . $user['username']);
                }
                
                // Chuyển hướng dựa trên username
                if ($user['username'] === 'Tv_F2') {
                    header('Location: tv.php');
                } else {
                    header('Location: index.php');
                }
                exit();
            } else {
                $error = 'Mật khẩu không đúng';
            }
        } else {
            $error = 'Tài khoản không tồn tại';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <title>Đăng nhập - Hệ Thống IoT MMB</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full space-y-8 p-10 bg-white rounded-xl shadow-lg">
            <div>
                <h2 class="text-center text-3xl font-bold text-gray-900">
                    Đăng nhập hệ thống
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                     IoT Xưởng Mì MMB
                </p>
            </div>
            
            <?php if ($error): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>

            <form class="mt-8 space-y-6" method="POST">
                <div class="rounded-md shadow-sm space-y-4">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">
                            Tài khoản
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
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Ghi nhớ đăng nhập
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Đăng nhập
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>