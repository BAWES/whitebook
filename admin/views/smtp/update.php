<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Smtp */

$this->title = 'Update smtp: ' . ' ' . $model->smtp_host;
$this->params['breadcrumbs'][] = ['label' => 'Smtps', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="smtp-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
