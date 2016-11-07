<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Vendoritem */

$this->title = 'Update item';
$this->params['breadcrumbs'][] = ['label' => 'Vendoritems', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->item_id, 'url' => ['view', 'id' => $model->item_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vendoritem-update">
    <?php
    
    echo $this->render('_update', [
        'model' => $model,
        'itemtype'=>$itemtype,
        'vendorname'=>$vendorname,
        'categoryname'=>$categoryname,
        'images'=>$images,
        'model1' => $model1,
        'categories' => $categories,
        'vendor_item_to_category' => $vendor_item_to_category,
        'loadpricevalues'=>$loadpricevalues,
        'guide_images'=>$guide_images,
        'model_question' => $model_question        
    ]) ?>
</div>