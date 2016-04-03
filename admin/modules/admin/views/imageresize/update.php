<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Imageresize */

$this->title = 'Update image resize';
$this->params['breadcrumbs'][] = ['label' => 'Imageresizes', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="imageresize-update">
     <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
