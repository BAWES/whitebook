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

$customer_id = Yii::$app->user->getId();

if($customer_id) {
    
    $my_addresses =  \common\models\CustomerAddress::find()
        ->select(['{{%location}}.id, {{%customer_address}}.address_id,{{%customer_address}}.address_name'])
        ->leftJoin('{{%location}}', '{{%location}}.id = {{%customer_address}}.area_id')
        ->where(['{{%customer_address}}.trash'=>'Default'])
        ->andwhere(['{{%customer_address}}.customer_id' => $customer_id])
        ->groupby(['{{%location}}.id'])
        ->asArray()
        ->all();

} else {
    $my_addresses = array();    
}
?>

<div class="panel panel-default" id="top_panel_location">
    <div class="panel-heading clearfix" id="top_panel_heading">
        <div class=""><p><?= Yii::t('frontend', 'Delivery Area');?><a href="javascript:void(0)" class="filter-clear" id="filter-clear" title="Clear">- <?= Yii::t('frontend', 'Clear') ?></a></p></div>
    </div>
    <div id="delivery-area" class="panel-collapse " aria-expanded="false">
        <div class="">
            <div class="form-group">
                <select id="delivery_area_filter" class="selectpicker" data-live-search="true" data-size="10">

                    <option value=""><?= Yii::t('frontend', 'All') ?></option>

                    <?php

                    if($my_addresses) { ?>
                        <optgroup label="My Addresses">
                        <?php foreach ($my_addresses as $key => $value) {
                            $checked = '';
                            if ($deliver_location != null) {
                                $checked = ($deliver_location == 'address_'.$value['address_id']) ? 'selected' : '';
                            }
                            ?>

                            <option <?=$checked; ?> value="address_<?= $value['address_id']; ?>">
                                <?= $value['address_name'] ?>   
                            </option>
                        <?php
                        }//foreach my addresses ?>

                        </optgroup>
                    <?php     
                    }//if my addresses

                    foreach ($cities as $key => $value) {  ?>
                        <optgroup label="<?= $value['city_name']; ?>">
                        <?php
                        
                        $area = \common\models\Location::find()->where(['status'=>'Active', 'trash' => 'Default', 'city_id' => $value['city_id']])->orderBy('city_id')->asArray()->all();

                        foreach ($area as $key => $value) {  

                            $checked = '';
                           
                            if ($deliver_location != null) {
                                $checked = ($deliver_location == $value['id']) ? 'selected' : '';
                            }
                        ?>
                            
                            <option <?=$checked?> value="<?= $value['id']; ?>">
                                <?=\common\components\LangFormat::format($value['location'],$value['location_ar']); ?>
                            </option>
                            <?php
                        }//foreach area ?>
                        </optgroup>
                    <?php     
                    } //foreach city 
                    ?>
                </select>
            </div>

        </div>
    </div>
</div>

<?php $this->registerJS("$('#delivery-area').fadeIn(1000);",\yii\web\View::POS_READY) ?>

<?php

$this->registerCss("
    #top_panel_location {
        border-bottom: none;
        border-top: none;
        border: none;
        border-radius: 0;
        -webkit-box-shadow: none;
        box-shadow: none;
    }
    #top_panel_heading {border: none;box-shadow: none;}
"); ?>