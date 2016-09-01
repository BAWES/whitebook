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
        'itemType'=>$itemType,
        'categoryname'=>$categoryname,
        'subcategory'=>$subcategory,
        'images'=>$images,
        'model_question' => $model_question,
        'themes'=>$themes,
        'grouplist'=>$grouplist,
        'exist_themes'=>$exist_themes,
        'childcategory'=>$childcategory,
        'itemPricing'=>$itemPricing,
        'guideImages'=>$guideImages
    ]); ?>

</div>
