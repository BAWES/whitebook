<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Deliverytimeslot */

$this->title = 'Update delivery time slot';
$this->params['breadcrumbs'][] = ['label' => 'Delivery time slots', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->timeslot_id, 'url' => ['view', 'id' => $model->timeslot_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="deliverytimeslot-update">

    <?= $this->render('_form', [
        'model' => $model,'days'=>$days
    ]) ?>

</div>
