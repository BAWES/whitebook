<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Prioritylog */

$this->title = 'Create Prioritylog';
$this->params['breadcrumbs'][] = ['label' => 'Prioritylogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prioritylog-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
