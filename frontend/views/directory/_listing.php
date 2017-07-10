<?php
use yii\helpers\Url;

if ($directory) { ?>
    <div class="col-md-9">
        <?php foreach ($first_letter as $f) { ?>
        <div class="direct_list">
            <h2><?php echo $f; ?></h2>
            <ul class="vendor_list">
                <?php
                foreach ($directory as $d) {
                    
                    $first_letter = \common\components\LangFormat::format(strtoupper(mb_substr($d['vendor_name'], 0, 1, 'utf8')),strtoupper(mb_substr($d['vendor_name_ar'], 0, 1, 'utf8')));
                    
                    $vendor_name = \common\components\LangFormat::format(strtoupper($d['vendor_name']),strtoupper($d['vendor_name_ar']));
                    
                    if ($first_letter != $f) {
                        continue;
                    }

                    if(false) {//$d['vendor_logo_path']
                        $img = Yii::getAlias('@vendor_logo/').$d['vendor_logo_path'];
                    }else{
                        $img =  Url::to("@web/images/item-default.png");    
                    }
                    ?>
                    <li class="col-md-4 col-sm-6 col-xs-6">
                        <div class="vendor_thumbnail">
                            <img src="<?= $img ?>" />                                            
                            <a href="<?= Url::toRoute(['directory/profile','vendor'=>$d['slug']]); ?>" title="<?php echo $vendor_name; ?>">
                                <?php echo $vendor_name; ?>
                            </a>
                        </div>
                    </li>
                <?php 
                } ?>
            </ul>
        </div><!-- END .direct_list -->
        <?php } ?>
    </div>        
<?php } else { ?>
    <div class="col-md-9">
        <h5><?=Yii::t('frontend','No Records found')?></h5>
    </div>
<?php } ?>
