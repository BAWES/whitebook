<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Adverthome */

$this->title = 'Create home ads';
$this->params['breadcrumbs'][] = ['label' => 'Home ads', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Create ads';
?>
<div class="adverthome-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
