<?php  $get = Yii::$app->request->get();  ?>
<div class="panel panel-default" >
	<div class="panel-heading">
		<div class="clear_left"><p><?= Yii::t('frontend', 'Available For Sale') ?> <a href="javascript:void(0)" class="filter-clear" id="filter-clear" title="Clear">- <?= Yii::t('frontend', 'Clear') ?></a></p></div>
		<div class="clear_right">
			<a href="#for_sale" id="category" data-parent="#accordion" data-toggle="collapse" class="collapsed">
				<h4 class="panel-title">
					<span class="plus_acc"></span>
				</h4>
			</a>
		</div>
	</div>
	<div id="for_sale" class="panel-collapse collapse" aria-expanded="false">
		<div class="panel-body">
			<div class="table">
				<ul>
					<?php
					$checked1 = '';
						if (isset($get['for_sale'])) {
							$checked1 = 'checked=checked';
						}
						?>
						<li>
							<label class="label_check" for="checkbox-available-for-sale">
								<input name="for_sale" data-element="input" class="items"
									   id="checkbox-available-for-sale"
										value="Yes"
									   type="checkbox" <?php echo (isset($checked1) && $checked1 != "") ? $checked1 : ''; ?> >Available For Sale
							</label>
						</li>

					</ul>
				</div>
			</div>
		</div>
	</div>
