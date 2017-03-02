<?php 

use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\models\Order;
use common\components\CFormatter;

$this->title = Yii::t('frontend', 'Request Product | Whitebook');
?>
<section id="inner_pages_sections">
    <div class="container ">
        <div class="title_main">
			<h1><?= Yii::t('frontend', 'Requested Product'); ?></h1>
		</div>
		<div class="account_setings_sections clearfix">
			<?=$this->render('/users/_sidebar_menu');?>
			<div class="col-md-9">
				<?php if($orders) { ?>
					<table class="table table-bordered cart-table request-table">
						<thead>
							<tr>
								<td align="center"><?= Yii::t('frontend', 'Request Token') ?></td>
								<td align="center"><?= Yii::t('frontend', 'Product Name') ?></td>
								<td align="center"><?= Yii::t('frontend', 'Status') ?></td>
								<td align="center"><?= Yii::t('frontend', 'Price') ?></td>
								<td></td>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($orders as $order) { ?>
                                <tr>
                                    <td align="center"><?= $order->request_token ?></td>
                                    <td align="center"><?= \yii\helpers\Html::a($order->itemPurchased->vendoritem->item_name,['browse/detail','slug'=>$order->itemPurchased->vendoritem->slug],['target'=>'_blank']) ?></td>
                                    <td align="center">
                                        <?php
                                        $class = '';
                                        if ($order->request_status == 'Approved') {
                                            $class = 'success';
                                        } else if ($order->request_status == 'Declined') {
                                            $class = 'warning';
                                        }
                                        ?>
                                        <span class="badge <?=$class?>"><?= $order->request_status ?></span>
                                    </td>
                                    <td align="center">KD <?= $order->itemPurchased->vendoritem->item_price_per_unit  ?> / Unit</td>
                                    <td align="center">
                                    	<a href="<?= Url::to(['payment/index', 'token' => $order->request_token]) ?>" class="btn btn-default btn-sm"><?= Yii::t('frontend', 'Pay Now') ?>
                                    	</a>
                                    </td>
                                </tr>
                            <?php
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
                <br/>
                <?=\yii\helpers\Html::a('Back',['orders/request-order'],['class'=>'btn btn-default'])?>
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