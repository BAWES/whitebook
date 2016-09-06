<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SubOrder */

$this->title = 'Update Sub Order: ' . $model->suborder_id;
$this->params['breadcrumbs'][] = ['label' => 'Sub Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->suborder_id, 'url' => ['view', 'id' => $model->suborder_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sub-order-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
