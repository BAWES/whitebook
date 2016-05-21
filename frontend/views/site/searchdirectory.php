<?php
use yii\helpers\Url;

$total = count($directory);
if ((!empty($directory)) && ($total > 1)) {
    $first = $total / 3;
    $second = $first + $first;
    $third = $second + $first;
    $k = $first_letter;
    $l = $first_letter;
    ?>
    <div class="resposive-clearfix">
        <!-- first section start here-->
        <div class="col-md-3 resposive-clearfix">
            <?php $i = 0;
            foreach ($first_letter as $f) {
                ?>
                        <?php if ($i < $first) { ?>
                    <div class="direct_list">

                        <h2><?php echo $f; ?></h2>
                        <ul>
                            <?php
                            foreach ($directory as $d) {
                                $first_letter = strtoupper(substr($d['vname'], 0, 1));
                                if ($first_letter == $f) {
                                    if ($i < $first) {
                                        ?>
                                        <li><a href="<?= Url::toRoute('/site/vendor_profile/'.$d['slug']); ?>" title="<?php echo strtoupper($d['vname']); ?>"><?php echo strtoupper($d['vname']); ?></a></li>
                    <?php }
                }
            } ?>
                        </ul>
                    </div>
                <?php }$i++;
            } ?>
        </div>
        <!-- first section end here-->
        <!-- second section start here-->
        <div class="col-md-3">
                    <?php $i = 0;
                    foreach ($k as $f) {
                        ?>
                        <?php if (($i >= $first) && ($i < $second)) { ?>
                    <div class="direct_list">
                        <h2><?php echo $f; ?></h2>
                        <ul><?php
                    foreach ($directory as $d) {
                        $first_letter = strtoupper(substr($d['vname'], 0, 1));
                        if ($first_letter == $f) {
                            ?>
                                    <li><a href="<?= Url::toRoute('/site/vendor_profile/'.$d['slug']); ?>" title="<?php echo strtoupper($d['vname']); ?>"><?php echo strtoupper($d['vname']); ?></a></li>
                        <?php }
                    } ?>

                        </ul>
                    </div>
                        <?php }$i++;
                    } ?></div>
        <!-- second section end here-->
        <!-- Third section start here-->
        <div class="col-md-3 paddingright0">

                    <?php $i = 0;
                    foreach ($l as $f) {
                        ?>
        <?php if (($i >= $second) && ($i < $third)) { ?>
                    <div class="direct_list">
                        <h2><?php echo $f; ?></h2>
                        <ul>
            <?php
            foreach ($directory as $d) {
                $first_letter = strtoupper(substr($d['vname'], 0, 1));
                if ($first_letter == $f) {
                    ?>
                                    <li><a href="<?php Url::toRoute('/site/vendor_profile/'.$d['slug']); ?>" title="<?php echo strtoupper($d['vname']); ?>"><?php echo strtoupper($d['vname']); ?></a></li>
                <?php }
            } ?>

                        </ul>
                    </div>
        <?php }$i++;
    } ?></div>
        <!-- Third section end here-->
    </div>
<?php } else { ?>
    <div class="resposive-clearfix">
        <!-- first section start here-->
        <div class="col-md-3 resposive-clearfix">
            <h5>No Records found</h5>
        </div>
    </div>
<?php } ?>
