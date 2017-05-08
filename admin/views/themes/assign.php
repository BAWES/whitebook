<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Assign Theme';
$this->params['breadcrumbs'][] = ['label' => 'Assign Themes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$list = [];
if ($model->vendorItemThemes) {
    $list = \yii\helpers\ArrayHelper::map($model->vendorItemThemes, 'item_id', 'item_id');
}
$items = \yii\helpers\ArrayHelper::map(\common\models\VendorItem::find()->asArray()->all(),'item_id','item_name');
?>
<div class="themes-assign">
    <div class="themes-form">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <?php $form = ActiveForm::begin(); ?>
            <div class="form-group"><h3><strong>Theme :</strong> <?= $model->theme_name?></h3></div>
            <div class="padding-top-bottom form-group clearfix item-listing"><?php echo Html::checkboxList('items',$list,$items)?></div>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <?= Html::a('Back', ['index', ], ['class' => 'btn btn-defauult']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>