<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Area */

$this->title = 'Update Area: ' . ' ' . $model->area_id;
$this->params['breadcrumbs'][] = ['label' => 'Areas', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="area-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
