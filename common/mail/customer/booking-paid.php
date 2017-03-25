<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Booking;
use common\models\Siteinfo;
use common\components\CFormatter;

?>
<tr>
    <td width="20"></td>
    <td style=" font:normal 14px/21px arial; color:#333333;">
        Hi <?= $model->customer_name ?>,
    </td>
    <td width="20"></td>
</tr>
<tr height="5"></tr>
<tr>
    <td width="20"></td>
    <td style=" font:normal 15px arial; color:#333333;">

    	<br />
        <br />

        Your booking item payment processed. 

        <br />
        <br />

        <a href="<?= Url::to(['booking/view', 'booking_token' => $model->booking_token], true); ?>" style="background-color:#EB7035;border:1px solid #EB7035;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:44px;text-align:center;text-decoration:none;width:150px;">Track Now &rarr;</a>
        
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
	                Booking ID: #<?= $model->booking_id ?> 
	            </td>
	            <td style="border-bottom: 1px solid #ddd; border-top: 1px solid #ddd;">
	                Booking Token : <?= $model->booking_token ?>
	            </td>
	        </tr>
	        <tr>
	            <td style="border-right: 1px solid #ddd; border-bottom: 1px solid #ddd;">
	                Payment method: <?= $model->payment_method ?>
	            </td>
	            <td style="border-bottom: 1px solid #ddd;">
	                Transaction ID: <?= $model->transaction_id ?>             
	            </td>
	        </tr>    
	    </table>

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
	                    <?= Yii::t('frontend', 'Booking status') ?>: 
	                    <?= $model->statusName ?>
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
	        <?php foreach ($model->bookingItems as $item) { ?>
	            <tr>
	                <td align="left" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
	                    
	                    <?php if(Yii::$app->language == 'en') {
	                        echo $item->item_name;
	                    } else {
	                        echo $item->item_name_ar; 
	                    }

	                    if ($item->item_base_price != '0.000') {
                            echo '<div class="clearfix"></div> - <i class="cart_menu_item">Base Price : KD '.$item->item_base_price.'</i>';
                        }
	                    
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
	                    <br />
	                    x <?= $item->quantity ?>
	                </th>
	                <td aligh="left" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
	                	<?= $item->delivery_address ?>
	                	<br />
	                	
	                	<?= date('d/m/Y', strtotime($item->delivery_date)) ?>

	                	<br />

	                	<?= $item->timeslot ?>

	                </th>
	                <td align="right" style="border-bottom: 1px solid #DDDDDD;">
	                	<?= $item->total ?> KWD</th>   
	            </tr>
	            <?php } ?>

	            <tr>
	                <td align="right" colspan="2" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;"><b>Sub Total</b></td>
	                <td align="right" style="border-bottom: 1px solid #DDDDDD;"><?= $model->total_without_delivery ?> KWD</td>
	            </tr>
	            <tr>
	                <td align="right" colspan="2" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;"><b>Delivery Charge</b></td>
	                <td align="right" style="border-bottom: 1px solid #DDDDDD;"><?= $model->total_delivery_charge ?> KWD</td>
	            </tr>
	            <tr>
	                <td align="right" colspan="2" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;"><b>Total</b></td>
	                <td align="right" style="border-bottom: 1px solid #DDDDDD;"><?= $model->total_with_delivery ?> KWD</td>
	            </tr>
	        </tbody>
	    </table>

    </td>
    <td width="20"></td>
</tr>





       