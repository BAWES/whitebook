<?php
use yii\helpers\Url;
use yii\helpers\Html;
$session = Yii::$app->session;
$dLocation = $session->get('delivery-location');

$date = $session->get('delivery-date');

$event_time = $session->get('event_time');

$arr_time = ['12:00', '12:30', '01:00', '01:30', '02:00', '02:30', '03:00', '03:30', '04:00', '04:30', '05:00',
          '05:30', '06:00', '06:30', '07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30',
          '11:00', '11:30'];

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
$themes = \common\models\Themes::find()->where(['!=', 'theme_status', 'Deactive'])->andwhere(['!=', 'trash', 'Deleted'])->all();

?>

<div class="col-lg-12 col-md-12 col-sm-12 clearfix left-div">
    <div class="desktop-view-search">
        <form id='area-selection' name='area-selection' action="<?=Url::toRoute(['browse/all'], true);?>">
            <div class="left-offset-25">&nbsp;</div>
            <div class="col-lg-2 col-sm-2 col-md-2 location-div">
                <select class="selectpicker trigger" name="themes[]" data-style="btn-default" id="location_name" data-live-search="true" data-size="10">
                    <option value=""><?=Yii::t('frontend','All Themes')?></option>
                    <?php

                    if($themes) {
                        foreach ($themes as $key => $value) { ?>
                            <option value="<?= $value['slug']; ?>">
                                <?= $value['theme_name'] ?>
                            </option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-lg-2 col-sm-2 col-md-2 location-div">
                <select class="selectpicker trigger" name="location" data-style="btn-default" id="location_name" data-live-search="true" data-size="10">
                    <option value=""><?=Yii::t('frontend','Area')?></option>
                    <?php

                    if($my_addresses) { ?>
                        <optgroup label="My Addresses">
                            <?php foreach ($my_addresses as $key => $value) {
                                $checked = '';
                                if ($dLocation != null) {
                                    $checked = ($dLocation == 'address_'.$value['address_id']) ? 'selected' : '';
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


                    $cities = \common\models\City::find()->where(['trash'=>'Default','status'=>'Active'])->with('locations')->all();
                    $list = '';
                    foreach ($cities as $city) {
                        $city_name = \common\components\LangFormat::format($city->city_name,$city->city_name_ar);
                        $list .= '<optgroup label='.$city_name.'>';
                        if (isset($city->locations)) {
                            foreach ($city->locations as $location) {
                                if ($location->trash == 'Default' && $location->status=='Active') {
                                    $location_name = \common\components\LangFormat::format($location->location,$location->location_ar);
                                    $selected = (isset($dLocation) && $dLocation != '' && $dLocation == $location->id) ? 'selected="selected"' : '';
                                    $list .= '<option value="'.$location->id.'" '.$selected.'>'.$location_name.'</option>';
                                }
                            }
                        }
                        $list .= '</optgroup>';
                    }
                    echo $list;
                    ?>
                </select>
            </div>
            <div class="col-lg-2 col-sm-2 col-md-3 date-div">
                <div data-date-format="dd-mm-yyyy" data-date="12-02-2012" id="dp3" class="input-append date">
                    <input value="<?=$date?>" type="text" name="date" id="delivery_date" readonly size="16" class="form-control required datetimepicker date1" placeholder="<?php echo Yii::t('frontend', 'Event Date'); ?>" title="<?php echo Yii::t('frontend', 'Choose Delivery Date'); ?>">
                    <span class="add-on position_news"> <i class="flaticon-calendar189"></i></span>
                </div>
            </div>
            <div class="col-lg-3 col-sm-3 col-md-3">
                <select id="event_time" name="event_time" class="selectpicker" data-live-search="false" data-size="10" data-placeholder="">
                    <option value=""><?= Yii::t('frontend', 'Event Time') ?></option>
                    <?php foreach ($arr_time as $key => $value) {
                        if($value.' AM' == $event_time) 
                            $selected = 'selected'; 
                        else
                            $selected = ''; ?>
                        <option value="<?= $value ?> AM" <?= $selected ?>> 
                            <?= $value ?> AM
                        </option>
                    <?php } ?>
                    <?php foreach ($arr_time as $key => $value) { 
                        if($value.' PM' == $event_time) 
                            $selected = 'selected'; 
                        else
                            $selected = ''; ?>
                        <option value="<?= $value ?> PM" <?= $selected ?>>
                            <?= $value ?> PM
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-lg-1 col-sm-1 col-md-1 width-5-percent padding-right-0">
                <input type="submit" class="bg-000 color-fff btn btn-default btn-submit" value="Search">
            </div>
        </form>
    </div>
    <div class="mobile-view-search hide">    
<!--            <input type="submit" class=" width-100-percent bg-000 color-fff btn btn-default btn-submit" value="Search">-->
        <div class="input-append date position-relative" id="open-filter">
            <input type="text" name="date" readonly size="16" class="form-control height-46" placeholder="<?php echo Yii::t('frontend', 'Event Date'); ?>" >
            <span class="add-on position_news"> <i class="flaticon-calendar189"></i></span>
        </div>
    </div>
    <div class="mobile-view-form-popup" style="display: none;">
        <h4>Search <a href="#" class="text-right" id="close-search-div">x</a></h4>
        <form id='area-selection' name='area-selection' action="<?=Url::toRoute(['browse/all'], true); ?>">
            
            <div class="col-lg-12 margin-top-15 location-div">
                <select class="selectpicker trigger" name="themes[]" data-style="btn-default" id="location_name" data-live-search="true" data-size="10">
                    <option value=""><?=Yii::t('frontend','All Themes')?></option>
                    <?php

                    if($themes) {
                        foreach ($themes as $key => $value) { ?>
                            <option value="<?= $value['slug']; ?>">
                                <?= $value['theme_name'] ?>
                            </option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="col-lg-12 col-sm-12 col-md-12 margin-top-15" id="event-time">
                <select id="event_time" name="event_time" class="selectpicker" data-live-search="false" data-size="10" data-placeholder="">
                    <option value="" class="label"><?= Yii::t('frontend', 'Event Time') ?></option>
                    <optgroup label="am">                        
                        <?php foreach ($arr_time as $key => $value) {
                            if($value.' am' == $event_time) 
                                $selected = 'selected'; 
                            else
                                $selected = ''; ?>
                            <option value="<?= $value ?> am" data-content="<?= $value ?> <span>am</span>" <?= $selected ?>> 
                                <?= $value ?>
                            </option>
                        <?php } ?>
                    </optgroup>
                    <optgroup label="pm">                        
                        <?php foreach ($arr_time as $key => $value) { 
                            if($value.' pm' == $event_time) 
                                $selected = 'selected'; 
                            else
                                $selected = ''; ?>
                            <option value="<?= $value ?> pm" <?= $selected ?> data-content="<?= $value ?> <span>pm</span>">
                                <?= $value ?>
                            </option>
                        <?php } ?>
                    </optgroup>
                </select>
            </div>    
            <div class="col-lg-10 col-sm-10 col-md-10 margin-top-15">
                <div data-date-format="dd-mm-yyyy" data-date="12-02-2012" id="dp3" class="input-append date">
                    <input value="<?=$date?>" type="text" name="date" id="delivery_date" style ="color: #000!important;" readonly size="16" class="form-control required datetimepicker date1" placeholder="<?php echo Yii::t('frontend', 'Event Date'); ?>" title="<?php echo Yii::t('frontend', 'Choose Delivery Date'); ?>">
                    <span class="add-on position_news"> <i class="flaticon-calendar189"></i></span>
                </div>
            </div>            
            <div class="col-lg-12 col-sm-12 col-md-12 margin-top-15">
                <select class="selectpicker trigger" name="location" data-style="btn-default" id="location_name" data-live-search="true" data-size="10">
                    <option value=""><?=Yii::t('frontend','All'); ?></option>
                    <?php
                    $cities = \common\models\City::find()->where(['trash'=>'Default','status'=>'Active'])->with('locations')->all();
                    $list = '';
                    foreach ($cities as $city) {
                        $city_name = \common\components\LangFormat::format($city->city_name,$city->city_name_ar);
                        $list .= '<optgroup label='.$city_name.'>';
                        if (isset($city->locations)) {
                            foreach ($city->locations as $location) {
                                if ($location->trash == 'Default' && $location->status=='Active') {
                                    $location_name = \common\components\LangFormat::format($location->location,$location->location_ar);
                                    $selected = (isset($dLocation) && $dLocation != '' && $dLocation == $location->id) ? 'selected="selected"' : '';
                                    $list .= '<option value="'.$location->id.'" '.$selected.'>'.$location_name.'</option>';
                                }
                            }
                        }
                        $list .= '</optgroup>';
                    }
                    echo $list;
                    ?>
                </select>
            </div>

            <div class="col-lg-12 col-sm-12 col-md-12">
                <input type="submit" class=" color-fff bg-000 width-100-percent margin-top-15 btn btn-default btn-submit" value="Search">
            </div>
        </form>
    </div>
</div>
<div class="col-lg-12 col-sm-12 col-md-12 black-overlay">&nbsp;</div>