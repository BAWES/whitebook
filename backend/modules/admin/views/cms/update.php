<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Cms */

$this->title = 'Update static page';
$this->params['breadcrumbs'][] = ['label' => 'Static page', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cms-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
