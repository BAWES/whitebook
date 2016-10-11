<div class="panel panel-default" >
	<div class="panel-heading">
		<div class="clear_left"><p><?= Yii::t('frontend', 'Vendor') ?> <a href="javascript:void(0)" class="filter-clear" id="filter-clear" title="Clear">- <?= Yii::t('frontend', 'Clear') ?></a></p></div>
		<div class="clear_right">
			<a href="#vendor" id="category" data-parent="#accordion" data-toggle="collapse" class="collapsed">
				<h4 class="panel-title">
					<span class="plus_acc"></span>
				</h4>
			</a>
		</div>
	</div>
	<div id="vendor" class="panel-collapse collapse" area-expanded="false" >
		<div class="panel-body">
			<div class="table">
				<?php
				$get = Yii::$app->request->get();
				/* BEGIN Display scroll for more than three li */
				if(count($vendor) > 3 ) { $class = "test_scroll"; } else { $class = "";}
				/* END Display scroll for more than three li */
				?>
				<ul class="<?= $class; ?>">
					<?php foreach ($vendor as $key => $value) {

					if (isset($get['vendor']) && $get['vendor'] !="") {

						$val = explode(' ',$get['vendor']);
						$checked2 = (in_array($value['slug'],$val)) ? 'checked=checked' : '';
					}
					$name = (Yii::$app->language == "en") ? 'vendor_name' : 'vendor_name_ar';
					$vendor_name = ucfirst(strtolower($value[$name]));

					?>
					<li>
						<label class="label_check" for="checkbox-<?= $value['vendor_name'] ?>">
						<input name="vendor" data-element="input" class="items" id="checkbox-<?= $value['vendor_name'] ?>" step="<?= $value['vendor_name'] ?>" value="<?= $value['slug'] ?>" type="checkbox" <?php echo (isset($checked2) && $checked2 !="") ?  $checked2 : ''; ?> ><?= $vendor_name; ?></label>
					</li>
					<?php } ?>
				</ul>
			</div><!-- END table -->
		</div>
	</div>
</div>