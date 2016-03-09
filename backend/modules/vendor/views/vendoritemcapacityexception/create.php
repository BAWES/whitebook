<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Vendoritemcapacityexception */

$this->title = 'Create exception dates';
$this->params['breadcrumbs'][] = ['label' => 'Exception dates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="vendoritemcapacityexception-create">

    <?= $this->render('_form', [
        'model' => $model,'exist_dates' => $exist_dates,
    ]) ?>

</div>
