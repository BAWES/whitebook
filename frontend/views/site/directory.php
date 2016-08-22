<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\view;

?>

<!-- coniner start -->
<section id="inner_pages_white_back">

<div id="event_slider_wrapper">
	<div class="container paddng0">
		<?php require(__DIR__ . '/../product/events_slider.php'); ?>
	</div>
</div>

<div class="container paddng0">

<div class="directory_listing">
<div class="title_main">
	<h1><?= Yii::t("frontend", "Directory") ?></h1>
</div>
<div class="col-md-3 paddingleft0">
<div class="filter_content">
<div class="filter_section">
<span class="title_filter"><?= Yii::t("frontend", "Categories") ?></span>
<div class="listing_sub_cat">
<input type="hidden" id="ajaxdata" name="ajaxdata">
<select class="selectpicker" style="display: none;" id="filter_category" name="filter_category">
	<option value="All"><?= Yii::t("frontend", "All"); ?></option>
	<option data-icon="venues-category" value="venues"><?= Yii::t("frontend", "Venues") ?></option>
	<option data-icon="invitation-category" value="invitations"><?= Yii::t("frontend", "Invitations") ?></option>
	<option data-icon="food-category" value="food-beverage"><?= Yii::t("frontend", "Food & Beverage") ?></option>
	<option data-icon="decor-category" value="decor" ><?= Yii::t("frontend", "Decor") ?></option>
	<option data-icon="supply-category" value="supplies"><?= Yii::t("frontend", "Supplies") ?></option>
	<option data-icon="enter-category" value="entertainment"><?= Yii::t("frontend", "Entertainment") ?></option>
	<option data-icon="service-category" value="services"><?= Yii::t("frontend", "Services") ?></option>
	<option data-icon="others-category" value="others"><?= Yii::t("frontend", "Others") ?></option>
	<option data-icon="saythankyou-category" value="gift-favors"><?= Yii::t("frontend", "Gift Favors") ?></option>
</select>
</div>
</div>
</div>
</div>

<div id="mobile_respon">
	<div class="mobile-view col-xs-12 padding0 directory-responsive">
		<?php $fl = $first_letter;?>
		<div class="tabContainer">
			<ul id="demoOne" class="demo">
			<?php foreach($fl as $f) { ?>
				<li><h2><?php echo $f;?></h2></li>
				<?php foreach($directory as $d) {

				if(Yii::$app->language == "en") {
					$ltr = strtoupper(mb_substr($d['vname'], 0, 1, 'utf8'));
					$vname = strtoupper($d['vname']);
				}else{
					$ltr = strtoupper(mb_substr($d['vname_ar'], 0, 1, 'utf8'));
					$vname = strtoupper($d['vname_ar']);
				}

				if($ltr == $f) { ?>
				<li><?= Html::a($vname,Url::toRoute(['/site/vendor_profile/','slug'=>$d['slug']])) ?></li>
				<?php 
				} 

				}//foreach directory 
			}//foreach letter ?>
			</ul><!-- END #demoOne -->
		</div>
	</div>
</div>

<div id="filter">
<?php 

$total = count($directory);


$first = $total/3;
$second = $first+$first;
$third = $second+$first;
$k = $first_letter;
$l = $first_letter;?>

