<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Vendoritemquestion */

$this->title = 'Update vendor item question';
$this->params['breadcrumbs'][] = ['label' => 'Vendoritemquestions', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vendoritemquestion-update">

    <?= $this->render('_form', [
        'model' => $model,'category' => $category, 'subcategory' => $subcategory,'vendorname' => $vendorname,'vendoritem'=>$vendoritem,
    ]) ?>

</div>
