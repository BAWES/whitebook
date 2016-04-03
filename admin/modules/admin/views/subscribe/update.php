<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Subscribe */

$this->title = 'Update Subscribe: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Subscribes', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="subscribe-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
