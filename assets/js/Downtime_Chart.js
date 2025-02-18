let downtimeChart = null;
let currentView = 'totalF3';
let cachedData = null;  // Thêm biến để cache data

async function updateDowntimeChart(period) {
    try {
        // Luôn lấy data mới khi function được gọi
        const response = await fetch(`api/get_downtime_chart.php?period=${period}`);
        cachedData = await response.json();
        
        if (currentView === 'totalF3') {
            renderTotalF3Chart(cachedData.totalF3, cachedData.lineData);
        } else {
            renderLineChart(cachedData.lineData);
        }
    } catch (error) {
        console.error('Error updating downtime chart:', error);
    }
}


function renderTotalF3Chart(rawData, lineData) {
    // Tính tổng thời gian dừng
    const totalDowntime = rawData.reduce((sum, item) => sum + item.value, 0);
    
    // Tạo dữ liệu cho waterfall chart
    let cumulativeTotal = 0;
    const labels = rawData.map(item => item.name).concat(['Tổng']);
    const data = rawData.map(item => {
        const previousTotal = cumulativeTotal;
        cumulativeTotal += item.value;
        return { baseline: previousTotal, duration: item.value };
    }).concat({ baseline: 0, duration: totalDowntime });

    const durations = data.map(item => item.duration);
    const baselines = data.map(item => item.baseline);

    // Xóa chart cũ nếu tồn tại
    if (downtimeChart) {
        downtimeChart.destroy();
    }

    // Tạo chart mới
    const ctx = document.getElementById('downtimeChart').getContext('2d');
    downtimeChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    data: baselines,
                    backgroundColor: 'transparent',
                    borderWidth: 0,
                    stack: 'stack1'
                },
                {
                    data: durations,
                    backgroundColor: (context) => {
                        return context.dataIndex === labels.length - 1 ? 
                            'rgba(255, 99, 132, 0.8)' : 
                            'rgba(54, 162, 235, 0.8)';
                    },
                    borderColor: (context) => {
                        return context.dataIndex === labels.length - 1 ? 
                            'rgb(255, 99, 132)' : 
                            'rgb(54, 162, 235)';
                    },
                    borderWidth: 1,
                    stack: 'stack1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    stacked: true,
                    beginAtZero: true,
                    suggestedMax: Math.ceil(totalDowntime + 15),
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    title: {
                        display: true,
                        text: 'Thời gian (phút)'
                    },
                    ticks: {
                        callback: function(value) {
                            return value + ' phút';
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: getLineInfoText(lineData),
                    padding: {
                    bottom: 15
                    },
                    align: 'start',
                    color: 'blue',
                    font: {
                    size: 12,
                    family: 'monospace',  
                    weight: 'bold'
                    }
                },
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const index = context.dataIndex;
                            const value = durations[index];
                            const percentage = ((value / totalDowntime) * 100).toFixed(1);
                            
                            if (index === labels.length - 1) {
                                return `Tổng: ${totalDowntime} phút`;
                            }
                            
                            let tooltipText = `${value} phút (${percentage}%)`;
                            if (rawData[index] && rawData[index].details) {
                                tooltipText += `\nChi tiết: ${rawData[index].details}`;
                            }
                            return tooltipText;
                        }
                    }
                },
                datalabels: {
                    formatter: function(value, context) {
                        const index = context.dataIndex;
                        if (context.datasetIndex === 1) {
                            if (index === labels.length - 1) {
                                return `${value} phút`;
                            }
                            const percentage = ((value / totalDowntime) * 100).toFixed(1);
                            return `${value} phút\n(${percentage}%)`;
                        }
                        return null;
                    },
                    color: '#333',
                    anchor: 'end',
                    align: 'top',
                    offset: 5,
                    font: {
                        size: 11,
                        weight: 'bold'
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
}

function renderLineChart(lineData) {
    // Xóa chart cũ nếu tồn tại
    if (downtimeChart) {
        downtimeChart.destroy();
    }

    const ctx = document.getElementById('downtimeChart').getContext('2d');
    downtimeChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: lineData.map(item => item.line),
            datasets: [{
                data: lineData.map(item => item.duration),
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Thời gian (phút)'
                    },
                    ticks: {
                        callback: function(value) {
                            return value + ' phút';
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: getLineInfoText(lineData),
                    padding: {
                    bottom: 15
                    },
                    align: 'start',
                    color: 'blue',
                    font: {
                    size: 12,
                    family: 'monospace',  
                    weight: 'bold'
                    }
                },
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const line = lineData[context.dataIndex];
                            return `${line.duration} phút (${line.stopCount} lần dừng)`;
                        }
                    }
                },
                datalabels: {
                    formatter: function(value, context) {
                        const line = lineData[context.dataIndex];
                        return `${value} phút\n(${line.stopCount} lần)`;
                    },
                    color: '#333',
                    anchor: 'end',
                    align: 'top',
                    offset: 5,
                    font: {
                        size: 11,
                        weight: 'bold'
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
}

function getLineInfoText(lineData) {
    if (!lineData) return '';
    
    // Hàm helper để chọn màu dựa trên phần trăm
    function getColorIndicator(percentage) {
        if (percentage >= 40) return '🔴';
        if (percentage >= 25) return '🟡';
        return '🟢';
    }

    const totalStops = lineData.reduce((sum, line) => sum + line.stopCount, 0);
    const totalDuration = lineData.reduce((sum, line) => sum + line.duration, 0);

    return [
        'Chi tiết dừng máy:',
        ...lineData.map(line => {
            const stopPercentage = ((line.stopCount / totalStops) * 100).toFixed(1);
            const durationPercentage = ((line.duration / totalDuration) * 100).toFixed(1);
            const indicator = getColorIndicator(durationPercentage);
            return `${indicator} ${line.line} - Dừng: ${line.stopCount}/${totalStops} lần (${stopPercentage}%) | ${line.duration}/${totalDuration} phút (${durationPercentage}%)`;
        })
    ];
}

function switchChart(view) {
    currentView = view;
    if (cachedData) {
        if (view === 'totalF3') {
            renderTotalF3Chart(cachedData.totalF3, cachedData.lineData);
        } else {
            renderLineChart(cachedData.lineData);
        }
    } else {
        updateDowntimeChart(document.querySelector('#periodSelect').value);
    }
}
// Thêm function để clear cache khi cần load lại data mới
function refreshChart(period) {
    cachedData = null;  // Clear cache
    updateDowntimeChart(period);
}
// Hàm để set interval update
function startAutoUpdate() {
    // Update mỗi 5 phút
    setInterval(() => {
        updateDowntimeChart(document.querySelector('#periodSelect').value);
    }, 5 * 60 * 1000);
}

// Khởi tạo chart và bắt đầu auto update
function initDowntimeChart() {
    console.log('Initializing downtime chart...');
    updateDowntimeChart('today');
    startAutoUpdate();
}

// Thêm event listener khi DOM đã load
document.addEventListener('DOMContentLoaded', function() {
    initDowntimeChart();
});