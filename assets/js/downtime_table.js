// Khởi tạo biến
let downtimeTable;
let currentPeriod = 'today';
let currentLine = 'all';
let isAdmin = false;
let userRole = '';
let username = '';

// Danh sách lỗi
const errorTypes = [
   "",
   "Sự cố đóng gói",
   "Dừng Thả Phôi",
   "Dừng Thay dao",
   "Đứt bột cán",
   "Dừng Thay Dầu",
   "Đổi sản phẩm",
   "Chuẩn bị line",
   "Sự cố Cán",
   "Sự cố Hấp",
   "Sự cố Sea, KG",
   "Sự cố Cụm Dao cắt ĐL",
   "Sự cố Nguội", 
   "Sự cố Chiên",
   "Sự cố trộn",
   "Sự cố đổ bột",
   "Căn chỉnh thiết bị",
   "Nhảy CB, Mất, Nhá điện",
   "Dừng đi ăn",
   "Sự cố Khác"
];

// Hàm format date
function formatDateTime(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleString('vi-VN', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}

async function checkPermissions() {
    try {
        const response = await fetch('api/check_admin.php');
        const data = await response.json();
        isAdmin = data.isAdmin;
        userRole = data.role;  // Lưu role
        username = data.username;  // Lưu username
        
        if (!data.isLoggedIn) {
            window.location.href = 'login.php';
            return;
        }
    } catch (error) {
        console.error('Error checking permissions:', error);
        isAdmin = false;
    }
}


async function deleteRow(id) {
    if (!isAdmin) {
        alert('Bạn không có quyền xóa dữ liệu!');
        return;
    }
    
    if (!confirm('Bạn có chắc chắn muốn xóa dòng này?')) return;
    
    try {
        const response = await fetch('api/update_downtime.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                id: id,
                action: 'delete'
            })
        });

        const result = await response.json();
        
        if (result.success) {
            updateDowntimeTable(currentPeriod, currentLine);
        } else {
            alert('Xóa không thành công!');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Lỗi khi xóa dữ liệu!');
    }
}

async function updateDowntimeTable(period = 'today', line = 'all') {
    try {
        const response = await fetch(`api/get_downtime_table.php?period=${period}&line=${line}`);
        const data = await response.json();
        
        const tableContent = document.getElementById('downtimeTableContent');
        if (!tableContent) return;

        const editableUsers = ['ttcn', 'tcsx', 'ktcn', 'LinhNT'];
        const canEdit = isAdmin || editableUsers.includes(username); // Sử dụng username thay vì role

        let html = data.length === 0 ? 
            `<tr><td colspan="8" class="text-center py-4">Không có dữ liệu</td></tr>` :
            data.map((row, index) => {
                const bgClass = index % 2 === 0 ? 'bg-[#E9EDF4]' : 'bg-[#D9E1F2]';
                return `
                    <tr class="${bgClass}">
                        <td class="px-4 py-2 border border-[#8EA9DB]">${formatDateTime(row.Date)}</td>
                        <td class="px-4 py-2 border border-[#8EA9DB]">${row.Line}</td>
                        <td class="px-4 py-2 border border-[#8EA9DB]">
                            ${canEdit ? `
                                <select onchange="updateField(${row.ID}, 'Ten_Loi', this.value)" 
                                        class="w-full border-none focus:outline-none bg-transparent">
                                    ${errorTypes.map(type => `
                                        <option value="${type}" ${type === row.Ten_Loi ? 'selected' : ''}>
                                            ${type}
                                        </option>
                                    `).join('')}
                                </select>
                            ` : row.Ten_Loi}
                        </td>
                        <td class="px-4 py-2 border border-[#8EA9DB]">${row.Thoi_Gian_Dung} phút</td>
                        <td class="px-4 py-2 border border-[#8EA9DB]" ${canEdit ? 'contenteditable="true" onblur="updateField(' + row.ID + ', \'Ghi_Chu\', this.textContent)"' : ''}>
                            ${row.Ghi_Chu || '-'}
                        </td>
                        <td class="px-4 py-2 border border-[#8EA9DB]">${formatDateTime(row.Created_At)}</td>
                        <td class="px-4 py-2 border border-[#8EA9DB]">${formatDateTime(row.Updated_At)}</td>
                        ${isAdmin ? `
                            <td class="px-4 py-2 border border-[#8EA9DB]">
                                <button onclick="deleteRow(${row.ID})" 
                                        class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                    Xóa
                                </button>
                            </td>
                        ` : '<td class="px-4 py-2 border border-[#8EA9DB]"></td>'}
                    </tr>
                `;
            }).join('');

        tableContent.innerHTML = html;

    } catch (error) {
        console.error('Error:', error);
        const tableContent = document.getElementById('downtimeTableContent');
        if (tableContent) {
            tableContent.innerHTML = '<tr><td colspan="8" class="text-center py-4 text-red-500">Lỗi khi tải dữ liệu</td></tr>';
        }
    }
}

async function updateField(id, field, value) {
    const editableUsers = ['ttcn', 'tcsx', 'ktcn', 'LinhNT'];
    const canEdit = isAdmin || editableUsers.includes(username); // Sửa thành username
    
    // Kiểm tra quyền edit
    if (!canEdit) {
        alert('Bạn không có quyền chỉnh sửa!');
        return;
    }
   
    try {
        const response = await fetch('api/update_downtime.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id, field, value })
        });

        const result = await response.json();
        
        if (!result.success) {
            alert('Cập nhật không thành công!');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Lỗi khi cập nhật dữ liệu!');
    }
}
// Khởi tạo khi trang load
document.addEventListener('DOMContentLoaded', async () => {
    await checkPermissions();
    
    // Xử lý thay đổi line
    const lineSelect = document.getElementById('line-select');
    if (lineSelect) {
        lineSelect.addEventListener('change', (e) => {
            currentLine = e.target.value;
            updateDowntimeTable(currentPeriod, currentLine);
        });
    }

    // Xử lý các nút filter thời gian
    const periodButtons = document.querySelectorAll('.date-filter .btn');
    periodButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            currentPeriod = e.target.dataset.period;
            updateDowntimeTable(currentPeriod, currentLine);
            
            // Cập nhật trạng thái active của nút
            periodButtons.forEach(btn => btn.classList.remove('active'));
            e.target.classList.add('active');
        });
    });

    // Load dữ liệu ban đầu
    currentPeriod = document.querySelector('.date-filter .btn.active')?.dataset.period || 'today';
    updateDowntimeTable(currentPeriod, currentLine);
});

// Export for global access
window.updateDowntimeTable = updateDowntimeTable;
window.updateField = updateField;
window.deleteRow = deleteRow;