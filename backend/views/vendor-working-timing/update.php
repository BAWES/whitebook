<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\VendorWorkingTiming */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
        'modelClass' => 'Vendor Working Timing',
    ]) . $model->working_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vendor Working Timings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->working_id, 'url' => ['view', 'id' => $model->working_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="vendor-working-timing-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>