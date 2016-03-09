<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\City */

$this->title = 'Create Governorate';
$this->params['breadcrumbs'][] = ['label' => 'Governorate', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-create">

    <?= $this->render('_form', [
        'model' => $model,'country' => $country,
    ]) ?>

</div>
