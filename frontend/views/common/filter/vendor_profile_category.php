<?php

use common\models\VendorCategory;

$data = Yii::$app->request->get();

$category_list = VendorCategory::find()
    ->select(['{{%category}}.category_id', '{{%category}}.category_name', '{{%category}}.slug','{{%category}}.icon'])
    ->leftJoin('{{%category}}','{{%category}}.category_id = {{%vendor_category}}.category_id')
    ->leftJoin('{{%category_path}}', '{{%category}}.category_id = {{%category_path}}.path_id')            
    ->where([
        '{{%category}}.trash' =>'Default',
        '{{%category_path}}.level' => 0
    ])
    ->groupBy('{{%category}}.category_id')
    ->asArray()
    ->all();

if (count($category_list) > 3) {
    $class = "test_scroll";
} else {
    $class = "";
} ?>

<div class="responsive-category-top">
    <div class="listing_sub_cat1">
        <span class="title_filter"><?= Yii::t('frontend', 'Categories') ?></span>
        <select class="selectpicker " id="main-category">
            <option data-icon="venues-category" value="<?=yii\helpers\Url::toRoute(['site/vendor_profile', 'slug' => 'all','vendor'=>$data['vendor']]); ?>"><?=Yii::t('frontend','All')?></option>
            <?php
            foreach($category_list as $category) {

                if ($data['slug'] == $category['slug']) {
                    $selected = 'selected="selected"';
                } else {
                    $selected = '';
                }
                $category_name = \common\components\LangFormat::format($category['category_name'],$category['category_name_ar']);
                ?>
                <option
                    data-icon="<?= $category['icon'] ?>"
                    value="<?=yii\helpers\Url::toRoute(['site/vendor_profile','slug' => $category['slug'],'vendor'=>$data['vendor']]); ?>"
                    name="category" <?= $selected ?>>
                    <?= $category_name ?>
                </option>
            <?php } ?>
        </select>
    </div><!-- END .listing_sub_cat1 -->
</div><!-- END .responsive-category-top -->

<div class="panel panel-default" >
    <div class="panel-heading">
         <a href="javascript:void(0)" class="filter-clear" id="filter-clear" title="Clear">- <?=Yii::t('frontend','Clear')?></a>
<!--        <div class="clear_left"><p>--><?//= Yii::t('frontend', 'Categories'); ?><!-- <a href="javascript:void(0)" class="filter-clear" id="filter-clear" title="Clear">- Clear</a></p></div>-->
<!--        <div class="clear_right">-->
<!--            <a href="#bakery" id="sub_category_cakes" data-parent="#accordion" data-toggle="collapse" class="collapsed">-->
<!--                <h4 class="panel-title">-->
<!--                    <span class="plus_acc"></span>-->
<!--                </h4>-->
<!--            </a>-->
<!--        </div>-->
    </div>
    <div id="bakery" class="panel-collapse collapse in" area-expanded="true" aria-expanded="true">
        <div class="panel-body">
            <div class="table">
                <ul class="<?= $class; ?>">
                <?php foreach ($category_list as $key => $c_value) { ?>
                    <li>                
                    <label class="label_check" for="checkbox-filter-cat-<?= $c_value['category_id'] ?>" />
                    <input name="category" data-element="input" class="category" id="checkbox-filter-cat-<?= $c_value['category_id'] ?>" value="<?= $c_value['category_id'] ?>" step="<?= $c_value['category_id'] ?>" type="checkbox" <?php echo (isset($checked) && $checked != "") ? $checked : ''; ?> ><?= ucfirst(strtolower($c_value['category_name'])); ?></label>
                    </li>
                <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>
