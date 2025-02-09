<div class="card">
    <div class="flex justify-between items-center mb-2">
        <h3 class="text-lg font-semibold">Line <?php echo substr($line, -1); ?></h3>
        <div id="<?php echo strtolower($line); ?>-status" class="status">-</div>
    </div>
    <div class="flex flex-col space-y-2">
        <div class="text-sm text-gray-600">
            Tốc độ: <span id="<?php echo strtolower($line); ?>-speed">0 Dao/phút</span>
        </div>
        <div class="text-sm text-gray-600">
            SP đang chạy: <span id="<?php echo strtolower($line); ?>-product">-</span>
        </div>
        <a href="?page=line_details&line=<?php echo $line; ?>" class="text-blue-500 hover:underline text-sm">
            Xem chi tiết →
        </a>
    </div>
</div>