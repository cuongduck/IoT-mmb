<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Xưởng Mì MMB</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="apple-touch-icon" href="favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/kansui.css?v=<?php echo time(); ?>">
    <!-- Add Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Add Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-annotation/2.1.0/chartjs-plugin-annotation.min.js"></script>

    <!-- Các script khác -->
</head>
<body>
    <!-- Main Header -->
<div class="header-main">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div class="factory-nav">
                    <a href="index.php?factory=F3" class="factory-btn <?php echo (!isset($_GET['factory']) || $_GET['factory'] == 'F3') ? 'active' : ''; ?>">Mì_F3</a>
                    <a href="index.php?factory=F2" class="factory-btn <?php echo isset($_GET['factory']) && $_GET['factory'] == 'F2' ? 'active' : ''; ?>">Mì_F2</a>
                    <?php if (isAdmin()): ?>
                    <a href="index.php?page=production_plan" class="factory-btn <?php echo isset($_GET['page']) && $_GET['page'] == 'production_plan' ? 'active' : ''; ?>">KHSX</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="relative">
                    <button onclick="toggleUserMenu()" class="user-btn flex items-center">
                        <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="userMenu" class="user-menu hidden absolute right-0 mt-2 w-48 bg-white rounded shadow-lg">
                        <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Thoát
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal đổi mật khẩu -->

<!-- Sub Header -->
    <div class="sub-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="factory-title mb-0"></h2>
                <div class="date-filter d-flex gap-2">
                    <button class="btn active" data-period="today">Hôm nay</button>
                    <button class="btn" data-period="yesterday">Hôm qua</button>
                    <button class="btn" data-period="week">Tuần này</button>
                    <button class="btn" data-period="last_week">Tuần trước</button>
                    <button class="btn" data-period="month">Tháng này</button>
                </div>
            </div>
        </div>
    </div>
   