<!-- first section start here-->
<div class="resposive-clearfix">
	<div class="col-md-3 resposive-clearfix">
	<?php 

	$i=0;

	foreach($first_letter as $f) { ?>
		<?php if($i < $first){ ?>
			<div class="direct_list">
				<h2><?php echo $f;?></h2>
				<ul>
				<?php 

				foreach($directory as $d) {
				
					if(Yii::$app->language == "en") {
						$first_letter = strtoupper(mb_substr($d['vname'], 0, 1, 'utf8'));
						$vname = strtoupper($d['vname']);
					}else{
						$first_letter = strtoupper(mb_substr($d['vname_ar'], 0, 1, 'utf8'));
						$vname = strtoupper($d['vname_ar']);
					}

					if($first_letter==$f) {
						if($i < $first) { ?>
							<li><?= Html::a(
									$vname, Url::toRoute(['/site/vendor_profile/','slug'=>$d['slug']])
								) ?></li>
					<?php }  
					} 
				} ?>
				</ul>
			</div>
			<?php 
			} 
		$i++; 
	}
	?>
	</div>
	<!-- first section end here-->

	<!-- second section start here-->
	<div class="col-md-3">
	<?php 
	
	$i=0;
	
	foreach($k as $f) { 
	
	if(($i>=$first) && ($i<$second)) { ?>
	<div class="direct_list">
		<h2><?php echo $f; ?></h2>
		<ul>
			<?php 
			
			foreach($directory as $d) {
				
				if(Yii::$app->language == "en") {
					$first_letter = strtoupper(mb_substr($d['vname'], 0, 1, 'utf8'));
					$vname = strtoupper($d['vname']);
				}else{
					$first_letter = strtoupper(mb_substr($d['vname_ar'], 0, 1, 'utf8'));
					$vname = strtoupper($d['vname_ar']);	
				}

				if($first_letter == $f) { ?>
					<li>
						<?= Html::a(
								$vname, 
								Url::toRoute(['/site/vendor_profile/','slug'=>$d['slug']])
							) ?>
					</li>
					<?php  
				} 
			}
			?>
		</ul>
	</div>
	<?php 
	}//end if 
	$i++;
	}//end foreach 
	?>
	</div>
	<!-- second section end here-->

	<!-- Third section start here-->
	<div class="col-md-3 paddingright0">

	<?php 

	$i=0;

	foreach($l as $f) { 
		if(($i >= $second)&&($i < $third)) { ?>
		<div class="direct_list">
		<h2><?php echo $f;?></h2>
		<ul>
			<?php
		
			foreach($directory as $d) {
		
			if(Yii::$app->language == "en") {
				$first_letter = strtoupper(mb_substr($d['vname'], 0, 1, 'utf8'));
				$vname = strtoupper($d['vname']);
			}else{
				$first_letter = strtoupper(mb_substr($d['vname_ar'], 0, 1, 'utf8'));
				$vname = strtoupper($d['vname_ar']);
			}

			if($first_letter==$f) { ?>
				<li><?= Html::a(
						$vname,
						Url::toRoute(['/site/vendor_profile/','slug'=>$d['slug']])
					) ?></li>
				<?php  
			}//end if 
			}//end foreach 
			?>
		</ul>
		</div>
		<?php 
		}//end if 
		$i++;
	}//end foreach ?>
	</div><!-- END .col-md-3 -->
	<!-- Third section end here-->
</div><!-- END .resposive-clearfix -->	

</div><!-- END #filter -->

</div>
</div>
</section>

<?php 

$this->registerJsFile('@web/js/jquery-listnav.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile('@web/css/listnav.css?v=1.1');

$this->registerJs("
	jQuery('#demoOne').listnav({
		allText: '" . Yii::t('frontend', 'All') . "',
		noMatchText: '". Yii::t('frontend', 'No matching entries') ."'
	});

	jQuery('.demo a').click(function(e) {
		e.preventDefault();
	});

	jQuery('#filter_category').change(function(){

		var x= jQuery('#filter_category').val();
		var ajaxdata= jQuery('#ajaxdata').val();

		var path = '".Url::to('site/searchdirectory')."';
		
		jQuery.ajax({
			type:'POST',
			url:path,
			data:{ slug:x, ajaxdata:ajaxdata },
			success:function(data){
				if(ajaxdata=='1'){
					jQuery('#mobile_respon').html(data);
				} else {
					jQuery('#filter').html(data);
				}
			}
		}).done(function() {
			if(ajaxdata=='1'){
				jQuery('#demoOne').listnav({
					allText: '" . Yii::t('frontend', 'All') . "',
					noMatchText: '". Yii::t('frontend', 'No matching entries') ."'
				});

				jQuery('.demo a').click(function(e) {
					e.preventDefault();
				});
			}
		});
	});

	jQuery('#demoOne a').click(function(){
		var directive_link = jQuery(this).attr('href');
		window.location.href = directive_link;
	});

	if (jQuery(window).width() < 991) {
		jQuery('#ajaxdata').val('1');
	}else{
		jQuery('#ajaxdata').val('0');
	}

", View::POS_READY);



