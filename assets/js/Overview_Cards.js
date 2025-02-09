// Các giá trị target
const TARGETS = {
    OEE: 89,
    STEAM: 98,
    POWER: 4.5
};

// Format số thành to K, M (nghìn, triệu)
function formatNumber(num) {
    if (num >= 1000000) {
        return (num / 1000000).toFixed(2) + 'M';
    } else if (num >= 1000) {
        return (num / 1000).toFixed(1) + 'K';
    }
    return num.toString();
}

// Khởi tạo overview cards
function initOverviewCards() {
    console.log('Initializing overview cards...');
    updateOverviewCards('today');
}

// Hàm cập nhật Overview Cards
async function updateOverviewCards(period = 'today') {
    try {
        console.log('Fetching data for period:', period);
        const response = await fetch(`api/get_filtered_data.php?period=${period}`);
        const data = await response.json();
        console.log('Received data:', data);
        
        // Cập nhật tổng sản lượng
        updateProductionCard(data);
        
        // Cập nhật OEE
        updateOEECard(data);
        
        // Cập nhật tiêu hao hơi
        updateSteamCard(data);
        
        // Cập nhật tiêu hao điện
        updatePowerCard(data);
        
    } catch (error) {
        console.error('Error updating overview cards:', error);
    }
}

function updateProductionCard(data) {
    const totalProd = parseInt(data.total_production) || 0;
    const l5Prod = parseInt(data.l5_production) || 0;
    const l6Prod = parseInt(data.l6_production) || 0;
    const l7Prod = parseInt(data.l7_production) || 0;
    const l8Prod = parseInt(data.l8_production) || 0;
    const productionTarget = parseInt(data.total_plan) || 0;
    
    // Tính chênh lệch theo giá trị tuyệt đối
    const productionDiff = totalProd - productionTarget;
    
    // Cập nhật UI với định dạng số mới
    document.getElementById('total-production').textContent = formatNumber(totalProd);
    document.getElementById('l5-production').textContent = formatNumber(l5Prod);
    document.getElementById('l6-production').textContent = formatNumber(l6Prod);
    document.getElementById('l7-production').textContent = formatNumber(l7Prod);
    document.getElementById('l8-production').textContent = formatNumber(l8Prod);
    
    // Cập nhật phần trăm bằng giá trị chênh lệch
    const element = document.querySelector('#total-production + span');
    if (element) {
        const displayValue = productionDiff > 0 ? '+' + formatNumber(productionDiff) : formatNumber(productionDiff);
        element.textContent = displayValue;
        element.className = `ml-2 px-2 py-1 rounded text-sm ${
            productionDiff >= 0 ? 'bg-green-50 text-green-500' : 'bg-red-50 text-red-500'
        }`;
    }
}

function updateOEECard(data) {
    const totalOEE = parseFloat(data.total_oee) || 0;
    const l5OEE = parseFloat(data.l5_oee) || 0;
    const l6OEE = parseFloat(data.l6_oee) || 0;
    const l7OEE = parseFloat(data.l7_oee) || 0;
    const l8OEE = parseFloat(data.l8_oee) || 0;
    // Cập nhật UI
    document.getElementById('total-oee').textContent = totalOEE.toFixed(2) + '%';
    document.getElementById('l5-oee').textContent = l5OEE.toFixed(2) + '%';
    document.getElementById('l6-oee').textContent = l6OEE.toFixed(2) + '%';
    document.getElementById('l7-oee').textContent = l7OEE.toFixed(2) + '%';
    document.getElementById('l8-oee').textContent = l8OEE.toFixed(2) + '%';
    // Tính và hiển thị % so với target
    const oeePercent = ((totalOEE - TARGETS.OEE) / TARGETS.OEE * 100).toFixed(2);
    updatePercentageTag('total-oee', oeePercent);
}

function updateSteamCard(data) {
    const totalSteam = parseFloat(data.total_steam) || 0;
    const l5Steam = parseFloat(data.l5_steam) || 0;
    const l6Steam = parseFloat(data.l6_steam) || 0;
    
    // Cập nhật UI
    document.getElementById('steam-consumption').textContent = totalSteam.toFixed(2);
    document.getElementById('l5-steam').textContent = l5Steam.toFixed(2);
    document.getElementById('l6-steam').textContent = l6Steam.toFixed(2);
    
    // Tính phần trăm (giá trị thấp hơn là tốt hơn)
    const steamPercent = ((totalSteam - TARGETS.STEAM) / TARGETS.STEAM * 100).toFixed(2);
    updatePercentageTag('steam-consumption', steamPercent);
}

function updatePowerCard(data) {
    const totalPower = parseFloat(data.total_power) || 0;
    const powerTarget = parseFloat(data.power_target) || 0;
    const l5Power = parseFloat(data.l5_power) || 0;
    const l6Power = parseFloat(data.l6_power) || 0;
    const l7Power = parseFloat(data.l7_power) || 0;
    const l8Power = parseFloat(data.l8_power) || 0;
    const mnkPower = parseFloat(data.mnk_power) || 0;
    const ahuPower = parseFloat(data.ahu_power) || 0;
    
    // Làm tròn số nguyên và thêm đơn vị Kw
    document.getElementById('power-consumption').textContent = Math.round(totalPower) + ' Kw';
    document.getElementById('l5-power').textContent = Math.round(l5Power) + ' Kw';
    document.getElementById('l6-power').textContent = Math.round(l6Power) + ' Kw';
    document.getElementById('l7-power').textContent = Math.round(l7Power) + ' Kw';
    document.getElementById('l8-power').textContent = Math.round(l8Power) + ' Kw';
    document.getElementById('mnk-power').textContent = Math.round(mnkPower) + ' Kw';
    document.getElementById('ahu-power').textContent = Math.round(ahuPower) + ' Kw';
    
    // Cập nhật phần trăm cho tiêu thụ điện
    const element = document.querySelector('#power-consumption + span');
    if (element) {
        const powerPercent = ((totalPower / powerTarget) * 100).toFixed(2);
        element.textContent = powerPercent + '%';
        element.className = `ml-2 px-2 py-1 rounded text-sm ${
            parseFloat(powerPercent) > 50 ? 'bg-red-50 text-red-500' : 'bg-green-50 text-green-500'
        }`;
    }
}

function updatePercentageTag(elementId, percentage) {
    const element = document.querySelector(`#${elementId} + span`);
    if (element) {
        // Xử lý riêng cho power-consumption
        if (elementId === 'power-consumption') {
            return; // Bỏ qua vì đã xử lý trong updatePowerCard
        }
        
        // Xử lý cho các trường hợp khác
        const isPositiveMetric = ['total-oee'].includes(elementId);
        const displayValue = isPositiveMetric ? percentage : -percentage;
        const isGood = isPositiveMetric ? (percentage >= 0) : (percentage < 0);
        
        element.textContent = (displayValue > 0 ? '+' : '') + displayValue + '%';
        element.className = `ml-2 px-2 py-1 rounded text-sm ${
            isGood ? 'bg-green-50 text-green-500' : 'bg-red-50 text-red-500'
        }`;
    }
}

// Khởi tạo khi trang load
document.addEventListener('DOMContentLoaded', initOverviewCards);