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
            'itemPricing' => $itemPricing,
            'guideImages' => $guideImages,
            'categories' => $categories,
            'vendor_item_to_category' => $vendor_item_to_category
    ]); ?>

</div>
