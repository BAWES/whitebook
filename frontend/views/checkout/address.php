<?php

use common\models\CustomerCart;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="panel panel-default">
        <div class="panel-heading">
                <h4 class="panel-title">
                        <?= Yii::t('frontend', 'Select delivery address for each items') ?>                        
                </h4>
        </div>
        <div class="panel-body" style="padding: 0px">

        <br />

        <form id="address_selection_form">

        <?php foreach ($items as $item) { ?>
                
                <div class="item_wrapper" id="item_wrapper_<?= $item['cart_id'] ?>">

                        <input type="hidden" class="hdn_address" name="address[<?= $item['cart_id'] ?>]" value="" />

                        <div class="item_name">
                                <?php if(Yii::$app->language == 'en'){ 
                                        echo $item['item_name'];
                                } else{ 
                                        echo $item['item_name_ar'];
                                } ?>
                        </div>

                        <div class="address_block_wrapper">
                        <?php 

                        $addresses = CustomerCart::customerAddress($item['area_id']);

                        foreach ($addresses as $address) { ?>
                                <div class="address_block" data-id="<?= $address['address_id'] ?>">
                                        <?php 

                                        echo $address['address_data'].'<br />';

                                        if(Yii::$app->language == 'en'){ 
                                                echo $address['location']['location'].'<br />';
                                                echo $address['city']['city_name'];
                                        }else{
                                                echo $address['location']['location_ar'].'<br />';
                                                echo $address['city']['city_name_ar'];
                                        } ?>
                                </div>
                        <?php } ?>
                                
                                <div class="address_insert_block" data-toggle="modal" data-target="#modal_create_address">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <br /><br /> 
                                        <?= Yii::t('frontend', 'Add new address') ?>
                                </div>

                        </div>
                        <div class="clearfix"></div>
                </div>
        <?php } ?>   

        </form>

        </div>
</div>

<div class="btn-set">
        <a href="<?= Url::to(['shop/index']); ?>" class="btn btn-primary btn-checkout pull-left" style="margin-left: 0;">
                <?= Yii::t('frontend', 'Back to shopping') ?>
        </a>

        <button class="btn btn-primary btn-checkout pull-right" onclick="save_address();">
                <?= Yii::t('frontend', 'Next') ?>
        </button>
</div>

<br />
<br />
<br />

<div class="modal fade" id="modal_create_address">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

        <?php $form = ActiveForm::begin(); ?>

        <div class="modal-header" style="padding-bottom:0; margin-bottom: 0;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php echo Yii::t('frontend','Add new address') ?></h4>
        </div>
        <div class="modal-body" style="background: white; margin-top: 0;">

                <?= $form->field($customer_address_modal, 'address_type_id')->dropDownList($addresstype, 
                    ['prompt' => Yii::t('frontend', 'Select...')]
                ); ?>

                <div class="question_wrapper">
                        <!-- question will go here -->
                </div>

                <?= $form->field($customer_address_modal, 'country_id')->dropDownList($country, 
                    ['prompt' => Yii::t('frontend', 'Select...')]
                ); ?>
                <span class="error country_id"></span>

                <?= $form->field($customer_address_modal, 'city_id')->dropDownList([], 
                    ['prompt' => Yii::t('frontend', 'Select...')]
                ); ?>
                <span class="error city_id"></span>

                <?= $form->field($customer_address_modal, 'area_id')->dropDownList([], 
                    ['prompt' => Yii::t('frontend', 'Select...')]
                ); ?>
                <span class="error area_id"></span>

                <div class="form-group">
                        <?= $form->field($customer_address_modal, 'address_data',['template' => "{label}<div class='controls1'>{input}</div> {hint} {error}"
                        ])->textArea(['rows' => 6]) ?>
                </div>
                <span class="error address_data"></span>
        </div>

        <div class="modal-footer submitt_buttons">
            <button type="submit" class="btn btn-submit-address btn-default">
                <?= Yii::t('frontend', 'Submit') ?>
            </button>
        </div>

        <?php ActiveForm::end(); ?>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



    