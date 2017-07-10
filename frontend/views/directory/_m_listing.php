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

                        $ltr = \common\components\LangFormat::format(strtoupper(mb_substr($d['vendor_name'], 0, 1, 'utf8')),strtoupper(mb_substr($d['vendor_name_ar'], 0, 1, 'utf8')));
                        $vname = \common\components\LangFormat::format(strtoupper($d['vendor_name']),strtoupper($d['vendor_name_ar']));

                        if ($ltr == $f) { ?>
                            <li>
                                <a href="<?= \yii\helpers\Url::toRoute(['directory/profile','vendor'=>$d['slug']]); ?>" title="<?php echo $vname; ?>"><?php echo $vname; ?></a>
                            </li>
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
