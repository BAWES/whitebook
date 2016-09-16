<?php
use yii\widgets\ActiveForm;
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
                                            <select class="selectpicker required trigger" name="location_name" data-style="btn-primary" id="location_name" >
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
                                    <div id="eventresult" style="color:red"></div>
                                    <div class="eventErrorMsg error" style="color:red;margin-bottom: 10px;"></div>
                                    <div class="event_loader" style="display:none;text-align:center;margin-bottom: 10px;"><img src="<?php echo \yii\helpers\Url::to('@web/images/ajax-loader.gif', true); ?>" title="Loader"></div>
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
