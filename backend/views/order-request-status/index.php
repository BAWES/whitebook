<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Order;
/* @var $this yii\web\View */
/* @var $searchModel common\models\OrderRequestStatusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Request');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-request-status-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            'request_id',
            [
                'label' => 'Request Item',
                'value' => function ($model) {
                    return (strlen($model->orderDetail->subOrder->itemPurchased->item->item_name)>35) ?
                        substr($model->orderDetail->subOrder->itemPurchased->item->item_name,0,35).'...' :
                        $model->orderDetail->subOrder->itemPurchased->item->item_name;
                }
            ],
            [
                'label' => 'Remain Quantity',
                'format' => 'html',
                'value' => function ($model) {
                    return '<strong>'.Order::totalPendingItem(
                            $model->orderDetail->subOrder->itemPurchased->item_id,
                        $model->orderDetail->subOrder->itemPurchased->purchase_delivery_date,
                        $model->orderDetail->subOrder->itemPurchased->item->item_default_capacity
                    ).'</strong>';
                }
            ],
            [
                'attribute'=>'request_status',
                'filter'=>['Approved'=>'Approved','Pending'=>'Pending','Declined'=>'Declined']
            ],
            'created_datetime:date',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view}'
            ],
        ],
    ]); ?>
</div>