<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<section id="inner_pages_white_back">
    <div class="container paddng0">
        <div class="shop_sect">
            <div class="plan_inner_sec">
                <h2><?= Yii::t("frontend", "Shop") ?></h2>
                <h5 class="text-center"><?= Yii::t("frontend", "Shop is where you purchase, customise, and schedule delivery of your products and services") ?></h5>
            </div>
        </div>
        <div class="plan_catg">
        <ul>
                <li>
                    <a href="<?= Url::to(["shop/products", 'slug' => 'venues']); ?>" class="venue_lnk">
                        <span class="venue"></span>
                        <span class="responsi_common"><?= Yii::t("frontend", "Venues") ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["shop/products", 'slug' => 'invitations']); ?>" class="invitations_lnk">
                        <span class="invitations "></span>
                        <?= Yii::t("frontend", "Invitations") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["shop/products", 'slug' => 'food-beverage']); ?>">
                        <span class="food1"></span>
                        <?= Yii::t("frontend", "Food & Beverage") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["shop/products", 'slug' => 'decor']); ?>" >
                        <span class="decor1"></span>
                        <?= Yii::t("frontend", "Decor") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["shop/products", 'slug' => 'supplies']); ?>">
                        <span class="supplies1"></span>
                        <?= Yii::t("frontend", "Supplies") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["shop/products", 'slug' => 'entertainment']); ?>" class="entertainment_lnk">
                        <span class="entertainment  "></span>
                        <?= Yii::t("frontend", "Entertainment") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["shop/products", 'slug' => 'services']); ?>" class="services_lnk">
                        <span class="services  "></span>
                        <?= Yii::t("frontend", "Services") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["shop/products", 'slug' => 'others']); ?>" class="others_lnk">
                        <span class="other1"></span>
                        <?= Yii::t("frontend", "Others") ?>
                    </a>
                </li>
                <li>
                    <a href="<?= Url::to(["shop/products", 'slug' => 'gift-favors']); ?>">
                        <span class="say1"></span>
                        <?= Yii::t("frontend", "Gift Favors") ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="add_banner">
            <?= Html::img("@web/images/explore_banner.jpg", ['alt' => 'Banner']) ?>
        </div>
    </div>
</section>
<!-- continer end -->

<div class="modal fade" id="AreaModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" id="eventModal">
        <div class="modal-content  modal_member_login signup_poupu row">
            <div class="modal-header">
                <button type="button" class="close" id="boxclose" name="boxclose" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="text-center">
                    <span class="yellow_top"></span>
                </div>
                <h4 class="modal-title text-center" id="myModalLabel"><?php echo Yii::t('frontend', 'Select Area & Delivery Date'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-8 col-xs-offset-2">
                        <div class="product_popup_signup_box">
                            <div class="">
                                <!--<form name="create_event" id="create_event">-->

                                <?php

                                $form = ActiveForm::begin([
                                    'id' => 'area-selection',
                                ]) ?>
                                    <input type="hidden" id="_csrf" name="_csrf" value="<?=Yii::$app->request->csrfToken; ?>" />
                                    <div class="form-group new_popup_common">
                                        <div class="bs-docs-example">
                                            <?php
                                            if (Yii::$app->language == "en") {
                                                $name = 'city_name';
                                            } else {
                                                $name = 'city_name_ar';
                                            }
                                            echo Html::dropDownList('city','',\yii\helpers\ArrayHelper::map($city,'city_id',$name),['prompt'=>'Please Select City','class'=>'selectpicker required trigger','id'=>'area']);
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group new_popup_common">
                                        <div class="bs-docs-example" id="location_div">
                                            <?php
                                            echo Html::dropDownList('location','',[],['prompt'=>'Please Select Location','class'=>'selectpicker required trigger','id'=>'location'])
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group top_calie_new">
                                        <div data-date-format="dd-mm-yyyy" data-date="12-02-2012" id="dp3" class="input-append date">
                                            <input type="text" name="delivery_date" id="delivery_date" readonly size="16" class="form-control required datetimepicker date1" placeholder="<?php echo Yii::t('frontend', 'Choose Event Date'); ?>" title="<?php echo Yii::t('frontend', 'Choose Delivery Date'); ?>">
                                            <span class="add-on position_news"> <i class="flaticon-calendar189"></i></span>
                                        </div>
                                    </div>
                                    <div class="buttons">
                                        <div class="m-0 creat_evn_sig col-lg-6 padding-left-0">
                                            <button type="button" id="submit" name="submit" class="btn btn-default" title="<?php echo Yii::t('frontend', 'Create Event'); ?>"><?php echo Yii::t('frontend', 'Save'); ?></button>
                                        </div>
                                        <div class="m-0 cancel_sig col-lg-6 padding-right-0">
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

if (!Yii::$app->user->isGuest) {
    $session = Yii::$app->session;
    if (!$session->has('deliver-location')) {
        echo $this->render('_popup');
        $this->registerJS("
            jQuery('#ShopLocationDateModal').modal('show');

            jQuery('#select_date_button').click(function(event)
            {
                jQuery.ajax({
                    type:'POST',
                    url:'" . yii\helpers\Url::to(['shop/location-date-selection']) . "',
                    data:jQuery('#shop-date-location-hook').serialize(),
                    success:function(data){

                    if (jQuery.trim(data) == 'date') {
                        jQuery('.datetimepicker').addClass('border-red');
                    } else {
                        jQuery('#ShopLocationDateModal').modal('hide');
                    }
                    jQuery('.event_loader').hide();
                }
            })
        });", \yii\web\View::POS_READY);
    }
    $this->registerCss("
    .border-red{border:1px solid red! important;}
    .datepicker{border: 2px solid rgb(242, 242, 242);}
    .datepicker table {font-size: 15px;}
    .product_popup_signup_log > form {padding: 2px 0 0;}
    .m-0{margin:0px! important;}
    .padding-left-0{padding-left: 0px!important;}
    .padding-right-0{padding-right: 0px!important;}
    .new_popup_common .bootstrap-select button,#dp3 input[type=\"text\"]{color: #0a0a0a!important;}
    ");
}
?>