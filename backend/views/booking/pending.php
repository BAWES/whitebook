<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

$this->title = Yii::t('app', 'Booking Request');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-request-status-index">

    <?= $this->render('_search-pending', [
        'model' => $searchModel,
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'booking_id',
                'label' => 'Booking Request ID',
            ],
            [
                'label' => 'Requested Item',
                'value' => function ($model) {
                    if (isset($model->bookingItems[0]->item_name)) {
                        return $model->bookingItems[0]->item_name;
                    }
                }
            ],
            [
                'label' => 'Delivery Date',
                'format' => 'html',
                'value' => function ($model) {
                    if (isset($model->bookingItems[0])) {
                        return date('d/m/Y', strtotime($model->bookingItems[0]->delivery_date))
                        .'<br/>'.$model->bookingItems[0]->timeslot;
                    }
                }
            ],
            [
                'attribute'=>'booking_status',
                'value' => function ($model) {
                    return $model->getStatusName();
                }
            ],
            [
                'attribute'=>'total_with_delivery',
                'label' => 'Total',
                'value' => function ($model) {
                    return 'KD '.$model->total_with_delivery;
                }
            ],
            [
                'attribute'=>'created_datetime',
                'label' => 'Sent On',
                'value' => function ($model) {
                    return $model->created_datetime;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {approve} {reject}',
                'buttons' => [
                    'view' => function ($url,$model) {
                        $url = \yii\helpers\Url::to(['view-pending','id'=>$model->booking_id]);
                        return \yii\helpers\Html::a('<span class="glyphicon glyphicon-eye-open"></span>',$url);
                    },
                    'approve' => function ($url, $model) {     
                        return Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, [
                                'title' => Yii::t('yii', 'Approve'),
                        ]);  
                    },
                    'reject' => function ($url, $model) {     
                        return Html::a('<span class="glyphicon glyphicon-remove"></span>', $url, [
                                'title' => Yii::t('yii', 'Reject'),
                        ]);  
                    },
                ],
            ],
        ],
    ]); ?>
</div>

<div class="modal fade" id="booking_modal">
  <div class="modal-dialog" role="document" style="max-width: 500px;">
    <div class="modal-content">
        <form id="form_booking_reject" method="post">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Reason to reject</h4>
            </div>
            <div class="modal-body">
                <textarea class="form-control" name="booking_note" required></textarea>            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary save_status">Submit</button>
            </div>
        </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php 

$this->registerJsFile('@web/themes/default/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerCssFile('@web/themes/default/plugins/bootstrap-datepicker/css/datepicker.min.css', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJs("
    jQuery('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });

    jQuery(document).delegate('.glyphicon-remove', 'click', function(e){
        jQuery('#form_booking_reject').attr('action', jQuery(this).parent().attr('href'));
        jQuery('#booking_modal').modal('show');
        e.preventDefault();
    });
", View::POS_READY);
