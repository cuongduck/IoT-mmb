let steamChartF2 = null;

async function updateSteamChartF2(period) {
    try {
        const response = await fetch(`api/get_steam_data_f2.php?period=${period}`);
        const data = await response.json();

        if (steamChartF2) {
            steamChartF2.destroy();
        }

        const ctx = document.getElementById('steamChartF2').getContext('2d');
        steamChartF2 = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.dates,
                datasets: [
                    {
                        label: 'Hơi F2',
                        data: data.steamPerProduct,
                        borderColor: '#9c27b0',
                        backgroundColor: 'rgba(156, 39, 176, 0.1)',
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: '#9c27b0',
                        fill: true,
                        tension: 0.4,
                        order: 1
                    },
                    {
                        label: 'Hơi Line 1',
                        data: data.line1SteamPerProduct,
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.1)',
                        borderWidth: 2,
                        pointRadius: 3,
                        fill: true,
                        tension: 0.4,
                        order: 2
                    },
                    {
                        label: 'Hơi Line 2',
                        data: data.line2SteamPerProduct,
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.1)',
                        borderWidth: 2,
                        pointRadius: 3,
                        fill: true,
                        tension: 0.4,
                        order: 3
                    },
                    {
                        label: 'Hơi Line 3',
                        data: data.line3SteamPerProduct,
                        borderColor: 'rgb(255, 206, 86)',
                        backgroundColor: 'rgba(255, 206, 86, 0.1)',
                        borderWidth: 2,
                        pointRadius: 3,
                        fill: true,
                        tension: 0.4,
                        order: 4
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
                            text: 'Hơi/1000SP'
                        },
                        grid: {
                            drawOnChartArea: true
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('en-US', { maximumFractionDigits: 2 });
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
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
                                let label = context.dataset.label || '';
                                let value = context.parsed.y || 0;
                                return `${label}: ${value.toLocaleString('en-US', { maximumFractionDigits: 2 })}`;
                            }
                        }
                    }
                }
            }
        });

        console.log('Steam chart F2 updated successfully');
    } catch (error) {
        console.error('Error updating Steam chart F2:', error);
    }
}

// Khởi tạo chart
function initSteamChartF2() {
    console.log('Initializing Steam chart F2...');
    updateSteamChartF2('today');
}