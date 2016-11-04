<?php
use yii\helpers\Url;
use yii\helpers\Html;
$session = Yii::$app->session;
$dLocation = $session->get('deliver-location');
$date = $session->get('deliver-date');
?>

<div class="col-lg-12 col-md-12 col-sm-12 clearfix left-div">
    <div class="desktop-view-search">
        <form id='area-selection' name='area-selection' action="<?=Url::toRoute(['browse/list'],true);?>">
            <input type="hidden" name="slug" value="all">
            <div class="left-offset-25">&nbsp;</div>
            <div class="col-lg-3 col-sm-3 col-md-3 location-div">
                <select class="selectpicker trigger" name="location" data-style="btn-default" id="location_name" data-live-search="true" data-size="10">
                    <option value="">All</option>
                    <?php
                    $cities = \common\models\City::find()->where(['trash'=>'Default','status'=>'Active'])->with('locations')->all();
                    $list = '';
                    foreach ($cities as $city) {
                        $city_name = (Yii::$app->language == 'en') ? $city->city_name : $city->city_name_ar;
                        $list .= '<optgroup label='.$city_name.'>';
                        if (isset($city->locations)) {
                            foreach ($city->locations as $location) {
                                if ($location->trash == 'Default' && $location->status=='Active') {
                                    $location_name = (Yii::$app->language == 'en') ? $location->location : $location->location_ar;
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
            <div class="col-lg-3 col-sm-3 col-md-3 date-div">
                <div data-date-format="dd-mm-yyyy" data-date="12-02-2012" id="dp3" class="input-append date">
                    <input value="<?=$date?>" type="text" name="date" id="delivery_date" readonly size="16" class="form-control required datetimepicker date1" placeholder="<?php echo Yii::t('frontend', 'Event Date'); ?>" title="<?php echo Yii::t('frontend', 'Choose Delivery Date'); ?>">
                    <span class="add-on position_news"> <i class="flaticon-calendar189"></i></span>
                </div>
            </div>
            <div class="col-lg-1 col-sm-1 col-md-1 width-5-percent padding-right-0">
                <input type="submit" class="bg-000 color-fff btn btn-default btn-submit" value="Search">
            </div>
            <div class="col-lg-1 col-sm-1 col-md-1 or-area">
                OR
            </div>
            <div class="col-lg-1 col-sm-1 col-md-1 padding-left-0">
                <?=Html::a('Just Browse',['browse/list','slug'=>'all'],['class'=>'btn btn-default btn-submit bg-000 color-fff']);?>
            </div>
        </form>
    </div>
    <div class="mobile-view-search hide">
        <div class="width-44-percent pull-left">
<!--            <input type="submit" class=" width-100-percent bg-000 color-fff btn btn-default btn-submit" value="Search">-->
            <div class="input-append date position-relative">
                <input type="text" name="date" readonly size="16" id="open-filter" class="form-control height-46" placeholder="<?php echo Yii::t('frontend', 'Event Date'); ?>" >
                <span class="add-on position_news"> <i class="flaticon-calendar189"></i></span>
            </div>
        </div>
        <div class="width-10-percent or_mobile pull-left">
            OR
        </div>
        <div class="width-44-percent pull-left">
            <?=Html::a('Just Browse',['browse/list','slug'=>'all'],['class'=>'btn btn-default btn-submit bg-000  width-100-percent color-fff']);?>
        </div>
    </div>
    <div class="mobile-view-form-popup" style="display: none;">
        <h4>Search <a href="#" class="text-right" id="close-search-div">x</a></h4>
        <form id='area-selection' name='area-selection' action="<?=Url::toRoute(['browse/list'],true);?>">
            <input type="hidden" name="slug" value="all">
            <div class="col-lg-10 col-sm-10 col-md-10">
                <div data-date-format="dd-mm-yyyy" data-date="12-02-2012" id="dp3" class="input-append date">
                    <input value="<?=$date?>" type="text" name="date" id="delivery_date" style ="color: #000!important;" readonly size="16" class="form-control required datetimepicker date1" placeholder="<?php echo Yii::t('frontend', 'Event Date'); ?>" title="<?php echo Yii::t('frontend', 'Choose Delivery Date'); ?>">
                    <span class="add-on position_news"> <i class="flaticon-calendar189"></i></span>
                </div>
            </div>
            <div class="col-lg-12 col-sm-12 col-md-12 margin-top-15">
                <select class="selectpicker trigger" name="location" data-style="btn-default" id="location_name" data-live-search="true" data-size="10">
                    <option value="">All</option>
                    <?php
                    $cities = \common\models\City::find()->where(['trash'=>'Default','status'=>'Active'])->with('locations')->all();
                    $list = '';
                    foreach ($cities as $city) {
                        $city_name = (Yii::$app->language == 'en') ? $city->city_name : $city->city_name_ar;
                        $list .= '<optgroup label='.$city_name.'>';
                        if (isset($city->locations)) {
                            foreach ($city->locations as $location) {
                                if ($location->trash == 'Default' && $location->status=='Active') {
                                    $location_name = (Yii::$app->language == 'en') ? $location->location : $location->location_ar;
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