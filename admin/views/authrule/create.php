<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AuthRule */

$this->title = 'Create AuthRule';
$this->params['breadcrumbs'][] = ['label' => 'Authrules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authrule-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
