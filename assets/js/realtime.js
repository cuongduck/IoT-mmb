let realtimeInterval = null;
let chillerInterval = null;
console.log('Realtime.js loaded');

function showRealtime(line) {
    console.log('Showing realtime for line:', line);
    const container = document.getElementById('realtimeContainer');
    const content = document.getElementById('realtimeContent');
    const title = document.getElementById('realtimeTitle');

    title.textContent = `Realtime - Line ${line}`;

    fetch(`includes/realtime_panel.php?line=${line}`)
        .then(response => response.text())
        .then(html => {
            content.innerHTML = html;
            container.classList.remove('hidden');
            container.scrollIntoView({ behavior: 'smooth', block: 'start' });
            
            updateRealtime(line);
            if (realtimeInterval) {
                clearInterval(realtimeInterval);
            }
            realtimeInterval = setInterval(() => updateRealtime(line), 500);
        })
        .catch(error => {
            console.error('Error loading realtime panel:', error);
            content.innerHTML = '<div class="text-red-500">Error loading content. Please try again.</div>';
        });
}

function closeRealtime() {
    const container = document.getElementById('realtimeContainer');
    container.classList.add('hidden');
    
    if (realtimeInterval) {
        clearInterval(realtimeInterval);
        realtimeInterval = null;
    }
}

function switchTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(`${tabName}-tab`).classList.remove('hidden');
    
    // Add active class to clicked button
    event.currentTarget.classList.add('active');

    // Nếu là tab Lô Cán thì bắt đầu update dữ liệu
    if (tabName === 'Can') {
        // Lấy line hiện tại từ title
        const titleElement = document.querySelector('#realtimeTitle');
        const match = titleElement?.textContent?.match(/Line (L\d+)/);
        const line = match ? match[1] : 'L5';
        
        // Clear interval cũ nếu có
        if (window.loCanInterval) {
            clearInterval(window.loCanInterval);
        }
        
        // Update dữ liệu lần đầu
        updateLoCanData(line);
        
        // Set interval mới
        window.loCanInterval = setInterval(() => updateLoCanData(line), 500);
    } 
    // Thêm xử lý cho tab Chiller
    else if (tabName === 'Chiller') {
        // Clear interval cũ nếu có
        if (window.chillerInterval) {
            clearInterval(window.chillerInterval);
        }
        
        // Update dữ liệu lần đầu
        updateChillerData();
        
        // Set interval mới
        window.chillerInterval = setInterval(() => updateChillerData(), 500);
    }
    else {
        // Clear các interval khi chuyển sang tab khác
        if (window.loCanInterval) {
            clearInterval(window.loCanInterval);
            window.loCanInterval = null;
        }
        if (window.chillerInterval) {
            clearInterval(window.chillerInterval);
            window.chillerInterval = null;
        }
    }
}

