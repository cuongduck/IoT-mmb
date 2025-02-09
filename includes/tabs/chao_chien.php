<?php
if (!isset($line)) {
    $line = $_GET['line'] ?? 'L5';
}
?>

<div class="w-full mb-4 bg-white rounded-lg shadow p-4 relative">
    <!-- Container cho hình ảnh và overlay -->
    <div class="w-full relative">
        <img src="images/Chaochien.PNG" 
             alt="Sơ đồ chảo chiên" 
             class="w-full h-auto"
        />
        
        <!-- Overlay cho các giá trị realtime -->
        <div class="absolute inset-0">
            <!-- Thông tin tốc độ dao và tần số -->
            <div class="absolute left-0 md:left-[3%] top-0 md:top-[2%] w-full md:w-auto bg-white/80 rounded p-1 md:p-2 text-xs md:text-sm">
                <div class="flex flex-wrap md:flex-nowrap items-center justify-center gap-2 md:gap-6">
                    <div class="flex items-center">
                        <span class="text-blue-600 font-medium text-sm md:text-xl">Speed: </span>
                        <div class="text-sm md:text-xl font-bold text-orange-600">
                            <span id="<?= $line ?>-toc-do-dao">60</span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="text-blue-600 font-medium text-sm md:text-xl">BTH: </span>
                        <div class="text-sm md:text-xl font-bold text-orange-600">
                            <span id="<?= $line ?>-tan-so-BTH">0</span>
                            <span class="text-xs md:text-lg">HZ</span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="text-blue-600 font-medium text-sm md:text-xl">QHD: </span>
                        <div class="text-sm md:text-xl font-bold text-red-600">
                            <span id="<?= $line ?>-tan-so-QHD">0</span>
                            <span class="text-xs md:text-lg">HZ</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nhiệt độ + áp suất + lưu lượng hấp -->
            <div class="absolute left-[10%] md:left-[15%] top-[25%] md:top-[29%] bg-white/80 backdrop-blur rounded p-0.5 md:p-1">
                <div class="flex items-center gap-2 md:gap-4">
                    <div class="flex items-center">
                        <div class="text-sm md:text-xl font-bold text-red-600">
                            <span id="<?= $line ?>-hap-pressure">0</span>
                            <span class="text-xs md:text-base">bar</span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="text-sm md:text-xl font-bold text-blue-600">
                            <span id="<?= $line ?>-hap-flow">0</span>
                            <span class="text-xs md:text-base">kg/h</span>
                        </div>
                    </div>
                </div>
            </div>
  
  <!-- Thời gian chien -->
  
            <div class="absolute left-[34%] top-[30%] bg-white/80 rounded px-0.5 md:px-1 py-0.5 md:py-1">
                <div class="text-m md:text-l font-bold text-red-500">
                    <span id="<?= $line ?>-time-hap">0.0</span> S
                </div>
            </div>

  <!-- Thời gian nguoi -->
  
            <div class="absolute right-[28%] top-[70%] bg-white/95 rounded px-0.5 md:px-1 py-0.5 md:py-1">
                <div class="text-m md:text-l font-bold text-red-500">
                    <span id="<?= $line ?>-time-nguoi">0.0</span> S
                </div>
            </div>

            <!-- Nhiệt độ + áp suất + lưu lượng chiên -->
            <div class="absolute left-[15%] md:left-[21%] top-[81%] md:top-[81%] bg-white/80 backdrop-blur rounded p-0.5 md:p-1">
                <div class="flex items-center gap-2 md:gap-4">
                    <div class="flex items-center">
                        <div class="text-sm md:text-xl font-bold text-red-600">
                            <span id="<?= $line ?>-chien-pressure">0</span>
                            <span class="text-xs md:text-base">bar</span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="text-sm md:text-xl font-bold text-blue-600">
                            <span id="<?= $line ?>-chien-flow">0</span>
                            <span class="text-xs md:text-base">kg/h</span>
                        </div>
                    </div>
                </div>
            </div>
                 <!-- Thời gian chien -->
                <div class="absolute left-[28%] top-[70.5%] bg-white/80 backdrop-blur rounded px-0.5 md:px-1 py-0.5 md:py-1">
                <div class="text-m md:text-l font-bold text-red-600">
                    <span id="<?= $line ?>-time-chien">0</span> S
                </div>
            </div>
            
            <!-- Các tần số lưới -->
            <div class="absolute right-[42%] top-[10%] bg-white/80 rounded px-0.5 md:px-1 py-0.5 md:py-1">
                <div class="text-xs md:text-l font-bold text-red-600">
                    <span id="<?= $line ?>-tan-so-hap">5.0</span> Hz
                </div>
            </div>

            <div class="absolute right-[19%] top-[3%] bg-white/80 rounded px-0.5 md:px-1 py-0.5 md:py-1">
                <div class="text-xs md:text-l font-bold text-red-600">
                    <span id="<?= $line ?>-tan-so-luoi-sea">5.0</span> Hz
                </div>
            </div>

            <div class="absolute right-[10%] top-[2%] bg-white/80 rounded px-0.5 md:px-1 py-0.5 md:py-1">
                <div class="text-xs md:text-l font-bold text-red-600">
                    <span id="<?= $line ?>-tan-so-luoi-kg1">5.0</span> Hz
                </div>
            </div>

            <div class="absolute right-[2%] top-[1%] bg-white/80 rounded px-0.5 md:px-1 py-0.5 md:py-1">
                <div class="text-xs md:text-l font-bold text-red-600">
                    <span id="<?= $line ?>-tan-so-luoi-kg2">5.0</span> Hz
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chi tiết nhiệt độ chảo chiên -->
<div class="bg-white rounded-lg shadow p-2 md:p-4">
    <div class="grid grid-cols-3 md:grid-cols-6 gap-2 md:gap-4">
        <!-- Headers -->
        <div class="font-medium hidden md:block"></div>
        <?php for($i = 1; $i <= 5; $i++): ?>
            <div class="text-center font-medium text-sm md:text-base">Nhiệt <?= $i ?></div>
        <?php endfor; ?>

        <!-- Nhiệt độ trái -->
        <div class="font-medium text-red-600 text-sm md:text-base">Trái</div>
        <?php for($i = 1; $i <= 5; $i++): ?>
            <div class="text-center bg-gray-50 p-1 rounded relative group">
                <span id="<?= $line ?>-nhiet<?= $i ?>-t" class="temperature-value text-base md:text-xl font-bold">0</span>
                <!-- Tooltip -->
                <div class="hidden group-hover:block absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-xs rounded">
                    Cài đặt: <span id="<?= $line ?>-nhiet<?= $i ?>-t-set">0</span>°C
                </div>
            </div>
        <?php endfor; ?>

        <!-- Nhiệt độ phải -->
        <div class="font-medium text-orange-600 text-sm md:text-base">Phải</div>
        <?php for($i = 1; $i <= 5; $i++): ?>
            <div class="text-center bg-gray-50 p-1 rounded relative group">
                <span id="<?= $line ?>-nhiet<?= $i ?>-p" class="temperature-value text-base md:text-xl font-bold">0</span>
                <!-- Tooltip -->
                <div class="hidden group-hover:block absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-xs rounded">
                    Cài đặt: <span id="<?= $line ?>-nhiet<?= $i ?>-p-set">0</span>°C
                </div>
            </div>
        <?php endfor; ?>
    </div>
