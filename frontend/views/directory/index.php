<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\view;

?>

<!-- coniner start -->
<section id="inner_pages_white_back" class="<?=Yii::$app->controller->id;?>">
    <?php /* @TODO Removed Event Section ?>
	<div id="event_slider_wrapper">
		<div class="container paddng0">
			<?=$this->render('/product/events_slider.php'); ?>
		</div>
	</div>
    <?php */ ?>
	<div class="container paddng0">

		<div class="directory_listing">
			<div class="title_main">
				<h1><?= Yii::t("frontend", "Directory") ?></h1>
			</div>
			<div class="col-md-3 paddingleft0 left-section">
				<div class="filter_content">
					<div class="filter_section">
					<span class="title_filter"><?= Yii::t("frontend", "Categories") ?></span>
						<div class="listing_sub_cat">
							<input type="hidden" id="ajaxdata" name="ajaxdata">
							<select class="selectpicker" style="display: none;" id="filter_category" name="filter_category">
							<option value="All"><?= Yii::t("frontend", "All"); ?></option>
							<?php
							
							$categories = \frontend\models\Category::find()
								->leftJoin('{{%category_path}}', '{{%category}}.category_id = {{%category_path}}.path_id')
								->select('{{%category}}.category_name, {{%category}}.category_name_ar, {{%category}}.category_id, {{%category}}.icon')
								->where([
									'{{%category}}.trash' => 'Default',
									'{{%category_path}}.level' => 0
								])
								->orderBy('sort')
								->all();

							foreach ($categories as $category) {
								$select = \common\components\LangFormat::format($category->category_name,$category->category_name_ar);
								echo '<option data-icon="'.$category->icon.'" value="'.$category->category_id.'">'.$select.'</option>';
							}
							?>
							</select>
						</div>
					</div>
				</div>
			</div>

			<div id="filter" class="right-section">
				<?php echo $this->render('_listing',['directory'=>$directory,'first_letter'=>$first_letter]); ?>
			</div>

		</div>
	</div>
</section>

<?php 

//$this->registerJsFile('@web/js/jquery-listnav.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerCssFile('@web/css/listnav.css?v=1.1');

$this->registerJs("

	jQuery('#filter_category').change(function(){

		var x= jQuery('#filter_category').val();
		var path = '".Url::to(['directory/index'])."';
		
		jQuery.ajax({
			type:'POST',
			url:path,
			data:{ slug:x },
			success:function(data){
				jQuery('#filter').html(data);
			}
		});
	});

", View::POS_READY);



