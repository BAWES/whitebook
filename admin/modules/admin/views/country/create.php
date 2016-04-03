<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Country */

$this->title = 'Create country';
$this->params['breadcrumbs'][] = ['label' => 'Countries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="country-create">

     <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
