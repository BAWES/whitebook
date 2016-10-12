<style>.no-record-found {padding: 12px 0 36px 0px;text-align: center;}</style>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\components\CFormatter;

if (!empty($items)) {
    foreach ($items as $key => $value) { ?>
        <div class="col-lg-3">
            <div class="events_items">
            <div class="events_images">
                <div class="hover_events">
                    <div class="pluse_cont">
                        <?php if(Yii::$app->user->isGuest) { ?>
                            <a href=""  role="button" class=""  data-toggle="modal"  onclick="show_login_modal(<?php echo $value['item_id'];?>);" data-target="#myModal" title="<?php echo Yii::t('frontend','Add to Event');?>"></a>
                        <?php } else { ?>
                            <a  href="#" role="button" id="<?php echo $value['item_id'];?>" name="<?php echo $value['item_id'];?>" class=""   data-toggle="modal" data-target="#add_to_event<?php echo $value['item_id'];?>" onclick="addevent('<?php echo $value['item_id']; ?>')" title="<?php echo Yii::t('frontend','Add to Event');?>"></a>
                        <?php } ?></div>

                    <?php if(Yii::$app->user->isGuest) { ?>
                        <div class="faver_icons">
                            <a href=""  role="button" class=""  data-toggle="modal" id="<?php echo $value['item_id']; ?>" onclick="show_login_modal_wishlist(<?php echo $value['item_id'];?>);" data-target="#myModal" title="<?php echo Yii::t('frontend','Add to Things I Like');?>"></a>
                        </div>
                    <?php } else {

                    $k=array();

                    foreach((array)$customer_events_list as $l){
                        $k[]=$l['item_id'];
                    }

                    $result = array_search($value['item_id'],$k);

                    if (is_numeric ($result)) { ?>
                        <div class="faver_icons faverited_icons">
                            <?php } else { ?>
                        <div class="faver_icons">
                            <?php } ?>
                            <a  href="javascript:;" role="button" id="<?php echo $value['item_id']; ?>"  class="add_to_favourite" name="add_to_favourite" title="<?php echo Yii::t('frontend','Add to Things I Like');?>"></a></div>
                        <?php } ?>
                    </div>

                    <a href="<?= Url::to(["shop/product", 'slug' => $value['slug']]) ?>" title="" ><?= Html::img(Yii::getAlias("@s3/vendor_item_images_210/").$value['image_path'], ['class' => 'item-img', 'style'=>'width:210px; height:208px;']); ?></a>
                </div>
                <div class="events_descrip">
                    <?php

                    $vendor_name = '';

                    if (Yii::$app->language == "en") {
                        $vendor_name = $value['vendor_name'];
                        $item_name = $value['item_name'];
                    } else {
                        $vendor_name = $value['vendor_name_ar'];
                        $item_name = $value['item_name_ar'];
                    }

                    echo Html::a(
                        $vendor_name,
                        Url::toRoute(['/shop/product/','slug' => $value['slug']])
                    ) ?>

                    <h3><?= $item_name ?></h3>

                    <p>
                        <?= CFormatter::format($value['item_price_per_unit']) ?>
                    </p></a>
                </div>
            </div>
        </div>
    <?php }
} else {
    echo '<div class="no-record-found">'.Yii::t('frontend', "No records found").'</div>';
}
