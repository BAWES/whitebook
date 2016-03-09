<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Featuregroup */

$this->title = 'Update feature group';
$this->params['breadcrumbs'][] = ['label' => 'Featuregroups', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="featuregroup-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
