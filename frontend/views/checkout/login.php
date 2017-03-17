<?php 

use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="col-md-12">

	<div class="col-md-6 pull-left">

		<h3>
			<?= Yii::t('frontend', 'If you are a <span>Whitebook customer</span>, please login using the same <span>username and password</span>.') ?>
		</h3>

		<br />

		<?php $form = ActiveForm::begin(['options' => ['class' => 'frm_login']]); ?>
			
			<input type="hidden" name="type" value="login" />

			<div class="form-group field-customer-customer_email required">
				<label class="control-label" for="customer-customer_email"><?= Yii::t('frontend', 'Email') ?></label>
				<div class="controls1">
					<input type="text" id="customer-customer_email" class="form-control" name="Customer[customer_email]">
				</div>  
				<span class="error customer_email"></span>
			</div>
			
			<div class="form-group">
				<label class="control-label" for="customer-customer_password"><?= Yii::t('frontend', 'Password') ?></label>
				<div class="controls1">
					<input type="password" id="customer-customer_password" class="form-control" name="Customer[customer_password]">
				</div>
				<span class="error customer_password"></span>
			</div>

			<div class="pull-left">
				<button class="btn btn-primary">
					<?= Yii::t('frontend', 'Sign In') ?>
				</button>
			</div>

			<div class="pull-left">
				<a data-target="#chkForgotPwdModal" onclick="forgot_modal();" data-dismiss="modal" data-toggle="modal" title="Signup" class="actionButtons" href="#chkForgotPwdModal" style="line-height: 35px;margin-right: 20px;margin-left: 20px;"> 
					<?= Yii::t('frontend', 'Forgot your password?') ?>						
				</a>
			</div>
		
			<div class="clearfix"></div>

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

<!-- checkout page forgot password Modal -->

<div class="modal fade" id="chkForgotPwdModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog">
        <div class="modal-content  modal_member_login row">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="text-center">
                    <span class="yellow_top"></span>
                </div>
                <h4 class="modal-title text-center" id="myModalLabel">
                    <span><?= Yii::t('frontend', 'Forgot Password') ?></span></h4>
            </div>
            <div class="modal-body">
                <form id="forgotForm" name="forgotForm" method="POST" class="form col-md-12 center-block">
                    <div class="login-padding">
                        <div class="form-group">
                            <label><?= Yii::t('frontend', 'Email') ?></label>
                            <input type="text" placeholder="" name="forget_email" id="forget_email" class="form-control input-lg validation required" data-msg-required="<?= Yii::t('frontend', 'This field is required.') ?>">
                            <span class="help-block"></span>
                        </div>
                        <div id="forgot_result color-red"></div>
                        <div class="button-signin">
                            <button type="button" class="btn btn-primary btn-lg btn-block login_btn" id="forgot_button" name="forgot_button"><?= Yii::t('frontend', 'Send') ?></button>
                        </div>
                        <div id="forgot_loader"><img src="<?php  echo Url::to('@web/images/ajax-loader.gif',true);?>"  title="Loader"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>