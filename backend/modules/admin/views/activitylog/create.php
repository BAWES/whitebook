<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Activitylog */

$this->title = 'Create Activitylog';
$this->params['breadcrumbs'][] = ['label' => 'Activitylogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activitylog-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
