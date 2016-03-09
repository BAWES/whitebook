<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Authassignment */

$this->title = 'Create Authassignment';
$this->params['breadcrumbs'][] = ['label' => 'Authassignments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authassignment-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
