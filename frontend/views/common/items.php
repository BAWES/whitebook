<div class="events_listing">
<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Image;
use common\components\CFormatter;

if (!empty($items->getModels())) {

    $result = \yii\helpers\ArrayHelper::getColumn($customer_events_list,'item_id');

    foreach ($items->getModels() as $key => $value) {

        if (
            $value['item_approved'] == 'Yes' &&
            $value['trash'] == 'Default' &&
            $value['item_status'] == 'Active'
        ) {
            $AvailableStock = true;
        } else {
            $AvailableStock = false;
        }

        $image_data = Image::find()
            ->where(['item_id' => $value['item_id']])
            ->orderBy(['vendorimage_sort_order' => SORT_ASC])
            ->one();

        if ($image_data) {
            $image = Yii::getAlias("@s3/vendor_item_images_210/").$image_data->image_path;
        } else {
            $image = Url::to("@web/images/item-default.png");
        }
        
        $item_url = Url::to(["browse/detail", 'slug' => $value['slug']]);
        ?>
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 min-height-301 pull-left">
            <?php if (!$AvailableStock) { ?>
                <img src="<?php echo Url::to("@web/images/sold-out.png");?>" class="sold-out">
            <?php } ?>
            <div class="events_items width-100-percent">
                
                <div class="events_images text-center position-relative">
                    <div class="hover_events">
                        <?php
                        /*
                         @TODO Removed Event Section
                        ?>
                        <div class="pluse_cont">
                            <?php
                            if(Yii::$app->user->isGuest) { ?>
                            <a
                                href=""
                                role="button"
                                class=""
                                data-toggle="modal"
                                onclick="show_login_modal(<?php echo $value['item_id'];?>);"
                                data-target="#myModal"
                                title="<?php echo Yii::t('frontend','Add to Event');?>"
                            >
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </a>
                            <?php } else { ?>
                                <a
                                    href="#"
                                    role="button"
                                    id="<?php echo $value['item_id'];?>"
                                    name="<?php echo $value['item_id'];?>"
                                    class=""
                                    data-toggle="modal"
                                    data-target="#add_to_event<?php echo $value['item_id'];?>"
                                    onclick="addevent('<?php echo $value['item_id']; ?>')"
                                    title="<?php echo Yii::t('frontend','Add to Event');?>"
                                >
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </a>
                            <?php } ?>
                        </div>
                        <?php */ ?>
                        <?php if(Yii::$app->user->isGuest) { ?>
                            <div class="faver_icons">
                                <a
                                    href=""
                                    role="button"
                                    class=""
                                    data-toggle="modal"
                                    id="<?php echo $value['item_id']; ?>"
                                    onclick="show_login_modal_wishlist(<?php echo $value['item_id'];?>);"
                                    data-target="#myModal"
                                    title="<?php echo Yii::t('frontend','Add to Things I Like');?>"
                                >
                                    <i class="fa fa-heart-o" aria-hidden="true"></i>
                                </a>
                            </div>
                        <?php } else { ?>
                            <div class="faver_icons <?=(in_array($value['item_id'],$result)) ? 'faverited_icons' : ''?>">
                                <a
                                    href="javascript:;"
                                    role="button"
                                    id="<?php echo $value['item_id']; ?>"
                                    class="add_to_favourite"
                                    name="add_to_favourite"
                                    title="<?php echo Yii::t('frontend','Add to Things I Like');?>"
                                >
                                    <i class="fa fa-heart<?=(in_array($value['item_id'],$result)) ? '' : '-o'?>" aria-hidden="true"></i>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                        <a href="<?= $item_url ?>" class="" >
                            <?= Html::img($image, ['class'=>'item-img']); ?>

                            <?php if (isset($value['item_how_long_to_make']) && $value['item_how_long_to_make'] > 0) { ?>
                            <div class="callout-container">
                                <span class="callout light">
                                    <?php

                                    if(isset($value['notice_period_type']) && $value['notice_period_type'] == 'Day') {
                                        
                                        if ($value['item_how_long_to_make'] % 7 == 0) {
                                            echo Yii::t('frontend', 'Notice: {count} week(s)', [
                                                'count' => substr(($value['item_how_long_to_make'] / 7),0,3)
                                            ]);
                                        } else {
                                            echo Yii::t('frontend', 'Notice: {count} day(s)', [
                                                'count' => $value['item_how_long_to_make']
                                            ]);
                                        }
                                    }
                                    else
                                    {
                                        if ($value['item_how_long_to_make'] >= 24) {
                                            echo Yii::t('frontend', 'Notice: {count} day(s)', [
                                                'count' => substr(($value['item_how_long_to_make'] / 24),0,3)
                                            ]);
                                        } else {
                                            echo Yii::t('frontend', 'Notice: {count} hours', [
                                                'count' => $value['item_how_long_to_make']
                                            ]);
                                        }

                                    } ?>
                                </span>
                            </div>
                            <?php } ?>

                        </a>
                </div>
                <div class="events_descrip">
                    <a href="<?= $item_url ?>"><?= \common\components\LangFormat::format( $value['vendor_name'], $value['vendor_name_ar']) ?>
                        <h3>
                            <?= \common\components\LangFormat::format(
                                    $value['item_name'], 
                                    $value['item_name_ar']
                                ); ?>
                        </h3>
                        <p><?php
                            if (trim($value['item_base_price'])) {
                                echo CFormatter::format($value['item_base_price']);
                            //} else if (trim($value['item_price_per_unit'])) {
                            //    echo CFormatter::format($value['item_price_per_unit']);
                            } else if($value['min_order_amount'] > 0) {


                                echo '<span class="small">' . Yii::t('frontend','Min. order amount : {amount}', [
                                    'amount' => CFormatter::format($value['min_order_amount'])
                                ]) . '<span>';

                            } else {
                                echo '<span class="small">' . Yii::t('app', 'Price based on selection') . '<span>';
                            }
                            ?></p>
                    </a>
                </div>
            </div>
        </div>
    <?php
    }
} else {
    echo '<div class="no-record-found">'.Yii::t('frontend', "No records found").'</div>';
}
?>
    <div id="planloader">
        <img src="<?php echo Url::to("@web/images/ajax-loader.gif");?>" title="Loader" class="margin-top-15">
    </div>
</div>
<div class="add_more_commons text-center">
    <?php
        echo \yii\widgets\LinkPager::widget([
            'pagination'=>$items->pagination,
        ]);
    ?>
</div>
<?php
$this->registerCss("
    .no-record-found {padding: 12px 0 36px 0px;text-align: center;}
    .min-height-301 {min-height: 310px;padding-left: 3px;padding-right: 3px;}
    img.item-img{width: 100%; max-height: 210px;}
    .width-100-percent{width: 100%;}
    .margin-top-15{margin-top: 15%;}
");
?>