<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Vendoritemquestion */

$this->title = 'Create vendor item question';
$this->params['breadcrumbs'][] = ['label' => 'Vendoritemquestions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendoritemquestion-create">

    <?= $this->render('_form', [
        'model' => $model,'category' => $category, 'subcategory' => $subcategory,'vendorname' => $vendorname,'vendoritem'=>$vendoritem,
    ]) ?>

</div>
