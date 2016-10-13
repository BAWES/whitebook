<?php

use yii\helpers\Html;

$this->title = 'Create Area';
$this->params['breadcrumbs'][] = ['label' => 'Locations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="location-create">

     <?= $this->render('_form', [
        'model' => $model,
        'country' => $country   
    ]) ?>

</div>
