<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\view;

$this->title = 'Update vendor item';
$this->params['breadcrumbs'][] = ['label' => 'Vendor items', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';

$model->item_status = ($model->item_status == 'Active') ? 1 : 0;

?>

<div class="vendoritem-update">

<div class="col-md-12 col-sm-12 col-xs-12">

<?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>

<div class="loadingmessage" style="display: none;">
	<p>
    	<?= Html::img(Yii::getAlias('@web/themes/default/img/loading.gif'), ['class'=>'','width'=>'64px','height'=>'64px','id'=>'loading','alt'=>'loading']);?>
	</p>
</div>

<div class="tabbable">
	<ul class="nav nav-tabs">
	    <li>
	    	<a href="javascript::void()">
	    		Item Info 
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void()">
	    		Item description
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void()">
	    		Item price 
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void()">
	    		Menu items
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void()">
	    		Addons
	    	</a>
	    </li>
	    <li class="active">
	    	<a href="javascript::void()">
	    		Approval 
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void()">
	    		Images
	    	</a>
	    </li>
	    <li>
	    	<a href="javascript::void()">
	    		Other
	    	</a>
	    </li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane clearfix active">

			<?= $form->field($model, 'item_approved')
					->dropDownList([ 'Pending' => 'Pending','Yes' => 'Yes', 'Rejected'=>'Rejected']); ?>

			<?= $form->field($model, 'item_status')->checkbox(['Value' => true]); ?>

			<hr />

			<a href="<?= Url::to(['vendor-item/addon-menu-items', 'id' => $model->item_id]) ?>" class="btn btn-info pull-left">Prev</a>
		
			<input type="submit" name="btnNext" class="btn btn-info pull-right" value="Next" />

		</div>
	</div>
</div>

<?php 

ActiveForm::end(); 

?>

</div>

</div><!-- END .vendoritem-update -->
