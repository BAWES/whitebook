<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Cms */

$this->title = 'Create static page';
$this->params['breadcrumbs'][] = ['label' => 'static page', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
