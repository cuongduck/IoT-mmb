let PowerTable = null;
async function updatePowerTable(period) {
    try {
        const response = await fetch(`api/get_power_table.php?period=${period}`);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.json();
        
        const tableBody = document.getElementById('powerTableContent');
        let html = '';
        
        data.data.forEach((row, index) => {
            const time = new Date(row.time);
            const formattedTime = time.toLocaleString('vi-VN', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
            
            // Thêm class để tô màu xen kẽ các hàng
            const rowClass = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
            
            html += `
                <tr class="${rowClass} hover:bg-gray-100 transition-colors">
                    <td class="px-4 py-2 border border-gray-200">${formattedTime}</td>
                    <td class="px-4 py-2 border border-gray-200 text-right">${row.thong_gio.toFixed(1)}</td>
                    <td class="px-4 py-2 border border-gray-200 text-right">${row.van_phong.toFixed(1)}</td>
                    <td class="px-4 py-2 border border-gray-200 text-right">${row.mnk.toFixed(1)}</td>
                    <td class="px-4 py-2 border border-gray-200 text-right">${row.ahu_chiller.toFixed(1)}</td>
                    <td class="px-4 py-2 border border-gray-200 text-right">${row.kansui.toFixed(1)}</td>
                    <td class="px-4 py-2 border border-gray-200 text-right">${row.line5.toFixed(1)}</td>
                    <td class="px-4 py-2 border border-gray-200 text-right">${row.line6.toFixed(1)}</td>
                    <td class="px-4 py-2 border border-gray-200 text-right">${row.line7.toFixed(1)}</td>
                    <td class="px-4 py-2 border border-gray-200 text-right">${row.line8.toFixed(1)}</td>
                    <td class="px-4 py-2 border border-gray-200 text-right">${row.pho1.toFixed(1)}</td>
                    <td class="px-4 py-2 border border-gray-200 text-right">${row.pho2.toFixed(1)}</td>
                    <td class="px-4 py-2 border border-gray-200 text-right">${row.kho.toFixed(1)}</td>
                    <td class="px-4 py-2 border border-gray-200 text-right font-bold">${row.total.toFixed(1)}</td>
                </tr>
            `;
        });
        
        tableBody.innerHTML = html;
        
        console.log('Cập nhật bảng điện năng thành công');
    } catch (error) {
        console.error('Lỗi khi cập nhật bảng điện năng:', error);
    }
}

// Khởi tạo bảng
function initPowerTable() {
    updatePowerTable('today');
}

// Đăng ký sự kiện khi period thay đổi
document.addEventListener('DOMContentLoaded', function() {
    initPowerTable();
});