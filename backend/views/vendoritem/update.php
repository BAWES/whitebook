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
    <?= $this->render('_form', [
        'model' => $model,
        'itemtype'=>$itemtype,
        'vendorname'=>$vendorname,
        'categoryname'=>$categoryname,
        'subcategory'=>$subcategory,
        'imagedata'=>$imagedata,
        'model1' => $model1,
        'childcategory'=>$childcategory,
        'loadpricevalues'=>$loadpricevalues,
        'guideimagedata'=>$guideimagedata,
        'model_question' => $model_question,
        
    ]) ?>

</div>
