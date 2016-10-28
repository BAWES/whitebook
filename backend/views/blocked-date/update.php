<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Blockeddate */

$this->title = 'Update Blocked date ';
$this->params['breadcrumbs'][] = ['label' => 'Blocked dates', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="blockeddate-update">

    <?= $this->render('_form', [
        'model' => $model,'block'=>$block,
    ]) ?>

</div>
