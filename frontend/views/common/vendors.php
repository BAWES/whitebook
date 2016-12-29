<div class="events_listing">
<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Image;
use common\components\CFormatter;
$baselink =  Url::to("@web/images/item-default.png");


if ($items) {
    foreach ($items as $key => $value) {

        if($value->vendor_logo_path) {
            $baselink = Yii::getAlias('@vendor_logo/').$value->vendor_logo_path;
        }


        $item_url = Url::to(["directory/profile", 'vendor' => $value->slug]);
        ?>
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 min-height-301 pull-left">
            <div class="events_items width-100-percent">
                <div class="events_images text-center position-relative">
                        <a href="<?= $item_url ?>" class="" >
                            <?=Html::img($baselink, ['class'=>'item-img']); ?>
                        </a>
                </div>
                <div class="events_descrip">
                    <a href="<?= $item_url ?>">
                        <h3>
                            <?= \common\components\LangFormat::format( $value['vendor_name'], $value['vendor_name_ar']) ?>
                        </h3>
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