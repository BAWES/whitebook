<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Image;
use common\components\CFormatter;

if (!empty($wishlist)) {
    foreach ($wishlist as $key => $value) {
        ?>
        <li id="<?php echo $value['item_id']; ?>">
            <div class="events_items">
                <div class="events_images">
                    <div class="hover_events">
                        <div class="pluse_cont"><a href="javascript:;" role="button" id="<?php echo $value['item_id']; ?>" name="<?php echo $value['item_id']; ?>" class=""   data-toggle="modal" data-target="#add_to_event<?php echo $value['item_id']; ?>" onclick="addevent('<?php echo $value['item_id']; ?>')" title="<?php echo Yii::t('frontend', 'Add to Event'); ?>"></a></div>
                        <div class="delet_icons"><a href="javascript:;" title=""   onclick="remove_from_favourite(<?php echo $value['item_id']; ?>)"onclick="remove_from_favourite(<?php echo $value['item_id']; ?>)"></a></div>
                    </div>
                    <?php
                    $image = Image::find()->select('image_path')->where(['item_id' => $value['item_id'], 'module_type' => 'vendor_item', 'trash' => 'Default'])->asArray()->one();
                    ?>
        <?= Html::img(Yii::getAlias("@vendor_item_images_210/") . $image['image_path'], ['class' => 'item-img', 'style' => 'width:210px; height:208px;']); ?>
                </div>
                <div class="events_descrip">
                    <a title="" href="#"><?= $value['vendor_name']; ?>
                        <h3><?= $value['item_name']; ?></h3>

                        <p><?= CFormatter::format($f['item_price_per_unit']); ?></p>

                    </a>
                </div>
            </div>
        </li>
    <?php
    }
}
else {
    ?>

    <li>
        No Record found
    </li>
<?php } ?>
