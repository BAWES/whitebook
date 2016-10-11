<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\view;

?>

<!-- coniner start -->
<section id="inner_pages_white_back">

	<div id="event_slider_wrapper">
		<div class="container paddng0">
			<?=$this->render('/product/events_slider.php'); ?>
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
							<?php
							$select = (Yii::$app->language == 'en') ? 'category_name' : 'category_name_ar';
							$categories = \frontend\models\Category::find()
								->select(''.$select.' as category_name,category_id,icon')
								->where(['category_level'=>'0'])
								->orderBy('sort')
								->all();
									foreach ($categories as $category) {
										echo '<option data-icon="'.$category->icon.'" value="'.$category->category_id.'">'.$category->category_name.'</option>';
									}
							?>
							</select>
						</div>
					</div>
				</div>
			</div>

			<div id="mobile_respon">
				<?php echo $this->render('_m_listing',['directory'=>$directory,'first_letter'=>$first_letter]); ?>
			</div>

			<div id="filter">
				<?php echo $this->render('_listing',['directory'=>$directory,'first_letter'=>$first_letter]); ?>
			</div>

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

		var path = '".Url::to(['directory/index'])."';
		
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


