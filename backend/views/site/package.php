<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$this->title = 'Choose Package';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="grid simple package_grid">
	<div class="grid-body no-border"> <br>
		<div class="row">
			<?php foreach($package as $pack=>$val) {?>
				<div class="col-md-4 col-sm-6 plan-tile plan-pro">
					<div class="pro-plan-wrap">
						<h3 class="plan-title text-center" style="min-height: 33px;"><?php echo $val['package_name']; ?></h3>
						<p class="plan-text">Package price : <?php echo $val['package_pricing']; ?> <?php echo CURRENCY; ?></p>
						<p class="plan-text">Package days : <?php echo $val['package_days']; ?></p>
						<p class="h6">Package maximum listings : <?php echo $val['package_max_number_of_listings']; ?></p>
						
						<form id="login-forms" method="post" action = '<?= Url::to(['site/package']) ?>'>
							<input type="hidden" name="package_value" value="<?php echo $val['package_pricing'];?>">
							<input type="hidden" name="package_id" value="<?php echo $val['package_id'];?>">
							<input type="hidden" name="package_name" value="<?php echo $val['package_name'];?>">
							<input type="hidden" name="package_days" value="<?php echo $val['package_days'];?>">
							<input type="hidden" name="package_max_number_of_listings" value="<?php echo $val['package_max_number_of_listings'];?>">
							<input id="product-D" data-ci="90621" data-plan="host_GridHostStrDiabloLin1Yr_in" class="btn btn-purchase btn-plan btn-lg btn-block" type="submit" value="Subscribe Now">
						</form>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>

<?php echo $this->registerCss("
	.package_grid .pro-plan-wrap{border: 2px solid #efefef;padding: 0 15px 10px;margin:0 0 30px;}
	.package_grid .pro-plan-wrap .plan-title{color: #000000;display: inline-block;font-size: 20pt;line-height: 20px;font-weight: 700;width: 100%;}
	.package_grid .pro-plan-wrap .plan-price-wrap {padding-bottom: 30px;}
	.package_grid .pro-plan-wrap p.plan-text,.package_grid .pro-plan-wrap p.h6 {color: #808080;cursor: default;font-size: 11pt;line-height: 20px; font-weight: 500;}
	.package_grid .btn-group-lg > .btn,.package_grid .btn-lg{background: #c4c4c4 none repeat scroll 0 0;border-radius: 0; color: #fff;display: inline-block;font-size: 12pt;
	font-weight: 700;padding: 10px 0; text-align: center;text-transform: uppercase;width: 100%;border:none;margin:20px 0 0;}
	.package_grid .btn-group-lg > .btn:hover,.package_grid .btn-lg:hover{background: #ebc000 none repeat scroll 0 0;}
");
?>
