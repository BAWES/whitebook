<?php

use yii\helpers\Html;
use yii\helpers\Url;

if (!empty($imageData)) {
    foreach ($imageData as $key => $value) {
        if ($value['image_path'] != "") {
?>
            <li>
                <div class="events_items">
                    <div class="events_images">
                        <div class="hover_events">
                            <div class="pluse_cont">
                                <?php if (Yii::$app->user->isGuest) { ?>
                                    <a href=""  role="button" class=""  data-toggle="modal"  onclick="show_login_modal();" data-target="#myModal" title="<?php echo Yii::t('frontend', 'Add to Event'); ?>"></a>
                                <?php } else { ?>
                                    <a  href="#" role="button" id="<?php echo $value['item_id']; ?>" name="<?php echo $value['item_id']; ?>" class=""   data-toggle="modal" data-target="#add_to_event<?php echo $value['item_id']; ?>" onclick="addevent('<?php echo $value['item_id']; ?>')" title="<?php echo Yii::t('frontend', 'Add to Event'); ?>"></a>
                                <?php } ?>
                            </div>
                            <?php if (Yii::$app->user->isGuest) { ?>
                                <div class="faver_icons">
                                    <a href=""  role="button" class=""  data-toggle="modal"  onclick="show_login_modal();" data-target="#myModal" title="<?php echo Yii::t('frontend', 'Add to Things I Like'); ?>"></a>
                                </div>
                                <?php
                                } else {
                                    $k = array();
                                    if (!empty($customer_events_list)) {
                                        foreach ($customer_events_list as $l) {
                                            $k[] = $l['item_id'];
                                        }
                                    }
                                    $result = (!empty($k))  ? array_search($value['item_id'], $k) : null;
                                    ?>
                                    <div class="faver_icons <?=(is_numeric($result)) ? 'faverited_icons' : ''; ?>">
                                    <a  href="javascript:;" role="button" id="<?php echo $value['item_id']; ?>"  class="add_to_favourite" name="add_to_favourite" title="<?php echo Yii::t('frontend', 'Add to Things I Like'); ?>"></a></div>
                                <?php } ?>
                            </div>
                            <?= Html::a(Html::img(Yii::getAlias("@vendor_item_images_210/").$value['image_path'],['class'=>'item-img', 'style'=>'width:210px; height:208px;']),
                                Url::toRoute(['/shop/product/','slug'=>$value['slug']])); ?>
                        </div>
                        <div class="events_descrip">
                            <a href="<?=Url::toRoute(['/shop/product/','slug'=>$value['slug']]) ?>" title=""><?= $value['vendor_name'] ?>
                                <h3><?= $value['item_name'] ?></h3>
                                <p><? if($value['item_price_per_unit'] !='') {echo $value['item_price_per_unit'].'.00 KD'; }else echo '-';?></p></a>
                        </div>
                    </div>
            </li>
            <?php
        }
    }
} else {
    echo "No records found";
}
?>
