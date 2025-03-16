let weightTable = null;

async function updateWeightTable(period) {
    try {
        const response = await fetch(`api/get_weight_table.php?period=${period}`);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const result = await response.json();
        const data = result.data;
        
        const tableBody = document.getElementById('weightTableContent');
        let html = '';
        
        // Render các hàng dữ liệu
        data.forEach((row, index) => {
            const time = new Date(row.time);
            const formattedTime = time.toLocaleString('vi-VN', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
            
            const rowClass = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
            
            html += `
                <tr class="${rowClass} hover:bg-gray-100 transition-colors">
                    <td class="px-2 py-2 border border-gray-200">${formattedTime}</td>
                    <td class="px-2 py-2 border border-gray-200">${row.L5_sp || ''}</td>
                    <td class="px-2 py-2 border border-gray-200 text-right">${row.L5_chuan.toFixed(2)}</td>
                    <td class="px-2 py-2 border border-gray-200 text-right">${row.L5_TLTB.toFixed(2)}</td>
                    <td class="px-2 py-2 border border-gray-200 text-right">${row.L5_chenh_lech.toFixed(2)}</td>
                    <td class="px-2 py-2 border border-gray-200">${row.L6_sp || ''}</td>
                    <td class="px-2 py-2 border border-gray-200 text-right">${row.L6_chuan.toFixed(2)}</td>
                    <td class="px-2 py-2 border border-gray-200 text-right">${row.L6_TLTB.toFixed(2)}</td>
                    <td class="px-2 py-2 border border-gray-200 text-right">${row.L6_chenh_lech.toFixed(2)}</td>
                    <td class="px-2 py-2 border border-gray-200">${row.L7_sp || ''}</td>
                    <td class="px-2 py-2 border border-gray-200 text-right">${row.L7_chuan.toFixed(2)}</td>
                    <td class="px-2 py-2 border border-gray-200 text-right">${row.L7_TLTB.toFixed(2)}</td>
                    <td class="px-2 py-2 border border-gray-200 text-right">${row.L7_chenh_lech.toFixed(2)}</td>
                    <td class="px-2 py-2 border border-gray-200">${row.L8_sp || ''}</td>
                    <td class="px-2 py-2 border border-gray-200 text-right">${row.L8_chuan.toFixed(2)}</td>
                    <td class="px-2 py-2 border border-gray-200 text-right">${row.L8_TLTB.toFixed(2)}</td>
                    <td class="px-2 py-2 border border-gray-200 text-right">${row.L8_chenh_lech.toFixed(2)}</td>
                </tr>
            `;
        });
        
        tableBody.innerHTML = html;
        console.log('Cập nhật bảng trọng lượng thành công');
    } catch (error) {
        console.error('Lỗi khi cập nhật bảng trọng lượng:', error);
    }
}

// Khởi tạo bảng
function initWeightTable() {
    updateWeightTable('today');
    // Cập nhật mỗi 5 phút
    setInterval(() => updateWeightTable('today'), 5 * 60 * 1000);
}

// Đăng ký sự kiện khi DOM đã sẵn sàng
document.addEventListener('DOMContentLoaded', function() {
    initWeightTable();
});