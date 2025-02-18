<div class="container mx-auto p-4">


    <!-- Line Status Cards -->
   
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Line 5 -->
    <div class="bg-white rounded shadow">
        <div class="p-4">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-lg font-semibold">Line 5</h3>
                <div id="l5-status" class="status-badge"></div>
            </div>
            <div class="mb-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Tên SP:</span>
                    <span id="l5-product" class="font-medium"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Tốc độ:</span>
                    <span id="l5-speed" class="font-medium"></span>
                </div>
            </div>
            <div class="flex gap-2">
    <a href="?page=line_details&line=L5" class="flex-1 text-center py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
        Chi tiết
    </a>
    <button onclick="showRealtime('L5')" class="flex-1 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors">
    Xem Realtime
</button>
</div>
        </div>
    </div>

    <!-- Line 6 -->

    <div class="bg-white rounded shadow">
        <div class="p-4">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-lg font-semibold">Line 6</h3>
                <div id="l6-status" class="status-badge"></div>
            </div>
            <div class="mb-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Tên SP:</span>
                    <span id="l6-product" class="font-medium"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Tốc độ:</span>
                    <span id="l6-speed" class="font-medium"></span>
                </div>
            </div>
             <div class="flex gap-2">
    <a href="?page=line_details&line=L6" class="flex-1 text-center py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
        Chi tiết
    </a>
    <button onclick="showRealtime('L6')" class="flex-1 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors">
    Xem Realtime
</button>
</div>
        </div>
    </div>

    <!-- Line 7 -->
    <div class="bg-white rounded shadow">
        <div class="p-4">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-lg font-semibold">Line 7</h3>
                <div id="l7-status" class="status-badge"></div>
            </div>
            <div class="mb-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Tên SP:</span>
                    <span id="l7-product" class="font-medium"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Tốc độ:</span>
                    <span id="l7-speed" class="font-medium"></span>
                </div>
            </div>
           <div class="flex gap-2">
    <a href="?page=line_details&line=L7" class="flex-1 text-center py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
        Chi tiết
    </a>
    <button onclick="showRealtime('L7')" class="flex-1 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors">
    Xem Realtime
</button>
</div>
        </div>
    </div>

    <!-- Line 8 -->
    <div class="bg-white rounded shadow">
        <div class="p-4">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-lg font-semibold">Line 8</h3>
                <div id="l8-status" class="status-badge"></div>
            </div>
            <div class="mb-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Tên SP:</span>
                    <span id="l8-product" class="font-medium"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Tốc độ:</span>
                    <span id="l8-speed" class="font-medium"></span>
                </div>
            </div>
             <div class="flex gap-2">
    <a href="?page=line_details&line=L8" class="flex-1 text-center py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
        Chi tiết
    </a>
    <button onclick="showRealtime('L8')" class="flex-1 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors">
    Xem Realtime
</button>
</div>
        </div>
    </div>
    </div>
    <!-- Realtime panel container -->
<div id="realtimeContainer" class="hidden mb-6">
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold" id="realtimeTitle">Realtime - Line 5</h3>
            <button onclick="closeRealtime()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div id="realtimeContent"></div>
    </div>
</div>
 <!-- Kết thúc Stutus line -->
   <!-- Overview Cards -->
<!-- Trong factory.php -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
<!-- Tổng gói -->
<!-- Tổng gói -->
<div class="bg-white rounded-lg p-4 shadow">
    <div class="flex items-center gap-2 mb-3">
        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"/>
        </svg>
        <span class="text-gray-600 font-medium">TỔNG GÓI</span>
    </div>
    <div class="mb-2">
        <span id="total-production" class="text-blue-600 text-3xl font-bold">0</span>
        <span class="ml-2 px-2 py-1 bg-red-50 text-red-500 text-sm rounded">0</span>
    </div>
    <div class="text-sm grid grid-cols-2 gap-x-4">
        <div class="flex items-center gap-1">
            <span class="inline-block w-2 h-2 bg-green-500 rounded-full"></span>
            <span class="text-gray-600">L5: </span>
            <span id="l5-production" class="font-medium">0</span>
        </div>
        <div class="flex items-center gap-1">
            <span class="inline-block w-2 h-2 bg-blue-500 rounded-full"></span>
            <span class="text-gray-600">L6: </span>
            <span id="l6-production" class="font-medium">0</span>
        </div>
        <div class="flex items-center gap-1">
            <span class="inline-block w-2 h-2 bg-yellow-500 rounded-full"></span>
            <span class="text-gray-600">L7: </span>
            <span id="l7-production" class="font-medium">0</span>
        </div>
        <div class="flex items-center gap-1">
            <span class="inline-block w-2 h-2 bg-purple-500 rounded-full"></span>
            <span class="text-gray-600">L8: </span>
            <span id="l8-production" class="font-medium">0</span>
        </div>
    </div>
