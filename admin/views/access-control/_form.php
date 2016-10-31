<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveField;

?>

<?= Html::csrfMetaTags() ?>

<div class="accesscontrol-form">
	<div class="col-md-8 col-sm-8 col-xs-8">
	
    <?php $form = ActiveForm::begin(array(
                'options' => array('id' => 'myform','onsubmit'=>'return check_validation();')
            )); ?>
	
    <?php if ($model->isNewRecord) { ?>
    	<?= $form->field($model, 'admin_id')
                ->label('Select a user')
                ->dropDownList($admin,['prompt' => 'Select...']) ?>
	
    <?php } else { ?>
    
    	<?= $form->field($model, 'admin_id')
                ->label('Select a user')
                ->dropDownList($admin, ['prompt' => 'Select...','disabled' => true]); ?>
    <?php } ?>
	
    <div class="form-group">
	   <div id="no-more-tables">
            <table class="col-md-12 table-bordered table-striped table-condensed cf">
        		<thead class="cf">
        			<tr>
        				<th><span class="check_box"><input type="checkbox" id="module" onclick="checkall(this.checked);"> </span><span class="check_text"> Modules</span></th>
        				<th><span class="check_box"> <input type="checkbox" id="create"  onclick="create1(this.checked);"></span><span class="check_text"> Create </span></th>
        				<th><span class="check_box"> <input type="checkbox" id="update" onclick="update1(this.checked);"></span><span class="check_text"> Update</span></th>
        				<th><span class="check_box"> <input type="checkbox" id="delete" onclick="delete1(this.checked);"></span><span class="check_text">Delete</span></th>
        				<th><span class="check_box"><input type="checkbox" id="manage" onclick="manage1(this.checked);"> </span><span class="check_text"> Manage </span></th>
        				<th><span class="check_box"></span><span class="check_text"> View item</span></th>
        			</tr>
        		</thead>
        		<tbody id="myTable">
					<?php if($model->isNewRecord){ ?>
					<?php  foreach($controller as $key => $value){	?>
        			<tr>
        				<td><input type="checkbox" id="ctrl" name="AccessController[controller][<?php echo $value;?>][controller_id]" class="checkbox_all" value="<?php echo $key; ?>">&nbsp;<?php echo $value;?></td>
        				<td><input type="checkbox" id="create" name="AccessController[controller][<?php echo $value;?>][create]" class="create"value="Create" ></td>
        				<td><input type="checkbox" id="update" name="AccessController[controller][<?php echo $value;?>][update]"  class="update" value="Update"></td>
        				<td><input type="checkbox" id="delete" name="AccessController[controller][<?php echo $value;?>][delete]" class="delete" value="Delete"></td>
        				<td><input type="checkbox" id="manage" name="AccessController[controller][<?php echo $value;?>][manage]" class="manage" value="Manage"></td>
        				<?php if($key=='23'){?>
        				<td><input type="checkbox" id="view" name="AccessController[controller][<?php echo $value;?>][view]" class="view" value="View"></td>
        				<?php } else {  ?>
        				<td>N/A</td>
        				<?php }?>
        			</tr>
        			<?php } } else {
        			foreach($accesslist as $al){	?>
				    <tr>
        				<td><input type="checkbox" id="ctrl" name="AccessController[controller][<?php echo $al['controller'];?>][controller_id]" class="checkbox_all" value="<?php echo $al['id'];?>" <?php if(($al['id'])){echo 'checked';}?>>&nbsp;<?php echo $al['controller'];?></td>
						<td><input type="checkbox" id="create" name="AccessController[controller][<?php echo $al['controller'];?>][create]" class="create"value="Create" <?php if($al['create']){echo 'checked';}?>></td>
						<td><input type="checkbox" id="update" name="AccessController[controller][<?php echo $al['controller'];?>][update]"  class="update"value="Update" <?php if($al['update']){echo 'checked';}?>></td>
						<td><input type="checkbox" id="delete" name="AccessController[controller][<?php echo $al['controller'];?>][delete]" class="delete" value="Delete" <?php if($al['delete']){echo 'checked';}?>></td>
						<td><input type="checkbox"  id="manage" name="AccessController[controller][<?php echo $al['controller'];?>][manage]" class="manage" value="Manage" <?php if($al['manage']){echo 'checked';}?>> </td>
						<?php if($al['view']){ ?>
        				<td><input type="checkbox" id="view" name="AccessController[controller][<?php echo $al['controller'];?>][view]" class="view" value="View"  <?php if($al['view']){echo 'checked';}?>></td>
        				<?php }else if(!($al['id']=='23')){  ?>
        				<td>N/A</td>
        				<?php }?>
        				<?php if(($al['id']=='23')&&(!($al['view']))){?>
        				<td><input type="checkbox" id="view" name="AccessController[controller][<?php echo $al['controller'];?>][view]" class="view" value="View"></td>
        				<?php }?>
					</tr>
				    <?php } ?>
        			<?php  foreach($controller as $key => $value){	?>
        			<tr>
        				<td><input type="checkbox"  id="ctrl" name="AccessController[controller][<?php echo $value;?>][controller_id]" class="checkbox_all" value="<?php echo $key; ?>">&nbsp;<?php echo $value;?></td>
        				<td><input type="checkbox"  id="create"name="AccessController[controller][<?php echo $value;?>][create]" class="create"value="Create" ></td>
        				<td><input type="checkbox" id="update" name="AccessController[controller][<?php echo $value;?>][update]"  class="update" value="Update"></td>
        				<td><input type="checkbox" id="delete" name="AccessController[controller][<?php echo $value;?>][delete]" class="delete" value="Delete"></td>
        				<td><input type="checkbox"  id="manage" name="AccessController[controller][<?php echo $value;?>][manage]" class="manage" value="Manage"></td>
        				<?php if($key=='23'){?>
        				<td><input type="checkbox" id="view" name="AccessController[controller][<?php echo $value;?>][view]" class="view" value="View"></td>
        				<?php }else {  ?>
        				<td>N/A</td>
        				<?php }?>
        			</tr>

        			<?php } ?>

        			<?php } ?>
				</tbody>
        	</table>
        </div>
		<div class="ctrlnew" style="color:#a94442; margin-top:8px;">Select atleast one module</div>
		<div class="functionnew" style="color:#a94442; margin-top:8px;">Select atleast one function</div>
    </div>

	<div class="form-group mrg_top">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?=  Html::a('Back', ['index', ], ['class' => 'btn btn-default']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
	
    </div>
</div>

<?php 

$this->registerJs("
    var auth_item_url = '".Url::to(['/access-control/authitem'])."';
");

if(!$model->isNewRecord){ 

    $this->registerJs("

        $(function (){
            check_checkbox_all(); // when page load check the checkbox count and make check all to be checked
            $('.checkbox_all,.create,.update,.delete,.manage').on('click',function(){ // when click any one of the checkbox and make check all to be checked or not
                    check_checkbox_all();
            });
        });

        function check_checkbox_all(){
            if($('.checkbox_all:checked').length==$('.checkbox_all').length) $('#module').prop('checked', true);else $('#module').prop('checked', false);
            if($('.create:checked').length==$('.checkbox_all').length) $('#create').prop('checked', true); else $('#create').prop('checked', false);
            if($('.update:checked').length==$('.checkbox_all').length) $('#update').prop('checked', true); else $('#update').prop('checked', false);
            if($('.delete:checked').length==$('.checkbox_all').length) $('#delete').prop('checked', true); else $('#delete').prop('checked', false);
            if($('.manage:checked').length==$('.checkbox_all').length) $('#manage').prop('checked', true); else $('#manage').prop('checked', false);
        }
    ");
}

$this->registerCssFile('@web/themes/default/plugins/bootstrap-select2/select2.css');

$this->registerJsFile("@web/themes/default/plugins/bootstrap-select2/select2.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerJsFile("@web/themes/default/js/accesscontrol.js", ['depends' => [\yii\web\JqueryAsset::className()]]);
