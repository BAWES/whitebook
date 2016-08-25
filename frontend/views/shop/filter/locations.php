<?php

$cities = \common\models\City::find()->select(['{{%city}}.*'])
    ->leftJoin('{{%location}}', '{{%location}}.city_id = {{%city}}.city_id')
    ->where(['{{%city}}.status'=>'Active'])
    ->andwhere(['{{%location}}.trash'=>'Default'])
    ->andwhere(['{{%location}}.status'=>'Active'])
    ->groupby(['{{%location}}.city_id'])
    ->asArray()
    ->all();

$locations = \common\models\Location::findAll(['trash'=>'Default']);
$col = 1;
?>

<div class="panel panel-default" >
    <div class="panel-heading">
        <div class="clear_left"><p><?= Yii::t('frontend', 'Delivery Area');?><a href="javascript:void(0)" class="filter-clear" id="filter-clear" title="Clear">- <?= Yii::t('frontend', 'Clear') ?></a></p></div>
        <div class="clear_right">
            <a href="#delivery-area" id="category" data-parent="#accordion" data-toggle="collapse" class="collapsed">
                <h4 class="panel-title">
                    <span class="plus_acc"></span>
                </h4>
            </a>
        </div>
    </div>
    <div id="delivery-area" class="panel-collapse collapse" aria-expanded="false">
        <div class="panel-body">
            <div class="table">
                <ul class="test_scroll">
                    <?php
                    foreach ($cities as $key => $value) {  ?>
                        <input type="hidden" name="city[]" value=<?= $value['city_id']; ?>>
                        <label style="margin-left: 12px;"><b> <?= $value['city_name']; ?></b></label>
                        <?php
                        $area = \common\models\Location::find()->where(['status'=>'Active', 'trash' => 'Default', 'city_id' => $value['city_id']])->orderBy('city_id')->asArray()->all();
                        foreach ($area as $key => $value) {
                            $vendor_area = \common\models\Vendorlocation::find()->select('area_id')->where(['area_id'=>$value['id']])->one(); ?>
                            <li>
                                <label class="label_check" for="checkbox-<?= $value['location']; ?>">
                                    <input type="checkbox" data-element="input" name="location" class="items" id="checkbox-<?= $value['location']; ?>" value="<?= $value['id']; ?>">
                                    <?= $value['location']; ?>
                                </label>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>
