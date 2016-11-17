<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\base;
use yii\web\View;
use yii\widgets\ActiveForm;
use common\models\Location;
use frontend\models\AddressType;

$this->title ='Address Book | Whitebook';

?>

<!-- coniner start -->
<section id="inner_pages_sections">
    <div class="container">
        <div class="title_main">
            <h1><?php echo Yii::t('frontend','Address Book'); ?></h1>
        </div>

        <?php if($addresses) { ?>
            <br /><br />
        <?php } ?>

        <div class="account_setings_sections">
            <?=$this->render('_sidebar_menu');?>
            <div class="col-md-9 border-left">
                <div class="accont_informations">
                    <?= \yii\grid\GridView::widget([
                        'dataProvider' => $provider,
                        'summary' => '',
                        'columns' => [
                            'address_name',
                            [
                                'attribute' =>'address_type_id',
                                'header' =>'Type',
                                'value' => function($model) {
                                    return AddressType::type_name($model['address_type_id']);
                                }
                            ],
                            [
                                'attribute' =>'location',
                                'value' => function($model) {
                                    return \common\components\LangFormat::format($model['location'],$model['location_ar']);
                                }
                            ],
                            [
                                'attribute' =>'city_name',
                                'value' => function($model) {
                                    return \common\components\LangFormat::format($model['city_name'],$model['city_name_ar']);
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header'=>'Action',
                                'contentOptions' => ['class' => 'text-center'],
                                'buttons' => [
                                    'view' => function ($url, $model) {
                                        $url = Url::to(['users/view-address','address_id'=>$model['address_id']],true);
                                        return  Html::a('<span class="fa fa-search"></span> &nbsp;View', $url,
                                            [ 'title' => Yii::t('app', 'View'), 'class'=>'btn btn-primary btn-xs', ]) ;
                                    },
                                    'update' => function ($url, $model) {
                                        $url = Url::to(['users/edit-address','address_id'=>$model['address_id']],true);
                                        return  Html::a('<span class="fa fa-pencil"></span> &nbsp;Update', $url,
                                            [ 'title' => Yii::t('app', 'View'), 'class'=>'btn btn-primary btn-xs', ]) ;
                                    },
                                    'delete' => function ($url, $model) {
                                        $url = Url::to(['users/address-delete','address_id'=>$model['address_id']],true);
                                        return  Html::a('<span class="fa fa-trash"></span >&nbsp;Delete', $url,
                                            [ 'title' => Yii::t('app', 'View'), 'class'=>'btn btn-primary btn-xs', 'onclick'=>'return (confirm("Are you sure you want to delete this address?"))']
                                        ) ;
                                    },
                                ]
                            ],
                        ],
                    ]); ?>

                    </div>
                </div>

            <div class="clearfix"></div>

            <hr />

            <center class="submitt_buttons">
                <a class="btn btn-default" data-toggle="modal" data-target="#modal_create_address">
                    <?php echo Yii::t('frontend','Add new address') ?>
                </a>
            </center>
            </div>    
            </div>
        </div>
    </div>
</section>

<?php $form = ActiveForm::begin(); ?>

<div class="modal fade" id="modal_create_address">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header header-updated">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><?php echo Yii::t('frontend','Add new address') ?></h4>
      </div>
      <div class="modal-body body-updated" >

			<?= $form->field($customer_address_modal, 'address_name'); ?>

            <?= $form->field($customer_address_modal, 'address_type_id')->dropDownList($addresstype, 
                    ['class' => 'selectpicker', 'prompt' => Yii::t('frontend', 'Select...')]
                ); ?>

			<div class="question_wrapper">
				<!-- question will go here -->
			</div>

			<?= $form->field($customer_address_modal, 'area_id')->dropDownList(Location::areaOptions(), 
                    ['class' => 'selectpicker', 'data-live-search' => 'true', 'data-size' => 10]
                ); ?>

			<div class="form-group">
				<?= $form->field($customer_address_modal, 'address_data',['template' => "{label}<div class='controls1'>{input}</div> {hint} {error}"
				])->textArea(['rows' => 6]) ?>
			</div>

	  <div class="modal-footer submitt_buttons">
        <button type="submit" class="btn btn-submit-address btn-default">
            <?= Yii::t('frontend', 'Submit') ?>
        </button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<?php 

ActiveForm::end(); 
    
$this->registerJs("

    jQuery('.btn-submit-address').click(function(e){

        jQuery('.has-error').removeClass('has-error');
        jQuery('.has-success').removeClass('has-success');

        //check all textarea 
        jQuery('#modal_create_address textarea').each(function(){
            if(!jQuery(this).val()){
                jQuery(this).parent().addClass('has-error');
            }
        })

        //check address type
        var address_type_id = jQuery('#customeraddress-address_type_id').val();

        if(!address_type_id) {
            jQuery('.field-customeraddress-address_type_id').addClass('has-error');
        }

        //address name
        var address_name = jQuery('#customeraddress-address_name').val();

        if(!address_name) {
            jQuery('.field-customeraddress-address_name').addClass('has-error');
        }

        if(jQuery('#modal_create_address .has-error').length > 0){
            return false;
            e.preventDefault();
            e.stopPropagation();
        }
    });

	jQuery('.address_delete').click(function(){

		var csrfToken = jQuery('meta[name=\"csrf-token\"]').attr('content');
        var path = '".Url::to(['/users/address_delete'])."';

        var address_id = jQuery(this).attr('data-id');

        jQuery.ajax({
            type: 'POST',
            url: path, //url to be called
            data: { address_id: address_id, _csrf : csrfToken}, //data to be send
            success: function( data ) {
                 jQuery('#customeraddress-city_id').html(data);
            }
        });

        jQuery(this).parent().parent().remove();
	});

    //.field-customeraddress-address_type_id select
    jQuery('#customeraddress-address_type_id').on('change', function(){
       
        var csrfToken = jQuery('meta[name=\"csrf-token\"]').attr('content');
        var address_type_id = jQuery('#customeraddress-address_type_id').val();
        var path = '".Url::to(['/users/questions'])."';
        
        jQuery.ajax({
            type: 'POST',
            url: path, //url to be called
            data: { address_type_id: address_type_id ,_csrf : csrfToken}, //data to be send
            success: function( data ) {
                 jQuery('.question_wrapper').html(data);
            }
        });
    });

", View::POS_READY);

$this->registerCss("
table{    font-size: 12px;}
.header-updated{padding-bottom:0; margin-bottom: 0;}
.body-updated{background: white; margin-top: 0;}
#inner_pages_sections .container{background:#fff; margin-top:12px;}
.border-left{border-left: 1px solid #e2e2e2;}
");

    