<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Imageresize */

$this->title = 'Create image resize';
$this->params['breadcrumbs'][] = ['label' => 'Imageresizes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="imageresize-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
