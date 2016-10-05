<?php

//we already getting theme for listed items, so no need to have sql like price filter here

if($themes) { ?>

<div class="panel panel-default" >
	<div class="panel-heading">
		<div class="clear_left"><p><?= Yii::t('frontend', 'Themes') ?> <a href="javascript:void(0)" class="filter-clear" id="filter-clear" title="Clear">- <?= Yii::t('frontend', 'Clear') ?></a></p></div>
		<div class="clear_right">
			<a href="#themes" id="category" data-parent="#accordion" data-toggle="collapse" class="collapsed">
				<h4 class="panel-title">
					<span class="plus_acc"></span>
				</h4>
			</a>
		</div>
	</div>
	<div id="themes" class="panel-collapse collapse" aria-expanded="false">
		<div class="panel-body">
			<div class="table">
				<?php
				/* BEGIN Display scroll for more than three li */
				if(count($themes) > 3 ) { $class = "test_scroll"; } else { $class = "";}
				/* END Display scroll for more than three li */
				?>
				<ul class="<?= $class; ?>">
					<?php
					foreach ($themes as $key => $value) {

						if(isset($get['themes']) && $get['themes'] !="")
						{
							$val = explode(' ',$get['themes']);

							if(in_array($value['slug'],$val))
							{
								$checked1 = 'checked=checked';
							}
							else
							{
								$checked1 = '';
							}
						}
							if (isset($value['theme_name'])) {
								if (Yii::$app->language == "en") {
									$theme_name = ucfirst(strtolower($value['theme_name']));
								} else {
									$theme_name = ucfirst(strtolower($value['theme_name_ar']));
								}

						?>
							<li>
								<label class="label_check" for="checkbox-<?= $value['theme_name'] ?>">

								<input name="themes" data-element="input" class="items" id="checkbox-<?= $value['theme_name'] ?>" step="<?= $value['theme_id'] ?>" value="<?= $value['slug'] ?>" type="checkbox" <?php echo (isset($checked1) && $checked1 !="") ?  $checked1 : ''; ?> ><?= $theme_name ?></label>
							</li>
						<?php
							}
						} ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
<?php 
}	