<?php

/* Get slug name to find category */
if($category_slug !=""){
$subcategory = SubCategory::loadsubcat($category_slug);

$col=1;
foreach ($subcategory as $key => $value) {
$t = $in ='';
if($col==1){
$s_class='minus_acc';$t='area-expanded="true"';$in='in';
}else{
$s_class='plus_acc';
}
?>
<div class="panel panel-default" >
<div class="panel-heading">
<div class="clear_left"><p><?= $value['category_name']; ?> <a href="javascript:void(0)" class="filter-clear" id="filter-clear" title="Clear">- Clear</a></p></div>
<div class="clear_right">
<a href="#<?= $value['category_id']; ?>" id="category" data-parent="#accordion" data-toggle="collapse" class="collapsed">
<h4 class="panel-title">
<span class="<?= $s_class;?>"></span>
</h4>
</a>
</div>
</div>
<div id="<?= $value['category_id']; ?>" <?= $t; ?> class="panel-collapse collapse <?= $in; ?>"  >
<div class="panel-body">
<div class="table">
<?php $childcategory = ChildCategory::loadchildcategoryslug($value['category_id']);
/* Display scroll for more than three li */
if(count($childcategory) > 3 ) { $class = "test_scroll"; } else { $class = "";}
/* Display scroll for more than three li */
?>
<ul class="<?= $class; ?>">
<?php

foreach ($childcategory as $key => $value) {

if(isset($get['category']) && $get['category'] !="")
{
$val = explode(' ',$get['category']);

if(in_array($value['slug'], $val))
{
	$checked = 'checked=checked';
}
else
{
$checked = '';
}
}
/* END check category checbox values */
?>
<li>
<label class="label_check" for="checkbox-<?= $value['category_name'] ?>">
<input name="items" data-element="input" class="items" id="checkbox-<?= $value['category_name'] ?>"
value="<?= $value['slug'] ?>" step="<?= $value['category_id'] ?>"
type="checkbox" <?php echo (isset($checked) && $checked !="") ?  $checked : ''; ?> >
<?= ucfirst(strtolower($value['category_name'])); ?></label>
</li>
<?php }  ?>
</ul>
</div>
</div>
</div>
</div>
<?php $col++; } ?>
<?php } ?>
<!--  END CATEGORY FILTER-->