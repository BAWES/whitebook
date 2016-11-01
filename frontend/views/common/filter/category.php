<?php

use common\models\ChildCategory;
use common\models\SubCategory;
use frontend\models\Category;

/* Get slug name to find category */
$subcategory = SubCategory::loadsubcat($slug);

$col = 1;

	$t = $in ='';

	if($col==1){
		$s_class='minus_acc';
		$t='area-expanded="true"';
		$in='in';
	}else{
		$s_class='plus_acc';
	}
$get = Yii::$app->request->get();
	?>
<style>
	li.subcat {
		margin-left:30px!important;
	}
	.subcat {
		padding-top: 0px!important;
		padding-bottom: 0px!important;
		height:28px!important;
	}
	ul.list-group .list-group li {
		background: #f8f8f8;
		height: auto;
		line-height: normal;
		padding: 0px 0;
		margin-bottom: 1px;
		margin-right: 1px;
		clear: both;
	}
</style>
<?php if ($subcategory) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="clear_left">
				<p>
					<a href="javascript:void(0)" class="filter-clear" id="filter-clear" title="Clear">
						- <?= Yii::t('frontend', 'Clear') ?></a>
				</p>
			</div>
<!--			<div class="clear_right">-->
<!--				<a href="#sub_categories" id="category" data-parent="#accordion" data-toggle="collapse"-->
<!--				   class="collapsed">-->
<!--					<h4 class="panel-title">-->
<!--						<span class="--><?//= $s_class; ?><!--"></span>-->
<!--					</h4>-->
<!--				</a>-->
<!--			</div>-->
		</div>
		<div id="sub_categories">
			<div class="panel-body" style="background-color: #f8f8f8">
				<div class="table clearfix">
					<ul class="list-group test_scrolls" style="height:170px;overflow: scroll">
						<?php
						$val = [];
						if (isset($get['category']) && $get['category'] != "") {
							$val = $get['category'];
						}
						foreach ($subcategory as $key => $value) {

							if (isset($value['category_name'])) {
								$lang_name = (Yii::$app->language == "en") ? 'category_name' : 'category_name_ar';
								$category_name = ucfirst(strtolower($value[$lang_name]));
								?>
								<li for="<?= "class_" . $value['slug'] ?>">
									<label class="label_check" for="checkbox-<?= $value['slug'] ?>"
										   data-class="<?= "class_" . $value['slug'] ?>">
										<input
											name="category"
											data-element="input"
											class="items category <?=$value['slug'] ?>"
											id="checkbox-<?= $value['slug'] ?>"
											step="<?= $value['category_id'] ?>"
											value="<?= $value['slug'] ?>"
											data-slug="<?=$value['slug'] ?>"
											data-parent = "yes"
											type="checkbox"
											<?php echo (in_array($value['slug'], array_values($val))) ? 'checked="checked"' : ''; ?> >
										<strong><?= $category_name ?></strong>
									</label>
								</li>

								<?php
								$_subcategory = SubCategory::loadsubcat($value['slug']);
								if ($_subcategory) {
									echo  "<ul class=".$value['slug'].">";
									foreach ($_subcategory as $_key => $_value) {
										$_category_name = ucfirst(strtolower($_value[$lang_name]));
										?>
										<li class="subcat" for="<?= "class_" . $value['slug'] ?>">
											<label class="label_check"
												   for="checkbox-<?= $_value['slug'] ?>"
												   data-class="<?= "class_" . $value['slug'] ?>">
												<input name="category" data-element="input"
													   class="items <?=$value['slug'] ?>"
													   id="checkbox-<?= $_value['slug'] ?>"
													   step="<?= $_value['category_id'] ?>"
													   value="<?= $_value['slug'] ?>"
													   type="checkbox"
													   data-slug="<?=$value['slug'] ?>"
													<?php echo (in_array($_value['slug'], array_values($val))) ? 'checked="checked"' : ''; ?> >
												<?= $_category_name ?>
											</label>
										</li>
										<?php
									}
									echo "</ul>";
								}
								?>
								<?php
							}
						} ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<?php $col++;
}?>