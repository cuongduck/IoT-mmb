<?php
if (!isset($line)) {
    $line = $_GET['line'] ?? 'L5';
}
?>
    <div class="w-full mb-4 bg-white rounded-lg shadow p-12">

        <div class="kansui-system">

            <div class="container">

                <div class="tank-system">

                    <!-- Arrow Left -->

                    <div class="arrow-container-left">

                        <div class="line-label left">Tưới Sea Line <?= str_replace('L', '', $line) ?></div>

                        <div class="arrow-pipe arrow-left"></div>

                        <svg class="arrow left" viewBox="0 0 12 20">

                            <polygon points="12,0 0,10 12,20"/>

                        </svg>

                    </div>



                    <?php 

                    $tanks = [

                        ['name' => 'Pha Sea', 'hasCapacity' => true, 'hasVerticalPipe' => true, 'hasPipeAfter' => false],

                        ['name' => 'Pha kansui', 'hasCapacity' => true, 'hasVerticalPipe' => true, 'hasPipeAfter' => true],

                        ['name' => 'Làm lạnh', 'hasCapacity' => false, 'hasVerticalPipe' => false, 'hasPipeAfter' => true, 'hasCoolingPipes' => true],

                        ['name' => 'Bồn chứa', 'hasCapacity' => false, 'hasVerticalPipe' => false, 'hasPipeAfter' => false, 'hasCoolingPipes' => true]

                    ];

                    ?>



                    <?php foreach ($tanks as $index => $tank): ?>

                        <div class="tank-unit">

                            <?php if ($index < 2): ?>

                                <div class="capacity">

                                    <span id="<?= $line ?>-tank<?= $index+1 ?>-water">0.0</span>L

                                </div>

                            <?php endif; ?>



                            <div class="tank">

                                <?php if ($tank['hasVerticalPipe']): ?>

                                    <div class="vertical-pipe"></div>

                                <?php endif; ?>

                                

                                <?php include('../images/tank.svg'); ?>

                                

                                <?php if (isset($tank['hasCoolingPipes']) && $tank['hasCoolingPipes']): ?>

                                    <div class="cooling-system">

                                        <div class="cooling-pipe cooling-in">

                                            <div class="pipe-horizontal"></div>

                                            <div class="arrow-up orange"></div>

                                        </div>

                                        <div class="cooling-pipe cooling-out">

                                            <div class="pipe-horizontal"></div>

                                            <div class="arrow-up blue"></div>

                                        </div>

                                    </div>

                                <?php endif; ?>



                                <?php if ($index < 2): ?> 

                                    <div class="brick">

                                       Brix : <span id="<?= $line ?>-tank<?= $index+1 ?>-brick">0.0</span>

                                    </div>

                                <?php else: ?>

                                    <div class="specs">

                                        <span id="<?= $line ?>-tank<?= $index+1 ?>-temp">0.0</span>°C

                                    </div>

                                <?php endif; ?>

                            </div>

                            <div class="tank-name"><?php echo $tank['name']; ?></div>

                        </div>



                        <?php if ($tank['hasPipeAfter']): ?>

                            <div class="pipe-section">
                                     
                                <div class="pipe"></div>
 <div class="van-chuyen-bon">  <?php include('../images/Valve.svg'); ?></div>
                            </div>

                        <?php endif; ?>

                    <?php endforeach; ?>



                    <!-- Arrow Right -->

                    <div class="arrow-container-right">

                        <div class="line-label right">Cối trộn Line <?= str_replace('L', '', $line) ?></div>

                        <div class="arrow-pipe arrow-right"></div>

                        <svg class="arrow right" viewBox="0 0 12 20">

                            <polygon points="0,0 12,10 0,20"/>

                        </svg>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>