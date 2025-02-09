document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo các biểu đồ khi trang load
    initializeCharts();
    
    // Cập nhật dữ liệu real-time
    setInterval(updateData, 60000); // Cập nhật mỗi phút
    
    // Xử lý sự kiện chuyển đổi period
    setupPeriodButtons();
});



function updateData() {
    const activePeriod = document.querySelector('[data-period].active').dataset.period;
    updateAllCharts(activePeriod);
}

function toggleUserMenu() {
    document.getElementById('userMenu').classList.toggle('hidden');
}

// Đóng menu khi click ra ngoài
document.addEventListener('click', function(event) {
    const menu = document.getElementById('userMenu');
    const userBtn = event.target.closest('.user-btn');
    if (!userBtn && !menu.contains(event.target)) {
        menu.classList.add('hidden');
    }
});