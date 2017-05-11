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

$items = \common\models\VendorItem::find()
    ->select('{{%vendor}}.vendor_name, {{%vendor_item}}.item_id, {{%vendor_item}}.item_name')
    ->innerJoin('{{%vendor}}', '{{%vendor}}.vendor_id = {{%vendor_item}}.vendor_id')
    ->where([
        '{{%vendor_item}}.hide_from_admin' => 0,
        '{{%vendor_item}}.trash' => 'Default'
    ])
    ->orderBy('{{%vendor}}.vendor_name')
    ->asArray()
    ->all();

?>
<div class="themes-assign">
    <div class="themes-form">
        <div class="col-md-12 col-sm-12 col-xs-12">

            <div class="form-group pull-left"><h3><strong>Theme :</strong> <?= $model->theme_name?></h3></div>

            <div class="col-md-4 pull-right">
                <div class="input-group">
                  <input type="text" name="search" id="search" class="form-control" placeholder="Search for...">
                  <span class="input-group-btn">
                    <button class="btn btn-primary" type="button">Search</button>
                  </span>
                </div>
            </div>

            <div class="clearfix"></div>
            
            <?php $form = ActiveForm::begin(); ?>
            
            <table class="table table-bordered table-striped" id="tbl_items">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" name="chk_all" id="chk_all" />
                        </th>
                        <th>#</th>
                        <th>Vendor Name</th>
                        <th>Item Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=0; foreach ($items as $key => $value) { ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="items[]" value="<?= $value['item_id'] ?>" <?= in_array($value['item_id'], $list)?'checked':'' ?> />
                        </td>
                        <td><?= $i ?></td>
                        <td><?= $value['vendor_name'] ?></td>
                        <td><?= $value['item_name'] ?></td>
                    </tr>
                    <?php $i++; } ?>
                </tbody>
            </table>
           
            <div class="form-group">
                <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Back', ['index'], ['class' => 'btn btn-defauult']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


<?php 

$this->registerJsFile("@web/themes/default/js/theme_assign.js?v=1.1", ['depends' => [\yii\web\JqueryAsset::className()]]);
