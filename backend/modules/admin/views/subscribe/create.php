<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Subscribe */

$this->title = 'Create Subscribe';
$this->params['breadcrumbs'][] = ['label' => 'Subscribes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscribe-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
