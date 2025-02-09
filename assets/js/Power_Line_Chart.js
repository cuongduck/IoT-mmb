let powerLineChart = null;

async function updatePowerLineChart(period) {
    try {
        const response = await fetch(`api/get_power_trend.php?period=${period}`);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.json();

        const colors = [
            'rgb(54, 162, 235)',   // Thong gio CS
            'rgb(75, 192, 192)',   // Van phong
            'rgb(255, 206, 86)',   // MNK
            'rgb(153, 102, 255)',  // AHU Chiller
            'rgb(255, 159, 64)',   // Kansui
            'rgb(255, 99, 132)',   // Line 5
            'rgb(199, 199, 199)',  // Line 6
            'rgb(83, 102, 255)',   // Line 7
            'rgb(255, 159, 124)',  // Line 8
            'rgb(210, 120, 100)',  // Phoi 1
            'rgb(128, 128, 0)',    // Phoi 2
            'rgb(0, 128, 128)'     // Kho
        ];

        // Format và tính tổng cho mỗi dataset
        let datasets = data.labels.map((label, index) => {
            const values = data.datasets.map(d => d[Object.keys(d)[index + 1]]);
            const total = values.reduce((a, b) => a + b, 0);
            return {
                label,
                data: values,
                total,
                borderColor: colors[index],
                backgroundColor: colors[index],
                borderWidth: 2,
                tension: 0.4,
                pointRadius: 1
            };
        });

        // Sắp xếp datasets theo tổng giảm dần
        datasets = datasets.sort((a, b) => b.total - a.total);
        
        console.log('Datasets sorted by total:', datasets.map(d => ({
            label: d.label,
            total: d.total
        })));

        // Xóa chart cũ nếu tồn tại
        if (powerLineChart) {
            powerLineChart.destroy();
        }

        const ctx = document.getElementById('powerLineChart').getContext('2d');
        powerLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.datasets.map(d => {
                    const time = new Date(d.time);
                    return time.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
                }),
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                size: 11
                            },
                            boxWidth: 15,
                            generateLabels: function(chart) {
                                const datasets = chart.data.datasets;
                                // Tạo labels và sắp xếp theo total giảm dần
                                const labels = datasets.map((dataset, i) => ({
                                    text: `${dataset.label} (${dataset.total.toFixed(1)} kW)`,
                                    fillStyle: dataset.borderColor,
                                    strokeStyle: dataset.borderColor,
                                    lineWidth: 2,
                                    hidden: !chart.isDatasetVisible(i),
                                    index: i,
                                    datasetIndex: i,
                                    total: dataset.total
                                }));
                                return labels.sort((a, b) => b.total - a.total);
                            }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y.toFixed(1) + ' kW';
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'kW'
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
                }
            }
        });

        console.log('Cập nhật line chart thành công');
    } catch (error) {
        console.error('Lỗi khi cập nhật line chart:', error);
    }
}

// Khởi tạo biểu đồ
function initPowerLineChart() {
    console.log('Đang khởi tạo line chart...');
    updatePowerLineChart('today');
}