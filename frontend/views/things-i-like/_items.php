<?php 

use yii\helpers\Html;
use yii\helpers\Url;
use common\components\CFormatter;

foreach ($items as $key => $value) {

    $item_url = Url::to(["browse/detail", 'slug' => $value['slug']]);

    ?>
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 wishlist_item pull-left">
        <div class="events_items width-100-percent">
            <div class="events_images text-center position-relative">
                <div class="hover_events">
                    <a role="button" class="btn-wishlist-remove" data-href="<?= Url::to(['things-i-like/delete', 'id' => $value['item_id']]) ?>" title="<?php echo Yii::t('frontend','Remove');?>"></a>
                </div>
                <a href="<?= $item_url ?>" class="" >
                    <?php
                    
                    $path = (isset($value['image_path'])) ? Yii::getAlias("@s3/vendor_item_images_210/").$value['image_path'] : Url::to("@web/images/item-default.png");

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
                    <h3><?= \common\components\LangFormat::format( $value['item_name'], $value['item_name_ar'])?></h3>
                    <p><?= CFormatter::format($value['item_price_per_unit'])  ?></p>
                </a>
            </div>
        </div>
    </div>
<?php
}

if(!$items) { ?>
    
    <br />
    
    <p class="text-center"><?= Yii::t('frontend', 'Not added any item from this category yet!') ?></p>

<?php } ?>