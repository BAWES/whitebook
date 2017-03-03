<?php 

use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\models\Order;
use common\components\CFormatter;

$this->title = Yii::t('frontend', 'Orders | Whitebook'); 

?>

<section id="inner_pages_sections">
    <div class="container ">
       
        <div class="title_main">
			<h1><?= Yii::t('frontend', 'Order'); ?></h1>
		</div>

		<div class="account_setings_sections">
			<?=$this->render('/users/_sidebar_menu');?>
			<div class="col-md-9 border-left">
				<?php if($orders) { ?>

					<table class="table table-bordered cart-table">
						<thead>
							<tr>
								<td align="center"><?= Yii::t('frontend', 'Order ID') ?></td>
								<td align="left"><?= Yii::t('frontend', 'Date Added') ?></td>
								<td align="right"><?= Yii::t('frontend', 'Total') ?></td>
								<td></td>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($orders as $order) { ?>
							<tr>
								<td align="center"><?= $order->order_id ?></td>
								<td align="left"><?= date('d/m/Y', strtotime($order->created_datetime)) ?></td>
								<td align="right"><?= CFormatter::format($order->order_total_with_delivery) ?></td>
								<td width="50px">
									<a href="<?= Url::to(['orders/view', 'order_uid' => $order->order_uid]) ?>" class="btn btn-primary" title="<?= Yii::t('frontend', 'View Order') ?>">
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
				<?php } ?>
			</div>
		</div>
    </div>
</section>

<?php $this->registerCss("
table{    font-size: 12px;}
.header-updated{padding-bottom:0; margin-bottom: 0;}
.body-updated{background: white; margin-top: 0;}
#inner_pages_sections .container{background:#fff; margin-top:12px;padding-bottom: 18px;}
.border-left{border-left: 1px solid #e2e2e2;}

");