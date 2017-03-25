<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\VendorWorkingTiming */

$this->title = Yii::t('app', 'Create Vendor Working Timing');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vendor Working Timings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-working-timing-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>