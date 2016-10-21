<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AuthAssignment */

$this->title = 'Update AuthAssignment: ' . ' ' . $model->item_name;
$this->params['breadcrumbs'][] = ['label' => 'Authassignments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->item_name, 'url' => ['view', 'item_name' => $model->item_name, 'user_id' => $model->user_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="authassignment-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
