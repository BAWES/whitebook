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
							<div id="acc_status"></div>
							<h4><?= Yii::t('frontend','Account Info'); ?></h4>
						</div>
						<div class="account_form">
						
							<?php $form = ActiveForm::begin(); ?>
							<div class="clearfix">
								<div class="left-side col-lg-6 padding-right-0">
									<div class="col-md-12 paddingleft0">
										<?= $form->field($model, 'customer_name') ?>
									</div>

									<div class="col-md-12 paddingleft0">
										<?= $form->field($model, 'customer_email') ?>
									</div>

									<div class="col-md-12 paddingleft0">
										<?= $form->field($model, 'customer_dateofbirth') ?>
									</div>

								</div>
								<div class="right-side col-lg-6 padding-left-0">
									<div class="col-md-12 paddingright0">
										<?= $form->field($model, 'customer_last_name') ?>
									</div>


									<div class="col-md-12 paddingright0 position-relative">
										<?=$form->field($model, 'customer_gender')
											->dropDownList(['Male' => 'Male', 'Female' =>'Female']);   ?>
										<i class="position-absolute fa fa-sort" aria-hidden="true"></i>
									</div>


									<div class="col-md-12 paddingright0">
										<?= $form->field($model, 'customer_mobile') ?>
									</div>
								</div>
							</div>

								<div class="submitt_buttons">
									<button class="btn btn-default" title="Save Changes" id="saved">
										<?= Yii::t('frontend','Save Changes');?>
									</button>
								</div>

							<?php ActiveForm::end(); ?>

							<div id="login_loader" style="display:none;"><img src="<?php echo Url::to("@web/images/ajax-loader.gif");?>" title="Loader"></div>

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

$this->registerCss("
	#acc_status{color:green;margin-bottom: 10px;}
	#login_loader{text-align:center;margin-bottom: 10px;}
	.padding-right-0{padding-right:0px! important;}
	.padding-left-0{padding-left:0px! important;}
	.position-absolute.fa-sort{position: absolute;right: 17px;top: 41px;}
");


