<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Categoryads */

$this->title = 'Update category ads';
$this->params['breadcrumbs'][] = ['label' => 'Categoryads', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->advert_id, 'url' => ['view', 'id' => $model->advert_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="categoryads-update">

    <?= $this->render('_form', [
        'model' => $model,'category'=>$category
    ]) ?>

</div>
