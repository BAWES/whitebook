<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveField;
/* @var $this yii\web\View */
/* @var $model backend\models\Accesscontrol */
/* @var $form yii\widgets\ActiveForm */
?>
<?= Html::csrfMetaTags() ?>
<div class="accesscontrol-form">
	<div class="col-md-8 col-sm-8 col-xs-8">	
	<? $form = ActiveForm::begin(array('options' => array('id' => 'myform','onsubmit'=>'return check_validation();'))); ?>
	<div class="form-group">   
	<?php if ($model->isNewRecord) { ?>
	<?= $form->field($model, 'admin_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->label('Select a user')->dropDownList($admin,['prompt'=>'Select...']) ?>
	<?php } else { ?>
	<?= $form->field($model, 'admin_id',[  'template' => "{label}<div class='controls'>{input}</div> {hint} {error}" 
	])->label('Select a user')->dropDownList($admin,['prompt'=>'Select...','disabled' => true,]) ?><?php } ?>
	</div>	
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
        				<td><input type="checkbox" id="ctrl" name="Accesscontroller[controller][<?php echo $value;?>][controller_id]" class="checkbox_all" value="<?php echo $key; ?>">&nbsp;<?php echo $value;?></td>
        				<td><input type="checkbox" id="create" name="Accesscontroller[controller][<?php echo $value;?>][create]" class="create"value="Create" ></td>
        				<td><input type="checkbox" id="update" name="Accesscontroller[controller][<?php echo $value;?>][update]"  class="update" value="Update"></td>
        				<td><input type="checkbox" id="delete" name="Accesscontroller[controller][<?php echo $value;?>][delete]" class="delete" value="Delete"></td>
        				<td><input type="checkbox" id="manage" name="Accesscontroller[controller][<?php echo $value;?>][manage]" class="manage" value="Manage"></td>
        				<?php if($key=='23'){?>
        				<td><input type="checkbox" id="view" name="Accesscontroller[controller][<?php echo $value;?>][view]" class="view" value="View"></td>
        				<?php } else {  ?>
        				<td>N/A</td>
        				<?php }?>
        			</tr>
        			<?php } } else {  
        			foreach($accesslist as $al){	?>
				<tr>
        				<td><input type="checkbox" id="ctrl" name="Accesscontroller[controller][<?php echo $al['controller'];?>][controller_id]" class="checkbox_all" value="<?php echo $al['id'];?>" <?php if(($al['id'])){echo 'checked';}?>>&nbsp;<?php echo $al['controller'];?></td>
						<td><input type="checkbox" id="create" name="Accesscontroller[controller][<?php echo $al['controller'];?>][create]" class="create"value="Create" <?php if($al['create']){echo 'checked';}?>></td>
						<td><input type="checkbox" id="update" name="Accesscontroller[controller][<?php echo $al['controller'];?>][update]"  class="update"value="Update" <?php if($al['update']){echo 'checked';}?>></td>
						<td><input type="checkbox" id="delete" name="Accesscontroller[controller][<?php echo $al['controller'];?>][delete]" class="delete" value="Delete" <?php if($al['delete']){echo 'checked';}?>></td>
						<td><input type="checkbox"  id="manage" name="Accesscontroller[controller][<?php echo $al['controller'];?>][manage]" class="manage" value="Manage" <?php if($al['manage']){echo 'checked';}?>> </td>
						<?php if($al['view']){ ?>
        				<td><input type="checkbox" id="view" name="Accesscontroller[controller][<?php echo $al['controller'];?>][view]" class="view" value="View"  <?php if($al['view']){echo 'checked';}?>></td>
        				<?php }else if(!($al['id']=='23')){  ?>
        				<td>N/A</td>
        				<?php }?>
        				<?php if(($al['id']=='23')&&(!($al['view']))){?>
        				<td><input type="checkbox" id="view" name="Accesscontroller[controller][<?php echo $al['controller'];?>][view]" class="view" value="View"></td>
        				<?php }?>
					</tr>
				<?php } ?>
        			<?php  foreach($controller as $key => $value){	?>
        			<tr>
        				<td><input type="checkbox"  id="ctrl" name="Accesscontroller[controller][<?php echo $value;?>][controller_id]" class="checkbox_all" value="<?php echo $key; ?>">&nbsp;<?php echo $value;?></td>
        				<td><input type="checkbox"  id="create"name="Accesscontroller[controller][<?php echo $value;?>][create]" class="create"value="Create" ></td>
        				<td><input type="checkbox" id="update" name="Accesscontroller[controller][<?php echo $value;?>][update]"  class="update" value="Update"></td>
        				<td><input type="checkbox" id="delete" name="Accesscontroller[controller][<?php echo $value;?>][delete]" class="delete" value="Delete"></td>
        				<td><input type="checkbox"  id="manage" name="Accesscontroller[controller][<?php echo $value;?>][manage]" class="manage" value="Manage"></td>
        				<?php if($key=='23'){?>
        				<td><input type="checkbox" id="view" name="Accesscontroller[controller][<?php echo $value;?>][view]" class="view" value="View"></td>
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
<!-- BEGIN PLUGIN CSS -->
<link href="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-select2/select2.css" rel="stylesheet" type="text/css" />
<!-- END PLUGIN CSS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo Yii::$app->themeURL->createAbsoluteUrl(''); ?>plugins/bootstrap-select2/select2.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script>
$("#auth_item").select2({
    placeholder: "Select User Function.."
});

</script>

<script type="text/javascript">
$(function (){ 
	$(".admin").hide();
	$(".ctrlnew").hide();
	$(".functionnew").hide();
 });
</script>
<?php if(!$model->isNewRecord){ ?>
<script>
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
</script>
<?php }?>


<script type="text/javascript">
$(function (){ 
    $("#accesscontroller-admin_id").change(function (){   
		$('#myTable').addClass('has-error');
   });
 });
</script>

<script type="text/javascript">
$(function (){ 
    $("#accesscontroller-controller").change(function (){   
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var admin_id = $('#accesscontroller-admin_id').val();  
        var controller_id = $('#accesscontroller-controller').val();  
        var path = "<?php echo Url::to(['/admin/accesscontrol/authitem']); ?> ";
        $.ajax({
        type: 'POST',
        url: path, //url to be called
        data: { admin_id: admin_id ,controller_id : controller_id ,_csrf : csrfToken}, //data to be send
        success: function( data ) {
             $('#auth_item').html(data);
         }
        })
     });
 });
 
 
 function check_validation()
 {
	var ids = $("input[id=ctrl]:checked").get();
	var create = $("input[id=create]:checked").get();
	var update = $("input[id=update]:checked").get();
	var delete1 = $("input[id=delete]:checked").get();
	var manage = $("input[id=manage]:checked").get();
	var view = $("input[id=view]:checked").get();
    if(ids.length == 0)
    {
		$(".ctrlnew").show(); 
		$('#myTable').addClass('has-error');
		return false;
    }
        if((create.length == 0)&&(update.length == 0)&&(delete1.length == 0)&&(view.length == 0)&&(manage.length == 0))
    {
		$(".ctrlnew").hide();
		$(".functionnew").show();
		$('#myTable').addClass('has-error');
		return false;
    }
    else {
	$('#myTable').removeClass();
    return true;
    }
    return false;
 }
 //33 
</script>