// Thêm function update dữ liệu lô cán
function updateLoCanData(line) {
    fetch(`api/get_lo_can_data.php?line=${line}`)
        .then(response => response.json())
        .then(data => {
            const motors = [
                'BT', 'Nhoi1', 'Nhoi2', 
                'Tho1', 'Tho2', 'Tho3',
                'BTTho', 'BTTinh',
                'Tinh1', 'Tinh2', 'Tinh3', 'Tinh4', 'Tinh5', 'Tinh6', 'Tinh7',
                'DCS', 'LNhung'
            ];

            motors.forEach(motor => {
                // Update Hz values
                updateValueWithAnimation(
                    `${line}-${motor}-Hz`,
                    data[`${line}_${motor}_Hz`]
                );
                
                // Update Ampere values  
                updateValueWithAnimation(
                    `${line}-${motor}-A`, 
                    data[`${line}_${motor}_A`]
                );
                
                // Update Temperature values
                updateValueWithAnimation(
                    `${line}-${motor}-T`,
                    data[`${line}_${motor}_T`]
                );
            });
        })
        .catch(error => {
            console.error('Error updating lo can data:', error);
            if (window.loCanInterval) {
                clearInterval(window.loCanInterval);
                window.loCanInterval = null;
            }
        });
}
// Thêm function update chiller
function updateChillerData() {
    fetch('api/get_chiller_data.php')
        .then(response => response.json())
        .then(data => {
         
            // Update temperatures chiller
            updateValueWithAnimation('chiller-out-temp', data.F3_Nhiet_Chiller_out);
            updateValueWithAnimation('chiller-in-temp', data.F3_Nhiet_Chiller_in);

            // Update temperatures cooling tower and ap
            updateValueWithAnimation('cooling-tower-temp', data.F3_Nhiet_cooling_tower);
            updateValueWithAnimation('cooling-tower-press', data.F3_ap_cooling_tower);
            
            // Update Hz values
            updateValueWithAnimation('bth1-hz', data.F3_Hz_BTH_1);
            updateValueWithAnimation('bth2-hz', data.F3_Hz_BTH_2);

            // Update status circles
            updateStatus('bth1', data.F3_BTH_1_Run);
            updateStatus('bth2', data.F3_BTH_2_Run);
            updateStatus('chiller1', data.F3_Chiller_1_Run);
            updateStatus('chiller2', data.F3_Chiller_2_Run);
            updateStatus('bomll1', data.F3_Bom_LL_1_Run);
            updateStatus('bomll2', data.F3_Bom_LL_2_Run);
            updateStatus('fan-tower', data.F3_Fan_Tower_Run);

            // Update flow status squares
            updateStatus('flow1', data.F3_SW_Flow_1);
            updateStatus('flow2', data.F3_SW_Flow_2);
            updateStatus('flow3', data.F3_SW_Flow_3);
        })
        .catch(error => {
            console.error('Error updating chiller data:', error);
            if (window.chillerInterval) {
                clearInterval(window.chillerInterval);
            }
        });
}

function updateStatus(elementClass, status) {
    const element = document.querySelector(`.${elementClass}`);
    if (element) {
        element.setAttribute('data-status', status);
    }
}

function updateValueWithAnimation(elementId, value, maxValue = 100) {
    const element = document.getElementById(elementId);
    if (!element) return;

    const newValue = parseFloat(value) || 0;
    const oldValue = parseFloat(element.textContent) || 0;

    if (oldValue !== newValue) {
        element.classList.add('updated');
        element.textContent = newValue.toFixed(1);
        
        const progressBar = element.closest('.relative')?.querySelector('.progress-bar');
        if (progressBar) {
            if (newValue === 0) {
                progressBar.style.width = '0%';
            } else {
                const percentage = (newValue / maxValue) * 100;
                const clampedPercentage = Math.min(Math.max(percentage, 0), 100);
                progressBar.style.width = `${clampedPercentage}%`;
            }
        }

        setTimeout(() => {
            element.classList.remove('updated');
        }, 300);
    }
}

function updateSiloBar(elementId, barId, value, maxValue = 340) {
    const element = document.getElementById(elementId);
    const bar = document.getElementById(barId);
    if (!element || !bar) return;

    const newValue = parseFloat(value) || 0;
    element.textContent = newValue.toFixed(1);

    const percentage = (newValue / maxValue) * 100;
    const clampedPercentage = Math.min(Math.max(percentage, 0), 100);
    bar.style.height = `${clampedPercentage}%`;

    if (clampedPercentage > 90) {
        bar.classList.remove('bg-blue-500/80');
        bar.classList.add('bg-red-500/80');
    } else {
        bar.classList.remove('bg-red-500/80');
        bar.classList.add('bg-blue-500/80');
    }
}

