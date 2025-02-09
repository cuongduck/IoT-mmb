let lineOEEChart = null;

async function updateLineOEEChart(line, period) {
    try {
        const response = await fetch(`api/line_details/get_line_oee_data.php?line=${line}&period=${period}`);
        const data = await response.json();
        
        // Tạo dữ liệu target line (89%)
        const targetData = new Array(data.labels.length).fill(89);

        // Tính toán độ rộng của bar
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
        if (lineOEEChart) {
            lineOEEChart.destroy();
        }

        // Tạo chart mới
        const ctx = document.getElementById('lineOEEChart').getContext('2d');
        lineOEEChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: 'OEE (%)',
                        data: data.oee_values,
                        backgroundColor: data.oee_values.map(value => 
                            value >= 89 ? 'rgba(54, 162, 235, 0.8)' : 'rgba(255, 68, 68, 0.8)'
                        ),
                        borderColor: data.oee_values.map(value => 
                            value >= 89 ? 'rgb(54, 162, 235)' : 'rgb(255, 68, 68)'
                        ),
                        borderWidth: 1,
                        barThickness: getBarThickness(data.period),
                        order: 2
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
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            callback: function(value) {
                                return value + '%';
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
                    legend: {
                        position: 'top',
                        align: 'end'
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
                    }
                }
            }
        });

    } catch (error) {
        console.error('Error updating line OEE chart:', error);
    }
}