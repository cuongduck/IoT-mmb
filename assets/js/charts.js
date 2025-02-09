// assets/js/charts.js
// Hàm cập nhật tất cả dữ liệu
async function updateAllCharts(period) {
    console.log('Updating all data for period:', period);
    try {
        await Promise.all([
            updateLineStatus(),
            updateOverviewCards(period), 
            updateOEEChart(period),
            updateSteamChart(period),
            updateDowntimeChart(period),
            updateOEEByLineChart(period),
            updateSteamUsageChart(period),
            updateDowntimeTable(period),
            updatePowerDonutChart(period),
            updatePowerLineChart(period),
            updatePowerTable(period),
            updateSteamTable(period),
            updateWeightTable(period),
            //updateLineOEEChart(line, period),// Hàm oee_line_details-chart.js
        ]);
        console.log('All data updated successfully');
    } catch (error) {
        console.error('Error updating data:', error);
    }
}


// Khởi tạo tất cả biểu đồ
function initializeCharts() {
    console.log('Initializing all charts...');
    try {
        initOEEChart();
        initSteamChart(); 
        initDowntimeChart();
        initWeightChart();
        updateAllCharts('today');
        console.log('All charts initialized successfully');
    } catch (error) {
        console.error('Error initializing charts:', error);
    }
}

// Cấu hình chung cho chart
const chartDefaults = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom'
        },
        tooltip: {
            mode: 'index',
            intersect: false
        }
    },
};

// Hàm format thời gian cho trục X
function formatTime(timeStr) {
    const date = new Date(timeStr);
    return `${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}`;
}



// Hàm format dữ liệu cho biểu đồ OEE
function formatOEEData(data) {
    return {
        labels: data.map(item => formatTime(item.time)),
        datasets: [{
            label: 'Target',
            data: new Array(data.length).fill(85),
            borderColor: '#8884d8',
            borderDash: [5, 5],
            fill: false
        }, {
            label: 'OEE',
            data: data.map(item => item.l5_oee),
            borderColor: '#82ca9d',
            fill: false
        }]
    };
}

// Hàm format dữ liệu cho biểu đồ Steam
function formatSteamData(data) {
    return {
        labels: data.map(item => formatTime(item.time)),
        datasets: [{
            label: 'Line 5',
            data: data.map(item => item.l5_steam),
            borderColor: '#8884d8',
            fill: false
        }, {
            label: 'Line 6',
            data: data.map(item => item.l6_steam),
            borderColor: '#82ca9d',
            fill: false
        }]
    };
}

// Hàm format dữ liệu cho biểu đồ Downtime
function formatDowntimeData(data) {
    return {
        labels: data.map(item => item.name),
        datasets: [{
            label: 'Thời gian dừng (phút)',
            data: data.map(item => item.value),
            backgroundColor: '#36a2eb',
            barThickness: 30,
        }]
    };
}

// Hàm tạo custom tooltip cho Downtime
function createDowntimeTooltip(context) {
    if (context.raw && context.raw.details) {
        return [
            `Thời gian: ${context.raw.value} phút`,
            `Chi tiết: ${context.raw.details}`
        ];
    }
    return [`Thời gian: ${context.raw} phút`];
}

// Khởi tạo cấu hình chung cho line chart
const lineChartOptions = {
    ...chartDefaults,
    scales: {
        x: {
            grid: {
                display: false
            }
        },
        y: {
            beginAtZero: true,
            grid: {
                drawBorder: false
            }
        }
    }
};

// Khởi tạo cấu hình cho bar chart
const barChartOptions = {
    ...chartDefaults,
    scales: {
        x: {
            grid: {
                display: false
            }
        },
        y: {
            beginAtZero: true,
            grid: {
                drawBorder: false
            }
        }
    }
};

// Thêm sự kiện cho các nút chọn period
function setupPeriodButtons() {
    const buttons = document.querySelectorAll('[data-period]');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            // Xóa active class từ tất cả buttons
            buttons.forEach(btn => btn.classList.remove('active'));
            // Thêm active class cho button được click
            this.classList.add('active');
            // Cập nhật charts
            updateAllCharts(this.dataset.period);
        });
    });
}

// Thêm event listener khi DOM loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Charts.js');
    setupPeriodButtons();
    initializeCharts();
});