<?php
use yii\widgets\ActiveForm;
use common\components\LangFormat;
$customer_id = Yii::$app->user->getId();
if($customer_id) {

    $my_addresses =  \common\models\CustomerAddress::find()
        ->select(['{{%customer_address}}.address_id, {{%location}}.id, {{%customer_address}}.address_name'])
        ->leftJoin('{{%location}}', '{{%location}}.id = {{%customer_address}}.area_id')
        ->where(['{{%customer_address}}.trash'=>'Default'])
        ->andwhere(['{{%customer_address}}.customer_id' => $customer_id])
        ->groupby(['{{%location}}.id'])
        ->asArray()
        ->all();
} else {
    $my_addresses = array();
}

$cities = \common\models\City::find()->where(['trash'=>'Default','status'=>'Active'])->with('locations')->all();
?>
<!-- BEGIN Create event Modal Box -->
<div class="modal fade" id="ShopLocationDateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" id="eventModal">
        <div class="modal-content  modal_member_login signup_poupu row">
            <div class="modal-header">
                <button type="button" class="close" id="boxclose" name="boxclose" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="text-center">
                    <span class="yellow_top"></span>
                </div>
                <h4 class="modal-title text-center" id="myModalLabel"><?php echo Yii::t('frontend', 'Select Delivery Date & Location'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-8 col-xs-offset-2">
                        <div class="product_popup_signup_box">
                            <div class="product_popup_signup_log">
                                    <?php
                                    $form = ActiveForm::begin([
                                        'id' => 'shop-date-location-hook',
                                    ]) ?>
                                    <input type="hidden" id="_csrf" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
                                    <div class="form-group top_calie_new">
                                        <div data-date-format="dd-mm-yyyy" data-date="12-02-2012" id="dp3" class="input-append date">
                                            <input type="text"  name="delivery_date" id="delivery_date" readonly size="16" class="form-control required datetimepicker date1" placeholder="<?php echo Yii::t('frontend', 'Choose Delivery Date'); ?>" title="<?php echo Yii::t('frontend', 'Choose Delivery Date'); ?>">
                                            <span class="add-on position_news"> <i class="flaticon-calendar189"></i></span>
                                        </div>
                                        <label for="delivery_date" id="delivery_date_error" class="error"></label>
                                    </div>
                                    <div class="form-group new_popup_common">
                                        <div class="bs-docs-example">
                                            <select class="selectpicker required trigger" name="location_name" data-style="btn-primary" id="location_name" data-live-search="true" data-size="10">
                                            <?php

                                            if($my_addresses) { ?>
                                                <optgroup label="My Addresses">
                                                    <?php foreach ($my_addresses as $key => $value) {  ?>
                                                        <option value="address_<?= $value['address_id']; ?>">
                                                            <?= $value['address_name'] ?>
                                                        </option>
                                                        <?php
                                                    }//foreach my addresses ?>
                                                </optgroup>
                                                <?php
                                            }//if my addresses

                                                    //$data
                                                    $list = '';
                                                    foreach ($cities as $city) {
                                                        $city_name = LangFormat::format($city->city_name,$city->city_name_ar);
                                                        $list .= '<optgroup label='.$city_name.'>';
                                                        if (isset($city->locations)) {
                                                            foreach ($city->locations as $location) {
                                                                if ($location->trash == 'Default' && $location->status=='Active') {
                                                                    $location_name = LangFormat::format($location->location,$location->location_ar);
                                                                    $list .= '<option value="'.$location->id.'">'.$location_name.'</option>';
                                                                }
                                                            }
                                                        }
                                                        $list .= '</optgroup>';
                                                    }
                                                echo $list;
                                                ?>
                                            </select>

                                            <div class="error" id="type_error"></div>
                                        </div>
                                    </div>
                                    <div id="eventresult" class="color-red" ></div>
                                    <div class="eventErrorMsg error"></div>
                                    <div class="event_loader"><img src="<?php echo \yii\helpers\Url::to('@web/images/ajax-loader.gif', true); ?>" title="Loader"></div>
                                    <div class="buttons">
                                        <div class="creat_evn_sig">
                                            <button type="button" id="select_date_button" name="select_date_button" class="btn btn-default" title="<?php echo Yii::t('frontend', 'Submit'); ?>"><?php echo Yii::t('frontend', 'Submit'); ?></button>
                                        </div>
                                        <div class="cancel_sig">
                                            <input class="btn btn-default" data-dismiss="modal"  id="cancel_button" name="cancel_button" type="button" value="<?php echo Yii::t('frontend', 'Cancel'); ?>" title="<?php echo Yii::t('frontend', 'Cancel'); ?>">
                                        </div>
                                    </div>
                                <?php ActiveForm::end() ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->regsiterCss("
.color-red{color:red!important;}
.eventErrorMsg{color:red;margin-bottom: 10px;}
.event_loader{display:none;text-align:center;margin-bottom: 10px;}
");
?>