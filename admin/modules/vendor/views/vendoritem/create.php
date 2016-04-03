<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Vendoritem */

$this->title = 'Create item';
$this->params['breadcrumbs'][] = ['label' => 'Vendoritems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendoritem-create">

    <?= $this->render('_form', [
        'model' => $model,'model1' => $model1,'itemtype'=>$itemtype,'vendorname'=>$vendorname,'categoryname'=>$categoryname,'subcategory'=>$subcategory,        
    ]) ?>
</div>
