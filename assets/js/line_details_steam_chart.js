let lineSteamChart = null;

async function updateLineSteamChart(line, period) {
    try {
        const response = await fetch(`api/line_details/get_line_steam_data.php?line=${line}&period=${period}`);
        const data = await response.json();

        if (lineSteamChart) {
            lineSteamChart.destroy();
        }

        const ctx = document.getElementById('lineSteamChart').getContext('2d');
        lineSteamChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: 'Hơi/1000SP',
                        data: data.steam_per_product,
                        type: 'line',
                        borderColor: '#9c27b0',
                        backgroundColor: 'rgba(156, 39, 176, 0.1)',
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: '#9c27b0',
                        fill: true,
                        tension: 0.4,
                        yAxisID: 'y1',
                        order: 0
                    },
                    {
                        label: 'Hơi Hấp',
                        data: data.steam_hap,
                        backgroundColor: 'rgba(54, 162, 235, 0.8)',
                        borderColor: 'rgb(54, 162, 235)',
                        borderWidth: 1,
                        stack: 'Stack 0',
                        yAxisID: 'y',
                        order: 1
                    },
                    {
                        label: 'Hơi Chiên',
                        data: data.steam_chien,
                        backgroundColor: 'rgba(255, 99, 132, 0.8)',
                        borderColor: 'rgb(255, 99, 132)',
                        borderWidth: 1,
                        stack: 'Stack 0',
                        yAxisID: 'y',
                        order: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Lượng hơi (kg)'
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('en-US', { maximumFractionDigits: 2 }) + ' kg';
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Hơi/1000SP'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            maxRotation: period === 'today' ? 45 : 0,
                            minRotation: period === 'today' ? 45 : 0
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
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#333',
                        titleFont: {
                            size: 12,
                            weight: 'bold'
                        },
                        bodyColor: '#666',
                        bodyFont: {
                            size: 11
                        },
                        padding: 10,
                        displayColors: true,
                        borderColor: 'rgba(0, 0, 0, 0.1)',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                let value = context.parsed.y || 0;
                                
                                if (label === 'Hơi/1000SP') {
                                    return `${label}: ${value.toLocaleString('en-US', { maximumFractionDigits: 2 })}`;
                                }
                                
                                if (context.datasetIndex <= 2) {
                                    let total = 0;
                                    for (let i = 1; i <= 2; i++) {
                                        total += context.chart.data.datasets[i].data[context.dataIndex] || 0;
                                    }
                                    if (context.datasetIndex === 2) {
                                        return [
                                            `${label}: ${value.toLocaleString('en-US', { maximumFractionDigits: 2 })} kg`,
                                            `Tổng: ${total.toLocaleString('en-US', { maximumFractionDigits: 2 })} kg`
                                        ];
                                    }
                                    return `${label}: ${value.toLocaleString('en-US', { maximumFractionDigits: 2 })} kg`;
                                }
                            }
                        }
                    }
                }
            }
        });

    } catch (error) {
        console.error('Error updating Line Steam chart:', error);
    }
}