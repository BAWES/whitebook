<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Advertcategory */

$this->title = 'Update top category ads';
$this->params['breadcrumbs'][] = ['label' => 'Top categories ads', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="advertcategory-update">

     <?= $this->render('_form', [
        'model' => $model,'category'=>$category
    ]) ?>

</div>
