<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Order;
use common\models\Vendor;
use common\models\Siteinfo;
use common\models\OrderStatus;
use common\models\SuborderItemMenu;
use common\models\SuborderItemPurchase;
use common\components\CFormatter;

?>
<tr>
    <td width="20"></td>
    <td style=" font:normal 14px/21px arial; color:#333333;">
        Hi <?= $customer->customer_name ?>,
    </td>
    <td width="20"></td>
</tr>
<tr height="5"></tr>
<tr>
    <td width="20"></td>
    <td style=" font:normal 15px arial; color:#333333;">
        Your order item payment processed. 

        <br />
        <br />

        <a href="<?= Url::to(['order/view', 'order_uid' => $model->order->order_uid]); ?>" style="background-color:#EB7035;border:1px solid #EB7035;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:44px;text-align:center;text-decoration:none;width:150px;">Track order &rarr;</a>
        
        <br />
        <br />
    </td>
    <td width="20"></td>
</tr>
<tr>
    <td width="20"></td>
    <td style=" font:normal 15px arial; color:#333333;">

    	<br />

        <table class="table table-bordered" style="width:100%;">
	        <tr>
	            <td style="border-right: 1px solid #ddd; border-bottom: 1px solid #ddd; border-top: 1px solid #ddd;">
	                Order ID: #<?= $model->order_id ?> 
	            </td>
	            <td style="border-bottom: 1px solid #ddd; border-top: 1px solid #ddd;">
	                Order UID: #<?= $model->order->order_uid ?> 
	            </td>
	        </tr>
	        <tr>
	            <td style="border-right: 1px solid #ddd; border-bottom: 1px solid #ddd; border-top: 1px solid #ddd;">
	                Sub Order ID: #<?= $model->suborder_id ?> 
	            </td>
	            <td style="border-bottom: 1px solid #ddd; border-top: 1px solid #ddd;">
	                Payment method: <?= $model->suborder_payment_method ?>
	            </td>
	        </tr>
	        <tr>
	            <td style="border-right: 1px solid #ddd; border-bottom: 1px solid #ddd; border-top: 1px solid #ddd;"> 
	            	Transaction ID: #<?= $model->suborder_transaction_id ?>
	            </td>
	            <td style="border-bottom: 1px solid #ddd;">
	                Date: <?= date('d/m/Y', strtotime($model->created_datetime)) ?>                
	            </td>
	        </tr>    
	    </table>
	    
	    <?php 

	        $vendor = Vendor::findOne($model->vendor_id);

	    ?>

	    <br />

	    <table class="table table-bordered" style="width:100%;">
	        <thead>
	            <tr>
	                <td colspan="2" style="border-top: 1px solid #ddd; border-bottom: 1px solid #DDDDDD;">
	                    <b><?= $vendor->vendor_name; ?></b>
	                </td>
	            </tr>
	        </thead>
	        <tbody>
	            <tr>
	                <td style="border-right: 1px solid #ddd;">
	                   <?= Yii::t('frontend', 'Order status') ?>: 
	                    <?php if(Yii::$app->language == 'en') { 
	                            echo OrderStatus::findOne($model->status_id)->name;
	                          } else {
	                            echo OrderStatus::findOne($model->status_id)->name_ar;
	                          } ?>  
	                </td>
	                <td>
	                    <?= Yii::t('frontend', 'Contact Email') ?>: <?= $vendor->vendor_public_email ?>
	                </td>
	            </tr>
	        </tbody>
	    </table>

	    <br />

	    <table class="table table-bordered" style="width:100%;">
	        <thead>
	            <tr>
	                <td align="left" style="border-top: 1px solid #ddd; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
	                	<b><?= Yii::t('frontend', 'Item Name') ?></b></th>
	                <td align="left" style="border-top: 1px solid #ddd; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
	                	<b><?= Yii::t('frontend', 'Delivery Address') ?></b></th>
	                <td align="right" style="border-top: 1px solid #ddd; border-bottom: 1px solid #DDDDDD;">
	                	<b><?= Yii::t('frontend', 'Total') ?></b></th>   
	            </tr>
	        </thead>    
	        <tbody>
	        <?php foreach (Order::subOrderItems($model->suborder_id) as $item) { ?>
	            <tr>
	                <td align="left" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
	                    <?php if(Yii::$app->language == 'en') {
	                        echo $item->vendoritem->item_name;
	                    } else {
	                        echo $item->vendoritem->item_name_ar; 
	                    } 
	                    
	                    $menu_items = SuborderItemMenu::findAll(['purchase_id' => $item->purchase_id]);

	                    foreach ($menu_items as $key => $menu_item) { 
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
	                    <br />
	                    x <?= $item->purchase_quantity ?>
	                </th>
	                <td aligh="left" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
	                	<?= $item->purchase_delivery_address ?>
	                	<br />
	                	
	                	<?= date('d/m/Y', strtotime($item->purchase_delivery_date)) ?>

	                	<br />

	                	<?= $item->time_slot ?>

	                </th>
	                <td align="right" style="border-bottom: 1px solid #DDDDDD;">
	                	<?= $item->purchase_total_price ?> KWD</th>   
	            </tr>
	            <?php } ?>

	            <tr>
	                <td align="right" colspan="2" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;"><b>Sub Total</b></td>
	                <td align="right" style="border-bottom: 1px solid #DDDDDD;"><?= $model->suborder_total_without_delivery ?> KWD</td>
	            </tr>
	            <tr>
	                <td align="right" colspan="2" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;"><b>Delivery Charge</b></td>
	                <td align="right" style="border-bottom: 1px solid #DDDDDD;"><?= $model->suborder_delivery_charge ?> KWD</td>
	            </tr>
	            <tr>
	                <td align="right" colspan="2" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;"><b>Total</b></td>
	                <td align="right" style="border-bottom: 1px solid #DDDDDD;"><?= $model->suborder_total_with_delivery ?> KWD</td>
	            </tr>
	        </tbody>
	    </table>

    </td>
    <td width="20"></td>
</tr>





       