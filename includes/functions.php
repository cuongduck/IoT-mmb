<?php
function getDateRangeQuery($period) {
    // Set timezone về Asia/Ho_Chi_Minh
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    
    // Lấy thời gian hiện tại đầy đủ
    $currentDateTime = new DateTime();
    
    // Tạo mốc thời gian 6:35 của ngày hiện tại
    $cutoffTime = new DateTime(date('Y-m-d') . ' 06:35:00');
    
    // So sánh thời gian hiện tại với mốc 6:35
    $isBeforeCutoff = $currentDateTime < $cutoffTime;
    
    // Debug information
    error_log("Current Date Time: " . $currentDateTime->format('Y-m-d H:i:s'));
    error_log("Cutoff Time: " . $cutoffTime->format('Y-m-d H:i:s'));
    error_log("Is Before Cutoff: " . ($isBeforeCutoff ? 'true' : 'false'));
    
    switch($period) {
        case 'today':
            if ($isBeforeCutoff) {
                // Nếu thời gian hiện tại < 6:35, "hôm nay" sẽ lấy từ 7:00 hôm qua đến 6:35 hôm nay
                return "WHERE Time >= DATE_ADD(DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY), INTERVAL '7:00' HOUR_MINUTE)
                        AND Time < DATE_ADD(CURRENT_DATE, INTERVAL '6:35' HOUR_MINUTE)";
            } else {
                // Ngược lại, lấy từ 7:00 hôm nay đến 6:35 ngày mai
                return "WHERE Time >= DATE_ADD(CURRENT_DATE, INTERVAL '7:00' HOUR_MINUTE)
                        AND Time < DATE_ADD(DATE_ADD(CURRENT_DATE, INTERVAL 1 DAY), INTERVAL '6:35' HOUR_MINUTE)";
            }

        case 'yesterday':
            if ($isBeforeCutoff) {
                // Nếu thời gian hiện tại < 6:35, "hôm qua" sẽ lấy từ 7:00 hôm kia đến 6:35 hôm qua
                return "WHERE Time >= DATE_ADD(DATE_SUB(CURRENT_DATE, INTERVAL 2 DAY), INTERVAL '7:00' HOUR_MINUTE)
                        AND Time < DATE_ADD(DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY), INTERVAL '6:35' HOUR_MINUTE)";
            } else {
                // Ngược lại, lấy từ 7:00 hôm qua đến 6:35 hôm nay
                return "WHERE Time >= DATE_ADD(DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY), INTERVAL '7:00' HOUR_MINUTE)
                        AND Time < DATE_ADD(CURRENT_DATE, INTERVAL '6:35' HOUR_MINUTE)";
            }
            
        case 'week':
            if ($isBeforeCutoff) {
                // Điều chỉnh tuần khi thời gian < 6:35
                return "WHERE Time >= DATE_ADD(
                            DATE_SUB(DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY), INTERVAL WEEKDAY(DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)) DAY),
                            INTERVAL '7:00' HOUR_MINUTE
                        )
                        AND Time < DATE_ADD(
                            DATE_ADD(
                                DATE_SUB(DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY), INTERVAL WEEKDAY(DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)) DAY),
                                INTERVAL 7 DAY
                            ),
                            INTERVAL '6:35' HOUR_MINUTE
                        )";
            } else {
                return "WHERE Time >= DATE_ADD(
                            DATE_SUB(CURRENT_DATE, INTERVAL WEEKDAY(CURRENT_DATE) DAY),
                            INTERVAL '7:00' HOUR_MINUTE
                        )
                        AND Time < DATE_ADD(
                            DATE_ADD(
                                DATE_SUB(CURRENT_DATE, INTERVAL WEEKDAY(CURRENT_DATE) DAY),
                                INTERVAL 7 DAY
                            ),
                            INTERVAL '6:35' HOUR_MINUTE
                        )";
            }
                case 'last_week':
            if ($isBeforeCutoff) {
                // Nếu thời gian hiện tại < 6:35
                // Lấy từ 7:00 thứ 2 tuần trước của tuần trước đến 6:35 thứ 2 tuần trước
                return "WHERE Time >= DATE_ADD(
                            DATE_SUB(
                                DATE_SUB(DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY), 
                                INTERVAL WEEKDAY(DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)) DAY),
                                INTERVAL 7 DAY
                            ),
                            INTERVAL '7:00' HOUR_MINUTE
                        )
                        AND Time < DATE_ADD(
                            DATE_SUB(
                                DATE_SUB(DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY), 
                                INTERVAL WEEKDAY(DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)) DAY),
                                INTERVAL 0 DAY
                            ),
                            INTERVAL '6:35' HOUR_MINUTE
                        )";
            } else {
                // Nếu thời gian hiện tại >= 6:35
                // Lấy từ 7:00 thứ 2 tuần trước đến 6:35 thứ 2 tuần này
                return "WHERE Time >= DATE_ADD(
                            DATE_SUB(
                                DATE_SUB(CURRENT_DATE, INTERVAL WEEKDAY(CURRENT_DATE) DAY),
                                INTERVAL 7 DAY
                            ),
                            INTERVAL '7:00' HOUR_MINUTE
                        )
                        AND Time < DATE_ADD(
                            DATE_SUB(CURRENT_DATE, INTERVAL WEEKDAY(CURRENT_DATE) DAY),
                            INTERVAL '6:35' HOUR_MINUTE
                        )";
            }
            

        case 'month':
            if ($isBeforeCutoff) {
                // Điều chỉnh tháng khi thời gian < 6:35
                $firstDayLastMonth = date('Y-m-01', strtotime('first day of last month'));
                $firstDayThisMonth = date('Y-m-01');
                return "WHERE Time >= DATE_ADD('$firstDayLastMonth', INTERVAL '7:00' HOUR_MINUTE)
                        AND Time < DATE_ADD('$firstDayThisMonth', INTERVAL '6:35' HOUR_MINUTE)";
            } else {
                $firstDayThisMonth = date('Y-m-01');
                $firstDayNextMonth = date('Y-m-01', strtotime('first day of next month'));
                return "WHERE Time >= DATE_ADD('$firstDayThisMonth', INTERVAL '7:00' HOUR_MINUTE)
                        AND Time < DATE_ADD('$firstDayNextMonth', INTERVAL '6:35' HOUR_MINUTE)";
            }
            
        default:
            return getDateRangeQuery('today');
    }
}