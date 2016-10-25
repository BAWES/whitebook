<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */

$this->title = 'Update AuthItem: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Authitems', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="authitem-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
