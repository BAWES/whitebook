<?php

$this->title = 'Vendor Inventory';
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$form = ActiveForm::begin([
    'id' => 'inventory-form'
]) ?>
    <div class="vendoritem-index clearfix" style="margin-bottom:20px;">
        <div class="col-md-3">
            <input type="text" name="date" id="date" value="<?=$date?>" />
        </div>
        <div class="col-md-3">
            <?=\yii\helpers\Html::dropDownList('item_id',$item_id,\yii\helpers\ArrayHelper::map(\common\models\VendorItem::findAll(['trash'=>'Default']),'item_id','item_name'),['prompt'=>'All Items'])?>
        </div>
        <div class="col-md-3">
            <?= Html::submitButton('Login', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
<?php ActiveForm::end() ?>
    <div class="inventory-index">
        <?php
        echo yii\grid\GridView::widget([
            'dataProvider' => $provider,
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
                'item_id',
                'item_name',
                [
                    'label' =>'Total Sale',
                    'value' => function($model) {
                        $date = (Yii::$app->request->post('date')) ? Yii::$app->request->post('date') : date('Y-m-d');
                        return $model->getSoldItems($model,$date);
                    }
                ],
                [
                    'label' =>'Items in Stock',
                    'value' => function($model) {
                        $date = (Yii::$app->request->post('date')) ? Yii::$app->request->post('date') : date('Y-m-d');
                        return $model->getItemInStock($model,$date);
                    }
                ]
            ]
        ]);

        $this->registerJsFile('@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

        $this->registerCssFile('@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.min.css', ['depends' => [\yii\web\JqueryAsset::className()]]);

        $this->registerJs("
            jQuery('#date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        ", View::POS_READY);

        ?>
    </div>

