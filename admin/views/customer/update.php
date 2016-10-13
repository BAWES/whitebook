<?php

use yii\helpers\Html;

$this->title = 'Update Customer';
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';

?>

<div class="customer-update">
   <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
