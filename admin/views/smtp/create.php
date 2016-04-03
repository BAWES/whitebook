<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Smtp */

$this->title = 'Create Smtp';
$this->params['breadcrumbs'][] = ['label' => 'Smtps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="smtp-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
