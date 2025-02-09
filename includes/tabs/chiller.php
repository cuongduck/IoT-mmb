<?php
if (!isset($line)) {
    $line = $_GET['line'] ?? 'L5';
}
?>
    <div class="chiller-system w-full bg-white p-4 rounded-lg shadow">
        <!-- Background image -->
        <div class="relative w-full">
            <img src="../images/Chiller.png" alt="Chiller System" class="w-full">
            
            <!-- Overlay values -->
            <div class="absolute inset-0">
                <!-- Nhiệt độ đầu ra -->
                <div class="absolute left-[50%] top-[8%] value-box">
                    <span id="chiller-out-temp">0.0</span>°C
                </div>

                <!-- Nhiệt độ đầu vào -->
                <div class="absolute right-[8%] top-[65%] value-box">
                    <span id="chiller-in-temp">0.0</span>°C
                </div>

                <!-- Tần số bơm tuần hoàn -->
                <div class="value-box absolute left-[18%] top-[57%]">
                    <span id="bth1-hz">0.0</span>Hz
                </div>
                <div class="value-box absolute left-[18%] top-[76%]">
                    <span id="bth2-hz">0.0</span>Hz
                </div>
                
                                <!-- Nhiệt độ cooling tower -->
                <div class="absolute left-[8%] top-[35%] value-box-2">
                    <span id="cooling-tower-temp">0.0</span>°C
                </div>
                
                                                <!-- áp suất cooling tower -->
                <div class="absolute left-[23%] top-[48%] value-box-2">
                    <span id="cooling-tower-press">0.0</span>Bar
                </div>
                

                <!-- Status Indicators -->
                <div class="status-circle bth1 absolute left-[14.5%] top-[64.5%]" data-status="0"></div>
                <div class="status-circle bth2 absolute left-[14.5%] top-[75.5%]" data-status="0"></div>
                <div class="status-square chiller1 absolute left-[44%] top-[40%]" data-status="0"></div>
                <div class="status-square chiller2 absolute left-[44%] top-[71.5%]" data-status="0"></div>
                <div class="status-circle bomll1 absolute right-[19.1%] top-[64.4%]" data-status="0"></div>
                <div class="status-circle bomll2 absolute right-[19.1%] top-[75.4%]" data-status="0"></div>
                <div class="status-circle fan-tower absolute left-[10%] top-[15%]" data-status="0"></div>

                <!-- Flow Status -->
                <div class="status-circle-min flow1 absolute left-[30.5%] top-[60%]" data-status="0"></div>
                <div class="status-circle-min flow2 absolute left-[30.5%] top-[73%]" data-status="0"></div>
                <div class="status-circle-min flow3 absolute right-[28.4%] top-[65.2%]" data-status="0"></div>
            </div>
        </div>
    </div>


<style>
.value-box {
    background: rgba(3, 148, 252, 0.9);
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: bold;
    color: white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

.value-box-2 {
    background: rgba(245, 181, 7, 0.9);
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: bold;
    color: white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

.status-circle {
    width: 25px;
    height: 25px;
    border-radius: 50%;
    transition: all 0.3s;
}

.status-circle[data-status="0"] {
    background: #dc2626;
}

.status-circle[data-status="1"] {
    background: #22c55e;
}

.status-square {
    width: 20px;
    height: 20px;
    transition: all 0.3s;
}

.status-square[data-status="0"] {
    background: #dc2626;
}

.status-square[data-status="1"] {
    background: #22c55e;
}

.status-circle-min {
    width: 15px;
    height: 15px;
    border-radius: 50%;
    transition: all 0.3s;
}

.status-circle-min[data-status="0"] {
    background: #dc2626;
}

.status-circle-min[data-status="1"] {
    background: #0362fc;
}
</style> 


