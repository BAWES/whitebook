<?php 

use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\models\OrderStatus;
use common\components\CFormatter;
use common\components\LangFormat;
$this->title = Yii::t('frontend', 'Booking | Whitebook');

?>

<section id="inner_pages_sections">
    <div class="container ">
       
        <div class="title_main">
			<h1><?= Yii::t('frontend', 'Booking'); ?></h1>
		</div>

		<div class="account_setings_sections">
			<?=$this->render('/users/_sidebar_menu');?>
			<div class="col-md-9 border-left">
				<?php if($bookings) { ?>

					<table class="table table-bordered cart-table">
						<thead>
							<tr>
								<td align="center"><?= Yii::t('frontend', 'Booking ID') ?></td>
								<td align="center"><?= Yii::t('frontend', 'Product Name') ?></td>
								<td align="left"><?= Yii::t('frontend', 'Delivery Date') ?></td>
								<td align="center"><?= Yii::t('frontend', 'Status') ?></td>
								<td align="right"><?= Yii::t('frontend', 'Total') ?></td>
								<td></td>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($bookings as $booking) { ?>
							<tr>
								<td align="center"><?= $booking->booking_id?></td>
								<td align="center"><?= (isset($booking->bookingItems->item_name)) ? $booking->bookingItems->item_name : '';?></td>
								<td align="left">
                                    <?= date('d/m/Y', strtotime($booking->bookingItems->delivery_date)) ?><br/>
                                    <?= $booking->bookingItems->timeslot ?>
                                </td>
								<td align="center">
                                    <span class="badge"><?=LangFormat::format(OrderStatus::findOne($booking->booking_status)->name,OrderStatus::findOne($booking->booking_status)->name_ar);?></span>
                                </td>
								<td align="right"><?= CFormatter::format($booking->total_with_delivery) ?></td>
								<td width="50px">
									<a href="<?= Url::to(['booking/view-pending', 'booking_id' => $booking->booking_id]) ?>" class="btn btn-primary" title="<?= Yii::t('frontend', 'View Booking') ?>">
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