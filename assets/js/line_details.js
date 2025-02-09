// Khai báo các biến interval
let oeeUpdateInterval = null;
let steamUpdateInterval = null;
let trendUpdateInterval = null;
let mixingUpdateInterval = null;

function initLineCharts(line) {
    const periodButtons = document.querySelectorAll('.date-filter .btn');
    const activePeriod = Array.from(periodButtons).find(btn => btn.classList.contains('active'))?.dataset.period || 'today';
    
    // Khởi tạo các chart và bảng
    updateLineOEEChart(line, activePeriod);
    updateLineSteamChart(line, activePeriod);
    updateLineTrendChart(line, activePeriod);
    updateLineMixingTable(line);

    // Xóa các interval cũ nếu có
    clearInterval(oeeUpdateInterval);
    clearInterval(steamUpdateInterval);
    clearInterval(trendUpdateInterval);
    clearInterval(mixingUpdateInterval);

    // Thiết lập các interval mới
    oeeUpdateInterval = setInterval(() => updateLineOEEChart(line, activePeriod), 60000);
    steamUpdateInterval = setInterval(() => updateLineSteamChart(line, activePeriod), 60000);
    trendUpdateInterval = setInterval(() => updateLineTrendChart(line, activePeriod), 60000);
    mixingUpdateInterval = setInterval(() => updateLineMixingTable(line), 60000);

    // Thêm sự kiện click cho các nút period
    periodButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            periodButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            const period = this.dataset.period;
            
            // Cập nhật ngay lập tức các chart
            updateLineOEEChart(line, period);
            updateLineSteamChart(line, period);
            updateLineTrendChart(line, period);
            updateLineMixingTable(line);

            // Xóa và thiết lập lại các interval
            clearInterval(oeeUpdateInterval);
            clearInterval(steamUpdateInterval);
            clearInterval(trendUpdateInterval);
            clearInterval(mixingUpdateInterval);

            oeeUpdateInterval = setInterval(() => updateLineOEEChart(line, period), 60000);
            steamUpdateInterval = setInterval(() => updateLineSteamChart(line, period), 60000);
            trendUpdateInterval = setInterval(() => updateLineTrendChart(line, period), 60000);
            mixingUpdateInterval = setInterval(() => updateLineMixingTable(line), 60000);
        });
    });
}

// Thêm sự kiện khi rời khỏi trang
window.addEventListener('beforeunload', () => {
    clearInterval(oeeUpdateInterval);
    clearInterval(steamUpdateInterval);
    clearInterval(trendUpdateInterval);
    clearInterval(mixingUpdateInterval);
});

// Khởi tạo khi trang được load
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('[data-line]');
    if (container) {
        const line = container.dataset.line;
        initLineCharts(line);
    }
});