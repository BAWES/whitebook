<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\base;
use yii\widgets\ActiveForm;

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
                    ['prompt' => Yii::t('frontend', 'Select...')]
                ); ?>

			<div class="question_wrapper">
				<!-- question will go here -->
			</div>

			<?= $form->field($customer_address_modal, 'country_id')->dropDownList($country, 
                    ['prompt' => Yii::t('frontend', 'Select...')]
                ); ?>

			<?= $form->field($customer_address_modal, 'city_id')->dropDownList([], 
                    ['prompt' => Yii::t('frontend', 'Select...')]
                ); ?>

			<?= $form->field($customer_address_modal, 'area_id')->dropDownList([], 
                    ['prompt' => Yii::t('frontend', 'Select...')]
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

    function add_address() {



        $('#modal_create_address form').submit();    
    }
    

    $(function (){

    	$('.address_delete').click(function(){
    		var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
            var path = '".Url::to(['/users/address_delete'])."';

            var address_id = $(this).attr('data-id');

            $.ajax({
                type: 'POST',
                url: path, //url to be called
                data: { address_id: address_id, _csrf : csrfToken}, //data to be send
                success: function( data ) {
                     $('#customeraddress-city_id').html(data);
                }
            });

            $(this).parent().parent().remove();
    	});

    	$('#customeraddress-address_type_id').change(function (){
            var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
            var address_type_id = $('#customeraddress-address_type_id').val();
            var path = '".Url::to(['/users/questions'])."';
            
            $.ajax({
                type: 'POST',
                url: path, //url to be called
                data: { address_type_id: address_type_id ,_csrf : csrfToken}, //data to be send
                success: function( data ) {
                     $('.question_wrapper').html(data);
                }
            });
        });

        $('#customeraddress-country_id').change(function (){
            var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
            var country_id = $('#customeraddress-country_id').val();
            var path = '".Url::to(['/site/city'])."';
            
            $.ajax({
                type: 'POST',
                url: path, //url to be called
                data: { country_id: country_id ,_csrf : csrfToken}, //data to be send
                success: function( data ) {
                    $('#customeraddress-city_id').html(data);
                    $('#customeraddress-city_id').selectpicker('refresh');
                }
            });
        });
    
        $('#customeraddress-city_id').change(function (){
            var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
            var city_id = $('#customeraddress-city_id').val();
            var path = '".Url::to(['/site/area'])."';
            
            $.ajax({
                type: 'POST',
                url: path, //url to be called
                data: { city_id: city_id ,_csrf : csrfToken}, //data to be send
                success: function( data ) {
                    $('#customeraddress-area_id').html(data);
                    $('#customeraddress-area_id').selectpicker('refresh');
                }
            });
         });
    });
");

    