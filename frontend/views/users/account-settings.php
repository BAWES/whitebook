<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\view;

$this->title ='Account Setting | Whitebook';

?>

<!-- coniner start -->
<section id="inner_pages_sections">
	<div class="container">
		<div class="title_main">
			<h1><?php echo Yii::t('frontend','Account Settings'); ?></h1>
		</div>

		<div class="account_setings_sections">
			<div class="col-md-2 hidde_res"></div>
			<div class="col-md-8">
				<div class="accont_informations">
					<div class="accont_info">
						<div class="account_title">
							<div id="acc_status" style="color:green;margin-bottom: 10px;"></div>
							<h4><?= Yii::t('frontend','Account Info'); ?></h4>
						</div>
						<div class="account_form">
						
							<?php $form = ActiveForm::begin(); ?>

								<div class="col-md-6 paddingleft0">
									<?= $form->field($model, 'customer_name') ?>
								</div>

								<div class="col-md-6 paddingright0">
									<?= $form->field($model, 'customer_last_name') ?>
								</div>

								<div class="col-md-6 paddingleft0">
									<?= $form->field($model, 'customer_email') ?>
								</div>

								<div class="col-md-6 paddingright0">
									<?= $form->field($model, 'customer_gender') ?>
								</div>

								<div class="col-md-6 paddingleft0">
									<?= $form->field($model, 'customer_dateofbirth') ?>
								</div>

								<div class="col-md-6 paddingright0">
									<?= $form->field($model, 'customer_mobile') ?>
								</div>

								<div class="submitt_buttons">
									<button class="btn btn-default" title="Save Changes" id="saved">
										<?= Yii::t('frontend','Save Changes');?>
									</button>
								</div>

							<?php ActiveForm::end(); ?>

							<div id="login_loader" style="display:none;text-align:center;margin-bottom: 10px;"><img src="<?php echo Url::to("@web/images/ajax-loader.gif");?>" title="Loader"></div>

						</div>
					</div>
				</div><!-- END .accont_informations-->
				<div class="col-md-2 hidde_res"></div>
				</div>
			</div>	
		</div><!-- END .account_setings_sections -->
	</div><!-- END .container -->
</section>

<?php 

$this->registerJs("

	jQuery('#customer-customer_dateofbirth').datepicker({
	    format: 'yyyy-mm-dd',
	    autoclose:true,
	});

", View::POS_READY);


