<?php 

use yii\widgets\ActiveForm;

?>

<div class="col-md-12">

	<div class="col-md-6">

		<h3>
			<?= Yii::t('frontend', 'If you are a <span>Whitebook customer</span>, please login using the same <span>username and password</span>.') ?>
		</h3>

		<br />

		<?php $form = ActiveForm::begin(['options' => ['class' => 'frm_login']]); ?>
			
			<input type="hidden" name="type" value="login" />

			<div class="form-group field-customer-customer_email required">
				<label class="control-label" for="customer-customer_email">Email</label>
				<div class="controls1">
					<input type="text" id="customer-customer_email" class="form-control" name="Customer[customer_email]">
				</div>  
				<span class="error customer_email"></span>
			</div>
			
			<div class="form-group">
				<label class="control-label" for="customer-customer_password">Password</label>
				<div class="controls1">
					<input type="password" id="customer-customer_password" class="form-control" name="Customer[customer_password]">
				</div>
				<span class="error customer_password"></span>
			</div>

			<button class="btn btn-primary">
				<?= Yii::t('frontend', 'Sign In') ?>
			</button>

		<?php ActiveForm::end(); ?>

		<hr />

		<button class="btn btn-primary btn-guest-checkout">
			<?= Yii::t('frontend', 'Continue as a guest') ?>
		</button>

		<br />
		<br />
		<br />

	</div>

	<div class="clearfix"></div>
</div>
