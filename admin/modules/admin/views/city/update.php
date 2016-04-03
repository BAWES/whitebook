<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\City */

$this->title = 'Update Governorate: ' . ' ' . $model->city_name;
$this->params['breadcrumbs'][] = ['label' => 'Cities', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="city-update">

      <?= $this->render('_form', [
        'model' => $model,'country' => $country,
    ]) ?>

</div>
