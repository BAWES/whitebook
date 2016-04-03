<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Prioritylog */

$this->title = 'Update Prioritylog: ' . ' ' . $model->log_id;
$this->params['breadcrumbs'][] = ['label' => 'Prioritylogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->log_id, 'url' => ['view', 'id' => $model->log_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="prioritylog-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