</div>

<style>
/* Temperature value animations and styles */
.temperature-value {
    transition: all 0.3s ease;
}

.temperature-value.updated {
    animation: pulse 0.5s ease-in-out;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

/* Tooltip styles */
.group:hover .temperature-value {
    color: #2563eb;
}

/* Overlay background styles */
.bg-white\/80 {
    background-color: rgba(255, 255, 255, 0.8);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .temperature-value {
        font-size: 0.875rem;
    }
    
    .grid-cols-6 {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

/* Value update animation */
.value-update {
    animation: highlight 0.5s ease-in-out;
}

@keyframes highlight {
    0% { background-color: rgba(59, 130, 246, 0.1); }
    100% { background-color: transparent; }
}
</style>

<script>
// Function to update temperature values with animation
function updateTemperature(elementId, value) {
    const element = document.getElementById(elementId);
    if (!element) return;

    const currentValue = parseFloat(element.textContent);
    const newValue = parseFloat(value);

    if (currentValue !== newValue) {
        element.classList.add('updated');
        element.textContent = newValue.toFixed(1);
        
        // Remove animation class after animation completes
        setTimeout(() => {
            element.classList.remove('updated');
        }, 500);
    }
}

// Function to check temperature threshold and update style
function checkTemperatureThreshold(elementId, value, threshold) {
    const element = document.getElementById(elementId);
    if (!element) return;

    const temperature = parseFloat(value);
    if (temperature > threshold) {
        element.classList.add('text-red-600');
    } else {
        element.classList.remove('text-red-600');
    }
}
</script>