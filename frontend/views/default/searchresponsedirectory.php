<?php if (!empty($directory)) { ?>
    <div class="mobile-view col-xs-12 padding0 directory-responsive">
        <?php $fl = $first_letter; ?>
        <div class="tabContainer">                                                       
            <ul id="demoOne" class="demo">
                <?php foreach ($fl as $f) {
                    ?>
                    <li><h2><?php echo $f; ?></h2></li>
                    <?php
                    foreach ($directory as $d) {
                        $ltr = strtoupper(substr($d['vname'], 0, 1));
                        if ($ltr == $f) {
                            ?>
                            <li><a href="<?php echo Yii::$app->params['BASE_URL']; ?>/experience/<?php echo $d['slug']; ?>" title="<?php echo strtoupper($d['vname']); ?>"><?php echo strtoupper($d['vname']); ?></a></li>
                        <?php }
                    } ?>
    <?php } ?>
            </ul>
        </div>
    </div>

<?php } else { ?>                     
    <div class="resposive-clearfix">
        <!-- first section start here-->							                     
        <div class="col-md-3 resposive-clearfix">
            <h5>No Records found</h5>
        </div>
    </div>
<?php } ?>
