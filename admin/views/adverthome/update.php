<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AdvertHome */

$this->title = 'Update home ads';
$this->params['breadcrumbs'][] = ['label' => 'Home ads', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update ads';
?>
<div class="adverthome-update">

    <?= $this->render('_form', [
        'model' => $model,
        'imagedata'=>$imagedata,
    ]) ?>

</div>
