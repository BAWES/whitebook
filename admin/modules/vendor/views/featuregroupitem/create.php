<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Featuregroupitem */

$this->title = 'Create feature group item';
$this->params['breadcrumbs'][] = ['label' => 'Featuregroupitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="featuregroupitem-create">
    <?= $this->render('_form', [
        'model' => $model,'group' => $group,'category'=>$category,'vendoritem'=>$vendoritem,'subcategory'=>$subcategory,'themelist'=>$themelist,
    ]) ?>
</div>
