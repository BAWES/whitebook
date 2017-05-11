<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\view;

$this->title = 'Update vendor item';
$this->params['breadcrumbs'][] = ['label' => 'Vendor items', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
$question_count = 1;
?>
<div class="vendoritem-update">

<div class="col-md-12 col-sm-12 col-xs-12">

<?php $form = ActiveForm::begin(); ?>

<div class="loadingmessage" style="display: none;">
	<p>
    	<?= Html::img(Yii::getAlias('@web/themes/default/img/loading.gif'), ['class'=>'','width'=>'64px','height'=>'64px','id'=>'loading','alt'=>'loading']);?>
	</p>
</div>

<div class="tabbable">
	<ul class="nav nav-tabs">
		<li>
	    	<a href="<?= Url::to(['vendor-item/update', 'id' => $item_id]) ?>">
	    		Item Info 
	    	</a>
	    </li>
	    <li>
	    	<a href="<?= Url::to(['vendor-item/item-description', 'id' => $item_id]) ?>">
	    		Description
	    	</a>
	    </li>
	    <li>
	    	<a href="<?= Url::to(['vendor-item/item-price', 'id' => $item_id]) ?>">
	    		Price and Inventory
	    	</a>
	    </li>
	    <li>
	    	<a href="<?= Url::to(['vendor-item/menu-items', 'id' => $item_id]) ?>">
	    		Menu
	    	</a>
	    </li>
	    <li>
	    	<a href="<?= Url::to(['vendor-item/addon-menu-items', 'id' => $item_id]) ?>">
	    		Addons
	    	</a>
	    </li>
        <li  class="active">
            <a href="<?= Url::to(['vendor-item/item-questions', 'id' => $item_id]) ?>">
                <?=Yii::t('app','Questions')?>
            </a>
        </li>
	    <li>
	    	<a href="<?= Url::to(['vendor-item/item-images', 'id' => $item_id]) ?>">
	    		Images
	    	</a>
	    </li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane clearfix active">

			<fieldset>
				<legend>Item Questions</legend>
                <div class="form-group questions">
                    <?php

                    if (!isset($model->isNewRecord)) {
                        foreach($model as $question) { ?>
                            <div class="clearfix question question-<?=$question_count?> margin-top-10">
                                <div class=" col-md-9">
                                    <div class="control-label margin-top-10">Question <?=$question_count?></div>
                                    <input value="<?=$question->question?>" type="text" id="question-<?=$question_count?>" class="form-control" name="VendorDraftItemQuestion[<?=$question_count?>][question]">
                                </div>
                                <div class="col-md-2">
                                    <div class="control-label margin-top-10">Required?</div>
                                    <select id="required-<?=$question_count?>" class="form-control" name="VendorDraftItemQuestion[<?=$question_count?>][required]">
                                        <option value="1" <?=($question->required == 1) ? 'selected="selected"' : '' ?>> Yes </option>
                                        <option value="0" <?=($question->required == 0) ? 'selected="selected"' : '' ?>>  No </option>
                                    </select>
                                </div>
                                <div class="col-md-1 margin-top-30">
                                    <button data-id="question-<?=$question_count?>" type="button" class="btn btn-danger btn-remove-question"><i class="fa fa-trash-o"></i></button>
                                </div>
                            </div>
                        <?php
                        $question_count++;
                        }
                    } ?>
                </div>

                <br />
                <button type="button" class="btn btn-primary btn-add-question">
                    <i class="fa fa-plus"></i> Add new Question
                </button>

                <hr />
			<div class="row">				
				<div class="col-md-4">
					<a href="<?= Url::to(['vendor-item/addon-menu-items', 'id' => $item_id]) ?>" class="btn btn-info pull-left">Prev</a>
				</div>
				<div class="col-md-4 text-center">
					<input type="submit" name="complete" class="btn btn-info" value="Complete" />
				</div>
				<div class="col-md-4">
					<input type="submit" name="btnNext" class="btn btn-info pull-right" value="Next" />
				</div>
			</div>
			
		</div><!-- END .tab-pane -->
	</div><!-- END .tab-content -->
</div><!-- END .tabbable -->

<?php 

ActiveForm::end();
echo Html::hiddenInput('question_count', $question_count, ['id' => 'question_count']);

echo Html::hiddenInput('isNewRecord', 0, ['id'=>'isNewRecord']);
echo Html::hiddenInput('item_id', $item_id, ['id'=>'item_id']);

$this->registerJsFile('@web/themes/default/plugins/ckeditor/ckeditor.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/vendor_item_validation.js?v=1.21", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/vendor_item_steps/price.js?v=1.7", ['depends' => [\yii\web\JqueryAsset::className()]]);