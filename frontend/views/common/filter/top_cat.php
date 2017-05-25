<?php

$top_categories = \common\models\Category::find()
    ->select('{{%category}}.*')
    ->leftJoin('{{%category_path}}', '{{%category}}.category_id = {{%category_path}}.path_id')
    ->where([
        '{{%category_path}}.level' => 0,
        'trash' =>'Default'
    ])
    ->orderBy('sort')
    ->asArray()
    ->all();

$get = Yii::$app->request->get();

?>

<div class="responsive-category-top">
    <div class="listing_sub_cat1">
        <span class="title_filter"><?= Yii::t('frontend', 'Categories') ?></span>
        <select class="selectpicker" id="main-category">
            <?php
            foreach($top_categories as $category) {

                if ($get['slug'] == $category['slug']) {
                    $selected = 'selected="selected"';
                    $data = 'data-hidden="true"';
                } else {
                    $data = '';
                    $selected = '';
                }
                $category_name  = \common\components\LangFormat::format($category['category_name'],$category['category_name_ar']);
                ?>
                <option
                    data-icon="<?= $category['icon'] ?>"
                    data-href="<?php
                    if (isset($theme)) {
                        echo yii\helpers\Url::toRoute([$path, 'slug' => $category['slug'],'themes'=>$theme]); // for theme page
                    } else {
                        echo yii\helpers\Url::toRoute([$path, 'slug' => $category['slug']]);
                    }

                    ?>"
                    value="<?= $category['slug'] ?>"
                    <?= $data . ' ' .$selected ?>>
                    <?= $category_name ?>
                </option>
            <?php } ?>
        </select>
    </div><!-- END .listing_sub_cat1 -->
</div><!-- END .responsive-category-top -->