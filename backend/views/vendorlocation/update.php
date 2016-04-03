<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\vendorlocation */

$this->title = 'Update Vendorlocation: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Vendorlocations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vendorlocation-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
