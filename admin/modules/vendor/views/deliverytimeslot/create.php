<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Deliverytimeslot */

$this->title = 'Create Delivery time slot';
$this->params['breadcrumbs'][] = ['label' => 'Delivery time slots', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deliverytimeslot-create">

    <?= $this->render('_form', [
        'model' => $model,'days'=>$days,
    ]) ?>

</div>
