let oeeChartF2 = null;

async function updateOEEChartF2(period) {
    try {
        const response = await fetch(`api/get_oee_data_f2.php?period=${period}`);
        const data = await response.json();
        
        // Tạo dữ liệu target line (89%)
        const targetData = new Array(data.dates.length).fill(89);

        // Tính toán độ rộng của bar dựa trên period
        const getBarThickness = (period) => {
            switch(period) {
                case 'today': return 25;
                case 'yesterday': return 40;
                case 'week': return 30;
                case 'last_week': return 30;
                case 'month': return 40;
                default: return 25;
            }
        };

        // Xóa chart cũ nếu tồn tại
        if (oeeChartF2) {
            oeeChartF2.destroy();
        }

        // Tạo chart mới
        const ctx = document.getElementById('oeeChartF2').getContext('2d');
        oeeChartF2 = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.dates,
                datasets: [
                    {
                        label: 'OEE (%)',
                        data: data.values,
                        backgroundColor: data.values.map(value => 
                            value >= 89 ? 'rgba(54, 162, 235, 0.8)' : 'rgba(255, 68, 68, 0.8)'
                        ),
                        borderColor: data.values.map(value => 
                            value >= 89 ? 'rgb(54, 162, 235)' : 'rgb(255, 68, 68)'
                        ),
                        borderWidth: 1,
                        barThickness: getBarThickness(period),
                        order: 2,
                        borderRadius: 4
                    },
                    {
                        label: 'Target (89%)',
                        data: targetData,
                        type: 'line',
                        borderColor: '#2196F3',
                        borderWidth: 2,
                        pointRadius: 0,
                        fill: false,
                        order: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            },
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxRotation: period === 'today' ? 45 : 0,
                            minRotation: period === 'today' ? 45 : 0,
                            font: {
                                size: 11
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            boxWidth: 12,
                            font: {
                                size: 11
                            }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                if (context.dataset.type === 'line') {
                                    return 'Target: 89%';
                                }
                                return `OEE: ${context.parsed.y.toFixed(1)}%`;
                            }
                        }
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        formatter: function(value, context) {
                            if (context.dataset.type === 'line') return '';
                            return value.toFixed(1) + '%';
                        },
                        color: function(context) {
                            const value = context.dataset.data[context.dataIndex];
                            return value >= 89 ? '#1976D2' : '#D32F2F';
                        },
                        font: {
                            weight: 'bold',
                            size: 11
                        },
                        padding: {
                            top: 4
                        },
                        offset: -2
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        console.log('OEE chart F2 updated successfully');
    } catch (error) {
        console.error('Error updating OEE chart F2:', error);
    }
}

// Khởi tạo chart
function initOEEChartF2() {
    console.log('Initializing OEE chart F2...');
    updateOEEChartF2('today');
}