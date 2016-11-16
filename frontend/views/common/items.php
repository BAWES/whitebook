<div class="events_listing">
<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\components\CFormatter;

if(!empty($items->getModels()))  {
    $result = \yii\helpers\ArrayHelper::getColumn($customer_events_list,'item_id');
    foreach ($items->getModels() as $key => $value) {

        $item_url = Url::to(["browse/detail", 'slug' => $value['slug']]);

        ?>
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 min-height-301 pull-left">
            <div class="events_items width-100-percent">
                <div class="events_images text-center position-relative">
                    <div class="hover_events">
                        <div class="pluse_cont">
                            <?php if(Yii::$app->user->isGuest) { ?>
                                <a href="" role="button" class="" data-toggle="modal"  onclick="show_login_modal(<?php echo $value['item_id'];?>);" data-target="#myModal" title="<?php echo Yii::t('frontend','Add to Event');?>"></a>
                            <?php } else { ?>
                                <a href="#" role="button" id="<?php echo $value['item_id'];?>" name="<?php echo $value['item_id'];?>" class="" data-toggle="modal" data-target="#add_to_event<?php echo $value['item_id'];?>" onclick="addevent('<?php echo $value['item_id']; ?>')" title="<?php echo Yii::t('frontend','Add to Event');?>"></a>
                            <?php } ?>
                        </div>

                        <?php if(Yii::$app->user->isGuest) { ?>
                            <div class="faver_icons">
                                <a href="" role="button" class="" data-toggle="modal" id="<?php echo $value['item_id']; ?>" onclick="show_login_modal_wishlist(<?php echo $value['item_id'];?>);" data-target="#myModal" title="<?php echo Yii::t('frontend','Add to Things I Like');?>"></a>
                            </div>
                        <?php } else { ?>
                            <div class="faver_icons <?=(in_array($value['item_id'],$result)) ? 'faverited_icons' : ''?>">
                                <a href="javascript:;" role="button" id="<?php echo $value['item_id']; ?>"  class="add_to_favourite" name="add_to_favourite" title="<?php echo Yii::t('frontend','Add to Things I Like');?>"></a>
                            </div>
                            <?php } ?>
                        </div>
                        <a href="<?= $item_url ?>" class="" >
                            <?php
                            $path = (isset($value['image_path'])) ? Yii::getAlias("@s3/vendor_item_images_210/").$value['image_path'] : 'https://placeholdit.imgix.net/~text?txtsize=20&txt=No%20Image&w=208&h=208';
                            echo Html::img($path,['class'=>'item-img']);
                            ?>
                            <?php if($value['item_for_sale'] == 'Yes') { ?>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <span class="buy-text"><?=Yii::t('frontend','Buy');?></span>
                                <!--                            <img class="sale_ribbon" src="--><?//= Url::to('@web/images/product_sale_ribbon.png') ?><!--" />-->
                            <?php } ?>
                        </a>



                    </div>
                    <div class="events_descrip">
                        <a href="<?= $item_url ?>"><?= \common\components\LangFormat::format( $value['vendor_name'], $value['vendor_name_ar']) ?>
                            <h3><?=\common\components\LangFormat::format( $value['item_name'], $value['item_name_ar'])?></h3>
                            <p><?= CFormatter::format($value['item_price_per_unit'])  ?></p>
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
.min-height-301 {min-height: 301px;padding-left: 3px;padding-right: 3px;}
img.item-img{width: 100%;}
.width-100-percent{width: 100%;}
.margin-top-15{margin-top: 15%;}
");

?>