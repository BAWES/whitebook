<?php 

use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\models\Order;
use common\components\CFormatter;

$this->title = Yii::t('frontend', 'Orders | Whitebook'); 

?>

<section id="inner_pages_white_back">
    <div class="container paddng0">
       
        <div class="title_main">
			<h1><?= Yii::t('frontend', 'Orders'); ?></h1>
		</div>

		<br />
		<br />
		<br />

        <?php if($orders) { ?>

        	<table class="table table-bordered cart-table">
		        <thead>
		        	<tr>
		        		<td align="center"><?= Yii::t('frontend', 'Order ID') ?></td>
		        		<td align="left"><?= Yii::t('frontend', 'Date Added') ?></td>
		        		<td align="right" class="hidden-xs hidden-sm">
		        			<?= Yii::t('frontend', 'No. of Products') ?>
		        		</td>
		        		<td align="right"><?= Yii::t('frontend', 'Total') ?></td>
		        		<td></td>
		        	</tr>
		        </thead>
		        <tbody>
			    <?php foreach ($orders as $order) { ?>
				    <tr>
				    	<td align="center"><?= $order->order_id ?></td>
		        		<td align="left"><?= date('d/m/Y', strtotime($order->created_datetime)) ?></td>
		        		<td align="right" class="hidden-xs hidden-sm"><?= Order::itemCount($order->order_id) ?></td>
		        		<td align="right"><?= CFormatter::format($order->order_total_with_delivery) ?></td>
		        		<td width="50px">
		        			<a href="<?= Url::to(['orders/view', 'order_id' => $order->order_id]) ?>" class="btn btn-primary" title="<?= Yii::t('frontend', 'View Order') ?>">
		        				<i class="glyphicon glyphicon-eye-open"></i>
		        			</a>
		        		</td>
				    </tr>
	        	<?php } ?>
	        	</tbody>
	        </table>

	        <center>
	        	<?= LinkPager::widget([
				    	'pagination' => $pagination,
				  	]); ?>
	        </center>
    
        <?php } else { ?>
        	
        	<center><?= Yii::t('frontend', 'You have not placed any order yet!') ?></center>
        	<br />
        	<br />
        	<br />
        	<br />
        	<br />

        <?php } ?>
    </div>
</section>        

