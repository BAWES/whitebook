<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Vendoritem */

$this->title = 'Update vendor item';
$this->params['breadcrumbs'][] = ['label' => 'Vendor items', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vendoritem-update">
    <?= $this->render('_form', [
        'model' => $model,'itemtype'=>$itemtype,'vendorname'=>$vendorname,'categoryname'=>$categoryname,
        'subcategory'=>$subcategory,'imagedata'=>$imagedata,'model_question' => $model_question,
        'themelist'=>$themelist,'grouplist'=>$grouplist,'exist_themes'=>$exist_themes,
        'childcategory'=>$childcategory,'loadpricevalues'=>$loadpricevalues,'guideimagedata'=>$guideimagedata
    ]) ?>

</div>
