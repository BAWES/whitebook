<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Vendoritemcapacityexception */

$this->title = 'Update exception dates';
$this->params['breadcrumbs'][] = ['label' => 'Exception dates', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vendoritemcapacityexception-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
