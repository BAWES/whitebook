<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Featuregroupitem */

$this->title = 'Create theme group item';
$this->params['breadcrumbs'][] = ['label' => 'Featuregroupitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="featuregroupitem-create">
    <?= $this->render('_form', [
        'model' => $model,'vendoritem'=>$vendoritem,'category_id'=>$category_id,'subcategory_id'=>$subcategory_id,'themelist'=>$themelist,
    ]) ?>

</div>
