<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Booking;
use common\models\Vendor;
use common\models\BookingStatus;
use common\models\SuborderItemPurchase;
use common\models\SuborderItemMenu;
use common\components\CFormatter;

$this->title = 'Booking #' . $model->booking_id;
$this->params['breadcrumbs'][] = ['label' => 'Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="order-view">
        <?php /*Html::a('Delete', ['delete', 'id' => $model->booking_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])*/ ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'booking_id',
            'customer_name',
            'customer_lastname',
            'customer_email',
            'customer_mobile',
            //'statusName',
            'booking_note',
            'expired_on',
            'notification_status',
            'total_delivery_charge',
            'total_without_delivery',
            'total_with_delivery',
            'commission_percentage',
            'commission_total',
            'gateway_percentage',
            'gateway_fees',
            'gateway_total',
            'ip_address',
            'created_datetime',
            'modified_datetime'
        ],
    ]) ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <td colspan="2">
                <?php if(Yii::$app->language == 'en') { 
                        echo $model->vendor->vendor_name;
                      } else { 
                        echo $model->vendor->vendor_name_ar;
                      } ?>
                </td>
            </tr>
        </thead>
        <tbody>            
            <tr>
                <td>
                    <div class="order_status_wrapper" data-id="<?= $model->booking_id ?>">
                        <?= Yii::t('frontend', 'Booking status') ?>: 
                        <span>
                        <?= $model->statusName ?>
                        </span> 
                    </div>
                </td>
                <td>
                    <?= Yii::t('frontend', 'Contact Email') ?>: <?= $model->vendor->vendor_public_email ?> 
                </td>
            </tr>
            <tr>                    
                <td data-id="<?= $model->booking_id ?>">
                    Payment method :
                    <span class="payment_method_<?= $model->booking_id ?>"><?= $model->payment_method ?></span>
                    <a data-toggle="modal" href="#payment_method_modal" class="btn btn-default edit_payment pull-right">
                        <i class="glyphicon glyphicon-pencil"></i>
                    </a>
                </td>
                <td>
                    Transaction ID : <?= $model->transaction_id ?>
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
        <?php foreach ($model->bookingItems as $item) { ?>
            <tr>
                <td align="left">
                    
                    <a target="_blank" href="<?= Url::to(['vendor-item/view', 'id' => $item->item_id]) ?>" style="padding: 0"><b><?= $item->item_name ?></b></a>
                    
                    <?php

                    foreach ($item->bookingItemMenus as $key => $menu_item) { 
                        echo '<div class="clearfix"></div> - <i class="cart_menu_item">'.$menu_item['menu_item_name'].' x '.$menu_item['quantity'];

                        $menu_item_total = $menu_item['quantity'] * $menu_item['price'];

                        if($menu_item_total) {
                            echo ' = '.CFormatter::format($menu_item_total);    
                        }
                        
                        echo '</i>';
                    } 

                    if($item['female_service']) {
                        echo '<div class="clearfix"></div> - <i class="cart_menu_item">'.Yii::t('frontend', 'Female service').'</i>';
                    }

                    if($item['special_request']) {
                        echo '<div class="clearfix"></div> - <i class="cart_menu_item">'.$item['special_request'].'</i>';
                    }
                    
                    ?>
                </th>
                <td align="left">
                    <?= date('d/m/Y', strtotime($item->delivery_date)) ?>
                    <br />

                    <?= $item->timeslot ?>
                </td>
                <td aligh="left"><?= $item->delivery_address ?></td>
                <td aligh="left"><?= $item->quantity ?></td>
                <td align="right"><?= $item->price ?> KWD</td>
                <td align="right"><?= $item->total ?> KWD</td>   
            </tr>
            <?php } ?>
            <tr>
                <td align="right" colspan="5">Sub Total</td>
                <td align="right"><?= $model->total_without_delivery ?> KWD</td>
            </tr>
            <tr>
                <td align="right" colspan="5">Delivery Charge</td>
                <td align="right"><?= $model->total_delivery_charge ?> KWD</td>
            </tr>
            <tr>
                <td align="right" colspan="5">Total</td>
                <td align="right"><?= $model->total_with_delivery ?> KWD</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="modal fade" id="payment_method_modal">
  <div class="modal-dialog" role="document">
      <form class="payment_method_form" id="payment_method_form" action="#" method="POST">
        <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Payment Made by</h4>
              </div>
              <div class="modal-body">
                    <input type="hidden" name="booking_id" value="" id="booking_id" />
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                    <select name="mode" class="form-control" id="mode">
                        <option val="0">Please select Payment Mode</option>
                        <option val="Cash">Cash</option>
                        <option val="Cheque">Cheque</option>
                        <option val="Tap - Paid with KNET">Tap - Paid with KNET</option>
                    </select>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary save_payment">Submit</button>
              </div>
        </div><!-- /.modal-content -->
      </form>
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php 

$this->registerJs("
    jQuery('.edit_payment').click(function(){
        jQuery('input[name=\'booking_id\']').val(jQuery(this).parent().attr('data-id'));
    });
    
    jQuery('.save_payment').click(function(){

        jQuery(this).html('Sending Mail...');
        jQuery(this).attr('disabled', 'disabled');
        jQuery.post('".Url::to(["booking/booking-payment"])."', jQuery('#payment_method_form').serialize(), function(data){
            jQuery('#payment_method_modal').modal('hide');
            jQuery('.'+data).html(jQuery('#mode').val());
            jQuery('.save_payment').html('Submit');
            jQuery('.save_payment').removeAttr('disabled');
        });
    });
", View::POS_READY);
    