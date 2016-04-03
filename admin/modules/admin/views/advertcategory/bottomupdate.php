<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Advertcategory */

$this->title = 'Update Bottom category ads';
$this->params['breadcrumbs'][] = ['label' => 'Bottom categories ads', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="advertcategory-update">

     <?= $this->render('bottomform', [
        'model' => $model,'category'=>$category
    ]) ?>

</div>
