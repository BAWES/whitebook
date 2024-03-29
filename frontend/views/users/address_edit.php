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

<?php $form = ActiveForm::begin(); ?>

<!-- coniner start -->
<section id="inner_pages_sections">
    <div class="container">
        <div class="title_main">
            <h1><?php echo Yii::t('frontend','Edit Address'); ?></h1>
        </div>

        <br />
        <br />

        <div class="edit_address_sections">
			<?=$this->render('_sidebar_menu');?>
			<div class="col-md-9 border-left">
				<div class="col-md-6 paddingleft0 left-side">
					<?= $form->field($address, 'address_type_id')->dropDownList($addresstype, 
		                    ['class' => 'selectpicker', 'prompt' => Yii::t('frontend', 'Select...')]
		                ); ?>

					<div class="question_wrapper">
						<!-- question will go here -->
					</div>

					<?= $form->field($address, 'area_id')->dropDownList(Location::areaOptions(), 
		                    ['class' => 'selectpicker', 'data-live-search' => 'true', 'data-size' => 10]
		                ); ?>
		        </div>
		                
		        <div class="col-md-6 paddingleft0 right-side">

					<div class="form-group">
						<?= $form->field($address, 'address_name'); ?>
					</div>

					<div class="form-group">
						<?= $form->field($address, 'address_data',['template' => "{label}<div class='controls1'>{input}</div> {hint} {error}"
						])->textArea(['rows' => 6]) ?>
					</div>
				</div>

			</div>
		</div>

		<div class="submitt_buttons">
			<button class="btn btn-default btn-submit-address" type="submit" title="Save Changes" id="saved" name="saved">
				Save Changes	
			</button>
		</div>
	</div>
</section>

<?php 

ActiveForm::end(); 

$this->registerJs("

	jQuery('.btn-submit-address').click(function(e){

        jQuery('.has-error').removeClass('has-error');
        jQuery('.has-success').removeClass('has-success');

        //check all textarea 
        jQuery('.edit_address_sections textarea').each(function(){
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

        if(jQuery('.edit_address_sections .has-error').length > 0){
            return false;
            e.preventDefault();
            e.stopPropagation();
        }
    });

	jQuery('#customeraddress-address_type_id').on('change', function(){
       
        var csrfToken = jQuery('meta[name=\"csrf-token\"]').attr('content');
        var address_type_id = jQuery('#customeraddress-address_type_id').val();
        var path = '".Url::to(['/users/questions'])."';
        
        jQuery.ajax({
            type: 'POST',
            url: path, //url to be called
            data: { hide_area: true, address_type_id: address_type_id, address_id: '".$address_id."', _csrf : csrfToken}, 
            success: function( data ) {
                 jQuery('.question_wrapper').html(data);
            }
        });
    });

	jQuery('#customeraddress-address_type_id').trigger('change');

", View::POS_READY);


$this->registerCss("
#inner_pages_sections .container{background:#fff; margin-top:12px;}
.border-left{border-left: 1px solid #e2e2e2;}
");