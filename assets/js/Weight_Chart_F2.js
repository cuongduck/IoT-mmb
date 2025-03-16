let weightChartF2 = null;
let weightUpdateIntervalF2;

// Hàm tính stepSize dựa vào khoảng cách min-max
function calculateStepSizeF2(min, max) {
    const range = max - min;
    if (range <= 20) return 2;       // Nếu khoảng < 20g, chia mỗi 2g
    if (range <= 50) return 5;       // Nếu khoảng < 50g, chia mỗi 5g
    return 10;                       // Mặc định chia mỗi 10g
}

// Hàm tìm min, max từ dữ liệu
function findMinMaxValuesF2(data, type) {
    let allValues = [];
    
    data.forEach(item => {
        const values = type === 'shift' ? [
            item.L1.actual,
            item.L2.actual,
            item.L3.actual,
            item.L4.actual
        ] : [
            item.L1.actual, item.L1.target,
            item.L2.actual, item.L2.target,
            item.L3.actual, item.L3.target,
            item.L4.actual, item.L4.target
        ];
        allValues.push(...values);
    });
    
    // Lọc bỏ giá trị 0 và null/undefined
    allValues = allValues.filter(val => val > 0);
    
    const minValue = Math.min(...allValues) - 5;  // Giảm 5g cho giá trị min
    const maxValue = Math.max(...allValues) + 5;  // Tăng 5g cho giá trị max
    
    return { min: minValue, max: maxValue };
}

