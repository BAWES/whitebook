<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\VendorLocation */

$this->title = 'Update Delivery Area';
$this->params['breadcrumbs'][] = ['label' => 'Manage Area', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vendorlocation-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
