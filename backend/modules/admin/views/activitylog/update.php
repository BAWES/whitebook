<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Activitylog */

$this->title = 'Update Activitylog: ' . ' ' . $model->log_id;
$this->params['breadcrumbs'][] = ['label' => 'Activitylogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->log_id, 'url' => ['view', 'id' => $model->log_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="activitylog-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
