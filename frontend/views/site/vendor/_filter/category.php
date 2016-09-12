<?php

use frontend\models\Vendor;
use frontend\models\Category;

$category_ids = Vendor::Vendorcategories($slug);
$category_list = Category::Vendorcategorylist($category_ids['category_id']);

if (count($category_list) > 3) {
    $class = "test_scroll";
} else {
    $class = "";
} ?>

<div class="panel panel-default" >
    <div class="panel-heading">
        <div class="clear_left"><p><?= Yii::t('frontend', 'Categories'); ?> <a href="javascript:void(0)" class="filter-clear" id="filter-clear" title="Clear">- Clear</a></p></div>
        <div class="clear_right">
            <a href="#bakery" id="sub_category_cakes" data-parent="#accordion" data-toggle="collapse" class="collapsed">
                <h4 class="panel-title">
                    <span class="plus_acc"></span>
                </h4>
            </a>
        </div>
    </div>
    <div id="bakery" class="panel-collapse collapse" area-expanded="true" aria-expanded="true">
        <div class="panel-body">
            <div class="table">
                <ul class="<?= $class; ?>">
                <?php foreach ($category_list as $key => $c_value) { ?>
                    <li>                
                    <label class="label_check" for="checkbox-<?= $c_value['category_name'] ?>"><input name="category" data-element="input" class="category" id="checkbox-<?= $c_value['category_name'] ?>" value="<?= $c_value['slug'] ?>" step="<?= $c_value['category_id'] ?>" type="checkbox" <?php echo (isset($checked) && $checked != "") ? $checked : ''; ?> ><?= ucfirst(strtolower($c_value['category_name'])); ?></label>
                    </li>
                <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>
