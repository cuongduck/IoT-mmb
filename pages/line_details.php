<?php
$line = isset($_GET['line']) ? $_GET['line'] : 'L5';
$validLines = ['L5', 'L6', 'L7', 'L8'];

if (!in_array($line, $validLines)) {
    header('Location: ?page=factory');
    exit;
}
?>

<div data-line="<?php echo $line; ?>" class="container mx-auto p-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4">
            <a href="?page=factory" class="text-blue-500 hover:underline">← Quay lại</a>
            <h1 class="text-2xl font-bold">Line <?php echo substr($line, -1); ?></h1>
        </div>
    </div>

    <!-- Status Card -->
    <div class="card mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <div class="text-sm text-gray-600">Trạng thái</div>
                <div id="<?php echo strtolower($line); ?>-status" class="status mt-1">-</div>
            </div>
            <div>
                <div class="text-sm text-gray-600">Tốc độ</div>
                <div id="<?php echo strtolower($line); ?>-speed" class="text-xl font-semibold mt-1">0 Dao/phút</div>
            </div>
            <div>
                <div class="text-sm text-gray-600">SP đang chạy</div>
                <div id="<?php echo strtolower($line); ?>-product" class="text-xl font-semibold mt-1">-</div>
            </div>
        </div>
    </div>

    

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="card">
            <h3 class="text-lg font-semibold mb-4">OEE</h3>
            <div class="chart-container" style="height: 300px;">
                <canvas id="lineOEEChart"></canvas>
            </div>
        </div>
        <div class="card">
            <h3 class="text-lg font-semibold mb-4">Tiêu hao hơi</h3>
            <div class="chart-container" style="height: 300px;">
                <canvas id="lineSteamChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Line trend chart -->
    <div class="card mt-6">
        <h3 class="text-lg font-semibold mb-4">Biểu đồ Trend TLTB - Nhiệt cuối - Hơi chiên</h3>
        <div class="chart-container" style="height: 300px;">
            <canvas id="lineTrendChart"></canvas>
        </div>
    </div>

    <!-- Data Mixing Table -->
    <div class="card mt-6">
        <h3 class="text-lg font-semibold mb-4">Dữ liệu Trộn Bột <?php echo $line; ?></h3>
        <div class="overflow-x-auto">
            <div class="max-h-[350px] overflow-y-auto">
                <table class="min-w-full border-collapse">
                    <thead class="sticky top-0">
                        <tr class="bg-[#4472C4] text-white">
                            <th class="px-4 py-2 border border-[#8EA9DB] text-center">Thời gian</th>
                            <th class="px-4 py-2 border border-[#8EA9DB] text-center">KL_Cối_1_Bơm</th>
                            <th class="px-4 py-2 border border-[#8EA9DB] text-center">KL_Cối_1_Xả</th>
                            <th class="px-4 py-2 border border-[#8EA9DB] text-center">KL_KS_Cối_1</th>
                            <th class="px-4 py-2 border border-[#8EA9DB] text-center">KL_Cối_2_Bơm</th>
                            <th class="px-4 py-2 border border-[#8EA9DB] text-center">KL_Cối_2_Xả</th>
                            <th class="px-4 py-2 border border-[#8EA9DB] text-center">KL_KS_Cối_2</th>
                        </tr>
                    </thead>
                    <tbody id="mixingTableBody" class="text-sm">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.sticky {
    position: sticky;
    top: 0;
    z-index: 10;
    background: #4472C4;
}

.btn {
    padding: 0.5rem 1rem;
    margin-right: 0.5rem;
    border-radius: 0.375rem;
    background-color: #f3f4f6;
    transition: all 0.2s;
}

.btn:hover {
    background-color: #e5e7eb;
}

.btn.active {
    background-color: #2563eb;
    color: white;
}
</style>

<!-- Load Scripts -->
<script src="assets/js/line_details_oee_chart.js?v=<?php echo time(); ?>"></script>
<script src="assets/js/line_details_steam_chart.js?v=<?php echo time(); ?>"></script>
<script src="assets/js/line_details_trend_chart.js?v=<?php echo time(); ?>"></script>
<script src="assets/js/line_details_mixing_table.js?v=<?php echo time(); ?>"></script>
<script src="assets/js/line_details.js?v=<?php echo time(); ?>"></script>