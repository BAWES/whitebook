<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AuthAssignment */

$this->title = 'Create AuthAssignment';
$this->params['breadcrumbs'][] = ['label' => 'Authassignments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authassignment-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
