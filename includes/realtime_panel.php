<?php
if (!isset($line)) {
    $line = isset($_GET['line']) ? $_GET['line'] : 'L5';
}
?>

<div id="<?= $line ?>-realtime" class="bg-gray-100 p-2 md:p-4">
    <!-- Include tab navigation buttons -->
    <?php include 'components/tab_buttons.php'; ?>
    
    <!-- Include tab contents -->
    <div id="chao_chien-tab" class="tab-content">
        <?php include 'tabs/chao_chien.php'; ?>
    </div>
    
    <div id="Can-tab" class="tab-content hidden">
        <?php include 'tabs/lo_can.php'; ?>
    </div>
    
    <div id="tron-tab" class="tab-content hidden">
        <?php include 'tabs/tron_bot.php'; ?>
    </div>
    
    <div id="kansui-tab" class="tab-content hidden">
        <?php include 'tabs/kansui.php'; ?>
    </div>
    
    <div id="Chiller-tab" class="tab-content hidden">
        <?php include 'tabs/chiller.php'; ?>
    </div>
    
    <div id="bao_goi-tab" class="tab-content hidden">
        <?php include 'tabs/bao_goi.php'; ?>
    </div>
</div>
<!-- Include shared styles -->
<?php include 'components/tab_styles.php'; ?>