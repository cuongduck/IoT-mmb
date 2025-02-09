<?php
if (!isset($line)) {
    $line = $_GET['line'] ?? 'L5';
}
?>

<div class="w-full mb-4 bg-white rounded-lg shadow p-16">
    <div class="relative">
        <img src="images/Lo_Can.PNG" alt="Sơ đồ lô cán" class="w-full h-auto"/>
        <div class="absolute inset-0">
            <!-- BT -->
            <div class="absolute left-[4%] bottom-[75%] motor-box">
                <div class="motor-title bg-blue-600">BT</div>
                <div class="motor-values">
                    <div class="hz-value"><span id="<?= $line ?>-BT-Hz">0.0</span>Hz</div>
                    <div class="amp-value"><span id="<?= $line ?>-BT-A">0.0</span>A</div>
                    <div class="temp-value"><span id="<?= $line ?>-BT-T">0.0</span>°C</div>
                </div>
            </div>

            <!-- Nhồi -->
            <?php 
            $nhoiPositions = [
                ['left' => '0%', 'top' => '28%'],
                ['left' => '21%', 'top' => '5%']
            ];
            foreach($nhoiPositions as $index => $pos): ?>
            <div class="absolute motor-box" style="left: <?= $pos['left'] ?>; top: <?= $pos['top'] ?>">
                <div class="motor-title bg-green-600">Nhồi <?= $index + 1 ?></div>
                <div class="motor-values">
                    <div class="hz-value"><span id="<?= $line ?>-Nhoi<?= $index + 1 ?>-Hz">0.0</span>Hz</div>
                    <div class="amp-value"><span id="<?= $line ?>-Nhoi<?= $index + 1 ?>-A">0.0</span>A</div>
                    <div class="temp-value"><span id="<?= $line ?>-Nhoi<?= $index + 1 ?>-T">0.0</span>°C</div>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Thô -->
            <?php 
            $thoPositions = [
                ['right' => '90%', 'top' => '60%'],
                ['right' => '80%', 'top' => '60%'],
                ['right' => '65%', 'top' => '5%']
            ];
            foreach($thoPositions as $index => $pos): ?>
            <div class="absolute motor-box" style="right: <?= $pos['right'] ?>; top: <?= $pos['top'] ?>">
                <div class="motor-title bg-yellow-500">Thô <?= $index + 1 ?></div>
                <div class="motor-values">
                    <div class="hz-value"><span id="<?= $line ?>-Tho<?= $index + 1 ?>-Hz">0.0</span>Hz</div>
                    <div class="amp-value"><span id="<?= $line ?>-Tho<?= $index + 1 ?>-A">0.0</span>A</div>
                    <div class="temp-value"><span id="<?= $line ?>-Tho<?= $index + 1 ?>-T">0.0</span>°C</div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <!-- bt tho - tinh -->
            <div class="absolute left-[21%] top-[74%] motor-box">
                <div class="motor-title bg-blue-500">BT Thô</div>
                <div class="motor-values">
                    <div class="hz-value"><span id="<?= $line ?>-BTTho-Hz">0.0</span>Hz</div>
                    <div class="amp-value"><span id="<?= $line ?>-BTTho-A">0.0</span>A</div>
                    <div class="temp-value"><span id="<?= $line ?>-BTTho-T">0.0</span>°C</div>
                </div>
            </div>

            <div class="absolute left-[32%] top-[74%] motor-box">
                <div class="motor-title bg-blue-500">BT Tinh</div>
                <div class="motor-values">
                    <div class="hz-value"><span id="<?= $line ?>-BTTinh-Hz">0.0</span>Hz</div>
                    <div class="amp-value"><span id="<?= $line ?>-BTTinh-A">0.0</span>A</div>
                    <div class="temp-value"><span id="<?= $line ?>-BTTinh-T">0.0</span>°C</div>
                </div>
            </div>            
            <!-- Tinh -->
            <?php 
            $tinhPositions = [
                ['left' => '35%', 'top' => '-10%'],
                ['left' => '45%', 'top' => '-10%'],
                ['left' => '54%', 'top' => '-10%'],
                ['left' => '63%', 'top' => '-10%'],
                ['left' => '72%', 'top' => '-10%'],
                ['left' => '81%', 'top' => '-10%'],
                ['left' => '90%', 'top' => '-10%']
            ];
            foreach($tinhPositions as $index => $pos): ?>
            <div class="absolute motor-box" style="left: <?= $pos['left'] ?>; top: <?= $pos['top'] ?>">
                <div class="motor-title bg-red-600">Tinh <?= $index + 1 ?></div>
                <div class="motor-values">
                    <div class="hz-value"><span id="<?= $line ?>-Tinh<?= $index + 1 ?>-Hz">0.0</span>Hz</div>
                    <div class="amp-value"><span id="<?= $line ?>-Tinh<?= $index + 1 ?>-A">0.0</span>A</div>
                    <div class="temp-value"><span id="<?= $line ?>-Tinh<?= $index + 1 ?>-T">0.0</span>°C</div>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- DCS & LNhung -->
            <div class="absolute right-[3%] bottom-[0%] motor-box">
                <div class="motor-title bg-orange-500">DCS</div>
                <div class="motor-values">
                    <div class="hz-value"><span id="<?= $line ?>-DCS-Hz">0.0</span>Hz</div>
                    <div class="amp-value"><span id="<?= $line ?>-DCS-A">0.0</span>A</div>
                    <div class="temp-value"><span id="<?= $line ?>-DCS-T">0.0</span>°C</div>
                </div>
            </div>

            <div class="absolute left-[96%] bottom-[41%] motor-box">
                <div class="motor-title bg-orange-500">L.Nhung</div>
                <div class="motor-values">
                    <div class="hz-value"><span id="<?= $line ?>-LNhung-Hz">0.0</span>Hz</div>
                    <div class="amp-value"><span id="<?= $line ?>-LNhung-A">0.0</span>A</div>
                    <div class="temp-value"><span id="<?= $line ?>-LNhung-T">0.0</span>°C</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.motor-box {
    background: rgba(255, 255, 255, 0.55);
    border-radius: 0.25rem;
    padding: 0.25rem;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    min-width: 70px;
    transform: scale(0.85);
}

