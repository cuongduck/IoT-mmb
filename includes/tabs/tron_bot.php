<?php
if (!isset($line)) {
    $line = $_GET['line'] ?? 'L5';
}
?>

<div class="w-full mb-4 bg-white rounded-lg shadow p-4 relative">
    <img src="images/Tron.PNG" 
         alt="Sơ đồ trộn bột" 
         class="w-full h-auto"
    />

    <!-- Overlay cho các giá trị Silo -->
    <div class="absolute inset-0">
        <!-- Silo 1 -->
        <div class="absolute left-[33%] top-[30%] bg-gray-200/90 backdrop-blur rounded p-1 text-xs md:text-sm shadow-sm">
            <div class="flex items-center">
                <div class="text-sm md:text-xl font-bold text-blue-600">
                    <span id="<?= $line ?>-tron-silo1">0</span>
                    <span class="text-xs md:text-base">KG</span>
                </div>
            </div>
        </div>

        <!-- Silo 2 -->
        <div class="absolute right-[34%] top-[30%] bg-gray-200/90 backdrop-blur rounded p-1 text-xs md:text-sm shadow-sm">
            <div class="flex items-center">
                <div class="text-sm md:text-xl font-bold text-blue-600">
                    <span id="<?= $line ?>-tron-silo2">0</span>
                    <span class="text-xs md:text-base">KG</span>
                </div>
            </div>
        </div>

        <!-- Kansui -->
        <div class="absolute right-[48%] top-[65%] bg-gray-200/90 backdrop-blur rounded p-1 text-xs md:text-sm shadow-sm">
            <div class="flex items-center">
                <div class="text-sm md:text-xl font-bold text-blue-600">
                    <span id="<?= $line ?>-tron-kansui">0</span>
                    <span class="text-xs md:text-base">KG</span>
                </div>
            </div>
        </div>

        <!-- Thanh bar Silo 1 -->
        <div class="absolute left-[35%] top-[14%]">
            <div class="h-20 w-8 bg-gray-200/80 rounded-sm overflow-hidden">
                <div id="bar-silo1" class="absolute bottom-0 w-full bg-blue-500/80 transition-all duration-300 rounded-sm"
                     style="height: 0%">
                </div>
            </div>
        </div>

        <!-- Thanh bar Silo 2 -->
        <div class="absolute right-[36%] top-[14.5%]">
            <div class="h-20 w-8 bg-gray-200/80 rounded-sm overflow-hidden">
                <div id="bar-silo2" class="absolute bottom-0 w-full bg-blue-500/80 transition-all duration-300 rounded-sm"
                     style="height: 0%">
                </div>
            </div>
        </div>

        <!-- Thanh bar Kansui -->
        <div class="absolute right-[50.5%] top-[51%]">
            <div class="h-12 w-4 bg-gray-200/80 rounded-sm overflow-hidden">
                <div id="bar-kansui" class="absolute bottom-0 w-full bg-blue-500/80 transition-all duration-300 rounded-sm"
                     style="height: 0%">
                </div>
            </div>
        </div>

        <!-- Thời gian trộn cối 1 -->
        <div class="absolute left-[5%] md:left-[10%] top-[58%] md:top-[65%] bg-gray-200/90 backdrop-blur rounded-lg shadow-lg p-2 md:p-4 text-xs md:text-sm">
            <div class="flex flex-col items-start gap-3 md:gap-6">
                <div class="text-base md:text-lg font-semibold text-blue-600">Thời gian trộn Cối 1</div>
                
                <div class="flex items-center gap-2">
                    <span class="text-blue-600 font-medium text-sm md:text-xl">Trộn Khô:</span>
                    <div class="text-lg md:text-xl font-bold text-red-600">
                        <span id="<?= $line ?>-time-tron-kho-1">0</span>
                        <span class="text-sm md:text-base">Phút</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-blue-600 font-medium text-sm md:text-xl">Trộn Ướt:</span>
                    <div class="text-lg md:text-xl font-bold text-red-600">
                        <span id="<?= $line ?>-time-tron-uot-1">0</span>
                        <span class="text-sm md:text-base">Phút</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thời gian trộn cối 2 -->
        <div class="absolute right-[6%] md:right-[10%] top-[58%] md:top-[65%] bg-gray-200/90 backdrop-blur rounded-lg shadow-lg p-2 md:p-4 text-xs md:text-sm">
            <div class="flex flex-col items-start gap-3 md:gap-6">
                <div class="text-base md:text-lg font-semibold text-blue-600">Thời gian trộn Cối 2</div>
                
                <div class="flex items-center gap-2">
                    <span class="text-blue-600 font-medium text-sm md:text-xl">Trộn Khô:</span>
                    <div class="text-lg md:text-xl font-bold text-red-600">
                        <span id="<?= $line ?>-time-tron-kho-2">0</span>
                        <span class="text-sm md:text-base">Phút</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-blue-600 font-medium text-sm md:text-xl">Trộn Ướt:</span>
                    <div class="text-lg md:text-xl font-bold text-red-600">
                        <span id="<?= $line ?>-time-tron-uot-2">0</span>
                        <span class="text-sm md:text-base">Phút</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>