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
			<h1><?= Yii::t('frontend', 'Request(s)'); ?></h1>
		</div>
		<div class="account_setings_sections clearfix">
			<?=$this->render('/users/_sidebar_menu');?>
			<div class="col-md-9">
				<?php if($orders) { ?>
					<table class="table table-bordered cart-table request-table">
						<thead>
							<tr>
								<td align="center"><?= Yii::t('frontend', 'Request ID') ?></td>
								<td align="center"><?= Yii::t('frontend', 'Sent On') ?></td>
								<td align="center"><?= Yii::t('frontend', 'Total Product') ?></td>
								<td align="center"><?= Yii::t('frontend', 'View') ?></td>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($orders as $order) {
                            if (isset($order->requestStatus) && count($order->requestStatus)>0) {
                                ?>
                                <tr>
                                    <td align="center"><?= $order->order_id ?></td>
                                    <td align="center"><?= date('d/m/Y', strtotime($order->created_datetime)) ?></td>
                                    <td align="center"><?= count($order->requestStatus) ?></td>
                                    <td width="50px">
                                        <a href="<?= Url::to(['orders/requested-products', 'request_id' => $order->order_id]) ?>"
                                           class="btn btn-primary" title="<?= Yii::t('frontend', 'View Order') ?>">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php }
                        }
						?>
						</tbody>
					</table>

					<center>
						<?= LinkPager::widget([
								'pagination' => $pagination,
							]); ?>
					</center>

				<?php } else { ?>

					<center><?= Yii::t('frontend', 'You have not placed any request yet!') ?></center>
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