.motor-title {
    color: white;
    font-weight: 600;  /* Tăng từ 500 lên 600 */
    padding: 0.125rem 0.25rem;
    border-radius: 0.125rem;
    text-align: center;
    margin-bottom: 0.125rem;
    font-size: 0.7rem;
    letter-spacing: 0.025em;
}

.motor-values {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
}

.hz-value, .amp-value, .temp-value {
    padding: 0.125rem;
    border-radius: 0.125rem;
    text-align: center;
    font-size: 0.7rem;
    font-weight: 600;  /* Thêm font-weight cho giá trị */
    transition: all 0.2s;
}

.hz-value {
    background: rgba(59, 130, 246, 0.15);  /* Tăng độ đậm của background */
    color: #1d4ed8;  /* Màu xanh đậm hơn */
}

.amp-value {
    background: rgba(245, 158, 11, 0.15);
    color: #b45309;  /* Màu cam đậm hơn */
}

.temp-value {
    background: rgba(239, 68, 68, 0.15);
    color: #b91c1c;  /* Màu đỏ đậm hơn */
}

/* Màu title đậm hơn */
.motor-title.bg-blue-600 { background-color: #2563eb; }
.motor-title.bg-green-600 { background-color: #16a34a; }
.motor-title.bg-yellow-500 { background-color: #d97706; }
.motor-title.bg-red-600 { background-color: #dc2626; }
.motor-title.bg-orange-500 { background-color: #ea580c; }

.value-update {
    animation: pulse 0.5s ease-in-out;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
</style>