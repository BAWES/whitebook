<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Featuregroupitem */

$this->title = 'Update theme group item';
$this->params['breadcrumbs'][] = ['label' => 'Featuregroupitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="featuregroupitem-update">
    <?= $this->render('_form', [
         'model' => $model,'vendoritem'=>$vendoritem,'category_id'=>$category_id,'subcategory_id'=>$subcategory_id,'themelist'=>$themelist,
    ]) ?>

</div>
