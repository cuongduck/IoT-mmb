// line_details_downtime_chart.js
console.log('Downtime chart script loaded'); // Kiểm tra script được load

let lineDowntimeChart = null;

async function updateLineDowntimeChart(lineId, period) {
   try {
       console.log('updateLineDowntimeChart called with:', { lineId, period }); // Log tham số đầu vào
       
       const response = await fetch(`api/get_line_downtime.php?period=${period}&line=${lineId}`);
       console.log('API Response received'); // Log khi nhận response
       
       const rawData = await response.json();
       console.log('API Data:', rawData); // Log dữ liệu từ API
       
       if (!rawData || rawData.length === 0) {
           console.log('No data returned from API');
           return;
       }
       
       // Tính tổng thời gian dừng
       const totalDowntime = rawData.reduce((sum, item) => sum + item.value, 0);
       console.log('Total downtime:', totalDowntime); // Log tổng thời gian
       
       // Tạo dữ liệu cho waterfall chart
       let cumulativeTotal = 0;
       const labels = rawData.map(item => item.name).concat(['Tổng']);
       console.log('Chart labels:', labels); // Log labels

       const data = rawData.map(item => {
           const previousTotal = cumulativeTotal;
           cumulativeTotal += item.value;
           return { baseline: previousTotal, duration: item.value };
       }).concat({ baseline: 0, duration: totalDowntime });

       console.log('Chart data prepared:', data); // Log dữ liệu đã chuẩn bị

       const durations = data.map(item => item.duration);
       const baselines = data.map(item => item.baseline);

       // Xóa chart cũ nếu tồn tại
       if (lineDowntimeChart) {
           console.log('Destroying old chart');
           lineDowntimeChart.destroy();
       }

       // Lấy context cho chart
       const canvas = document.getElementById('lineDowntimeChart');
       console.log('Canvas element:', canvas); // Log element canvas

       if (!canvas) {
           console.error('Canvas element not found!');
           return;
       }

       const ctx = canvas.getContext('2d');
       
       // Tạo chart mới
       console.log('Creating new chart...'); // Log trước khi tạo chart
       lineDowntimeChart = new Chart(ctx, {
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
                       suggestedMax: Math.ceil(totalDowntime + 8),
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
       
       console.log('Chart created successfully');
   } catch (error) {
       console.error('Error in updateLineDowntimeChart:', error);
   }
}

// Khởi tạo chart
function initLineDowntimeChart() {
    console.log('initLineDowntimeChart called'); // Log khi hàm được gọi
    
    const lineElement = document.querySelector('[data-line]');
    console.log('Line element found:', lineElement); // Log element tìm được
    
    if (lineElement) {
        const lineId = lineElement.getAttribute('data-line');
        console.log('Line ID:', lineId); // Log lineId
        updateLineDowntimeChart(lineId, 'today');
    } else {
        console.error('No element with data-line attribute found');
    }
}

// Thêm event listener cho DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');
    initLineDowntimeChart();
});