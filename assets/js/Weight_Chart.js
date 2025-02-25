let weightChart = null;
let weightUpdateInterval;

// Hàm tính stepSize dựa vào khoảng cách min-max
function calculateStepSize(min, max) {
    const range = max - min;
    if (range <= 20) return 2;       // Nếu khoảng < 20g, chia mỗi 2g
    if (range <= 50) return 5;       // Nếu khoảng < 50g, chia mỗi 5g
    return 10;                       // Mặc định chia mỗi 10g
}

// Hàm tìm min, max từ dữ liệu
function findMinMaxValues(data, type) {
    let allValues = [];
    
    data.forEach(item => {
        const values = type === 'shift' ? [
            item.L5.actual,
            item.L6.actual,
            item.L7.actual,
            item.L8.actual
        ] : [
            item.L5.actual, item.L5.target,
            item.L6.actual, item.L6.target,
            item.L7.actual, item.L7.target,
            item.L8.actual, item.L8.target
        ];
        allValues.push(...values);
    });
    
    // Lọc bỏ giá trị 0 và null/undefined
    allValues = allValues.filter(val => val > 0);
    
    const minValue = Math.min(...allValues) - 5;  // Giảm 5g cho giá trị min
    const maxValue = Math.max(...allValues) + 5;  // Tăng 5g cho giá trị max
    
    return { min: minValue, max: maxValue };
}

