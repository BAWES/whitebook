<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\VendorItemCapacityException */

$this->title = 'Update exception dates';
$this->params['breadcrumbs'][] = ['label' => 'Exception dates', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vendor-item-capacity-exception-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
