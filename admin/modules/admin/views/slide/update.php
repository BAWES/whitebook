<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Slide */

$this->title = 'Update Slide';
$this->params['breadcrumbs'][] = ['label' => 'Slides', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="slide-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
