<!-- Load charts in correct order -->
    <script src="assets/js/Status_line.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/Overview_Cards.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/oeeByLine.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/realtime.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/Oee_Chart.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/Steam_Chart.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/steamUsageChart.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/Weight_Chart.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/Downtime_Chart.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/downtime_table.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/Power_Chart.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/Power_Line_Chart.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/Power_Table.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/Steam_table.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/Weight_table.js?v=<?php echo time(); ?>"></script>

    <!-- Load main.js last -->
        <script src="assets/js/charts.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/Main.js?v=<?php echo time(); ?>"></script>
<?php
require_once 'includes/visitor_counter.php';
$counter = new VisitorCounter();
$active_visitors = $counter->updateVisitor();
?>

<!-- Visitor Counter -->
<?php if (isAdmin()): ?> <!-- Chỉ admin mới thấy được link -->
    <a href="login_statistics.php" class="fixed bottom-2 right-2 bg-blue-600 text-white px-2 py-1 rounded-full shadow-lg text-sm hover:bg-blue-700 transition-colors cursor-pointer">
        <div class="flex items-center gap-1">
            <div class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></div>
            <span class="text-xs"><?php echo $active_visitors; ?></span>
        </div>
    </a>
<?php else: ?>
    <div class="fixed bottom-2 right-2 bg-blue-600 text-white px-2 py-1 rounded-full shadow-lg text-sm">
        <div class="flex items-center gap-1">
            <div class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></div>
            <span class="text-xs"><?php echo $active_visitors; ?></span>
        </div>
    </div>
<?php endif; ?>
</body>
</html>