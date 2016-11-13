<?php

use yii\helpers\Html;

$this->title = 'Update Event';
$this->params['breadcrumbs'][] = ['label' => 'Event', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';

?>

<div class="customer-update">
   <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
