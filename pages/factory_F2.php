<!DOCTYPE html>
<html>
<head>
    <title>Thông báo</title>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full text-center">
        <?php
            // Icon "Đang xây dựng" sử dụng Heroicon
            echo '<svg class="mx-auto h-16 w-16 text-yellow-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>';
            
            echo '<h2 class="text-2xl font-bold text-gray-800 mb-4">Xưởng F2 đang xây dựng</h2>';
            echo '<p class="text-gray-600 mb-4">Đang chuyển hướng về xưởng F3...</p>';
            echo '<div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-800 mx-auto"></div>';
            
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'https://iot-mmb.online/index.php';
                }, 2500);
            </script>";
        ?>
    </div>
</body>
</html>