async function updateWeightChartF2(type = '5min') {
    try {
        const response = await fetch(`api/get_weight_chart_f2.php?type=${type}`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();

        if (weightChartF2 instanceof Chart) {
            weightChartF2.destroy();
        }

        const ctx = document.getElementById('weightChartF2');
        if (!ctx) {
            console.error('Cannot find weightChartF2 canvas');
            return;
        }

        // Tính toán min, max và stepSize
        const { min, max } = findMinMaxValuesF2(data, type);
        const stepSize = calculateStepSizeF2(min, max);

        // Cấu hình cho từng loại biểu đồ
        let chartConfig;
        
        // Cấu hình cho biểu đồ cột (Bar Chart) - Theo ca
        if (type === 'shift') {
            // Lấy giá trị target cho từng line
            const targets = {
                L1: data[0]?.L1.target || 0,
                L2: data[0]?.L2.target || 0,
                L3: data[0]?.L3.target || 0,
                L4: data[0]?.L4.target || 0
            };

            chartConfig = {
                type: 'bar',
                data: {
                    labels: ['Line 1', 'Line 2', 'Line 3', 'Line 4'],
                    datasets: [
                        {
                            label: 'Ca 1',
                            data: [
                                data.find(item => item.shift === 'Ca 1')?.L1.actual || 0,
                                data.find(item => item.shift === 'Ca 1')?.L2.actual || 0,
                                data.find(item => item.shift === 'Ca 1')?.L3.actual || 0,
                                data.find(item => item.shift === 'Ca 1')?.L4.actual || 0
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
                                data.find(item => item.shift === 'Ca 2')?.L2.actual || 0,
                                data.find(item => item.shift === 'Ca 2')?.L2.actual || 0,
                                data.find(item => item.shift === 'Ca 2')?.L3.actual || 0,
                                data.find(item => item.shift === 'Ca 2')?.L4.actual || 0
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
                                data.find(item => item.shift === 'Ca 3')?.L3.actual || 0,
                                data.find(item => item.shift === 'Ca 3')?.L3.actual || 0,
                                data.find(item => item.shift === 'Ca 3')?.L3.actual || 0,
                                data.find(item => item.shift === 'Ca 3')?.L4.actual || 0
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
                                return diff < 0 ? '#16a34a' : '#ff0000';
                            },
                            anchor: 'end',
                            align: 'bottom',
                            offset: -30,
                            formatter: function(value, context) {
                                const lineNumber = context.chart.data.labels[context.dataIndex].replace('Line ', 'L');
                                const target = targets[lineNumber];
                                const diff = value - target;
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
                                l1Target: {
                                    type: 'line',
                                    xMin: -0.3,
                                    xMax: 0.3,
                                    yMin: targets.L1,
                                    yMax: targets.L1,
                                    borderColor: '#ff0000',
                                    borderWidth: 2,
                                    borderDash: [5, 5],
                                    label: {
                                        enabled: true,
                                        content: `Target: ${targets.L1.toFixed(1)}g`,
                                        position: 'start',
                                        backgroundColor: 'rgba(255,255,255,0.9)',
                                        color: '#8884d8',
                                        padding: 4
                                    }
                                },
                                l2Target: {
                                    type: 'line',
                                    xMin: 0.7,
                                    xMax: 1.3,
                                    yMin: targets.L2,
                                    yMax: targets.L2,
                                    borderColor: '#ff0000',
                                    borderWidth: 2,
                                    borderDash: [5, 5],
                                    label: {
                                        enabled: true,
                                        content: `Target: ${targets.L2.toFixed(1)}g`,
                                        position: 'start',
                                        backgroundColor: 'rgba(255,255,255,0.9)',
                                        color: '#82ca9d',
                                        padding: 4
                                    }
                                },
                                l3Target: {
                                    type: 'line',
                                    xMin: 1.7,
                                    xMax: 2.3,
                                    yMin: targets.L3,
                                    yMax: targets.L3,
                                    borderColor: '#ff0000',
                                    borderWidth: 2,
                                    borderDash: [5, 5],
                                    label: {
                                        enabled: true,
                                        content: `Target: ${targets.L3.toFixed(1)}g`,
                                        position: 'start',
                                        backgroundColor: 'rgba(255,255,255,0.9)',
                                        color: '#ffc658',
                                        padding: 4
                                    }
                                },
                                l4Target: {
                                    type: 'line',
                                    xMin: 2.7,
                                    xMax: 3.3,
                                    yMin: targets.L4,
                                    yMax: targets.L4,
                                    borderColor: '#ff0000',
                                    borderWidth: 2,
                                    borderDash: [5, 5],
                                    label: {
                                        enabled: true,
                                        content: `Target: ${targets.L4.toFixed(1)}g`,
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
                        // Line 1
                        {
                            label: 'L1 TLTB',
                            data: data.map(item => item.L1.actual),
                            borderColor: '#8884d8',
                            backgroundColor: '#8884d8',
                            borderWidth: 2,
                            tension: 0.1,
                            fill: false,
                            pointRadius: 2,
                            pointHoverRadius: 4
                        },
                        {
                            label: 'L1 TL_Chuan',
                            data: data.map(item => item.L1.target),
                            borderColor: '#8884d8',
                            borderWidth: 1,
                            borderDash: [5, 5],
                            tension: 0.1,
                            fill: false,
                            pointRadius: 0
                        },
                        // Line 2
                        {
                            label: 'L2 TLTB',
                            data: data.map(item => item.L2.actual),
                            borderColor: '#82ca9d',
                            backgroundColor: '#82ca9d',
                            borderWidth: 2,
                            tension: 0.1,
                            fill: false,
                            pointRadius: 2,
                            pointHoverRadius: 4
                        },
                        {
                            label: 'L2 TL_Chuan',
                            data: data.map(item => item.L2.target),
                            borderColor: '#82ca9d',
                            borderWidth: 1,
                            borderDash: [5, 5],
                            tension: 0.1,
                            fill: false,
                            pointRadius: 0
                        },
                        // Line 3
                        {
                            label: 'L3 TLTB',
                            data: data.map(item => item.L3.actual),
                            borderColor: '#ffc658',
                            backgroundColor: '#ffc658',
                            borderWidth: 2,
                            tension: 0.1,
                            fill: false,
                            pointRadius: 2,
                            pointHoverRadius: 4
                        },
                        {
                            label: 'L3 TL_Chuan',
                            data: data.map(item => item.L3.target),
                            borderColor: '#ffc658',
                            borderWidth: 1,
                            borderDash: [5, 5],
                            tension: 0.1,
                            fill: false,
                            pointRadius: 0
                        },
                        // Line 4
                        {
                            label: 'L4 TLTB',
                            data: data.map(item => item.L4.actual),
                            borderColor: '#ff7300',
                            backgroundColor: '#ff7300',
                            borderWidth: 2,
                            tension: 0.1,
                            fill: false,
                            pointRadius: 2,
                            pointHoverRadius: 4
                        },
                        {
                            label: 'L4 TL_Chuan',
                            data: data.map(item => item.L4.target),
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
        weightChartF2 = new Chart(ctx, chartConfig);
        
    } catch (error) {
        console.error('Error updating weight chart F2:', error);
    }
}

// Khởi tạo chart và cập nhật timer
function initWeightChartF2() {
    console.log('Initializing weight chart F2...');
    
    // Thêm event listeners cho buttons
    const btn5min = document.getElementById('weightBtn5minF2');
    const btnHour = document.getElementById('weightBtnHourF2');
    const btnShift = document.getElementById('weightBtnShiftF2');

    if (btn5min && btnHour && btnShift) {
        // Trend 5 phút
        btn5min.addEventListener('click', function() {
            if (weightUpdateIntervalF2) clearInterval(weightUpdateIntervalF2);
            
            // Cập nhật style buttons
            btn5min.classList.remove('bg-gray-500');
            btn5min.classList.add('bg-blue-500');
            btnHour.classList.remove('bg-blue-500');
            btnHour.classList.add('bg-gray-500');
            btnShift.classList.remove('bg-blue-500');
            btnShift.classList.add('bg-gray-500');

            // Cập nhật chart và set interval
            updateWeightChartF2('5min');
            weightUpdateIntervalF2 = setInterval(() => updateWeightChartF2('5min'), 60 * 1000);
        });

        // Trend theo giờ
        btnHour.addEventListener('click', function() {
            if (weightUpdateIntervalF2) clearInterval(weightUpdateIntervalF2);
            
            // Cập nhật style buttons
            btnHour.classList.remove('bg-gray-500');
            btnHour.classList.add('bg-blue-500');
            btn5min.classList.remove('bg-blue-500');
            btn5min.classList.add('bg-gray-500');
            btnShift.classList.remove('bg-blue-500');
            btnShift.classList.add('bg-gray-500');

            // Cập nhật chart và set interval
            updateWeightChartF2('hour');
            weightUpdateIntervalF2 = setInterval(() => updateWeightChartF2('hour'), 60 * 60 * 1000);
        });

        // Trend theo ca
        btnShift.addEventListener('click', function() {
            if (weightUpdateIntervalF2) clearInterval(weightUpdateIntervalF2);
            
            // Cập nhật style buttons
            btnShift.classList.remove('bg-gray-500');
            btnShift.classList.add('bg-blue-500');
            btn5min.classList.remove('bg-blue-500');
            btn5min.classList.add('bg-gray-500');
            btnHour.classList.remove('bg-blue-500');
            btnHour.classList.add('bg-gray-500');

            // Cập nhật chart và set interval cho cập nhật mỗi 10 phút
            updateWeightChartF2('shift');
            weightUpdateIntervalF2 = setInterval(() => updateWeightChartF2('shift'), 10 * 60 * 1000);
        });
    }

    // Khởi tạo mặc định với trend 5 phút
    updateWeightChartF2('5min');
    weightUpdateIntervalF2 = setInterval(() => updateWeightChartF2('5min'), 60 * 1000);
}