<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\Location;
use common\models\CustomerCart;
use common\components\LangFormat;
?>


<div class="col-md-12">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-6">

        <h3><?= Yii::t('frontend', 'Customer Information') ?></h3>

        <hr />

        <div class="row">
            <div class="col-md-6">
                <div class="form-group field-customer-customer_email required">
                    <label class="control-label" for="customer-customer_email">First name</label>
                    <div class="controls1">
                        <input type="text" id="customer-customer_name" class="form-control" name="Customer[customer_name]">
                    </div>  
                    <span class="error customer_name"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group field-customer-customer_email required">
                    <label class="control-label" for="customer-customer_email">Last name</label>
                    <div class="controls1">
                        <input type="text" id="customer-customer_lastname" class="form-control" name="Customer[customer_last_name]">
                    </div>  
                    <span class="error customer_last_name"></span>
                </div>
            </div>
        </div>

        <div class="form-group field-customer-customer_email required">
            <label class="control-label" for="customer-customer_email">Email</label>
            <div class="controls1">
                <input type="text" id="customer-customer_email" class="form-control" name="Customer[customer_email]">
            </div>  
            <span class="error customer_email"></span>
        </div>

        <div class="form-group field-customer-customer_mobile required">
            <label class="control-label" for="customer-customer_mobile">Phone</label>
            <div class="controls1">
                <input type="text" id="customer-customer_mobile" class="form-control" name="Customer[customer_mobile]">
            </div>  
            <span class="error customer_mobile"></span>
        </div>
    </div>

    <div class="col-md-6">

        <h3><?= Yii::t('frontend', 'Delivery Details') ?></h3>
        
        <hr />

        <?= $form->field($customer_address_modal, 'address_type_id')->dropDownList($addresstype,
            ['class' => 'selectpicker', 'prompt' => Yii::t('frontend', 'Select...')]
        ); ?>
        <span class="error address_type_id"></span>

        
        <div class="question_wrapper">
            <!-- question will go here -->
        </div>
        
        <?php /* $form->field($customer_address_modal, 'area_id')->dropDownList(Location::areaOptions(),
                ['class' => 'selectpicker', 'data-live-search' => 'true', 'data-size' => 10]
            );
            <span class="error area_id"></span>
        */ ?>
        
        <div class="form-group">
            <?= $form->field($customer_address_modal, 'address_data',['template' => "{label}<div class='controls1'>{input}</div> {hint} {error}"
                ])->textArea(['rows' => 6]) ?>
            <span class="error address_data"></span>
        </div>
            
    </div>

    <?php ActiveForm::end(); ?>

</div>

<div class="col-md-12">


    <hr />

    <div class="btn-set" style="padding: 0 15px;">
        <button onclick="login();" class="btn btn-primary btn-checkout pull-left margin-left-0">
            <?= Yii::t('frontend', 'Back') ?>
        </button>
        <button class="btn btn-primary btn-checkout pull-right" onclick="save_guest_address();">
            <?= Yii::t('frontend', 'Next') ?>
        </button>
    </div>

    <div class="clearfix"></div>

    <br />
    <br />
</div>