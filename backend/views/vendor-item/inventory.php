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
            <?=\yii\helpers\Html::dropDownList('item_id',$item_id,\yii\helpers\ArrayHelper::map(\common\models\VendorItem::findAll(['trash'=>'Default','vendor_id' => Yii::$app->user->getId()]),'item_id','item_name'),['prompt'=>'All Items'])?>
        </div>
        <div class="col-md-3">
            <?= Html::submitButton('Show Detail', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
<?php ActiveForm::end() ?>
    <div class="inventory-index">
        <?php
        echo yii\grid\GridView::widget([
            'dataProvider' => $provider,
            'columns' => [
                'item_id',
                'item_name',
                [
                    'label' =>'Capacity left for that day',
                    'value' => function($model) {
                        $date = (Yii::$app->request->post('date')) ? Yii::$app->request->post('date') : date('Y-m-d');
                        return $model->getItemInStock($model,$date);
                    }
                ],
                [
                    'label' =>'Item Sale for that day',
                    'value' => function($model) {
                        $date = (Yii::$app->request->post('date')) ? Yii::$app->request->post('date') : date('Y-m-d');
                        return $model->getSoldItems($model,$date);
                    }
                ],
                [
                    'label' =>'View Item',
                    'format' =>'raw',
                    'value' => function($model) {
                        return Html::a('<i class="glyphicon glyphicon-eye-open"></i>',['vendor-item/update','id'=>$model->item_id],['class'=>'btn btn-default','target'=>'_blank']);
                    },
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

        $this->registerCss("
        .table tr td:nth-child(3),.table tr td:nth-child(4),.table tr td:nth-child(5),
        .table tr th:nth-child(3),.table tr th:nth-child(4),.table tr th:nth-child(5)
         {
            text-align:center;
        }
        ");
        ?>
    </div>

