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
        Hi Vendor,
    </td>
    <td width="20"></td>
</tr>
<tr height="5"></tr>
<tr>
    <td width="20"></td>
    <td style=" font:normal 15px arial; color:#333333;">
        New booking request(s) registered.
    </td>
    <td width="20"></td>
</tr>

<tr>
    <td width="20"></td>
    <td style=" font:normal 15px arial; color:#333333;">

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
		            <td style="border-right: 1px solid #ddd; border-bottom: 1px solid #ddd;">
		                Customer: <?= $arr_booking[0]->customer_name ?>
		            </td>
		        </tr>  
	        </tbody>  
	    </table>
	</td>
</tr>

<?php foreach($arr_booking as $booking) { ?>
<tr>
    <td width="20"></td>
    <td style=" font:normal 15px arial; color:#333333;">

    	<br />

        <table class="table table-bordered" style="width:100%;">
	        <tr>
	            <td style="border-right: 1px solid #ddd; border-bottom: 1px solid #ddd; border-top: 1px solid #ddd;">
	                Booking ID: #<?= $booking->booking_id ?> 
	            </td>
	            <td style="border-bottom: 1px solid #ddd; border-top: 1px solid #ddd;">
	                Booking Token : <?= $booking->booking_token ?>
	            </td>
	        </tr>
	        <tr>
	            <td style="border-right: 1px solid #ddd; border-bottom: 1px solid #ddd;">
                    <?= Yii::t('frontend', 'Booking status') ?>: 
                    <?= $booking->statusName ?>	
                </td>
	            </td>
	            <td style="border-bottom: 1px solid #ddd;">
	                Date: <?= date('d/m/Y', strtotime($booking->created_datetime)) ?>                
	            </td>
	        </tr>   
	        <tr>
                <?php if (Vendor::vendorManageBy($arr_booking[0]->vendor_id) == 'vendor') { ?>
                    <td style="border-right: 1px solid #ddd; border-bottom: 1px solid #ddd;">
                        <a href="<?= Yii::$app->urlManagerVendor->createUrl(['booking/status', 'token' => $booking->booking_token,'action'=>'1'], true); ?>" style="background-color:#008a00;border:1px solid #008a00;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:44px;text-align:center;text-decoration:none;width:150px;">Accept</a>
                    </td>
                    <td style="border-bottom: 1px solid #ddd;">
                        <a href="<?= Yii::$app->urlManagerVendor->createUrl(['booking/status', 'token' => $booking->booking_token,'action'=>'0'], true); ?>" style="background-color:#ff0000;border:1px solid #ff0000;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:44px;text-align:center;text-decoration:none;width:150px;">Reject</a>
                    </td>
                <?php } else { ?>
                    <td style="border-right: 1px solid #ddd; border-bottom: 1px solid #ddd;">
                        <a href="<?= Yii::$app->urlManagerAdmin->createUrl(['booking/status', 'token' => $booking->booking_token,'action'=>'1'], true); ?>" style="background-color:#008a00;border:1px solid #008a00;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:44px;text-align:center;text-decoration:none;width:150px;">Accept</a>
                    </td>
                    <td style="border-bottom: 1px solid #ddd;">
                        <a href="<?= Yii::$app->urlManagerAdmin->createUrl(['booking/status', 'token' => $booking->booking_token,'action'=>'0'], true); ?>" style="background-color:#ff0000;border:1px solid #ff0000;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:44px;text-align:center;text-decoration:none;width:150px;">Reject</a>
                    </td>
                <?php } ?>
	        </tr>  
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
	        <?php foreach ($booking->bookingItems as $item) { ?>
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

	                    $menu_addon_items = $item->bookingItemAddons;

                        $menu_option_items = $item->bookingItemOptions;

                        if($menu_option_items)
                        {
                        	echo '<div class="clearfix"></div><b>Options</b>';
                        }

	                    foreach ($menu_option_items as $key => $menu_item) { 
	                        echo '<div class="clearfix"></div> - <i class="cart_menu_item">'.$menu_item['menu_item_name'].' x '.$menu_item['quantity'];

                            $menu_item_total = $menu_item['quantity'] * $menu_item['price'];

                            if($menu_item_total) {
                                echo ' = '.CFormatter::format($menu_item_total);    
                            }
                            
                            echo '</i>';
	                    } 

	                    if($menu_addon_items)
                        {
                        	echo '<div class="clearfix"></div><b>Add-Ons</b><br/>';
                        }

                        foreach ($menu_addon_items as $key => $menu_item) { 

	                        echo '<div class="clearfix"></div> - <i class="cart_menu_item">'.$menu_item['menu_item_name'].' x '.$menu_item['quantity'];

                            $menu_item_total = $menu_item['quantity'] * $menu_item['price'];

                            if($menu_item_total) {
                                echo ' = '.CFormatter::format($menu_item_total);    
                            }
                            
                            echo '</i>';
	                    }


                        if($booking->bookingItemAnswers)
                        {
                            echo '<div class="clearfix"></div><b>Custom</b><br/>';

                            $q =1;
                            foreach($booking->bookingItemAnswers as $answer) {
                                echo "Question $q: <i>".$answer->question.'</i>';
                                echo "<br/>answer $q: <i>".$answer->answer.'</i><br/>';
                                $q++;
                            }
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
	                <td align="right" style="border-bottom: 1px solid #DDDDDD;"><?= $booking->total_without_delivery ?> KWD</td>
	            </tr>
	            <tr>
	                <td align="right" colspan="2" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;"><b>Delivery Charge</b></td>
	                <td align="right" style="border-bottom: 1px solid #DDDDDD;"><?= $booking->total_delivery_charge ?> KWD</td>
	            </tr>
	            <tr>
	                <td align="right" colspan="2" style="border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;"><b>Total</b></td>
	                <td align="right" style="border-bottom: 1px solid #DDDDDD;"><?= $booking->total_with_delivery ?> KWD</td>
	            </tr>
	        </tbody>
	    </table>

    </td>
    <td width="20"></td>
</tr>
<?php } ?>