</div>

    <!-- OEE -->
    <div class="bg-white rounded-lg p-4 shadow">
        <div class="flex items-center gap-2 mb-3">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="text-gray-600 font-medium">OEE</span>
        </div>
        <div class="mb-2">
            <span id="total-oee" class="text-yellow-500 text-3xl font-bold">0.00%</span>
            <span class="ml-2 px-2 py-1 bg-red-50 text-red-500 text-sm rounded">0.00%</span>
        </div>
          <div class="text-sm grid grid-cols-2 gap-x-4">
            <span class="flex items-center gap-1">
                <span class="inline-block w-2 h-2 bg-green-500 rounded-full"></span>
                <span class="text-gray-600">L5: </span>
                <span id="l5-oee" class="font-medium">0.00%</span>
            </span>
            <span class="flex items-center gap-1 mt-1">
                <span class="inline-block w-2 h-2 bg-blue-500 rounded-full"></span>
                <span class="text-gray-600">L6: </span>
                <span id="l6-oee" class="font-medium">0.00%</span>
            </span>
            <span class="flex items-center gap-1">
                <span class="inline-block w-2 h-2 bg-yellow-500 rounded-full"></span>
                <span class="text-gray-600">L7: </span>
                <span id="l7-oee" class="font-medium">0.00%</span>
            </span>
            <span class="flex items-center gap-1 mt-1">
                <span class="inline-block w-2 h-2 bg-purple-500 rounded-full"></span>
                <span class="text-gray-600">L8: </span>
                <span id="l8-oee" class="font-medium">0.00%</span>
            </span>
        </div>
    </div>

    <!-- Tiêu hao hơi -->
    <div class="bg-white rounded-lg p-4 shadow">
        <div class="flex items-center gap-2 mb-3">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <span class="text-gray-600 font-medium">TIÊU THỤ HƠI</span>
        </div>
        <div class="mb-2">
            <span id="steam-consumption" class="text-blue-600 text-3xl font-bold">0.00</span>
            <span class="ml-2 px-2 py-1 bg-red-50 text-red-500 text-sm rounded">0.00%</span>
        </div>
        <div class="text-sm">
            <span class="flex items-center gap-1">
                            <span class="inline-block w-2 h-2 bg-green-500 rounded-full"></span>
                <span class="text-gray-600">L5: </span>
                <span id="l5-steam" class="font-medium">0.00</span>
            </span>
            <span class="flex items-center gap-1 mt-1">
                <span class="inline-block w-2 h-2 bg-blue-500 rounded-full"></span>
                <span class="text-gray-600">L6: </span>
                <span id="l6-steam" class="font-medium">0.00</span>
            </span>
        </div>
    </div>

    <!-- Tiêu hao điện -->
