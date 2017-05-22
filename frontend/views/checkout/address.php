<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\Location;
use common\models\CustomerCart;
use common\components\LangFormat;
use common\models\Booking;

$customer_address_modal->area_id = Yii::$app->session->get('delivery-location');

?>
<div class="panel panel-default">
        <div class="panel-heading">
                <h4 class="panel-title">
                    <?= Yii::t('frontend', 'Select delivery address') ?>
                </h4>
        </div>
        <div class="panel-body padding-0">

            <form id="address_selection_form">

                <input type="hidden" name="address_id" id="selected_address_id" />

                <div class="address_block_wrapper">
                <?php

                $addresses = CustomerCart::customerAddress();

                foreach ($addresses as $address) { ?>
                        <div class="address_block pull-left" data-id="<?= $address['address_id'] ?>">
                            <?=\yii\helpers\Html::a('<i class="fa fa-trash fa-1x pull-right"></i>',['checkout/remove-address'],['class'=>'delete-address color-black'])?>
                            <?php

                            if($address['address_name']) {
                                echo $address['address_name'].'<br />';
                            }

                            echo Booking::getPurchaseDeliveryAddress($address['address_id']);

                            ?>
                        </div>
                <?php } ?>

                        <div class="address_insert_block pull-left" data-toggle="modal" data-target="#modal_create_address">
                                <i class="glyphicon glyphicon-plus"></i>
                                <br /><br />
                                <?= Yii::t('frontend', 'Add new address') ?>
                        </div>

                </div>
            </form>

        </div>
</div>

<div class="btn-set">
    <a href="<?= Url::to(['browse/index']); ?>" class="btn btn-primary btn-checkout pull-left margin-left-0">
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
                <div class="modal-header margin-padding-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"><?php echo Yii::t('frontend','Add new address') ?></h4>
                </div>
                <div class="modal-body body-update">
                    
                    <?= $form->field($customer_address_modal, 'address_name', [
                        'template' => "{label}*\n{input}\n{hint}\n{error}"
                    ])->textInput(['maxlength' => true])?>

                    <?= $form->field($customer_address_modal, 'address_type_id')
                            ->radioList($addresstype, ['class' => 'selectpicker']); ?>
                    
                    <!--
                    <?= $form->field($customer_address_modal, 'area_id')->dropDownList(Location::areaOptions(),
                            ['class' => 'selectpicker', 'data-live-search' => 'true', 'data-size' => 10]
                        ); ?>
                    <span class="error area_id"></span>
                    -->

                    <div class="question_wrapper">
                        <!-- question will go here -->
                    </div>

                    <div class="form-group">
                        <?= $form->field($customer_address_modal, 'address_data',['template' => "{label}<div class='controls1'>{input}</div> {hint} {error}"
                            ])->textArea(['rows' => 6]) ?>
                        <span class="error address_data"></span>
                    </div>

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
<?php
$this->registerCss("
    .margin-left-0{margin-left: 0;}
    .padding-0{padding:0px!important;}
    .margin-padding-0{padding-bottom:0; margin-bottom: 0;}
    .body-update{background: white; margin-top: 0;}
");
