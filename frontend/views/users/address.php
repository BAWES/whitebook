<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\base;
use yii\web\View;
use yii\widgets\ActiveForm;
use common\models\Location;


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
            <div class="col-md-2 hidde_res"></div>
            <div class="col-md-8">
                <div class="accont_informations">
                    <div class="accont_info">                        
                        <div class="row">
                            <?php foreach ($addresses as $address) { ?>
                            	<div class="col-lg-4">
                            		<div class="address_box">
                            			
                            			<a data-id="<?= $address['address_id'] ?>" class="address_delete pull-right">
                            				<i class="glyphicon glyphicon-trash"></i>
                            			</a>

                            			<?= $address['address_data']?nl2br($address['address_data']).'<br />':'' ?>
                            			
                                        <?php 

                                        if(Yii::$app->language == 'en') { 

                            			    echo $address['location']?$address['location'].'<br />':'';
                                            echo $address['city_name']?$address['city_name'].'<br />':'';
                                        } else {

                                            echo $address['location_ar']?$address['location_ar'].'<br />':'';
                                            echo $address['city_name_ar']?$address['city_name_ar'].'<br />':'';
                                        }
                            			
                            			if($address['questions']) { ?>
                            			<h4><?php echo Yii::t('frontend', 'Questions') ?></h4>
                            			<ul>
                            			<?php foreach ($address['questions'] as $row) { ?>
                            				<li>
                            					<?php if(Yii::$app->language == 'en') { ?>
                                                    <b><?= $row['question'] ?></b>
                                                <?php } else{ ?>
                                                    <b><?= $row['question_ar'] ?></b>
                                                <?php } ?>

                            					<p><?= $row['response_text'] ?></p>
                            				</li>
                            			<?php } ?>
                            			<?php } ?>
                            			</ul>
                            		</div>
                            	</div>
                            <?php } ?>
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
            </div>
        </div>
    </div>
</section>

<?php $form = ActiveForm::begin(); ?>

<div class="modal fade" id="modal_create_address">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="padding-bottom:0; margin-bottom: 0;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><?php echo Yii::t('frontend','Add new address') ?></h4>
      </div>
      <div class="modal-body" style="background: white; margin-top: 0;">

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

    