<!-- Tiêu hao điện -->
<div class="bg-white rounded-lg p-4 shadow">
    <div class="flex items-center gap-2 mb-3">
        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        <span class="text-gray-600 font-medium">LƯỢNG ĐIỆN TIÊU THỤ</span>
    </div>
    <div class="mb-2">
        <span id="power-consumption" class="text-blue-600 text-3xl font-bold">0.00</span>
        <span class="ml-2 px-2 py-1 bg-red-50 text-red-500 text-sm rounded">0.00%</span>
    </div>
    <div class="text-sm grid grid-cols-2 gap-x-4">
        <div class="flex items-center gap-1">
            <span class="inline-block w-2 h-2 bg-green-500 rounded-full"></span>
            <span class="text-gray-600">L5: </span>
            <span id="l5-power" class="font-medium">0.00</span>
        </div>
        <div class="flex items-center gap-1">
            <span class="inline-block w-2 h-2 bg-blue-500 rounded-full"></span>
            <span class="text-gray-600">L6: </span>
            <span id="l6-power" class="font-medium">0.00</span>
        </div>
        <div class="flex items-center gap-1">
            <span class="inline-block w-2 h-2 bg-yellow-500 rounded-full"></span>
            <span class="text-gray-600">L7: </span>
            <span id="l7-power" class="font-medium">0.00</span>
        </div>
        <div class="flex items-center gap-1">
            <span class="inline-block w-2 h-2 bg-purple-500 rounded-full"></span>
            <span class="text-gray-600">L8: </span>
            <span id="l8-power" class="font-medium">0.00</span>
        </div>
        <div class="flex items-center gap-1">
            <span class="inline-block w-2 h-2 bg-orange-500 rounded-full"></span>
            <span class="text-gray-600">MNK: </span>
            <span id="mnk-power" class="font-medium">0.00</span>
        </div>
        <div class="flex items-center gap-1">
            <span class="inline-block w-2 h-2 bg-red-500 rounded-full"></span>
            <span class="text-gray-600">AHU: </span>
            <span id="ahu-power" class="font-medium">0.00</span>
        </div>
    </div>
</div>
</div>
  <!-- kết thúc overview -->
   <!-- Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- OEE Chart -->
    <div class="card">
        <h3 class="text-lg font-semibold mb-4">OEE Xưởng</h3>
        <div class="chart-container" style="height: 300px;">
            <canvas id="oeeChart"></canvas>
        </div>
    </div>
    <!-- OEE by Line Chart -->
    <div class="card">
        <h3 class="text-lg font-semibold mb-4">OEE Theo Line</h3>
        <div class="chart-container" style="height: 300px;">
            <canvas id="oeeByLineChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Steam Consumption Chart -->
    <div class="card">
        <h3 class="text-lg font-semibold mb-4">Hơi/Sp theo xưởng</h3>
        <div class="chart-container" style="height: 300px;">
            <canvas id="steamChart"></canvas>
        </div>
    </div>
    <!-- Steam Consumption by line Chart -->
    <div class="card">
        <h3 class="text-lg font-semibold mb-4">Lượng Hơi theo Khu vực</h3>
        <div class="chart-container" style="height: 300px;">
            <canvas id="steamUsageChart"></canvas>
        </div>
    </div>
</div>

<!-- Weight Chart -->
<div class="card mt-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Trọng Lượng Các Line</h3>
        <div class="flex gap-2">
    <button id="weightBtn5min" class="px-4 py-2 bg-blue-500 text-white rounded">Trend 5p</button>
    <button id="weightBtnHour" class="px-4 py-2 bg-gray-500 text-white rounded">Trend 1h</button>
    <button id="weightBtnShift" class="px-4 py-2 bg-gray-500 text-white rounded">Ca SX</button>
</div>
    </div>
    <div class="chart-container" style="height: 350px;">
        <canvas id="weightChart"></canvas>
    </div>
</div>
<!-- Downtime Chart -->

<div class="card mt-6">
        <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Downtime Xưởng</h3>
        <div class="flex gap-2">
        <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" onclick="switchChart('totalF3')">Xưởng</button>
        <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" onclick="switchChart('byLine')">Line</button>
</div>
    </div>
    <div class="chart-container" style="height: 390px;">
        <canvas id="downtimeChart"></canvas>
    </div>
</div>
     <!-- Downtime table -->
<div class="card mt-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Chi Tiết Downtime </h3>
        <select id="line-select" class="px-3 py-1 border rounded">
            <option value="all">Tất cả line</option>
            <option value="L5">Line 5</option>
            <option value="L6">Line 6</option>
            <option value="L7">Line 7</option>
            <option value="L8">Line 8</option>
        </select>
    </div>
    <div class="table-container" style="height: 350px; overflow-y: auto;">
        <table class="min-w-full">
            <thead class="table-header sticky top-0 bg-[#4472C4]">
    <tr>
        <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">Thời gian</th>
        <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">Line</th>
        <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">Tên lỗi</th>
        <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">Thời gian dừng</th>
        <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">Ghi chú</th>
        <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">Bắt đầu</th>
        <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">Kết thúc</th>
       <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">Action</th>
    </tr>
