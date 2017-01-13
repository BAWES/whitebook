<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Vendoritem */

$this->title = 'Update vendor item';
$this->params['breadcrumbs'][] = ['label' => 'Vendor items', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="vendoritem-update">

    <?= $this->render('_update', [
            'model' => $model,
            'itemType' => $itemType,
            'categoryname' => $categoryname,
            'images' => $images,
            'model_question' => $model_question,
            'themes' => $themes,
            'grouplist' => $grouplist,
            'packagelist' => $packagelist,
            'itemPricing' => $itemPricing,
            'guideImages' => $guideImages,
            'main_categories' => $main_categories,
            'sub_categories' => $sub_categories,
            'item_child_categories' => $item_child_categories,
            'category_model' => $category_model,
            'arr_menu' => $arr_menu
    ]); ?>

</div>
