let oeeByLineChartF2 = null;

async function updateOEEByLineChartF2(period) {
    try {
        const response = await fetch(`api/get_oee_by_line_f2.php?period=${period}`);
        const data = await response.json();
        
        // Tạo dữ liệu target line (89%)
        const targetData = new Array(data.lines.length).fill(89);

        // Xóa chart cũ nếu tồn tại
        if (oeeByLineChartF2) {
            oeeByLineChartF2.destroy();
        }

        // Tạo chart mới
        const ctx = document.getElementById('oeeByLineChartF2').getContext('2d');
        oeeByLineChartF2 = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.lines,
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
                        barThickness: 40,
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
                        order: 1,
                        datalabels: {
                            display: false
                        }
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
                            drawBorder: false,
                            drawTicks: true
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

        console.log('OEE by Line F2 chart updated successfully');
    } catch (error) {
        console.error('Error updating OEE by Line F2 chart:', error);
    }
}

// Khởi tạo chart
function initOEEByLineChartF2() {
    console.log('Initializing OEE by Line F2 chart...');
    updateOEEByLineChartF2('today');
}