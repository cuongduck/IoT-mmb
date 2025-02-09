let powerDonutChart = null;

async function updatePowerDonutChart(period) {
    try {
        const response = await fetch(`api/get_power_data.php?period=${period}`);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const rawData = await response.json();

        // Tạo mảng chứa cả label, value và phần trăm để sắp xếp
        const combinedData = rawData.labels.map((label, index) => ({
            label,
            value: rawData.values[index],
            percentage: rawData.percentages[index],
            color: [
                'rgba(54, 162, 235, 0.8)',   // Thong gio CS
                'rgba(75, 192, 192, 0.8)',   // Van phong
                'rgba(255, 206, 86, 0.8)',   // MNK
                'rgba(153, 102, 255, 0.8)',  // AHU Chiller
                'rgba(255, 159, 64, 0.8)',   // Kansui
                'rgba(255, 99, 132, 0.8)',   // Line 5
                'rgba(199, 199, 199, 0.8)',  // Line 6
                'rgba(83, 102, 255, 0.8)',   // Line 7
                'rgba(255, 159, 124, 0.8)',  // Line 8
                'rgba(210, 120, 100, 0.8)',  // Phoi 1
                'rgba(128, 128, 0, 0.8)',    // Phoi 2
                'rgba(0, 128, 128, 0.8)'     // Kho
            ][index]
        }));

        // Sắp xếp theo phần trăm từ lớn đến nhỏ
        combinedData.sort((a, b) => b.percentage - a.percentage);

        // Tạo dữ liệu đã sắp xếp
        const data = {
            labels: combinedData.map(item => item.label),
            values: combinedData.map(item => item.value),
            percentages: combinedData.map(item => item.percentage),
            colors: combinedData.map(item => item.color),
            total: rawData.total
        };

        // Xóa biểu đồ cũ nếu tồn tại
        if (powerDonutChart) {
            powerDonutChart.destroy();
        }

        const ctx = document.getElementById('powerDonutChart').getContext('2d');
        powerDonutChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.values,
                    backgroundColor: data.colors,
                    borderColor: data.colors.map(color => color.replace('0.8', '1')),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        top: 20,
                        bottom: 20
                    }
                },
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                size: 11
                            },
                            padding: 10
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const percentage = data.percentages[context.dataIndex];
                                return [
                                    context.label,
                                    `Giá trị: ${value.toFixed(1)} kW`,
                                    `Phần trăm: ${percentage}%`
                                ];
                            }
                        }
                    },
                    datalabels: {
                        formatter: (value, ctx) => {
                            const percentage = data.percentages[ctx.dataIndex];
                            return percentage > 1 ? percentage + '%' : '';
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 11
                        },
                        display: function(context) {
                            return context.dataset.data[context.dataIndex] > 0;
                        }
                    }
                },
                cutout: '60%'
            },
            plugins: [{
                id: 'centerText',
                beforeDraw: function(chart) {
                    const width = chart.width;
                    const height = chart.height;
                    const ctx = chart.ctx;
                    
                    ctx.restore();
                    
                    // Tính toán vị trí chính giữa của vòng tròn
                    const chartArea = chart.chartArea;
                    const centerX = (chartArea.left + chartArea.right) / 2;
                    const centerY = (chartArea.top + chartArea.bottom) / 2;
                    
                    // Vẽ tổng số
                    ctx.font = 'bold 24px Arial';
                    ctx.textBaseline = 'middle';
                    ctx.textAlign = 'center';
                    ctx.fillStyle = '#333';
                    ctx.fillText(data.total.toFixed(1), centerX, centerY - 10);
                    
                    // Vẽ text "kW" phía dưới
                    ctx.font = '14px Arial';
                    ctx.fillText('kW', centerX, centerY + 15);
                    
                    ctx.save();
                }
            }, ChartDataLabels]
        });

        console.log('Cập nhật biểu đồ điện năng thành công');
    } catch (error) {
        console.error('Lỗi khi cập nhật biểu đồ điện năng:', error);
    }
}

// Khởi tạo biểu đồ
function initPowerDonutChart() {
    console.log('Đang khởi tạo biểu đồ điện năng...');
    updatePowerDonutChart('today');
}