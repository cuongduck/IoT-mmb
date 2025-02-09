<?php
require_once 'includes/auth.php';
require_once 'config/database.php';

requireLogin();

include 'includes/header.php';

// Kiểm tra route
$page = isset($_GET['page']) ? $_GET['page'] : 'factory';

// Load trang tương ứng
switch($page) {
    case 'line_details':
        include 'pages/line_details.php';
        break;
    case 'production_plan':
        include 'pages/production_plan.php';
        break;
    default:
        include 'pages/factory.php';
        break;
}

include 'includes/footer.php';
?>