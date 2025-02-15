function editPlan(lineNumber) {
    // Lấy thẻ div chứa thông tin kế hoạch
    const planDiv = document.getElementById(`plan-${lineNumber}`);
    
    // Lấy các giá trị từ data attributes và các phần tử
    document.getElementById('line_number').value = lineNumber;
    document.getElementById('plan_id').value = planDiv.dataset.id || '';
    
    // Lấy và cập nhật tên sản phẩm
    const productElement = document.getElementById(`product-${lineNumber}`);
    const product = productElement.innerText;
    document.getElementById('product_name').value = 
        product !== 'Chưa có kế hoạch' ? product : '';
    
    // Lấy và cập nhật sản lượng
    const quantityElement = document.getElementById(`quantity-${lineNumber}`);
    const quantity = quantityElement.innerText
        .replace(' Gói', '')
        .replace(',', '');
    document.getElementById('quantity').value = 
        quantity !== '0' ? quantity : '';
    
    // Xử lý thời gian
    if (planDiv.dataset.startTime && planDiv.dataset.endTime) {
        // Parse thời gian từ data attributes mà không thêm 'Z' để tránh chuyển đổi timezone
        const startDateTime = new Date(planDiv.dataset.startTime);
        const endDateTime = new Date(planDiv.dataset.endTime);
        
        // Format datetime-local input value (YYYY-MM-DDTHH:mm)
        const formatDateTimeForInput = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        };

        document.getElementById('start_time').value = formatDateTimeForInput(startDateTime);
        document.getElementById('end_time').value = formatDateTimeForInput(endDateTime);
    } else {
        // Nếu không có dữ liệu, sử dụng thời gian mặc định
        const now = new Date();
        const formatDateTimeForInput = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        };
        
        document.getElementById('start_time').value = formatDateTimeForInput(now);
        const end = new Date(now.getTime() + (8 * 60 * 60 * 1000));
        document.getElementById('end_time').value = formatDateTimeForInput(end);
    }
    
    // Hiển thị modal
    document.getElementById('editModal').classList.remove('hidden');
}
function closeModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function savePlan() {
    const formData = new FormData(document.getElementById('editForm'));
    
    // Chuyển đổi thời gian sang định dạng MySQL datetime
    const startTime = new Date(formData.get('Tu_ngay'));
    const endTime = new Date(formData.get('den_ngay'));
    
    // Format MySQL datetime (YYYY-MM-DD HH:mm:ss)
    const formatDateTimeForMySQL = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const seconds = String(date.getSeconds()).padStart(2, '0');
        return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    };

    // Cập nhật giá trị thời gian trong formData
    formData.set('Tu_ngay', formatDateTimeForMySQL(startTime));
    formData.set('den_ngay', formatDateTimeForMySQL(endTime));
    
    fetch('includes/update_plan.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Có lỗi xảy ra: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi lưu kế hoạch');
    });
}