async function updateRealtime(line) {
    try {
        const response = await fetch(`api/get_line_realtime.php?line=${line}`);
        const data = await response.json();
        
        // Cập nhật nhiệt độ
        for (let i = 1; i <= 5; i++) {
            updateValueWithAnimation(`${line}-nhiet${i}-t`, data[`${line}_Nhiet${i}_T`]);
            updateValueWithAnimation(`${line}-nhiet${i}-p`, data[`${line}_Nhiet${i}_P`]);
        }

        // Hệ thống hấp
        updateValueWithAnimation(`${line}-hap-temp`, data[`${line}_Hap_Temp`], 200);
        updateValueWithAnimation(`${line}-hap-pressure`, data[`${line}_Hap_Pressure`], 15);
        updateValueWithAnimation(`${line}-hap-flow`, data[`${line}_Hap_Flow`], 700);
        updateValueWithAnimation(`${line}-time-hap`, data[`${line}_Time_Hap`], 300);
        
        // Hệ thống chiên
        updateValueWithAnimation(`${line}-chien-temp`, data[`${line}_Chien_Temp`], 200);
        updateValueWithAnimation(`${line}-chien-pressure`, data[`${line}_Chien_Pressure`], 15);
        updateValueWithAnimation(`${line}-chien-flow`, data[`${line}_Chien_Flow`], 3000);
        
        // Các tần số
        updateValueWithAnimation(`${line}-tan-so-BTH`, data[`${line}_BTH`], 50);
        updateValueWithAnimation(`${line}-tan-so-QHD`, data[`${line}_QHD`], 50);
        updateValueWithAnimation(`${line}-tan-so-hap`, data[`${line}_HAP`], 50);
        updateValueWithAnimation(`${line}-tan-so-luoi-sea`, data[`${line}_SEA`], 50);
        updateValueWithAnimation(`${line}-tan-so-luoi-kg1`, data[`${line}_KG1`], 50);
        updateValueWithAnimation(`${line}-tan-so-luoi-kg2`, data[`${line}_KG2`], 50);
        updateValueWithAnimation(`${line}-toc-do-dao`, data[`${line}_Speed`], 62);
        
        // Hệ thống trộn
        updateValueWithAnimation(`${line}-tron-silo1`, data[`${line}_Silo1`], 350);
        updateValueWithAnimation(`${line}-tron-silo2`, data[`${line}_Silo2`], 350);
        updateValueWithAnimation(`${line}-tron-kansui`, data[`${line}_Kansui`], 130);
        
        // Thời gian trộn
        updateValueWithAnimation(`${line}-time-tron-kho-1`, data[`${line}_Time_Tron_Kho_1`], 2);
        updateValueWithAnimation(`${line}-time-tron-uot-1`, data[`${line}_Time_Tron_uot_1`], 20);
        updateValueWithAnimation(`${line}-time-tron-kho-2`, data[`${line}_Time_Tron_Kho_2`], 2);
        updateValueWithAnimation(`${line}-time-tron-uot-2`, data[`${line}_Time_Tron_uot_2`], 20);
        
        // Cập nhật silo bars
        updateSiloBar(`${line}-tron-silo1`, 'bar-silo1', data[`${line}_Silo1`]);
        updateSiloBar(`${line}-tron-silo2`, 'bar-silo2', data[`${line}_Silo2`]);
        updateSiloBar(`${line}-tron-kansui`, 'bar-kansui', data[`${line}_Kansui`], 130);

        // Tank values
        updateValueWithAnimation(`${line}-tank1-water`, data[`${line}_water_sea_PV`]);
        updateValueWithAnimation(`${line}-tank2-water`, data[`${line}_water_ks_PV`]);
        
        // Tank temperatures
        updateValueWithAnimation(`${line}-tank3-temp`, data[`${line}_nhiet_bll`]);
        updateValueWithAnimation(`${line}-tank4-temp`, data[`${line}_nhiet_bc`]);
        
        // Fixed brick values
        const tank1Brick = document.getElementById(`${line}-tank1-brick`);
        const tank2Brick = document.getElementById(`${line}-tank2-brick`);
        if (tank1Brick) tank1Brick.textContent = "0.0";
        if (tank2Brick) tank2Brick.textContent = "0.0";
    } catch (error) {
        console.error('Error updating realtime data:', error);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM loaded, initializing realtime features');
});