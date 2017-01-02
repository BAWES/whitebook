<?php

use common\models\SubCategory;
use common\models\CategoryPath;
use common\models\ChildCategory;
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
			<div class="panel-body b-g-f8f8f8">
				<div class="table clearfix">
					<ul class="list-group test_scrolls css-updated">
						<?php
						$val = [];
						if (isset($get['category']) && $get['category'] != "") {
							$val = $get['category'];
						}
						foreach ($subcategory as $key => $value) {

							//check if items available in this category 
	                        $have_item = CategoryPath::find()
	                            ->leftJoin(
	                                '{{%vendor_item_to_category}}',
	                                '{{%vendor_item_to_category}}.category_id = {{%category_path}}.category_id'
	                            )
	                            ->leftJoin(
	                                '{{%vendor_item}}',
	                                '{{%vendor_item}}.item_id = {{%vendor_item_to_category}}.item_id'
	                            )
	                            ->where([
	                                '{{%vendor_item}}.trash' => 'Default',
	                                '{{%vendor_item}}.item_status' => 'Active',
	                                '{{%vendor_item}}.item_approved' => 'Yes',
	                                '{{%category_path}}.path_id' => $value['category_id']
	                            ])
	                            ->groupBy('{{%vendor_item}}.item_id')
	                            ->one();

	                        if(!$have_item)
	                        {
	                            continue;
	                        }

							if (isset($value['category_name'])) {
								
								$category_name = \common\components\LangFormat::format(strtolower($value['category_name']),strtolower($value['category_name_ar']));
								?>
								<li for="<?= "class_" . $value['slug'] ?>">
									<label class="label_check" for="checkbox-<?= $value['slug'] ?>"
										   data-class="<?= "class_" . $value['slug'] ?>">
										<input
											name="category"
											data-element="input"
											class="items category"
											id="checkbox-<?= $value['slug'] ?>"
											step="<?= $value['category_id'] ?>"
											value="<?= $value['category_id'] ?>"
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
									echo  "<ul>";
									foreach ($_subcategory as $_key => $_value) {

										 //check if items available in this category 
				                        $have_item = CategoryPath::find()
				                            ->leftJoin(
				                                '{{%vendor_item_to_category}}',
				                                '{{%vendor_item_to_category}}.category_id = {{%category_path}}.category_id'
				                            )
				                            ->leftJoin(
				                                '{{%vendor_item}}',
				                                '{{%vendor_item}}.item_id = {{%vendor_item_to_category}}.item_id'
				                            )
				                            ->where([
				                                '{{%vendor_item}}.trash' => 'Default',
				                                '{{%vendor_item}}.item_status' => 'Active',
				                                '{{%vendor_item}}.item_approved' => 'Yes',
				                                '{{%category_path}}.path_id' => $value['category_id']
				                            ])
				                            ->groupBy('{{%vendor_item}}.item_id')
				                            ->one();

				                        if(!$have_item)
				                        {
				                            continue;
				                        }

										$_category_name = \common\components\LangFormat::format(strtolower($_value['category_name']),strtolower($_value['category_name_ar']));
										?>
										<li class="subcat" for="<?= "class_" . $value['slug'] ?>">
											<label class="label_check"
												   for="checkbox-<?= $_value['slug'] ?>"
												   data-class="<?= "class_" . $value['slug'] ?>">
												<input name="category" data-element="input"
													   class="items <?=$value['slug'] ?>"
													   id="checkbox-<?= $_value['slug'] ?>"
													   step="<?= $_value['category_id'] ?>"
													   value="<?= $_value['category_id'] ?>"
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
}

$this->registerCss("
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
	.b-g-f8f8f8{background-color: #f8f8f8;}
	.css-updated {
	    max-height: 170px;
	    overflow-y: scroll;
	    float: left;
	    width: 100%;
	}
")
?>

