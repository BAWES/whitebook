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
            <div class="col-md-2 hidde_res"></div>
            <div class="col-md-8">
                <div class="accont_informations">
                    <div class="accont_info">                        
                        <table class="table table-bordered">
                            <?php foreach ($addresses as $address) { ?>
                            <tr>
                            	<td>
                            		<div class="address_box">
                            			<div class="control-icons clearfix">
                                            <a data-id="<?= $address['address_id'] ?>" class="address_delete btn pull-right">
                                                <i class="glyphicon glyphicon-trash"></i>
                                            </a>

                                            <a href="<?= Url::to(['users/edit-address', 'address_id' => $address['address_id']]) ?>" class="btn-edit btn-primary btn pull-right" >
                                                <i class="glyphicon glyphicon-pencil"></i>
                                            </a>
                                        </div>
                                        
                                        <?php if($address['address_name']) { ?>
                                            <b><?=Yii::t('frontend','Address Name:')?></b> <br />
                                            <?= $address['address_name'] ?>
                                            <br />
                                            <br />
                                        <?php } ?>

                                        <!-- address type -->
                                        <b><?=Yii::t('frontend','Address Type:')?></b> <br />
                                        <?= AddressType::type_name($address['address_type_id']); ?>

                                        <br />
                                        <br />

                                        <!-- address -->
                                        <b><?=Yii::t('frontend','Address:')?></b> <br />
                                        <?= $address['address_data']?nl2br($address['address_data']).'<br />':'' ?>
                            			
                                        <!-- address question response -->
                                        <?php if($address['questions']) { ?>
                                        <ul>
                                        <?php foreach ($address['questions'] as $row) { ?>
                                            <li>
                                                <br />
                                                <b><?= $row['question'] ?></b>
                                                <br />
                                                <?= $row['response_text'] ?>
                                            </li>
                                        <?php } ?>
                                        <?php } ?>
                                        </ul>

                                        <br />

                                        <b><?=Yii::t('frontend','Area:')?></b> <br />
                                        <?=\common\components\LangFormat::format($address['location'],$address['location_ar']); ?><br/>
                                        
                                        <br />

                                        <b><?=Yii::t('frontend','City:')?></b> <br />
                                        <?=\common\components\LangFormat::format($address['city_name'],$address['city_name_ar']); ?><br/>
                            		</div>
                            	</td>
                            </tr>    
                            <?php } ?>
                            </table>

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
.header-updated{padding-bottom:0; margin-bottom: 0;}
.body-updated{background: white; margin-top: 0;}
");

    