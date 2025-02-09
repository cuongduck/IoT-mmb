// CSS cho trạng thái
const styles = `
.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-badge.running {
    background-color: #10B981;
    color: white;
}

.status-badge.stopped {
    background-color: #EF4444;
    color: white;
}

.status-badge.unknown {
    background-color: #6B7280;
    color: white;
}`;

// Thêm CSS vào document
const styleSheet = document.createElement("style");
styleSheet.textContent = styles;
document.head.appendChild(styleSheet);

// Hàm cập nhật trạng thái chính
async function updateLineStatus() {
    try {
        const response = await fetch('api/get_line_status.php');
        if (!response.ok) {
            throw new Error(`Lỗi HTTP! status: ${response.status}`);
        }
        const data = await response.json();
        
        ['L5', 'L6', 'L7', 'L8'].forEach(line => updateLineStatusUI(line, data));
    } catch (error) {
        console.error('Lỗi cập nhật trạng thái:', error);
    }
}

// Hàm cập nhật UI cho từng line
function updateLineStatusUI(line, data) {
    const elements = {
        status: document.querySelector(`#${line.toLowerCase()}-status`),
        speed: document.querySelector(`#${line.toLowerCase()}-speed`),
        product: document.querySelector(`#${line.toLowerCase()}-product`)
    };
    
    if (!elements.status || !elements.speed || !elements.product) {
        console.warn(`Thiếu phần tử UI cho ${line}`);
        return;
    }

    const status = (data[`${line}_Status`] || '').toLowerCase();
    const speed = data[`${line}_Speed`] || '0';
    const product = data[`${line}_ten_SP`] || '-';

    // Cập nhật trạng thái với hiệu ứng
    elements.status.textContent = status === 'running' ? 'Đang chạy' : 'Dừng';
    elements.status.className = `status-badge ${status || 'unknown'}`;
    
    // Cập nhật tốc độ với hiệu ứng
    if (elements.speed.textContent !== `${speed} Dao/phút`) {
        elements.speed.style.transition = 'opacity 0.3s';
        elements.speed.style.opacity = '0';
        setTimeout(() => {
            elements.speed.textContent = `${speed} Dao/phút`;
            elements.speed.style.opacity = '1';
        }, 300);
    }
    
    // Cập nhật tên sản phẩm
    elements.product.textContent = product;
}

// Khởi động cập nhật định kỳ
let updateInterval;

function startStatusUpdates(intervalMs = 5000) {
    updateLineStatus(); // Cập nhật lần đầu
    updateInterval = setInterval(updateLineStatus, intervalMs);
}

function stopStatusUpdates() {
    if (updateInterval) {
        clearInterval(updateInterval);
    }
}

// Khởi tạo khi trang đã tải xong
document.addEventListener('DOMContentLoaded', () => {
    startStatusUpdates();
});

// Dọn dẹp khi thoát trang
window.addEventListener('unload', stopStatusUpdates);