</thead>
            <tbody id="downtimeTableContent">
         </tbody>
        </table>
    </div>
</div>
<div class="mt-6"></div>

<!-- Container cho 2 biểu đồ điện năng -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
    <!-- Power Donut Chart -->
    <div class="lg:col-span-4">
        <div class="card">
            <h3 class="text-lg font-semibold mb-4">Điện Năng Theo Khu Vực</h3>
            <div class="chart-container" style="height: 320px;">
                <canvas id="powerDonutChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Power Line Chart -->
    <div class="lg:col-span-8">
        <div class="card">
            <h3 class="text-lg font-semibold mb-4">Trend Theo Thời Gian</h3>
            <div class="chart-container" style="height: 320px;">
                <canvas id="powerLineChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Power Usage Table -->
<div class="card mt-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Chi Tiết Điện năng hộ tiêu thụ</h3>
    </div>
    <div class="table-container" style="height: 350px; overflow-y: auto;">
        <table class="min-w-full">
            <thead class="table-header sticky top-0 bg-[#4472C4]">
                <tr>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">Time</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">Thông gió CS</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">VP_F3</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">MNK</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">AHU</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">Kansui</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L5</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L6</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L7</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L8</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">Phở_1</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">Phở_2</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">Kho</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">Tổng</th>
                </tr>
            </thead>
            <tbody id="powerTableContent">
                <!-- Dữ liệu sẽ được thêm vào đây bằng JavaScript -->
            </tbody>
        </table>
    </div>
</div>
<!-- Steam Table -->
<div class="card mt-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Chi Tiết Hơi sử dụng Theo khu vực</h3>
    </div>
    <div class="table-container relative" style="height: 350px; overflow-y: auto;">
        <table class="min-w-full">
            <thead class="sticky top-0 bg-[#4472C4]">
                <tr>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">Time</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L1_Hấp</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L1_Chiên</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L2_Hấp</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L2_Chiên</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L3_Hấp</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L5_Hấp</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L5_Chiên</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L6_Hấp</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L6_Chiên</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">Tổng_F3</th>
                </tr>
            </thead>
            <tbody id="steamTableContent">
                <!-- Dữ liệu sẽ được thêm vào đây bằng JavaScript -->
            </tbody>
        </table>
    </div>
</div>
<!-- TLTB Table -->
<div class="card mt-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Chi Tiết Trọng lượng theo mốc giờ</h3>
    </div>
    <div class="table-container" style="height: 350px; overflow-y: auto;">
        <table class="min-w-full">
            <thead class="table-header sticky top-0 bg-[#4472C4]">
                <tr>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">Time</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L5_Tên_SP</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L5_TLChuẩn</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L5_TLTB</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L5_CL</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L6_Tên_SP</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L6_TLChuẩn</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L6_TLTB</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L6_CL</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L7_Tên_SP</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L7_TLChuẩn</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L7_TLTB</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L7_CL</th>   
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L8_Tên_SP</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L8_TLChuẩn</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L8_TLTB</th>
                    <th class="px-4 py-2 text-left text-white border border-[#8EA9DB]">L8_CL</th>                       
                </tr>
            </thead>
            <tbody id="weightTableContent">
                <!-- Dữ liệu sẽ được thêm vào đây bằng JavaScript -->
            </tbody>
        </table>
    </div>
</div>
</div>
<!-- Modal Container -->
<div id="realtimeModal" class="fixed inset-0 hidden z-50">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    
    <!-- Modal -->
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 
                w-full max-w-4xl bg-white rounded-lg shadow-xl">
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-xl font-semibold" id="modalTitle">Realtime - Line 5</h3>
            <button onclick="closeRealtimeModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6" id="modalContent">
            <!-- Realtime panel will be loaded here -->
        </div>
    </div>
</div>