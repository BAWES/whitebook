
<!--  BEGIN VENDOR FILTER -->
<div class="panel panel-default" >
<div class="panel-heading">
<div class="clear_left"><p>Vendor <a href="javascript:void(0)" class="filter-clear" id="filter-clear" title="Clear">- Clear</a></p></div>
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

/* BEGIN Display scroll for more than three li */
if(count($vendor) > 3 ) { $class = "test_scroll"; } else { $class = "";}
/* END Display scroll for more than three li */
?>
<ul class="<?= $class; ?>">
<?php foreach ($vendor as $key => $value) {

if(isset($get['vendor']) && $get['vendor'] !="")
{

$val = explode(' ',$get['vendor']);

if(in_array($value['slug'],$val))
{
	$checked2 = 'checked=checked';
}
else
{
$checked2 = '';
}
}

?>
<li>
<label class="label_check" for="checkbox-<?= $value['vendor_name'] ?>"><input name="vendor" data-element="input" class="items" id="checkbox-<?= $value['vendor_name'] ?>" step="<?= $value['vendor_name'] ?>" value="<?= $value['slug'] ?>" type="checkbox" <?php echo (isset($checked2) && $checked2 !="") ?  $checked2 : ''; ?> ><?= ucfirst(strtolower($value['vendor_name'])); ?></label>
</li>
<?php }?>

</ul>
</div>
</div>
</div>
</div>
<!--  END VENDOR FILTER-->
