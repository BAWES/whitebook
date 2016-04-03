<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Featuregroupitem */

$this->title = 'Update feature group item';
$this->params['breadcrumbs'][] = ['label' => 'Featuregroupitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->featured_id, 'url' => ['view', 'id' => $model->featured_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="featuregroupitem-update">
    <?= $this->render('_form', [
         'model' => $model,'group' => $group,'vendoritem'=>$vendoritem,'category'=>$category,'subcategory'=>$subcategory,'themelist'=>$themelist,'featuregroupitem'=>$featuregroupitem,'themeid'=>$themeid,
    ]) ?>

</div>
