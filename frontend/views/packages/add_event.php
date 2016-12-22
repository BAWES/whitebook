<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\components\CFormatter;

?>

<div class="text-center">

	<?= Url::to(Html::img("@s3/".$model->package_background_image, [
			'class'=>'item-img'
		])); ?>

	<form id="form_package_to_event">

		<input type="hidden" id="_csrf" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
		
		<input type="hidden" name="package_id" value="<?php echo $model->package_id;?>" />
		
		<div class="form-group new_popup_common">
			<div class="bs-docs-example">
			    <select name="event_id">
			        <option value=''><?= Yii::t('frontend', 'Select Event') ?></option>
			        <?php foreach($customer_events as $e) { ?>
			        <option value="<?php echo $e['event_id'];?>"><?php echo $e['event_name'];?></option>
			        <?php } ?>
			    </select>
			</div>
			<div class="error" id="add_to_event_error"></div>
		</div>

		<div class="error err-message" id="add_to_event_failure"></div>

		<div class="success_event" id="add_to_event_success"></div>

		<div class="event_loader" id="add_to_event_loader" style="display:none;">
			<img src="<?php echo Url::to("@web/images/ajax-loader.gif");?>" title="Loader">
		</div>

		<div class="buttons">
			<div class="creat_evn_sig">
				<button type="button" class="btn btn-default" title="<?php echo Yii::t('frontend','Add to Event');?>"><?php echo Yii::t('frontend','Add to Event');?>
				</button>
			</div>

			<span class="text-center forgotpwd">
				<a data-target="#EventModal" data-dismiss="modal" data-toggle="modal" title="<?php echo Yii::t('frontend','Create New Event');?>" class="actionButtons" href="#"> 
					<?php echo Yii::t('frontend','Create New Event');?>			
				</a>
			</span>
		</div><!-- END .buttons -->

	</form>

</div>
<?php 

$this->registerCss("
	.item-img{width:210px; height:208px;}
	.err-message{color:red;margin-bottom: 10px;}
	.success_event{color:red;margin-bottom: 10px;}
	#add_to_event_loader{text-align:center;margin-bottom: 10px;}
");

?>
