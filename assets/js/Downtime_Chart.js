// Downtime_Chart.js

let downtimeChart = null;

async function updateDowntimeChart(period) {
   try {
       const response = await fetch(`api/get_downtime_chart.php?period=${period}`);
       const rawData = await response.json();
       
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
                    maxRotation: 45, // Xoay nhãn để tránh overlap
                    minRotation: 45
                   }
                   }
               },
               plugins: {
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
                               // Thêm chi tiết nếu có
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
       
       console.log('Downtime chart updated successfully');
   } catch (error) {
       console.error('Error updating downtime chart:', error);
   }
}

// Khởi tạo chart
function initDowntimeChart() {
   console.log('Initializing downtime chart...');
   updateDowntimeChart('today');
}