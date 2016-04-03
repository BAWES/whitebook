<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Featuregroup */

$this->title = 'Create feature group';
$this->params['breadcrumbs'][] = ['label' => 'Featuregroups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="featuregroup-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
