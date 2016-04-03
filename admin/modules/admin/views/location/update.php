<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Location */

$this->title = 'Update Area';
$this->params['breadcrumbs'][] = ['label' => 'Locations', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="location-update">
	   <?= $this->render('_form', [
        'model' => $model,'city' => $city, 'country' => $country, 
    ]) ?>

</div>
