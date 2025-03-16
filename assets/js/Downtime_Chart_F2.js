let downtimeChartF2 = null;
let currentViewF2 = 'totalF2';
let cachedDataF2 = null;

async function updateDowntimeChartF2(period) {
    try {
        // Luôn lấy data mới khi function được gọi
        const response = await fetch(`api/get_downtime_chart_f2.php?period=${period}`);
        cachedDataF2 = await response.json();
        
        if (currentViewF2 === 'totalF2') {
            renderTotalF2Chart(cachedDataF2.totalF2, cachedDataF2.lineData);
        } else {
            renderLineChartF2(cachedDataF2.lineData);
        }
    } catch (error) {
        console.error('Error updating downtime chart F2:', error);
    }
}

function renderTotalF2Chart(rawData, lineData) {
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
    if (downtimeChartF2) {
        downtimeChartF2.destroy();
    }

    // Tạo chart mới
    const ctx = document.getElementById('downtimeChartF2').getContext('2d');
    downtimeChartF2 = new Chart(ctx, {
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
                    text: getLineInfoTextF2(lineData),
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

function renderLineChartF2(lineData) {
    // Xóa chart cũ nếu tồn tại
    if (downtimeChartF2) {
        downtimeChartF2.destroy();
    }

    const ctx = document.getElementById('downtimeChartF2').getContext('2d');
    downtimeChartF2 = new Chart(ctx, {
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
                    text: getLineInfoTextF2(lineData),
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

function getLineInfoTextF2(lineData) {
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

function switchChartF2(view) {
    currentViewF2 = view;
    if (cachedDataF2) {
        if (view === 'totalF2') {
            renderTotalF2Chart(cachedDataF2.totalF2, cachedDataF2.lineData);
        } else {
            renderLineChartF2(cachedDataF2.lineData);
        }
    } else {
        updateDowntimeChartF2(document.querySelector('#periodSelect').value);
    }
}

function refreshChartF2(period) {
    cachedDataF2 = null;  // Clear cache
    updateDowntimeChartF2(period);
}

function startAutoUpdateF2() {
    // Update mỗi 5 phút
    setInterval(() => {
        updateDowntimeChartF2(document.querySelector('#periodSelect').value);
    }, 5 * 60 * 1000);
}

function initDowntimeChartF2() {
    console.log('Initializing downtime chart F2...');
    updateDowntimeChartF2('today');
    startAutoUpdateF2();
}