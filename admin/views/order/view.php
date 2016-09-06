<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Order;
use common\models\Vendor;
use common\models\OrderStatus;
use common\models\SuborderItemPurchase;

$this->title = 'Order #' . $model->order_id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="order-view">

    <p>
        <?= Html::a('Delete', ['delete', 'id' => $model->order_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'order_id',
            'customerName',
            'order_total_delivery_charge',
            'order_total_without_delivery',
            'order_total_with_delivery',
            'order_payment_method',
            'order_transaction_id',
            'order_gateway_percentage',
            'order_gateway_total',
            'order_ip_address',
            'created_datetime',
            'modified_datetime'
        ],
    ]) ?>

    <?php 

        foreach($suborder as $row){

            $vendor = Vendor::findOne($row->vendor_id);

        ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <td colspan="2">
                    <?php if(Yii::$app->language == 'en') { 
                            echo $vendor->vendor_name;
                          } else { 
                            echo $vendor->vendor_name_ar;
                          } ?>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="order_status_wrapper" data-id="<?= $row->suborder_id ?>">
                            <?= Yii::t('frontend', 'Order status') ?>: 
                            <span>
                            <?php if(Yii::$app->language == 'en') { 
                                    echo OrderStatus::findOne($row->status_id)->name;
                                  } else {
                                    echo OrderStatus::findOne($row->status_id)->name_ar;
                                  } ?>  
                            </span>      
                            <a data-toggle="modal" href="#status_modal" class="edit_status">
                                <i class="glyphicon glyphicon-pencil"></i>
                            </a>
                        </div>
                    </td>
                    <td>
                        <?= Yii::t('frontend', 'Contact Email') ?>: <?= $vendor->vendor_public_email ?> <br />
                        <?= Yii::t('frontend', 'Contact Number') ?>: <?= $vendor->vendor_public_phone ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <td align="left"><?= Yii::t('frontend', 'Item Name') ?></th>
                    <td align="left"><?= Yii::t('frontend', 'Delivery Datetime') ?></th>
                    <td align="left"><?= Yii::t('frontend', 'Delivery Address') ?></th>
                    <td aligh="left"><?= Yii::t('frontend', 'Quantity') ?></th>
                    <td align="right"><?= Yii::t('frontend', 'Unit Price') ?></th>
                    <td align="right"><?= Yii::t('frontend', 'Total') ?></th>   
                </tr>
            </thead>    
            <tbody>
            <?php foreach (Order::subOrderItems($row->suborder_id) as $item) { ?>
                <tr>
                    <td align="left">
                        <?php if(Yii::$app->language == 'en') {
                            echo $item->vendoritem->item_name;
                        } else {
                            echo $item->vendoritem->item_name_ar; 
                        } ?>
                    </th>
                    <td align="left"><?= date('d/m/Y', strtotime($item->purchase_delivery_date)) ?></th>
                    <td aligh="left"><?= $item->purchase_delivery_address ?></th>
                    <td aligh="left"><?= $item->purchase_quantity ?></th>
                    <td align="right"><?= $item->purchase_price_per_unit ?> KWD</th>
                    <td align="right"><?= $item->purchase_total_price ?> KWD</th>   
                </tr>
                <?php } ?>
                <tr>
                    <td align="right" colspan="5">Sub Total</td>
                    <td align="right"><?= $row->suborder_total_without_delivery ?> KWD</td>
                </tr>
                <tr>
                    <td align="right" colspan="5">Delivery Charge</td>
                    <td align="right"><?= $row->suborder_delivery_charge ?> KWD</td>
                </tr>
                <tr>
                    <td align="right" colspan="5">Total</td>
                    <td align="right"><?= $row->suborder_total_with_delivery ?> KWD</td>
                </tr>
            </tbody>
        </table>
    <?php } ?>

</div>

<div class="modal fade" id="status_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Change Sub-order Status</h4>
      </div>
      <div class="modal-body">
            <input type="hidden" name="suborder_id" value="" id="suborder_id" />
            <select name="status_id" class="form-control" id="status_id">
            <?php foreach($status as $row) { ?>
                <option val="<?= $row->order_status_id ?>"><?= $row->name ?></option>
            <?php } ?>
            </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary save_status">Submit</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php 

$this->registerJs("
    jQuery('.edit_status').click(function(){
        jQuery('input[name=\'suborder_id\']').val(jQuery(this).parent().attr('data-id'));
    });

    jQuery('.save_status').click(function(){

        var status_id = jQuery('#status_id option:selected').attr('val');
        var status =  jQuery('#status_id option:selected').html();
        var suborder_id = jQuery('#suborder_id').val();

        jQuery.post('".Url::to(["order/order-status"])."', { 
            suborder_id : suborder_id,
            status_id : status_id
        }, function(){
            jQuery('#status_modal').modal('hide');
            jQuery('[data-id=\'' + suborder_id + '\'] span').html(status); 
        });
    });
", View::POS_READY);
    