async function updateWeightChart(type = '5min') {
    try {
        const response = await fetch(`api/get_weight_chart.php?type=${type}`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();

        if (weightChart instanceof Chart) {
            weightChart.destroy();
        }

        const ctx = document.getElementById('weightChart');
        if (!ctx) {
            console.error('Cannot find weightChart canvas');
            return;
        }

        // Tính toán min, max và stepSize
        const { min, max } = findMinMaxValues(data, type);
        const stepSize = calculateStepSize(min, max);

        // Cấu hình cho từng loại biểu đồ
        let chartConfig;
// Cấu hình cho biểu đồ cột (Bar Chart) - Theo ca
                if (type === 'shift') {
            // Lấy giá trị target cho từng line
            const targets = {
                L5: data[0]?.L5.target || 0,
                L6: data[0]?.L6.target || 0,
                L7: data[0]?.L7.target || 0,
                L8: data[0]?.L8.target || 0
            };

            chartConfig = {
                type: 'bar',
                data: {
                    labels: ['Line 5', 'Line 6', 'Line 7', 'Line 8'],
                    datasets: [
                        {
                            label: 'Ca 1',
                            data: [
                                data.find(item => item.shift === 'Ca 1')?.L5.actual || 0,
                                data.find(item => item.shift === 'Ca 1')?.L6.actual || 0,
                                data.find(item => item.shift === 'Ca 1')?.L7.actual || 0,
                                data.find(item => item.shift === 'Ca 1')?.L8.actual || 0
                            ],
                            backgroundColor: '#8884d8',
                            borderWidth: 1,
                            borderColor: '#8884d8',
                            barPercentage: 0.9,
                            categoryPercentage: 0.8
                        },
                        {
                            label: 'Ca 2',
                            data: [
                                data.find(item => item.shift === 'Ca 2')?.L5.actual || 0,
                                data.find(item => item.shift === 'Ca 2')?.L6.actual || 0,
                                data.find(item => item.shift === 'Ca 2')?.L7.actual || 0,
                                data.find(item => item.shift === 'Ca 2')?.L8.actual || 0
                            ],
                            backgroundColor: '#82ca9d',
                            borderWidth: 1,
                            borderColor: '#82ca9d',
                            barPercentage: 0.9,
                            categoryPercentage: 0.8
                        },
                        {
                            label: 'Ca 3',
                            data: [
                                data.find(item => item.shift === 'Ca 3')?.L5.actual || 0,
                                data.find(item => item.shift === 'Ca 3')?.L6.actual || 0,
                                data.find(item => item.shift === 'Ca 3')?.L7.actual || 0,
                                data.find(item => item.shift === 'Ca 3')?.L8.actual || 0
                            ],
                            backgroundColor: '#ffc658',
                            borderWidth: 1,
                            borderColor: '#ffc658',
                            barPercentage: 0.9,
                            categoryPercentage: 0.8
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            min: min,
                            max: max,
                            title: {
                                display: true,
                                text: 'Trọng lượng (g)'
                            },
                            ticks: {
                                stepSize: stepSize
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Line sản xuất'
                            },
                            grid: {
                                display: true
                            }
                        }
                    },
                    plugins: {
    datalabels: {
        color: function(context) {
            const value = context.dataset.data[context.dataIndex];
            const lineNumber = context.chart.data.labels[context.dataIndex].replace('Line ', 'L');
            const target = targets[lineNumber];
            const diff = value - target;
            // Nếu nhỏ hơn 0 thì màu 1 lơn hơn thì mầu 2
            return diff < 0 ? '#16a34a' : '#ff0000';
        },
        // Thay đổi vị trí của label
        anchor: 'end',    // Đặt điểm neo ở cuối cột (đỉnh cột)
        align: 'bottom',  // Căn chỉnh phía dưới của label
        offset: -30,        // Khoảng cách 5px từ đỉnh cột
        formatter: function(value, context) {
            const lineNumber = context.chart.data.labels[context.dataIndex].replace('Line ', 'L');
            const target = targets[lineNumber];
            const diff = value - target;
            // Định dạng chênh lệch: thêm dấu + cho số dương
            const diffText = diff > 0 ? `+${diff.toFixed(1)}` : diff.toFixed(1);
            return value > 0 ? `${value.toFixed(1)}g\n(${diffText})` : '';
        },
        font: {
            weight: 'bold',
            size: 11
        },
        display: function(context) {
            return context.dataset.data[context.dataIndex] > 0;
        }
    },
                        annotation: {
                            annotations: {
                                l5Target: {
                                    type: 'line',
                                    xMin: -0.3,
                                    xMax: 0.3,
                                    yMin: targets.L5,
                                    yMax: targets.L5,
                                    borderColor: '#ff0000',
                                    borderWidth: 2,
                                    borderDash: [5, 5],
                                    label: {
                                        enabled: true,
                                        content: `Target: ${targets.L5.toFixed(1)}g`,
                                        position: 'start',
                                        backgroundColor: 'rgba(255,255,255,0.9)',
                                        color: '#8884d8',
                                        padding: 4
                                    }
                                },
                                l6Target: {
                                    type: 'line',
                                    xMin: 0.7,
                                    xMax: 1.3,
                                    yMin: targets.L6,
                                    yMax: targets.L6,
                                    borderColor: '#ff0000',
                                    borderWidth: 2,
                                    borderDash: [5, 5],
                                    label: {
                                        enabled: true,
                                        content: `Target: ${targets.L6.toFixed(1)}g`,
                                        position: 'start',
                                        backgroundColor: 'rgba(255,255,255,0.9)',
                                        color: '#82ca9d',
                                        padding: 4
                                    }
                                },
                                l7Target: {
                                    type: 'line',
                                    xMin: 1.7,
                                    xMax: 2.3,
                                    yMin: targets.L7,
                                    yMax: targets.L7,
                                    borderColor: '#ff0000',
                                    borderWidth: 2,
                                    borderDash: [5, 5],
                                    label: {
                                        enabled: true,
                                        content: `Target: ${targets.L7.toFixed(1)}g`,
                                        position: 'start',
                                        backgroundColor: 'rgba(255,255,255,0.9)',
                                        color: '#ffc658',
                                        padding: 4
                                    }
                                },
                                l8Target: {
                                    type: 'line',
                                    xMin: 2.7,
                                    xMax: 3.3,
                                    yMin: targets.L8,
                                    yMax: targets.L8,
                                    borderColor: '#ff0000',
                                    borderWidth: 2,
                                    borderDash: [5, 5],
                                    label: {
                                        enabled: true,
                                        content: `Target: ${targets.L8.toFixed(1)}g`,
                                        position: 'start',
                                        backgroundColor: 'rgba(255,255,255,0.9)',
                                        color: '#ff7300',
                                        padding: 4
                                    }
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 20,
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                title: function(tooltipItems) {
                                    return tooltipItems[0].label;
                                },
                                label: function(context) {
                                    const value = context.raw || 0;
                                    const lineNumber = context.label.replace('Line ', 'L');
                                    const target = targets[lineNumber];
                                    const diff = value - target;
                                    const diffText = diff > 0 ? `+${diff.toFixed(1)}` : diff.toFixed(1);
                                    const percent = ((diff / target) * 100).toFixed(1);
                                    return [
                                        `${context.dataset.label}: ${value.toFixed(1)}g`,
                                        `Target: ${target.toFixed(1)}g`,
                                        `Chênh lệch: ${diffText}g (${percent}%)`
                                    ];
                                }
                            },
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#000',
                            bodyColor: '#000',
                            borderColor: '#ddd',
                            borderWidth: 1,
                            padding: 10,
                            titleFont: {
                                weight: 'bold'
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            };
        } else {
            // Cấu hình cho biểu đồ đường (Line Chart) - 5 phút và 1 giờ
            chartConfig = {
                type: 'line',
                data: {
                    labels: data.map(item => item.time),
                    datasets: [
// Line 5
                        {
                            label: 'L5 TLTB',
                            data: data.map(item => item.L5.actual),
                            borderColor: '#8884d8',
                            backgroundColor: '#8884d8',
                            borderWidth: 2,
                            tension: 0.1,
                            fill: false,
                            pointRadius: 2,
                            pointHoverRadius: 4
                        },
                        {
                            label: 'L5 TL_Chuan',
                            data: data.map(item => item.L5.target),
                            borderColor: '#8884d8',
                            borderWidth: 1,
                            borderDash: [5, 5],
                            tension: 0.1,
                            fill: false,
                            pointRadius: 0
                        },
                        // Line 6
                        {
                            label: 'L6 TLTB',
                            data: data.map(item => item.L6.actual),
                            borderColor: '#82ca9d',
                            backgroundColor: '#82ca9d',
                            borderWidth: 2,
                            tension: 0.1,
                            fill: false,
                            pointRadius: 2,
                            pointHoverRadius: 4
                        },
                        {
                            label: 'L6 TL_Chuan',
                            data: data.map(item => item.L6.target),
                            borderColor: '#82ca9d',
                            borderWidth: 1,
                            borderDash: [5, 5],
                            tension: 0.1,
                            fill: false,
                            pointRadius: 0
                        },
                        // Line 7
                        {
                            label: 'L7 TLTB',
                            data: data.map(item => item.L7.actual),
                            borderColor: '#ffc658',
                            backgroundColor: '#ffc658',
                            borderWidth: 2,
                            tension: 0.1,
                            fill: false,
                            pointRadius: 2,
                            pointHoverRadius: 4
                        },
                        {
                            label: 'L7 TL_Chuan',
                            data: data.map(item => item.L7.target),
                            borderColor: '#ffc658',
                            borderWidth: 1,
                            borderDash: [5, 5],
                            tension: 0.1,
                            fill: false,
                            pointRadius: 0
                        },
                        // Line 8
                        {
                            label: 'L8 TLTB',
                            data: data.map(item => item.L8.actual),
                            borderColor: '#ff7300',
                            backgroundColor: '#ff7300',
                            borderWidth: 2,
                            tension: 0.1,
                            fill: false,
                            pointRadius: 2,
                            pointHoverRadius: 4
                        },
                        {
                            label: 'L8 TL_Chuan',
                            data: data.map(item => item.L8.target),
                            borderColor: '#ff7300',
                            borderWidth: 1,
                            borderDash: [5, 5],
                            tension: 0.1,
                            fill: false,
                            pointRadius: 0
                        }
                    ]
                },
options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    scales: {
                        y: {
                            min: min,
                            max: max,
                            title: {
                                display: true,
                                text: 'Trọng lượng (g)'
                            },
                            ticks: {
                                stepSize: stepSize
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)',
                                drawBorder: false
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Thời gian'
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 20,
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw || 0;
                                    return `${context.dataset.label}: ${value.toFixed(2)}g`;
                                }
                            },
                            padding: 10,
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#000',
                            titleFont: { weight: 'bold' },
                            bodyColor: '#000',
                            borderColor: '#ddd',
                            borderWidth: 1
                        }
                    }
                }
            };
        }

        // Tạo biểu đồ mới
        weightChart = new Chart(ctx, chartConfig);
        
    } catch (error) {
        console.error('Error updating weight chart:', error);
    }
}

// Khởi tạo chart và cập nhật timer
function initWeightChart() {
    console.log('Initializing weight chart...');
    
    // Thêm event listeners cho buttons
    const btn5min = document.getElementById('weightBtn5min');
    const btnHour = document.getElementById('weightBtnHour');
    const btnShift = document.getElementById('weightBtnShift');

    if (btn5min && btnHour && btnShift) {
        // Trend 5 phút
        btn5min.addEventListener('click', function() {
            if (weightUpdateInterval) clearInterval(weightUpdateInterval);
            
            // Cập nhật style buttons
            btn5min.classList.remove('bg-gray-500');
            btn5min.classList.add('bg-blue-500');
            btnHour.classList.remove('bg-blue-500');
            btnHour.classList.add('bg-gray-500');
            btnShift.classList.remove('bg-blue-500');
            btnShift.classList.add('bg-gray-500');

            // Cập nhật chart và set interval
            updateWeightChart('5min');
            weightUpdateInterval = setInterval(() => updateWeightChart('5min'), 60 * 1000);
        });

        // Trend theo giờ
        btnHour.addEventListener('click', function() {
            if (weightUpdateInterval) clearInterval(weightUpdateInterval);
            
            // Cập nhật style buttons
            btnHour.classList.remove('bg-gray-500');
            btnHour.classList.add('bg-blue-500');
            btn5min.classList.remove('bg-blue-500');
            btn5min.classList.add('bg-gray-500');
            btnShift.classList.remove('bg-blue-500');
            btnShift.classList.add('bg-gray-500');

            // Cập nhật chart và set interval
            updateWeightChart('hour');
            weightUpdateInterval = setInterval(() => updateWeightChart('hour'), 60 * 60 * 1000);
        });

        // Trend theo ca
        btnShift.addEventListener('click', function() {
            if (weightUpdateInterval) clearInterval(weightUpdateInterval);
            
            // Cập nhật style buttons
            btnShift.classList.remove('bg-gray-500');
            btnShift.classList.add('bg-blue-500');
            btn5min.classList.remove('bg-blue-500');
            btn5min.classList.add('bg-gray-500');
            btnHour.classList.remove('bg-blue-500');
            btnHour.classList.add('bg-gray-500');

            // Cập nhật chart và set interval cho cập nhật mỗi 10 phút
            updateWeightChart('shift');
            weightUpdateInterval = setInterval(() => updateWeightChart('shift'), 10 * 60 * 1000);
        });
    }

    // Khởi tạo mặc định với trend 5 phút
    updateWeightChart('5min');
    weightUpdateInterval = setInterval(() => updateWeightChart('5min'), 60 * 1000);
}

// Thêm event listener để khởi tạo khi DOM đã load
document.addEventListener('DOMContentLoaded', function() {
    initWeightChart();
});                