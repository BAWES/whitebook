<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\base;
use yii\web\View;
use yii\widgets\ActiveForm;
use common\models\Location;
use frontend\models\Addresstype;

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
			<div class="col-md-2 hidde_res"></div>
			<div class="col-md-8">
				<div class="col-md-6 paddingleft0">
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
		                
		        <div class="col-md-6 paddingleft0">
					<div class="form-group">
						<?= $form->field($address, 'address_data',['template' => "{label}<div class='controls1'>{input}</div> {hint} {error}"
						])->textArea(['rows' => 6]) ?>
					</div>
				</div>

			</div>
		</div>

		<div class="submitt_buttons">
			<button class="btn btn-default" type="submit" title="Save Changes" id="saved" name="saved">
				Save Changes	
			</button>
		</div>
	</div>
</section>

<?php 

ActiveForm::end(); 

$this->registerJs("

	jQuery('#customeraddress-address_type_id').on('change', function(){
       
        var csrfToken = jQuery('meta[name=\"csrf-token\"]').attr('content');
        var address_type_id = jQuery('#customeraddress-address_type_id').val();
        var path = '".Url::to(['/users/questions'])."';
        
        jQuery.ajax({
            type: 'POST',
            url: path, //url to be called
            data: { address_type_id: address_type_id, address_id: '".$address_id."', _csrf : csrfToken}, 
            success: function( data ) {
                 jQuery('.question_wrapper').html(data);
            }
        });
    });

	jQuery('#customeraddress-address_type_id').trigger('change');

", View::POS_READY);

    