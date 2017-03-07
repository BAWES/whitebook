<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;

/* @var $searchModel common\models\OrderRequestStatusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Request');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-request-status-index">

    <?= $this->render('_search', [
            'arr_vendor' => $arr_vendor,
            'model' => $searchModel
        ]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            'booking_id',
            'customer_name',
            'customer_lastname',
            'vendor.vendor_name',
            [
                'label' => 'Request Item',
                'value' => function ($model) {        
                    if($model->bookingItems) {
                        return $model->bookingItems[0]->item_name;
                    }                            
                }
            ],
            'created_datetime:date',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view} {approve} {reject}',
                'buttons' => [
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
                ]
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
    });

    jQuery(document).delegate('.glyphicon-remove', 'click', function(e){
        jQuery('#form_booking_reject').attr('action', jQuery(this).parent().attr('href'));
        jQuery('#booking_modal').modal('show');
        e.preventDefault();
    });
", View::POS_READY);
