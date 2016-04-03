<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Blockeddate */

$this->title = 'Create blocked date';
$this->params['breadcrumbs'][] = ['label' => 'Blocked dates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blockeddate-create">

    <?= $this->render('_form', [
        'model' => $model,'block'=>$block
    ]) ?>

</div>
