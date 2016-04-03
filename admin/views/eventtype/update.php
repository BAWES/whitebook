<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Itemtype */

$this->title = 'Update event type: ' . ' ' . $model->type_name;
$this->params['breadcrumbs'][] = ['label' => 'Eventtypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->type_id, 'url' => ['view', 'id' => $model->type_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="itemtype-update">

   <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
