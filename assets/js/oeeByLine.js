let oeeByLineChart = null;

async function updateOEEByLineChart(period) {
    try {
        const response = await fetch(`api/get_oee_by_line.php?period=${period}`);
        const data = await response.json();
        
        // Tạo dữ liệu target line (89%)
        const targetData = new Array(data.lines.length).fill(89);

        // Xóa chart cũ nếu tồn tại
        if (oeeByLineChart) {
            oeeByLineChart.destroy();
        }

        // Tạo chart mới
        const ctx = document.getElementById('oeeByLineChart').getContext('2d');
        oeeByLineChart = new Chart(ctx, {
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
                            },
                            filter: function(legendItem, data) {
                                // Hiển thị chỉ OEE và Target trong legend
                                return legendItem.text === 'OEE (%)' || legendItem.text === 'Target (89%)';
                            }
                        }
                    },
                    tooltip: {
                        enabled: false,
                        position: 'nearest',
                        external: function(context) {
                            const {chart, tooltip} = context;
                            const tooltipEl = getOrCreateTooltip(chart);

                            // Hide if no tooltip
                            if (tooltip.opacity === 0) {
                                tooltipEl.style.opacity = 0;
                                return;
                            }

                            if (tooltip.body) {
                                const titleLines = tooltip.title || [];
                                const bodyLines = tooltip.body.map(b => b.lines);
                                const dataIndex = tooltip.dataPoints[0].dataIndex;
                                const value = data.values[dataIndex];
                                const target = 89;
                                const difference = (value - target).toFixed(1);
                                const diffColor = difference >= 0 ? '#4CAF50' : '#F44336';

                                const tableHead = document.createElement('thead');
                                
                                let tableHTML = `
                                    <tr>
                                        <th style="text-align: center; font-weight: 600; padding: 8px; font-size: 13px; color: #333;">
                                            ${titleLines[0]}
                                        </th>
                                    </tr>
                                `;
                                tableHead.innerHTML = tableHTML;

                                const tableBody = document.createElement('tbody');
                                tableHTML = `
                                    <tr>
                                        <td style="padding: 8px;">
                                            <div style="margin: 2px 0; font-size: 12px;">
                                                <span style="display: inline-block; width: 8px; height: 8px; background: ${value >= 89 ? '#36A2EB' : '#FF4444'}; border-radius: 50%; margin-right: 8px;"></span>
                                                <span style="color: #666;">OEE:</span>
                                                <span style="float: right; font-weight: 600; color: ${value >= 89 ? '#36A2EB' : '#FF4444'}">${value.toFixed(1)}%</span>
                                            </div>
                                            <div style="margin: 2px 0; font-size: 12px;">
                                                <span style="display: inline-block; width: 8px; height: 8px; background: #2196F3; border-radius: 50%; margin-right: 8px;"></span>
                                                <span style="color: #666;">Target:</span>
                                                <span style="float: right; font-weight: 600; color: #2196F3">89.0%</span>
                                            </div>
                                            <div style="margin: 2px 0; font-size: 12px; border-top: 1px solid #eee; padding-top: 4px; margin-top: 4px;">
                                                <span style="color: #666;">Difference:</span>
                                                <span style="float: right; font-weight: 600; color: ${diffColor}">${difference > 0 ? '+' : ''}${difference}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                `;
                                tableBody.innerHTML = tableHTML;

                                const tableRoot = tooltipEl.querySelector('table');
                                while (tableRoot.firstChild) {
                                    tableRoot.firstChild.remove();
                                }

                                tableRoot.appendChild(tableHead);
                                tableRoot.appendChild(tableBody);
                            }

                            const {offsetLeft: positionX, offsetTop: positionY} = chart.canvas;

                            tooltipEl.style.opacity = 1;
                            tooltipEl.style.left = positionX + tooltip.caretX + 'px';
                            tooltipEl.style.top = positionY + tooltip.caretY + 'px';
                            tooltipEl.style.padding = tooltip.options.padding + 'px ' + tooltip.options.padding + 'px';
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

        console.log('OEE by Line chart updated successfully');
    } catch (error) {
        console.error('Error updating OEE by Line chart:', error);
    }
}

// Hàm tạo custom tooltip
function getOrCreateTooltip(chart) {
    let tooltipEl = chart.canvas.parentNode.querySelector('div');

    if (!tooltipEl) {
        tooltipEl = document.createElement('div');
        tooltipEl.style.background = 'rgba(255, 255, 255, 0.95)';
        tooltipEl.style.borderRadius = '3px';
        tooltipEl.style.color = 'black';
        tooltipEl.style.opacity = 1;
        tooltipEl.style.pointerEvents = 'none';
        tooltipEl.style.position = 'absolute';
        tooltipEl.style.transform = 'translate(-50%, 0)';
        tooltipEl.style.transition = 'all .1s ease';
        tooltipEl.style.boxShadow = '0 2px 8px rgba(0,0,0,0.15)';
        tooltipEl.style.border = '1px solid rgba(0,0,0,0.1)';

        const table = document.createElement('table');
        table.style.margin = '0px';

        tooltipEl.appendChild(table);
        chart.canvas.parentNode.appendChild(tooltipEl);
    }

    return tooltipEl;
}

// Khởi tạo chart
function initOEEByLineChart() {
    console.log('Initializing OEE by Line chart...');
    updateOEEByLineChart('today');
}