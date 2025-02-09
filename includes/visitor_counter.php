<?php
class VisitorCounter {
    private $countFile = 'counter/active_visitors.txt';
    private $timeout = 300; // 5 phút timeout
    
    public function __construct() {
        if (!file_exists(dirname($this->countFile))) {
            mkdir(dirname($this->countFile), 0755, true);
        }
        if (!file_exists($this->countFile)) {
            file_put_contents($this->countFile, '{}');
        }
    }
    
    private function getVisitorIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    
    public function updateVisitor() {
        $ip = $this->getVisitorIP();
        $current_time = time();
        
        // Đọc dữ liệu hiện tại
        $data = json_decode(file_get_contents($this->countFile), true);
        
        // Xóa các IP không còn active
        foreach ($data as $visitor_ip => $last_time) {
            if ($current_time - $last_time > $this->timeout) {
                unset($data[$visitor_ip]);
            }
        }
        
        // Cập nhật thời gian cho IP hiện tại
        $data[$ip] = $current_time;
        
        // Lưu lại dữ liệu
        file_put_contents($this->countFile, json_encode($data));
        
        // Trả về số lượng visitor
        return count($data);
    }
}
?>